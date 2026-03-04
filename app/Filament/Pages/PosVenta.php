<?php

namespace App\Filament\Pages;

use App\Models\Services;
use App\Utilities\PosService;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section as ComponentsSection;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Support\Icons\Heroicon;
use Filament\Support\RawJs;
use Illuminate\Support\Str;

class PosVenta extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::ShoppingCart;

    protected static ?string $recordTitleAttribute = 'Clientes';

    protected static ?int $navigationSort = 1;

    protected string $view = 'filament.pages.pos-venta';

    public static function shouldRegisterNavigation(): bool
    {
        // Solo se registra en el menú si el usuario tiene un tenant asignado
        return auth()->user()->tenant_id !== null;
    }

    // Variables del Carrito
    public $cart = [];

    public $total = 0;

    // Variables del Cliente (Formulario)
    public $data = [];

    // variable para la busqueda de productos
    public $search = '';

    public function mount()
    {
        $this->form->fill([
            'total_carrito' => 0,
            'medio_pago' => 'efectivo',
        ]);
    }

    // Formulario para datos del carrito
    public function form($form)
    {
        return $form
            ->schema([
                Hidden::make('total_carrito')
                    ->default(0)
                    ->dehydrated(false),

                ComponentsSection::make('Datos del pago')
                    ->schema([
                        Select::make('medio_pago')
                            ->label('Medio de Pago')
                            ->options([
                                'efectivo' => 'Efectivo',
                                'transferencia' => 'Transferencia',
                                'transbank' => 'Transbank',
                                'otro' => 'Otro',
                            ])
                            ->default('efectivo')
                            ->live()
                            ->native(false),
                    ])
                    ->columns(1),

                ComponentsSection::make('Finalizar Venta')
                    ->schema([
                        TextInput::make('paga_con')
                            ->label('Paga con')
                            ->prefix('$')
                            ->numeric()
                            ->placeholder('Agregar monto...')
                            ->live(debounce: 500) // Se calcula cuando sales del campo o escribes
                            ->afterStateUpdated(function (Get $get, Set $set, ?string $state) {
                                if (blank($state)) {
                                    $set('vuelto', null);

                                    return;
                                }
                                // 2. Si hay un valor, realizamos el cálculo
                                $total = (float) $get('total_carrito');
                                $pago = (float) $state;

                                $set('vuelto', $pago - $total);
                            }),

                        TextInput::make('vuelto')
                            ->label('Vuelto')
                            ->default(null)
                            ->prefix('$')
                            ->readOnly()
                            ->extraInputAttributes(['style' => 'font-weight: bold; color: green; font-size: 1.2em']),
                    ]),
            ])
            ->statePath('data');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('addManualItem')
                ->label('Agregar producto')
                ->icon('heroicon-m-plus-circle')
                ->color('primary')
                ->form([
                    TextInput::make('nombre')
                        ->label('Descripción del Producto')
                        ->required()
                        ->placeholder('Ej: Pan 0.5kg'),

                    TextInput::make('precio')
                        ->label('Precio Total')
                        ->prefix('$')
                        ->mask(RawJs::make('$money($input)'))
                        ->stripCharacters(',')
                        ->numeric()
                        ->required(),

                    TextInput::make('cantidad')
                        ->label('Cantidad')
                        ->numeric()
                        ->default(1)
                        ->required(),
                ])
                ->action(function (array $data) {
                    // Generamos un ID temporal único para el array
                    $tempId = 'manual_'.Str::uuid();
                    $precioLimpio = (float) str_replace(['.', ','], '', $data['precio']);

                    $this->cart[$tempId] = [
                        'id' => null, // Es null porque no existe en la tabla services
                        'nombre' => $data['nombre'].' (Manual)',
                        'precio' => $precioLimpio, // Asumimos que ingresan el precio unitario
                        'cantidad' => $data['cantidad'],
                        'is_manual' => true, // Bandera para identificarlo luego
                    ];

                    $this->calculateTotal();

                    Notification::make()
                        ->title('Item manual agregado')
                        ->success()
                        ->send();
                }),
        ];
    }

    public function getViewData(): array
    {
        $services = Services::query()
            ->limit(20)
            ->where('is_active', true) // Mantén esto si solo quieres ver los activos
            ->when($this->search, function ($query) {
                $query->where(function ($subQuery) {
                    $term = '%'.$this->search.'%';

                    $subQuery->where('service_name', 'like', $term)
                        ->orWhere('sku', 'like', $term)    // <--- Asegúrate que tu columna se llame 'sku'
                        ->orWhere('codigo', 'like', $term); // <--- O 'code', 'barcode', según tu tabla
                });
            })
            ->get(); // Ejecuta la consulta

        return [
            'services' => $services,
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
        if (isset($this->cart[$serviceId])) {
            if ($cant > 0) {
                $this->cart[$serviceId]['cantidad'] = $cant;
            } else {
                $this->removeFromCart($serviceId);
            }
            $this->calculateTotal();
        }
    }

    public function calculateTotal()
    {
        $this->total = array_reduce($this->cart, function ($carry, $item) {
            return $carry + ($item['precio'] * $item['cantidad']);
        }, 0);

        $this->data['total_carrito'] = $this->total;

        // 3. NUEVO: Si el usuario ya escribió con cuánto paga, actualizamos el vuelto automáticamente
        if (isset($this->data['paga_con']) && is_numeric($this->data['paga_con'])) {
            $pago = (float) $this->data['paga_con'];
            $this->data['vuelto'] = $pago - $this->total;
        }
    }

    public function createOrder(PosService $posService)
    {
        $data = $this->form->getState();

        if (empty($this->cart)) {
            Notification::make()->title('Carrito vacío')->warning()->send();

            return;
        }

        try {
            // 1. Delegamos la lógica de negocio al Servicio (Igual que antes)
            $pedido = $posService->crearPedido(
                $this->cart,
                $data,
                $this->total,
                auth()->id(),
            );

            // 2. Cálculos para enviar a la vista de impresión
            $montoPagado = ($data['medio_pago'] === 'efectivo') ? ($data['paga_con'] ?? $this->total) : $this->total;
            $vuelto = $montoPagado - $this->total;

            // 3. Generamos la URL del ticket
            // Usamos la ruta 'imprimir.ticket' que creamos en el paso anterior
            $urlTicket = route('imprimir.ticket', [
                'pedido' => $pedido->id,
                'paga_con' => $montoPagado,
                'vuelto' => $vuelto,
            ]);

            // 4. Limpieza UI (Igual que antes)
            $this->cart = [];
            $this->total = 0;
            $this->form->fill([
                'total_carrito' => 0,
                'paga_con' => null,
                'vuelto' => null,
            ]);

            Notification::make()->title('Venta exitosa')->success()->send();

            // 5. ¡LA MAGIA! Abrir la ventana de impresión desde el navegador
            // Usamos $this->js para ejecutar JavaScript en el cliente
            // Esto abrirá una ventana emergente de 400x600 (tamaño ticket)
            $this->js("window.open('{$urlTicket}', '_blank', 'width=400,height=600');");

        } catch (\Exception $e) {
            Notification::make()
                ->title('Error en la venta')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function scanBarcode()
    {
        // 1. Si el input está vacío, no hacemos nada
        if (empty($this->search)) {
            return;
        }

        // 2. Buscamos coincidencia EXACTA por SKU o Código
        // Ajusta los nombres de columna 'sku' o 'codigo_barra' según tu BD
        $service = Services::where('is_active', true)
            ->where(function ($query) {
                $query->where('sku', $this->search)
                    ->orWhere('codigo', $this->search);
            })
            ->first();

        // 3. Si encontramos el producto exacto
        if ($service) {
            // Agregamos al carrito usando tu función existente
            $this->addToCart($service->id);

            // Limpiamos el buscador para el siguiente escaneo
            $this->search = '';

            // Opcional: Notificación discreta (Toast)
            Notification::make()
                ->title("{$service->service_name} agregado")
                ->success()
                ->duration(1500) // Duración corta para no molestar
                ->send();
        } else {
            // 4. Si NO es exacto, quizás el usuario escribió un nombre manual.
            // No borramos el search, dejamos que el filtro visual (getViewData) haga su trabajo.
            // Opcional: Mandar alerta de "Código no encontrado" si prefieres.
            Notification::make()
                ->title('Producto no encontrado')
                ->warning()
                ->send();
        }
    }
}
