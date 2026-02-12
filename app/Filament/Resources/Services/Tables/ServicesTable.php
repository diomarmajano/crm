<?php

namespace App\Filament\Resources\Services\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

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
                    ->searchable(),

                TextColumn::make('service_precio')
                    ->label('Precio')
                    ->icon('heroicon-s-currency-dollar')
                    ->iconColor('primary')
                    ->money('ClP'),

                ToggleColumn::make('is_active')
                    ->label('Servicio Activo'),
            ])
            ->filters([
                //
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
