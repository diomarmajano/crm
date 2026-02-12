<?php

namespace App\Filament\Resources\Clientes\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ClientesForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('cliente_name')
                    ->label('Nombre del Cliente'),
                TextInput::make('cliente_telefono')
                    ->label('Teléfono')
                    ->tel(),
                TextInput::make('cliente_email')
                    ->label('Email')
                    ->email(),
                TextInput::make('cliente_direccion')
                    ->label('Dirección'),
            ]);
    }
}
