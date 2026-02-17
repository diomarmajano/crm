<?php

namespace App\Filament\Resources\Inventories\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class InventoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('service.service_name')
                    ->label('producto')
                    ->badge()
                    ->color('primary'),
                TextColumn::make('stock_producto')
                    ->numeric()
                    ->numeric()
                // Color: Rojo (danger) si es menor/igual al mínimo, Verde (success) si está bien
                    ->color(fn ($record) => $record->stock_producto <= $record->stock_minimo ? 'danger' : 'success')
                // Ícono: Heroicon de alerta si es bajo
                    ->icon(fn ($record) => $record->stock_producto <= $record->stock_minimo ? 'heroicon-m-exclamation-triangle' : 'heroicon-m-check-circle')
                    ->badge(),
                TextColumn::make('stock_minimo')
                    ->numeric(),
                TextColumn::make('precio_compra')
                    ->numeric(),
                TextColumn::make('precio_venta')
                    ->numeric(),

                TextColumn::make('ganancia_unitaria')
                    ->label('Margen')
                    ->state(function ($record) {
                        return $record->precio_venta - $record->precio_compra;
                    })
                    ->money('clp')
                    ->badge()
                    ->color(fn (string $state): string => $state > 0 ? 'success' : 'danger'),

                // 2. Valor Total del Inventario (Costo)
                TextColumn::make('Recaudación')
                    ->label('Valor Stock')
                    ->state(fn ($record) => $record->precio_venta * $record->stock_producto)
                    ->money('clp')
                    ->color('gray')
                    ->badge()
                    ->color('success'),

                // 3. Utilidad Total Estimada (Si vendes todo)
                TextColumn::make('utilidad_total')
                    ->label('Utilidad Est.')
                    ->state(function ($record) {
                        $ganancia = $record->precio_venta - $record->precio_compra;

                        return $record->stock_producto * $ganancia;
                    })
                    ->money('clp')
                    ->badge()
                    ->color('primary'),
            ])
            ->filters([
                Filter::make('bajo_stock')
                    ->label('⚠️ Stock Crítico')
                    ->query(fn (Builder $query) => $query->whereColumn('stock_producto', '<=', 'stock_minimo'))
                    ->toggle(), // Esto lo hace un switch on/off
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
