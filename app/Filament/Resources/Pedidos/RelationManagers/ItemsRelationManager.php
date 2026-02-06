<?php

namespace App\Filament\Resources\Pedidos\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nombre_servicio')
                    ->label('Servicio')
                    ->icon('heroicon-s-cube')
                    ->iconColor('primary'),

                TextColumn::make('cantidad')
                    ->label('Cantidad')
                    ->icon('heroicon-s-hashtag')
                    ->iconColor('primary')
                    ->numeric(),

                TextColumn::make('precio_unitario')
                    ->label('Precio Unitario')
                    ->icon('heroicon-s-currency-dollar')
                    ->iconColor('primary')
                    ->numeric()
                    ->money('CLP'),

                TextColumn::make('subtotal')
                    ->label('Subtotal')
                    ->icon('heroicon-s-calculator')
                    ->iconColor('primary')
                    ->numeric()
                    ->money('CLP'),
            ])
            ->headerActions([

            ]);
    }
}
