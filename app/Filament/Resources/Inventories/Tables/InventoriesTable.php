<?php

namespace App\Filament\Resources\Inventories\Tables;

use App\Models\InventoryMovement;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class InventoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('service.service_name')
                    ->label('producto')
                    ->badge()
                    ->color('primary'),
                TextColumn::make('stock_producto')
                    ->numeric()
                    ->color(fn ($record) => $record->stock_producto <= $record->stock_minimo ? 'danger' : 'success')
                    ->icon(fn ($record) => $record->stock_producto <= $record->stock_minimo ? 'heroicon-m-exclamation-triangle' : 'heroicon-m-check-circle')
                    ->badge(),
                TextColumn::make('stock_minimo')
                    ->numeric()
                    ->icon(Heroicon::CheckBadge),
                TextColumn::make('precio_compra')
                    ->numeric()
                    ->money('clp')
                    ->icon(Heroicon::CurrencyDollar)
                    ->iconColor('success'),
                TextColumn::make('precio_venta')
                    ->numeric()
                    ->money('clp')
                    ->icon(Heroicon::CurrencyDollar)
                    ->iconColor('success'),

                TextColumn::make('ganancia_unitaria')
                    ->label('Margen')
                    ->state(function ($record) {
                        return $record->precio_venta - $record->precio_compra;
                    })
                    ->money('clp')
                    ->badge()
                    ->color(fn (string $state): string => $state > 0 ? 'success' : 'danger'),

                // 2. Valor Total del Inventario (Costo)
                TextColumn::make('valor_inventario')
                    ->label('Capital Invertido')
                    ->state(fn ($record) => $record->precio_compra * $record->stock_producto)
                    ->money('clp')
                    ->badge()
                    ->color('gray'),

                // 3. Utilidad Total Estimada (Si vendes todo)
                TextColumn::make('utilidad_total')
                    ->label('Utilidad Est.')
                    ->state(function ($record) {
                        $ganancia = $record->precio_venta - $record->precio_compra;

                        return $record->stock_producto * $ganancia;
                    })
                    ->money('clp')
                    ->badge()
                    ->color('primary'),
            ])
            ->filters([
                Filter::make('bajo_stock')
                    ->label('⚠️ Stock Crítico')
                    ->query(fn (Builder $query) => $query->whereColumn('stock_producto', '<=', 'stock_minimo'))
                    ->toggle(), // Esto lo hace un switch on/off
            ])
            ->recordActions([
                Action::make('ajustar_stock')
                    ->label('Ajustar Stock')
                    ->icon('heroicon-o-arrows-right-left')
                    ->color('warning')
                    ->form([
                        Select::make('tipo')
                            ->options([
                                'entrada' => 'Entrada (+)',
                                'salida' => 'Salida (-)',
                                'ajuste' => 'Ajuste por Pérdida/Merma (-)',
                            ])
                            ->required()
                            ->label('Tipo de Movimiento'),
                        TextInput::make('cantidad')
                            ->numeric()
                            ->minValue(1)
                            ->required()
                            ->label('Cantidad'),
                        TextInput::make('motivo')
                            ->required()
                            ->maxLength(255)
                            ->label('Motivo / Comentario'),
                    ])
                    ->action(function ($record, array $data) {
                        DB::transaction(function () use ($record, $data) {
                            $cantidad = (int) $data['cantidad'];
                            $stockAnterior = $record->stock_producto;

                            // Calculamos el nuevo stock dependiendo del tipo
                            $stockNuevo = $data['tipo'] === 'entrada'
                                ? $stockAnterior + $cantidad
                                : $stockAnterior - $cantidad;

                            // Evitar que el stock quede en negativo si es salida/ajuste
                            if ($stockNuevo < 0) {
                                Notification::make()
                                    ->title('Error de validación')
                                    ->body('No puedes restar más stock del que tienes disponible.')
                                    ->danger()
                                    ->send();

                                throw new \Exception('Stock negativo no permitido'); // Detiene la transacción
                            }

                            // 1. Registramos el movimiento
                            InventoryMovement::create([
                                'inventory_id' => $record->id,
                                'tenant_id' => $record->tenant_id,
                                'user_id' => auth()->id(),
                                'tipo' => $data['tipo'],
                                'cantidad' => $cantidad,
                                'stock_anterior' => $stockAnterior,
                                'stock_nuevo' => $stockNuevo,
                                'motivo' => $data['motivo'],
                            ]);

                            // 2. Actualizamos el stock principal
                            $record->update(['stock_producto' => $stockNuevo]);

                            Notification::make()
                                ->title('Stock actualizado')
                                ->success()
                                ->send();
                        });
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
