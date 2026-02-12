<?php

namespace App\Filament\Widgets;

use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class Info extends StatsOverviewWidget
{
    use HasWidgetShield;

    protected int|string|array $columnSpan = 'full';

    protected function getColumns(): int|array
    {
        return [
            'default' => 1,
            'md' => 1,
            'xl' => 1,
        ];
    }

    protected function getStats(): array
    {
        return [
            Stat::make('Necesitas permisos para acceder', 'Datos estadisticos disponibles solo para el administrador')
                ->description('Información')
                ->descriptionIcon('heroicon-o-information-circle')
                ->color('primary'),

        ];
    }
}
