<?php

namespace App\Filament\Resources\Services\Schemas;

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

                TextInput::make('service_precio')
                    ->label('Precio')
                    ->required()
                    ->prefix('$')
                    ->mask(RawJs::make('$money($input)'))
                    ->stripCharacters(',')
                    ->numeric(),

                TextInput::make('service_icon')
                    ->label('Url de la imagen'),

                Toggle::make('is_active')
                    ->label('¿Servicio Activo?')
                    ->default(true),
            ]);
    }
}
