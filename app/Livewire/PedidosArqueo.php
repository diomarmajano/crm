<?php

namespace App\Livewire;

use App\Filament\Resources\Pedidos\Pages\ListPedidos;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PedidosArqueo extends StatsOverviewWidget
{
    use InteractsWithPageTable;

    protected $listeners = ['refreshStats' => '$refresh'];

    protected function getColumns(): int
    {
        return 2;
    }

    protected function getTablePage(): string
    {
        return ListPedidos::class;
    }

    protected function getStats(): array
    {
        $query = $this->getPageTableQuery();

        $stats_primary = [
            Stat::make('Registros', $query->clone()->count())
                ->description('Cantidad de transacciones')
                ->descriptionIcon('heroicon-s-folder-open')
                ->color('vip'),

            Stat::make('Total General', '$ '.number_format($query->clone()->sum('total_pedido'), 0, ',', '.'))
                ->description('Suma total de todos los servicios')
                ->descriptionIcon('heroicon-s-currency-dollar')
                ->color('vip'),
        ];

        return $stats_primary;
    }
}
