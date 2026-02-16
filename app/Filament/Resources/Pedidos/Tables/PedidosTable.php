<?php

namespace App\Filament\Resources\Pedidos\Tables;

use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;

class PedidosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tenant.name')
                    ->label('Sucursal')
                    ->icon('heroicon-s-building-office')
                    ->iconColor('primary'),

                TextColumn::make('user.name')
                    ->label('Vendedor')
                    ->icon('heroicon-s-user')
                    ->iconColor('primary')
                    ->searchable(),

                TextColumn::make('total_pedido')
                    ->label('Total Pagado')
                    ->icon('heroicon-s-currency-dollar')
                    ->iconColor('primary')
                    ->money('CLP'),

                SelectColumn::make('medio_pago')
                    ->label('Medio de Pago')
                    ->native(false)
                    ->options([
                        'efectivo' => 'Efectivo',
                        'transferencia' => 'Transferencia',
                        'transbank' => 'Transbank',
                        'otro' => 'Otro',
                    ]),

                TextColumn::make('created_at')
                    ->label('Fecha')
                    ->icon('heroicon-s-calendar')
                    ->iconColor('primary')
                    ->dateTime('d/m/Y'),
            ])
            ->filters([
                Filter::make('fecha')
                    ->schema([
                        DatePicker::make('Dia')
                            ->label('Dia')
                            ->default(today())
                            ->native(false),
                    ])

                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['Dia'], fn ($q) => $q->whereDate('created_at', $data['Dia']));
                    }),
            ])
            ->recordActions([
                EditAction::make(),
                Action::make('descargarPDF')
                    ->label('PDF')
                    ->color('danger')
                    ->icon('heroicon-o-document-arrow-down')
                    ->action(function ($record) {
                        // $record es una instancia del modelo Pedidos automáticamente

                        $pdf = Pdf::loadView('pdf.boleta', ['venta' => $record]);

                        return response()->streamDownload(function () use ($pdf) {
                            echo $pdf->stream();
                        }, "factura_{$record->id}.pdf");
                    }),
            ])
            ->toolbarActions([

            ]);
    }
}
