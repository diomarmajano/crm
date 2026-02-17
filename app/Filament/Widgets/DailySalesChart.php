<?php

namespace App\Filament\Widgets;

use App\Models\Pedidos;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class DailySalesChart extends ApexChartWidget
{
    use HasWidgetShield;

    protected static ?string $chartId = 'dailySalesChart';

    protected static ?string $heading = 'Ventas por días del mes';

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 3;

    protected function getOptions(): array
    {
        $start = Carbon::now()->startOfMonth();
        $end = Carbon::now()->endOfMonth();

        $ventasPorDia = Pedidos::query()
            ->whereBetween('created_at', [$start, $end])
            ->selectRaw('DATE(created_at) as date, SUM(total_pedido) as total')
            ->groupBy('date')
            ->pluck('total', 'date'); // Crea un array asociativo ['2023-10-01' => 5000, ...]

        $dataSeries = [];
        $categories = [];

        $period = CarbonPeriod::create($start, $end);

        foreach ($period as $date) {
            $formatDate = $date->format('Y-m-d');

            // Si hay ventas ese día las pone, si no pone 0
            $dataSeries[] = $ventasPorDia->get($formatDate, 0);

            // Etiqueta para el Eje X (Solo el número del día)
            $categories[] = $date->format('d');
        }

        // $data = Pedidos::select('total_pedido')->whereDate('created_at', today())->get();

        return [
            'chart' => [
                'type' => 'area',
                'height' => 300, // Aumenté un poco la altura para ver mejor el mes
                'toolbar' => ['show' => false],
                'zoom' => [
                    'enabled' => false,
                ],
            ],
            'series' => [
                [
                    'name' => 'Ventas ($)',
                    'data' => $dataSeries,
                ],
            ],
            'xaxis' => [
                'categories' => $categories, // Los días del 01 al 30/31
                'labels' => ['style' => ['colors' => '#9ca3af']],
                'title' => ['text' => 'Día del Mes'], // Etiqueta opcional
            ],
            // Mantenemos tu estilo original
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
            'tooltip' => [
                'y' => [
                    'formatter' => "function (val) { return '$' + val }", // Formato de moneda en tooltip
                ],
            ],
            'theme' => [
                'mode' => 'light',
            ],
        ];
    }
}
