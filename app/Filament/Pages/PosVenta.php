<?php

namespace App\Filament\Pages;

use App\Models\Items;
use App\Models\Pedido;
use App\Models\Pedidos;
use App\Models\Services;
use BackedEnum;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Forms\Components\Select;
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

    public $search = '';

    public function mount()
    {
        $this->form->fill();
    }

    // Definimos el formulario del Cliente (Usamos componentes de Filament)
    public function form($form)
    {
        return $form
            ->schema([
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
                            ->native(false), // Para que se vea con el estilo de Filament
                    ])
                    ->columns(1),

            ])
            ->statePath('data');
    }

    // Obtener los servicios para mostrarlos en la vista
    public function getViewData(): array
    {
        // return [
        //     'services' => Services::all(),
        // ];

        $services = Services::query()
            ->where('is_active', true) // Mantén esto si solo quieres ver los activos
            ->when($this->search, function ($query) {
                // Si hay algo en $search, aplica este filtro
                $query->where('service_name', 'like', '%'.$this->search.'%');
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
        $data = $this->form->getState();

        if (empty($this->cart)) {
            Notification::make()->title('El carrito está vacío')->warning()->send();

            return;
        }

        // EL CAMBIO ESTÁ AQUÍ:
        // Asignamos a $pedidoCreado el resultado (return) de la transacción
        $pedidoCreado = DB::transaction(function () use ($data) {

            // 2. Crear el Pedido
            $pedido = Pedidos::create([
                'user_id' => auth()->id(),
                'total_pedido' => $this->total,
                'medio_pago' => $data['medio_pago'],
                'tenant_id' => auth()->user()->tenant_id ?? null,
            ]);

            // 3. Crear los Items
            foreach ($this->cart as $item) {
                Items::create([
                    'pedido_id' => $pedido->id,
                    'servicio_id' => $item['id'],
                    'nombre_servicio' => $item['nombre'],
                    'cantidad' => $item['cantidad'],
                    'precio_unitario' => $item['precio'],
                    'subtotal' => $item['precio'] * $item['cantidad'],
                    'tenant_id' => $pedido->tenant_id,
                ]);
            }

            // RETORNAMOS EL OBJETO PEDIDO PARA QUE SALGA DE LA TRANSACCIÓN
            return $pedido;
        });

        // Limpiar y Notificar
        $this->cart = [];
        $this->total = 0;
        $this->form->fill();

        Notification::make()->title('Venta realizada con éxito')->success()->send();

        // AHORA $pedidoCreado YA TIENE DATOS (NO ES NULL)
        // Asegúrate que las relaciones 'cliente' e 'items' existan en el modelo Pedidos
        // $pedidoCreado->load(['cliente', 'items']);

        // return response()->streamDownload(function () use ($pedidoCreado) {
        //     // Asegúrate de que la ruta de la vista sea correcta ('pdf.boleta' o 'ventas.invoice')
        //     echo Pdf::loadView('pdf.boleta', ['venta' => $pedidoCreado])->stream();
        // }, "venta-{$pedidoCreado->id}.pdf");
    }
}
