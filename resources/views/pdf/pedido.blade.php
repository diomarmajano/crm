<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket #{{ $pedido->id }}</title>
    <style>
        /* --- CONFIGURACIÓN DE PÁGINA --- */
        @page {
            size: 58mm auto;
            margin: 0; 
        }

        body {
            font-family: 'Courier New', Courier, monospace;
            font-weight: bold; /* Negrita para que se vea oscuro */
            font-size: 10px; /* Reducido para que quepa todo */
            text-transform: uppercase;
            
            /* AJUSTE CRÍTICO: Reducimos a 44mm para evitar corte a la derecha */
            width: 40mm; 
            margin-left: 1mm; /* Pequeño margen izq */
            margin-right: 0;  /* Sin margen derecho */
            padding-top: 5px;
            
            color: #000;
            line-height: 1.1; 
        }

        /* --- UTILIDADES --- */
        .center { text-align: center; }
        .right { text-align: right; }
        .left { text-align: left; }

        .divider {
            border-top: 1px dashed #000;
            margin: 5px 0;
            width: 100%;
        }

        /* --- GRILLA MATEMÁTICA SEGURA --- */
        .math-row {
            display: flex;
            /* Forzamos que los elementos se peguen a los extremos */
            justify-content: space-between; 
            width: 100%; /* Ocupa exactamente los 44mm definidos arriba */
        }

        .col-math {
            /* Lado Izquierdo: Cantidad x Precio */
            flex-grow: 1; /* Ocupa todo el espacio posible */
            text-align: left;
            white-space: nowrap; 
        }

        .col-total {
            /* Lado Derecho: Total */
            /* width: auto asegura que ocupe solo lo que mide el precio */
            width: auto; 
            text-align: right;
            white-space: nowrap;
            padding-left: 5px; /* Espacio mínimo entre las dos columnas */
        }

        .store-title {
            font-size: 14px; /* Un poco más grande pero no gigante */
            font-weight: 900;
            display: block;
        }

        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body>

    <div class="center">
        <span class="store-title">{{ substr($nombreTienda, 0, 20) }}</span>
        <span>Boleta N° {{ $pedido->id }}</span><br>
        <span>{{ $pedido->created_at->format('d-m-Y H:i') }}</span>
    </div>

    <div class="divider"></div>

    @foreach ($pedido->items as $item)
        @php
            $subtotal = $item->precio_unitario * $item->cantidad;
        @endphp
        
        <div style="width: 100%; overflow: hidden; white-space: nowrap;">
            {{ substr($item->nombre_servicio, 0, 25) }}
        </div>
        
        <div class="math-row">
            <div class="col-math">
                {{ $item->cantidad }} x ${{ number_format($item->precio_unitario, 0, ',', '.') }}
            </div>
            
            <div class="col-total">
                ${{ number_format($subtotal, 0, ',', '.') }}
            </div>
        </div>
        <div style="height: 2px;"></div>
    @endforeach

    <div class="divider"></div>

    <div class="math-row" style="font-size: 12px;">
        <div class="col-math" style="text-align: right; padding-right: 5px;">TOTAL:</div>
        <div class="col-total">${{ number_format($pedido->total_pedido, 0, ',', '.') }}</div>
    </div>

    <div class="math-row">
        <div class="col-math" style="text-align: right; padding-right: 5px;">MEDIO:</div>
        <div class="col-total">{{ substr($pedido->medio_pago, 0, 10) }}</div>
    </div>

    @if (strtolower($pedido->medio_pago) === 'efectivo' && $pagaCon > 0)
        <div class="math-row">
            <div class="col-math" style="text-align: right; padding-right: 5px;">PAGO:</div>
            <div class="col-total">${{ number_format($pagaCon, 0, ',', '.') }}</div>
        </div>
        <div class="math-row">
            <div class="col-math" style="text-align: right; padding-right: 5px;">VUELTO:</div>
            <div class="col-total">${{ number_format($vuelto, 0, ',', '.') }}</div>
        </div>
    @endif

    <br>
    <div class="center">
        ¡GRACIAS POR SU COMPRA!
    </div>
    
    <br><br><br>

    <script>
        window.onload = function() {
            window.print();
            setTimeout(function() {
                window.close();
            }, 500);
        }
    </script>
</body>
</html>