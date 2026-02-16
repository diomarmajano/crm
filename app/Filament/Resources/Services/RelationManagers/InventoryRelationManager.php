<?php

namespace App\Filament\Resources\Services\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

// Importante

class InventoryRelationManager extends RelationManager
{
    protected static string $relationship = 'inventory';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('stock_producto'),
                TextInput::make('stock_minimo'),
                TextInput::make('precio_compra'),
                TextInput::make('precio_venta'),

            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('stock_producto')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('stock_minimo')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('precio_compra')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('precio_venta')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('ganancia_unitaria')
                    ->label('Margen/Ud')
                    ->state(function ($record) {
                        // Calculamos la diferencia
                        return $record->precio_venta - $record->precio_compra;
                    })
                    ->money('clp')
                    ->badge() // Aquí está el badge que pediste
                    ->color(fn (string $state): string => $state > 0 ? 'success' : 'danger'),

                // 2. Valor Total del Inventario (Costo)
                TextColumn::make('valor_inventario')
                    ->label('Valor Stock')
                    ->state(fn ($record) => $record->stock_producto * $record->precio_compra)
                    ->money('clp')
                    ->color('gray')
                    ->toggleable(),

                // 3. Utilidad Total Estimada (Si vendes todo)
                TextColumn::make('utilidad_total')
                    ->label('Utilidad Est.')
                    ->state(function ($record) {
                        $ganancia = $record->precio_venta - $record->precio_compra;

                        return $record->stock_producto * $ganancia;
                    })
                    ->money('clp')
                    ->weight('bold') // Negrita para resaltar
                    ->color('success'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
                CreateAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
