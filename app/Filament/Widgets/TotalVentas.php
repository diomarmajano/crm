<?php

namespace App\Filament\Widgets;

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
            'md' => 2,
            'xl' => 2,
        ];
    }

    protected function getStats(): array
    {
        $total_ventas = Pedidos::whereDate('created_at', today())->sum('total_pedido');
        $count_ventas = Pedidos::whereDate('created_at', today())->count();
        $suma_efectivo = Pedidos::whereDate('created_at', today())->where('medio_pago', 'efectivo')->sum('total_pedido');
        $suma_transferencia = Pedidos::whereDate('created_at', today())->where('medio_pago', 'transferencia')->sum('total_pedido');
        $suma_transbak = Pedidos::whereDate('created_at', today())->where('medio_pago', 'transbank')->sum('total_pedido');

        return [
            Stat::make('Cantidad de Ventas', $count_ventas)
                ->description('Pedidos finalizados hoy')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('primary'),

            Stat::make('Total Ventas', '$'.number_format($total_ventas, 0, ',', '.'))
                ->description('Ventas del día')
                ->descriptionIcon('heroicon-o-arrow-trending-up')
                ->color('success'),

            Stat::make('Efectivo', '$'.number_format($suma_efectivo, 0, ',', '.'))
                ->description('Ventas en efetivo del dia')
                ->descriptionIcon('heroicon-o-currency-dollar')
                ->color('primary'),

            Stat::make('Transferencia', '$'.number_format($suma_transferencia, 0, ',', '.'))
                ->description('Ventas en efetivo del dia')
                ->descriptionIcon('heroicon-o-currency-dollar')
                ->color('primary'),

            Stat::make('Transbank', '$'.number_format($suma_transbak, 0, ',', '.'))
                ->description('Ventas en efetivo del dia')
                ->descriptionIcon('heroicon-o-currency-dollar')
                ->color('primary'),

        ];
    }
}
