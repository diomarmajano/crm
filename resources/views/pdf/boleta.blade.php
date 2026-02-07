<!DOCTYPE html>
<html>
<head>
    <title>Comprobante de Venta</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; }
        .header { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border-bottom: 1px solid #ddd; padding: 8px; text-align: left; }
        .total { font-weight: bold; text-align: right; margin-top: 20px; font-size: 18px;}
    </style>
</head>
<body>
    <div class="header">
        <h2>Comprobante de Venta #{{ $venta->id }}</h2>
        <p>Fecha: {{ $venta->created_at->format('d/m/Y H:i') }}</p>
    </div>

    <div class="cliente-info">
        <strong>Cliente:</strong> {{ $venta->cliente->cliente_name ?? 'Consumidor Final' }}<br>
        <strong>Teléfono:</strong> {{ $venta->cliente->cliente_telefono ?? '-' }}<br>
        <strong>Dirección:</strong> {{ $venta->cliente->cliente_direccion ?? '-' }}
    </div>

    <table>
        <thead>
            <tr>
                <th>Servicio/Producto</th>
                <th>Cant.</th>
                <th>Precio Unit.</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($venta->items as $item)
            <tr>
                <td>{{ $item->nombre_servicio }}</td>
                <td>{{ $item->cantidad }}</td>
                <td>${{ number_format($item->precio_unitario, 0, ',', '.') }}</td>
                <td>${{ number_format($item->subtotal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total">
        Total Pagado: ${{ number_format($venta->total_pedido, 0, ',', '.') }} <br>
        <small style="font-size: 12px; font-weight: normal;">Medio de Pago: {{ ucfirst($venta->medio_pago) }}</small>
    </div>
</body>
</html>