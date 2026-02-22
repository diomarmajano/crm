<?php

namespace App\Filament\Resources\Inventories\RelationManagers;

use App\Filament\Resources\Inventories\InventoryResource;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MovementsRelationManager extends RelationManager
{
    protected static string $relationship = 'movements';

    protected static ?string $relatedResource = InventoryResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')
                    ->label('Fecha')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                TextColumn::make('tipo')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'entrada' => 'success',
                        'salida' => 'danger',
                        'ajuste' => 'warning',
                        default => 'gray',
                    }),
                TextColumn::make('cantidad')
                    ->numeric(),
                TextColumn::make('stock_anterior')
                    ->label('Stock Ant.')
                    ->numeric(),
                TextColumn::make('stock_nuevo')
                    ->label('Stock Nuevo')
                    ->numeric(),
                TextColumn::make('motivo')
                    ->limit(50)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();

                        return strlen($state) > 50 ? $state : null;
                    }),
                TextColumn::make('user.name') // Asegúrate de tener la relación 'user' en InventoryMovement
                    ->label('Usuario'),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                //
            ])
            ->headerActions([
                // Vacío. ¡Nadie puede crear movimientos a mano desde aquí!
            ])
            ->actions([
                // Vacío. ¡El historial no se edita ni se borra!
            ])
            ->bulkActions([
                // Vacío
            ]);
    }
}
