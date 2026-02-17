<?php

namespace App\Filament\Resources\Pedidos;

use App\Filament\Resources\Pedidos\Pages\CreatePedidos;
use App\Filament\Resources\Pedidos\Pages\EditPedidos;
use App\Filament\Resources\Pedidos\Pages\ListPedidos;
use App\Filament\Resources\Pedidos\RelationManagers\ItemsRelationManager;
use App\Filament\Resources\Pedidos\Schemas\PedidosForm;
use App\Filament\Resources\Pedidos\Tables\PedidosTable;
use App\Models\Pedidos;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PedidosResource extends Resource
{
    protected static ?string $model = Pedidos::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::ClipboardDocumentList;

    protected static ?string $recordTitleAttribute = 'Pedidos';

    protected static ?string $pluralModelLabel = 'Pedidos';

    protected static ?string $modelLabel = 'Pedido';

    protected static ?int $navigationSort = 2;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return PedidosForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PedidosTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            ItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPedidos::route('/'),
            'create' => CreatePedidos::route('/create'),
            'edit' => EditPedidos::route('/{record}/edit'),
        ];
    }
}
