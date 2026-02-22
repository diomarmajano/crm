<?php

namespace App\Filament\Resources\CashShifts\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MovementsRelationManager extends RelationManager
{
    protected static string $relationship = 'movements';

    protected static ?string $title = 'Movimientos del Turno';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([

            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('tipo')
            ->columns([
                TextColumn::make('created_at')->label('Hora')->time('H:i')
                    ->icon(Heroicon::Clock)
                    ->iconColor('primary'),

                TextColumn::make('tipo')
                    ->colors([
                        'success' => 'ingreso',
                        'danger' => 'egreso',
                    ])
                    ->badge(),

                TextColumn::make('concepto')
                    ->icon(Heroicon::ListBullet)
                    ->iconColor('primary'),

                TextColumn::make('metodo_pago')
                    ->label('Método')
                    ->badge()
                    ->color('primary'),
                TextColumn::make('monto')
                    ->money('clp')
                    ->weight('bold')
                    ->icon(Heroicon::CurrencyDollar)
                    ->iconColor('primary'),
            ])
            ->defaultSort('created_at', 'desc')
            ->headerActions([]) // Solo lectura
            ->filters([
                //
            ])
            ->headerActions([

            ])
            ->recordActions([

            ])
            ->toolbarActions([

            ]);
    }
}
