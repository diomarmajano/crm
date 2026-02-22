<?php

namespace App\Filament\Resources\CashShifts\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\BadgeColumn;
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
                TextColumn::make('created_at')->label('Hora')->time('H:i'),
                BadgeColumn::make('tipo')
                    ->colors([
                        'success' => 'ingreso',
                        'danger' => 'egreso',
                    ]),
                TextColumn::make('concepto'),
                TextColumn::make('metodo_pago')->label('Método')->badge()->color('gray'),
                TextColumn::make('monto')->money('clp')->weight('bold'),
            ])
            ->defaultSort('created_at', 'desc')
            ->headerActions([]) // Solo lectura
            ->actions([]) // Solo lectura
            ->bulkActions([])
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
