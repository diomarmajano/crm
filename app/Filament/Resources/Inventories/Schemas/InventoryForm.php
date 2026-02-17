<?php

namespace App\Filament\Resources\Inventories\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
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
                    ->preload(),
                TextInput::make('stock_producto')
                    ->required()
                    ->numeric(),
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
                    ->stripCharacters(','),
            ]);
    }
}
