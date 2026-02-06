<?php

namespace App\Filament\Resources\Clientes\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ClientesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tenant.name')
                    ->label('Sucursal'),

                TextColumn::make('cliente_name')
                    ->label('Cliente')
                    ->icon('heroicon-s-user-circle')
                    ->iconColor('primary')
                    ->searchable(),

                TextColumn::make('cliente_telefono')
                    ->label('Teléfono')
                    ->icon('heroicon-s-phone')
                    ->iconColor('primary')
                    ->searchable(),

                TextColumn::make('cliente_email')
                    ->label('Email')
                    ->icon('heroicon-s-envelope')
                    ->iconColor('primary'),

                TextColumn::make('cliente_direccion')
                    ->label('Dirección')
                    ->icon('heroicon-s-map-pin')
                    ->iconColor('primary'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([

            ]);
    }
}
