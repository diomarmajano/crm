<?php

namespace App\Filament\Resources\Services\Schemas;

use App\Models\Category;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Support\RawJs;

class ServicesForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Wizard::make([
                    Step::make('Datos del producto')
                        ->icon(Heroicon::Pencil)
                        ->completedIcon(Heroicon::HandThumbUp)
                        ->schema([
                            Grid::make([
                                'sm' => 3,
                            ])
                                ->schema([
                                    TextInput::make('service_name')
                                        ->label('Servicio')
                                        ->required()
                                        ->columnSpan('full'),

                                    TextInput::make('sku')
                                        ->label('Sku del producto'),

                                    TextInput::make('codigo')
                                        ->label('código de barra'),

                                    Select::make('id_category')
                                        ->label('Categoría')
                                        ->options(Category::pluck('nombre_categoria', 'id'))
                                        ->required()
                                        ->searchable(),

                                ]),

                        ]),
                    Step::make('Precio y detalles')
                        ->icon(Heroicon::Banknotes)
                        ->completedIcon(Heroicon::HandThumbUp)
                        ->schema([
                            Grid::make([
                                'sm' => 2,
                            ])
                                ->schema([
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
                                        ->label('detalles del producto')
                                        ->columnSpan('full'),
                                ]),
                        ]),

                    Step::make('Detalles opcional')
                        ->icon(Heroicon::Cog6Tooth)
                        ->completedIcon(Heroicon::HandThumbUp)
                        ->schema([
                            Grid::make([
                                'sm' => 2,
                            ])
                                ->schema([
                                    DatePicker::make('fecha_vencimiento')
                                        ->label('Vencimiento del producto')
                                        ->native(false),

                                    TextInput::make('service_icon')
                                        ->label('Url de la imagen'),

                                    Toggle::make('is_active')
                                        ->label('¿Servicio Activo?')
                                        ->default(true),
                                ]),
                        ]),
                ])
                    ->columnSpanFull(),
            ]);
    }
}
