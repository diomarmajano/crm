<?php

namespace App\Filament\Resources\Pedidos\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\RawJs;

class PedidosForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Información del Pedido')
                    ->schema([
                        // Cliente
                        Select::make('user_id')
                            ->relationship('user', 'name')
                            ->columnSpan(1)
                            ->disabled(),

                        TextInput::make('total_pedido')
                            ->label('Total Pagado')
                            ->prefix('$')
                            ->mask(RawJs::make('$money($input)'))
                            ->stripCharacters(',')
                            ->numeric()
                            ->disabled(),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
