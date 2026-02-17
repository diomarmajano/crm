<x-filament-panels::page>
<style>
    .card {
    --card-bg: #ffffff;
    --card-accent: #22819A;
    --card-text: #1e293b;
    --card-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);

    width: 180px;
    height: 254px;
    background: var(--card-bg);
    border-radius: 20px;
    position: relative;
    overflow: hidden;
    transition: all 0.5s cubic-bezier(0.16, 1, 0.3, 1);
    box-shadow: var(--card-shadow);
    border: 1px solid rgba(255, 255, 255, 0.2);
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen,
        Ubuntu, Cantarell, sans-serif;
    }

    .card__shine {
    position: absolute;
    inset: 0;
    background: linear-gradient(
        120deg,
        rgba(255, 255, 255, 0) 40%,
        rgba(255, 255, 255, 0.8) 50%,
        rgba(255, 255, 255, 0) 60%
    );
    opacity: 0;
    transition: opacity 0.3s ease;
    }

    .card__glow {
    position: absolute;
    inset: -10px;
    background: radial-gradient(
        circle at 50% 0%,
        rgba(58, 142, 237, 0.3) 0%,
        rgba(58, 133, 237, 0) 70%
    );
    opacity: 0;
    transition: opacity 0.5s ease;
    }

    .card__content {
    padding: 1.25em;
    height: 100%;
    display: flex;
    flex-direction: column;
    gap: 0.75em;
    position: relative;
    z-index: 2;
    }

    .card__badge {
    position: absolute;
    top: 12px;
    right: 12px;
    background: #15868c;
    color: white;
    padding: 0.25em 0.5em;
    border-radius: 999px;
    font-size: 0.7em;
    font-weight: 600;
    transform: scale(0.8);
    opacity: 0;
    transition: all 0.4s ease 0.1s;
    }

    .card__image {
    width: 100%;
    height: 100px;
    background: linear-gradient(45deg, #22819A, #5c95f6);
    border-radius: 12px;
    transition: all 0.5s cubic-bezier(0.16, 1, 0.3, 1);
    position: relative;
    overflow: hidden;
    }

    .card__image::after {
    content: "";
    position: absolute;
    inset: 0;
    background: radial-gradient(
        circle at 30% 30%,
        rgba(255, 255, 255, 0.1) 0%,
        transparent 30%
        ),
        repeating-linear-gradient(
        45deg,
        rgba(58, 142, 237, 0.3) 0px,
        rgba(58, 133, 237, 0) 2px,
        transparent 2px,
        transparent 4px
        );
    opacity: 0.5;
    }

    .card__text {
    display: flex;
    flex-direction: column;
    gap: 0.25em;
    }

    .card__title {
    color: var(--card-text);
    font-size: 1.1em;
    margin: 0;
    font-weight: 700;
    transition: all 0.3s ease;
    }

    .card__description {
    color: var(--card-text);
    font-size: 0.75em;
    margin: 0;
    opacity: 0.7;
    transition: all 0.3s ease;
    }

    .card__footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: auto;
    }

    .card__price {
    color: var(--card-text);
    font-weight: 700;
    font-size: 1em;
    transition: all 0.3s ease;
    }

    .card__button {
    width: 28px;
    height: 28px;
    background: var(--card-accent);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    cursor: pointer;
    transition: all 0.3s ease;
    transform: scale(0.9);
    }

    /* Hover Effects */
    .card:hover {
    transform: translateY(-10px);
    box-shadow:
        0 20px 25px -5px rgba(0, 0, 0, 0.1),
        0 10px 10px -5px rgba(0, 0, 0, 0.04);
    border-color: rgba(58, 133, 237, 0);
    }

    .card:hover .card__shine {
    opacity: 1;
    animation: shine 3s infinite;
    }

    .card:hover .card__glow {
    opacity: 1;
    }

    .card:hover .card__badge {
    transform: scale(1);
    opacity: 1;
    z-index: 1;
    }

    .card:hover .card__image {
    transform: translateY(-5px) scale(1.03);
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }

    .card:hover .card__title {
    color: var(--card-accent);
    transform: translateX(2px);
    }

    .card:hover .card__description {
    opacity: 1;
    transform: translateX(2px);
    }

    .card:hover .card__price {
    color: var(--card-accent);
    transform: translateX(2px);
    }

    .card:hover .card__button {
    transform: scale(1);
    box-shadow: 0 0 0 4px rgba(38, 161, 140, 0.2);
    }

    .card:hover .card__button svg {
    animation: pulse 1.5s infinite;
    }

    /* Active State */
    .card:active {
    transform: translateY(-5px) scale(0.98);
    }

    /* Animations */
    @keyframes shine {
    0% {
        background-position: -100% 0;
    }
    100% {
        background-position: 200% 0;
    }
    }

    @keyframes pulse {
    0% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.2);
    }
    100% {
        transform: scale(1);
    }
    }

    @media(max-width: 640px) {
    .card {
        width: 160px;
        height: 200px;
        margin:auto;
    }
    }
    .search{
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 8px;
        padding: 0.5rem 1rem;
        transition: all 0.3s ease;
    }
    .search:focus {
        outline: none;
        border-color: #22819A;
        box-shadow: 0 0 0 1px #22819A;
    }

