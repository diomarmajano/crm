<?php

namespace App\Filament\Widgets;

use App\Models\Inventory;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class LowStockProducts extends TableWidget
{
    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 2;

    protected function getTableQuery(): Builder
    {
        // Asumiendo que tu modelo es Inventory
        return Inventory::query()->whereColumn('stock_producto', '<=', 'stock_minimo');
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('service.service_name')
                    ->label('producto')
                    ->badge(),

                TextColumn::make('stock_producto')
                    ->label('Stock Actual')
                    ->numeric()
                    ->badge()
                    ->color(fn ($record) => $record->stock_producto <= $record->stock_minimo ? 'danger' : 'success')
                    ->icon(fn ($record) => $record->stock_producto <= $record->stock_minimo ? 'heroicon-m-exclamation-triangle' : 'heroicon-m-check-circle'),
                TextColumn::make('stock_minimo')
                    ->numeric(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->recordActions([
                //
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
    }
}
