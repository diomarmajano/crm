<?php

namespace App\Filament\Resources\Inventories\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Support\RawJs;

class InventoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('id_service')
                    ->label('producto')
                    ->relationship('service', 'service_name')
                    ->searchable()
                    ->preload()
                    ->hidden(fn ($livewire) => $livewire instanceof RelationManager),
                TextInput::make('stock_producto')
                    ->required()
                    ->numeric()
                    ->disabled()
                    ->default(0)
                    ->disabledOn('edit')
                    ->dehydrated(),
                TextInput::make('stock_minimo')
                    ->required()
                    ->numeric(),
                TextInput::make('precio_compra')
                    ->required()
                    ->numeric()
                    ->mask(RawJs::make('$money($input)'))
                    ->stripCharacters(','),
                TextInput::make('precio_venta')
                    ->required()
                    ->numeric()
                    ->mask(RawJs::make('$money($input)'))
                    ->stripCharacters(',')
                    ->default(function ($livewire) {
                        // Verificamos si estamos dentro del RelationManager
                        if ($livewire instanceof RelationManager) {
                            // Obtenemos el producto padre y devolvemos su precio
                            return $livewire->getOwnerRecord()->service_precio;
                        }

                        return null; // Si creas el inventario desde otro lado, queda vacío
                    }),
            ]);
    }
}
