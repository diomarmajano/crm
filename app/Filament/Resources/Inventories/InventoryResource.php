<?php

namespace App\Filament\Resources\Inventories;

use App\Filament\Resources\Inventories\Pages\CreateInventory;
use App\Filament\Resources\Inventories\Pages\EditInventory;
use App\Filament\Resources\Inventories\Pages\ListInventories;
use App\Filament\Resources\Inventories\RelationManagers\MovementsRelationManager;
use App\Filament\Resources\Inventories\Schemas\InventoryForm;
use App\Filament\Resources\Inventories\Tables\InventoriesTable;
use App\Models\Inventory;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class InventoryResource extends Resource
{
    protected static ?string $model = Inventory::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::ArchiveBox;

    protected static ?int $navigationSort = 4;

    protected static ?string $recordTitleAttribute = 'Inventario';

    protected static ?string $pluralModelLabel = 'Inventario';

    protected static ?string $modelLabel = 'Inventario';

    public static function form(Schema $schema): Schema
    {
        return InventoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return InventoriesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            MovementsRelationManager::class,
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getNavigationBadge(): ?string
    {
        // Cuenta cuántos registros cumplen la condición de alerta
        $stock = static::getModel()::whereColumn('stock_producto', '<=', 'stock_minimo')->count();
        if ($stock != 0) {
            return (string) $stock;
        }

        return null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        // Si hay más de 0, el badge es rojo.
        return static::getModel()::whereColumn('stock_producto', '<=', 'stock_minimo')->exists()
            ? 'danger'
            : 'primary';
    }

    public static function getPages(): array
    {
        return [
            'index' => ListInventories::route('/'),
            // 'create' => CreateInventory::route('/create'),
            'edit' => EditInventory::route('/{record}/edit'),
        ];
    }
}
