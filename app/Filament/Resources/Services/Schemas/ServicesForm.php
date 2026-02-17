<?php

namespace App\Filament\Resources\Services\Schemas;

use App\Models\Category;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Support\RawJs;

class ServicesForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('service_name')
                    ->label('Servicio')
                    ->required(),

                TextInput::make('sku')
                    ->label('Sku del producto'),

                TextInput::make('codigo')
                    ->label('código del producto'),

                Select::make('id_category')
                    ->label('Categoría')
                    ->options(Category::all()->pluck('nombre_categoria', 'id'))
                    ->required(),

                TextInput::make('service_precio')
                    ->label('Precio')
                    ->required()
                    ->prefix('$')
                    ->mask(RawJs::make('$money($input)'))
                    ->stripCharacters(',')
                    ->numeric(),

                TextInput::make('precio_promocion')
                    ->label('Precio en promocion')
                    ->prefix('$')
                    ->mask(RawJs::make('$money($input)'))
                    ->stripCharacters(',')
                    ->numeric(),

                TextInput::make('detalles')
                    ->label('detalles del producto'),

                DatePicker::make('fecha_vencimiento')
                    ->label('Vencimiento del producto'),

                TextInput::make('service_icon')
                    ->label('Url de la imagen'),

                Toggle::make('is_active')
                    ->label('¿Servicio Activo?')
                    ->default(true),
            ]);
    }
}
