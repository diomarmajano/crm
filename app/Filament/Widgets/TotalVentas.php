<?php

namespace App\Filament\Widgets;

use App\Models\Pedidos;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TotalVentas extends StatsOverviewWidget
{
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

        return [
            Stat::make('Total Ventas', '$'.number_format($total_ventas, 0))
                ->description('Ventas del día')
                ->descriptionIcon('heroicon-o-arrow-trending-up')
                ->color('success'),
            Stat::make('Cantidad de Ventas', $count_ventas)
                ->description('Pedidos finalizados hoy')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('primary'),
        ];
    }
}
