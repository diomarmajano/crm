<?php

namespace App\Filament\Resources\Services\Tables;

use Carbon\Carbon;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class ServicesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tenant.name')
                    ->label('Sucursal')
                    ->badge(),

                TextColumn::make('service_name')
                    ->label('Servicio')
                    ->icon('heroicon-s-cube')
                    ->iconColor('primary')
                    ->searchable()
                    ->formatStateUsing(fn ($state) => Str::title($state)),

                TextColumn::make('service_precio')
                    ->label('Precio')
                    ->icon('heroicon-s-currency-dollar')
                    ->iconColor('primary')
                    ->money('ClP'),

                TextColumn::make('fecha_vencimiento')
                    ->label('Vencimiento')
                    ->badge()
                    ->color('primary'),

                ToggleColumn::make('is_active')
                    ->label('Servicio Activo'),
            ])
            ->filters([
                Filter::make('proximos_vencer')
                    ->label('Próximos a vencer')
                    ->toggle() // Se mostrará como un interruptor (switch) elegante
                    ->query(function (Builder $query) {
                        $tenant = auth()->user()->tenant;

                        if (! $tenant) {
                            return $query;
                        }

                        // Leemos los días configurados por este tenant específico
                        $diasAlerta = $tenant->settings['dias_alerta_vencimiento'] ?? 30;
                        $fechaLimite = Carbon::now()->addDays((int) $diasAlerta);

                        // Aplicamos la misma lógica que en el badge
                        return $query
                            ->whereNotNull('fecha_vencimiento')
                            ->whereDate('fecha_vencimiento', '<=', $fechaLimite)
                            ->whereDate('fecha_vencimiento', '>=', Carbon::now());
                    }),

                // Un filtro extra muy útil para la operación diaria
                Filter::make('ya_vencidos')
                    ->label('Ya vencidos')
                    ->toggle()
                    ->query(fn (Builder $query) => $query
                        ->whereNotNull('fecha_vencimiento')
                        ->whereDate('fecha_vencimiento', '<', Carbon::now())
                    ),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
