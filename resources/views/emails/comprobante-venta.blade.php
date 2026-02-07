<!DOCTYPE html>
<html>
<body>
    <h1>¡Gracias por tu compra, {{ $pedido->cliente->cliente_name }}!</h1>
    <p>Adjunto encontrarás el comprobante de tu pedido #{{ $pedido->id }}.</p>
    <p>Total pagado: ${{ number_format($pedido->total_pedido, 0, ',', '.') }}</p>
    <br>
    <p>Atentamente,<br>El equipo de ventas.</p>
</body>
</html>