</style>

        

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- COLUMNA IZQUIERDA: SERVICIOS --}}
        <div class="lg:col-span-2">  
             <div class="mb-8 w-full">
                <div class="relative max-w-xl mx-auto">
                    <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                    </div>
                    <input 
                        type="search" 
                        wire:model.live="search" 
                        wire:keydown.enter.prevent="scanBarcode"
                        wire:model.live.debounce.300ms="search" 
                        class="search block w-full p-4 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" 
                        placeholder="Buscar servicios por nombre..." 
                        placeholder="Escanear código o buscar nombre..." 
                        autofocus
                    />
                </div>
            </div>          
            <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-4">  
                
                @forelse($services as $service)
                   <!-- From Uiverse.io by SachinKumar666 --> 
                    <div class="card"  wire:click="addToCart({{ $service->id }})">
                        <div class="card__shine"></div>
                        <div class="card__glow"></div>
                        <div class="card__content">
                            <div class="card__badge">Agregar</div>
                            <div style="--bg-color: #22819A" class="card__image">
                                <img src="{{ $service->service_icon}}">
                            </div>
                            <div class="card__text">
                            <p class="card__title">{{ $service->service_name }}</p>
                            <div class="card__footer">
                            <div class="card__price">${{ number_format($service->service_precio, 0, ',', '.')  }}</div>
                            <div class="card__button"
                                wire:click="addToCart({{ $service->id }})">
                                <svg height="16" width="16" viewBox="0 0 24 24">
                                <path
                                    stroke-width="2"
                                    stroke="currentColor"
                                    d="M4 12H20M12 4V20"
                                    fill="currentColor"
                                ></path>
                                </svg>
                            </div>
                            </div>
                        </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-2 lg:col-span-3 text-center py-10 text-gray-500 dark:text-gray-400">
                        <p>No se encontraron servicios con ese nombre.</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- COLUMNA DERECHA: CARRITO Y CHECKOUT --}}
        <div class="lg:col-span-1 space-y-6">
            
            <div class="bg-white dark:bg-gray-900 border dark:border-gray-700 rounded-lg shadow-sm p-4">
                <h2 class="text-lg font-bold mb-4 border-b pb-2">Resumen del Pedido</h2>
                
                @if(count($cart) > 0)
                    <div class="space-y-4 max-h-60 overflow-y-auto mb-4">
                        @foreach($cart as $id => $item)
                            <div class="flex justify-between items-center bg-gray-50 dark:bg-gray-800 p-2 rounded">
                                <div class="flex-1">
                                    <div class="text-sm font-medium">{{ $item['nombre'] }}</div>
                                    <div class="text-xs text-gray-500">${{number_format($item['precio'], 0, ',','.') }} c/u</div>
                                </div>
                                
                                <div class="flex items-center gap-2">
                                    <button wire:click="updateQuantity({{ $id }}, {{ $item['cantidad'] - 1 }})" class="p-1 text-gray-500 hover:text-red-500 transition font-bold text-lg leading-none flex items-center justify-center h-6 w-6">-</button>
                                    <span class="font-bold text-sm w-4 text-center">{{ $item['cantidad'] }}</span>
                                    <button wire:click="addToCart({{ $id }})" class="p-1 text-gray-500 hover:text-green-500 transition font-bold text-lg leading-none flex items-center justify-center h-6 w-6">+</button>
                                </div>
                                
                                <div class="text-right ml-2 font-semibold">
                                    ${{ number_format($item['precio'] * $item['cantidad'], 0, ',', '.') }}
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="flex justify-between items-center border-t border-gray-200 dark:border-gray-700 pt-4 text-xl font-bold">
                        <span>Total:</span>
                        <span>${{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                @else
                    <div class="text-center text-gray-400 py-4">
                        Carrito vacío
                    </div>
                @endif
            </div>

            <div class="bg-white dark:bg-gray-900 rounded-lg shadow-sm">
                {{ $this->form }}
            </div>

            <x-filament::button 
                size="xl" 
                class="w-full" 
                color="primary" 
                wire:click="createOrder"
            >
                Finalizar Venta
            </x-filament::button>

        </div>
        
    </div>
</x-filament-panels::page>