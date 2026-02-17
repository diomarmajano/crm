<?php

namespace App\Filament\Pages;

use App\Models\Inventory;
use App\Models\Items;
use App\Models\Pedido;
use App\Models\Pedidos;
use App\Models\Services;
use BackedEnum;
use Barryvdh\DomPDF\Facade\Pdf;
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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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
        $this->form->fill([
            'total_carrito' => 0,
            'medio_pago' => 'efectivo',
        ]);
    }

    // Definimos el formulario del Cliente (Usamos componentes de Filament)
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
                            ->native(false), // Para que se vea con el estilo de Filament
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
                                // 1. Si el campo está vacío o es nulo, dejamos el vuelto vacío
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
                ->color('primary') // Un color diferente para destacar
                ->form([
                    TextInput::make('nombre')
                        ->label('Descripción del Producto')
                        ->required()
                        ->placeholder('Ej: Pan 0.5kg'),

                    TextInput::make('precio')
                        ->label('Precio Total')
                        ->prefix('$')
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

                    $this->cart[$tempId] = [
                        'id' => null, // Es null porque no existe en la tabla services
                        'nombre' => $data['nombre'].' (Manual)',
                        'precio' => $data['precio'], // Asumimos que ingresan el precio unitario
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

    // Obtener los servicios para mostrarlos en la vista
    public function getViewData(): array
    {
        // return [
        //     'services' => Services::all(),
        // ];

        $services = Services::query()
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

        $this->data['total_carrito'] = $this->total;

        // 3. NUEVO: Si el usuario ya escribió con cuánto paga, actualizamos el vuelto automáticamente
        if (isset($this->data['paga_con']) && is_numeric($this->data['paga_con'])) {
            $pago = (float) $this->data['paga_con'];
            $this->data['vuelto'] = $pago - $this->total;
        }
    }

    // FINALIZAR PEDIDO
    public function createOrder()
    {
        $data = $this->form->getState();

        if (empty($this->cart)) {
            Notification::make()->title('El carrito está vacío')->warning()->send();

            return;
        }
        // --- 1. VALIDACIÓN PREVIA DE STOCK  ---
        foreach ($this->cart as $item) {
            // Buscamos el inventario del servicio

            if (! empty($item['id'])) {
                $inventario = Inventory::where('id_service', $item['id'])->first();

                // Si no existe registro de inventario o el stock es menor a lo que piden
                if (! $inventario || $inventario->stock_producto < $item['cantidad']) {
                    Notification::make()
                        ->title('Stock insuficiente')
                        ->body("El producto '{$item['nombre']}' solo tiene {$inventario->stock_producto} unidades.")
                        ->danger()
                        ->send();

                    return; // Detenemos la venta aquí
                }
            }
        }
        // ------------------------------------------------------------------

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

                // B. Descontar del Inventario // <--- AQUÍ ESTÁ LA MAGIA
                // Buscamos el registro usando id_service (según tu estructura anterior)
                $inventario = Inventory::where('id_service', $item['id'])->first();

                if (! empty($item['id'])) {
                    $inventario = Inventory::where('id_service', $item['id'])->first();
                    if ($inventario) {
                        $inventario->decrement('stock_producto', $item['cantidad']);
                    }
                }

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
