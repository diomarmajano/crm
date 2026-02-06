<?php

namespace App\Filament\Pages;

use App\Models\Cliente;
use App\Models\Clientes;
use App\Models\Items;
use App\Models\Pedido;
use App\Models\Pedidos;
use App\Models\Services;
use BackedEnum;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section as ComponentsSection;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\DB;

class PosVenta extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::ShoppingCart;

    protected static ?string $recordTitleAttribute = 'Clientes';

    protected static ?int $navigationSort = 1;

    protected string $view = 'filament.pages.pos-venta';

    // Variables del Carrito
    public $cart = [];

    public $total = 0;

    // Variables del Cliente (Formulario)
    public $data = [];

    public function mount()
    {
        $this->form->fill();
    }

    // Definimos el formulario del Cliente (Usamos componentes de Filament)
    public function form($form)
    {
        return $form
            ->schema([
                ComponentsSection::make('Datos del Cliente')
                    ->schema([
                        TextInput::make('cliente_telefono') // O DNI/Cédula
                            ->label('Contacto')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, $set) {
                                // Buscar si el cliente ya existe para autocompletar
                                $cliente = Clientes::where('cliente_telefono', $state)->first();
                                if ($cliente) {
                                    $set('cliente_name', $cliente->cliente_name);
                                    $set('cliente_email', $cliente->cliente_email);
                                    $set('cliente_direccion', $cliente->cliente_direccion);
                                    $set('cliente_telefono', $cliente->cliente_telefono);
                                }
                            }),
                        TextInput::make('cliente_name')
                            ->label('Nombre')
                            ->required(),
                        TextInput::make('cliente_email')
                            ->label('Email')
                            ->email(),
                        TextInput::make('cliente_direccion')
                            ->label('Dirección'),
                    ])->columns(2),

                ComponentsSection::make('Detalles del Pago')
                    ->schema([
                        Select::make('medio_pago')
                            ->label('Medio de Pago')
                            ->options([
                                'efectivo' => 'Efectivo',
                                'transferencia' => 'Transferencia',
                                'debito' => 'Tarjeta Débito',
                                'credito' => 'Tarjeta Crédito',
                                'otro' => 'Otro',
                            ])
                            ->required() // Valor por defecto
                            ->native(false), // Para que se vea con el estilo de Filament
                    ])
                    ->columns(1),
            ])
            ->statePath('data');
    }

    // Obtener los servicios para mostrarlos en la vista
    public function getViewData(): array
    {
        return [
            'services' => Services::all(),
        ];
    }

    // LÓGICA DEL CARRITO
    public function addToCart($serviceId)
    {
        $service = Services::find($serviceId);

        if (isset($this->cart[$serviceId])) {
            $this->cart[$serviceId]['cantidad']++;
        } else {
            $this->cart[$serviceId] = [
                'id' => $service->id,
                'nombre' => $service->service_name,
                'precio' => $service->service_precio,
                'cantidad' => 1,
            ];
        }
        $this->calculateTotal();
    }

    public function removeFromCart($serviceId)
    {
        unset($this->cart[$serviceId]);
        $this->calculateTotal();
    }

    public function updateQuantity($serviceId, $cant)
    {
        if ($cant > 0) {
            $this->cart[$serviceId]['cantidad'] = $cant;
        } else {
            $this->removeFromCart($serviceId);
        }
        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        $this->total = array_reduce($this->cart, function ($carry, $item) {
            return $carry + ($item['precio'] * $item['cantidad']);
        }, 0);
    }

    // FINALIZAR PEDIDO
    public function createOrder()
    {
        $data = $this->form->getState(); // Valida los datos del cliente

        if (empty($this->cart)) {
            Notification::make()->title('El carrito está vacío')->warning()->send();

            return;
        }

        DB::transaction(function () use ($data) {
            // 1. Guardar o Actualizar Cliente
            $cliente = Clientes::updateOrCreate(
                ['cliente_telefono' => $data['cliente_telefono']], // Busca por teléfono
                [
                    'cliente_name' => $data['cliente_name'],
                    'cliente_email' => $data['cliente_email'],
                    'cliente_direccion' => $data['cliente_direccion'],
                ]
            );

            // 2. Crear el Pedido
            $pedido = Pedidos::create([
                'cliente_id' => $cliente->id,
                'total_pedido' => $this->total,
                'medio_pago' => $data['medio_pago'],
                'tenant_id' => auth()->user()->tenant_id ?? null, // Si usas multi-tenancy
            ]);

            // 3. Crear los Items
            foreach ($this->cart as $item) {
                Items::create([
                    'pedido_id' => $pedido->id,
                    'servicio_id' => $item['id'], // Guardamos ID
                    'nombre_servicio' => $item['nombre'], // Y Nombre histórico
                    'cantidad' => $item['cantidad'],
                    'precio_unitario' => $item['precio'],
                    'subtotal' => $item['precio'] * $item['cantidad'],
                    'cliente_id' => $cliente->id,
                    'tenant_id' => $pedido->tenant_id,
                ]);
            }
        });

        // Limpiar y Notificar
        $this->cart = [];
        $this->total = 0;
        $this->form->fill();

        Notification::make()->title('Venta realizada con éxito')->success()->send();
    }
}
