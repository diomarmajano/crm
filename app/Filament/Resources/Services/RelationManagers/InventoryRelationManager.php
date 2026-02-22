<?php

namespace App\Filament\Resources\Services\RelationManagers;

use App\Filament\Resources\Inventories\InventoryResource;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class InventoryRelationManager extends RelationManager
{
    protected static string $relationship = 'inventory';

    protected static ?string $relatedResource = InventoryResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make()
                    ->hidden(fn ($livewire) => $livewire->getOwnerRecord()->inventory()->exists()),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->filters([

            ]);
    }
}
