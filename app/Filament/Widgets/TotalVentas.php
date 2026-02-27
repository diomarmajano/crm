<?php

namespace App\Filament\Widgets;

use App\Models\CashShift;
use App\Models\Pedidos;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TotalVentas extends StatsOverviewWidget
{
    use HasWidgetShield;

    protected static ?int $sort = 1;

    protected int|string|array $columnSpan = 'full';

    protected function getColumns(): int|array
    {
        return [
            'default' => 1,
            'md' => 3,
            'xl' => 3,
        ];
    }

    protected function getStats(): array
    {
        // $tenantId = auth()->user()?->tenant_id;

        // 1. Buscamos el turno de caja abierto actual
        // $cajaAbierta = CashShift::where('tenant_id', $tenantId)
        //     ->where('estado', 'abierta')
        //     ->first();

        $cajaAbierta = CashShift::where('estado', 'abierta')
            ->first();

        // Si no hay caja abierta, mostramos todo en cero con un aviso
        if (! $cajaAbierta) {
            return [
                Stat::make('Estado de Caja', 'Cerrada')
                    ->description('Abre un turno para ver las estadísticas')
                    ->descriptionIcon('heroicon-o-lock-closed')
                    ->color('danger'),
            ];
        }

        // 2. Calculamos las ventas basándonos en la hora en que se abrió la caja (no a las 00:00)
        // $queryPedidos = Pedidos::where('tenant_id', $tenantId)
        //     ->where('created_at', '>=', $cajaAbierta->fecha_apertura);

        $queryPedidos = Pedidos::where('created_at', '>=', $cajaAbierta->fecha_apertura);

        $count_ventas = (clone $queryPedidos)->count();
        $total_ventas = (clone $queryPedidos)->sum('total_pedido');

        // Ventas puras del día
        $ventas_efectivo = (clone $queryPedidos)->where('medio_pago', 'efectivo')->sum('total_pedido');
        $suma_transferencia = (clone $queryPedidos)->where('medio_pago', 'transferencia')->sum('total_pedido');
        $suma_transbank = (clone $queryPedidos)->where('medio_pago', 'transbank')->sum('total_pedido');

        return [
            Stat::make('Ventas del Turno', $count_ventas)
                ->description('Total facturado: $'.number_format($total_ventas, 0, ',', '.'))
                ->descriptionIcon('heroicon-o-arrow-trending-up')
                ->color('success'),

            // ESTA ES LA TARJETA MÁS IMPORTANTE AHORA
            Stat::make('Efectivo en Caja', '$'.number_format($cajaAbierta->saldo_esperado, 0, ',', '.'))
                ->description('Fondo inicial y descuento de gastos')
                ->descriptionIcon('heroicon-o-banknotes')
                ->color('success'),

            Stat::make('Gastos / Retiros', '$'.number_format($cajaAbierta->total_egresos, 0, ',', '.'))
                ->description('Retiros de caja')
                ->descriptionIcon('heroicon-o-arrow-trending-down')
                ->color('danger'),

            Stat::make('Transferencias', '$'.number_format($suma_transferencia, 0, ',', '.'))
                ->description('Ingresos digitales')
                ->descriptionIcon('heroicon-o-device-phone-mobile')
                ->color('primary'),

            Stat::make('Transbank', '$'.number_format($suma_transbank, 0, ',', '.'))
                ->description('Ingresos por tarjeta')
                ->descriptionIcon('heroicon-o-credit-card')
                ->color('primary'),
        ];
    }
}
