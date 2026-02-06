<?php

namespace App\Filament\Widgets;

use App\Models\Pedidos;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class DailySalesChart extends ApexChartWidget
{
    protected static ?string $chartId = 'dailySalesChart';

    protected static ?string $heading = 'Total Ventas del Día';

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 2;

    protected function getOptions(): array
    {
        $data = Pedidos::select('total_pedido')->whereDate('created_at', today())->get();

        return [
            'chart' => [
                'type' => 'area',
                'height' => 200,
                'toolbar' => ['show' => false],
            ],
            'series' => [
                [
                    'name' => 'Ventas ($)',
                    'data' => $data->pluck('total_pedido')->toArray(), // Aquí conectarías con tu lógica de BD
                ],
            ],
            'xaxis' => [
                'categories' => ['10:00', '12:00', '14:00', '16:00', '18:00', '20:00'],
                'labels' => ['style' => ['colors' => '#9ca3af']],
            ],
            // Aplicando tu gama de colores
            'colors' => ['#22819A'],
            'stroke' => ['curve' => 'smooth', 'width' => 3],
            'fill' => [
                'type' => 'gradient',
                'gradient' => [
                    'shadeIntensity' => 1,
                    'opacityFrom' => 0.45,
                    'opacityTo' => 0.05,
                    'stops' => [20, 100, 100],
                ],
            ],
            'dataLabels' => ['enabled' => false],
            'theme' => [
                'mode' => 'light', // Filament ApexCharts maneja el cambio a dark automáticamente si está bien configurado
            ],
        ];
    }
}
