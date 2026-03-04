<?php

namespace App\Filament\Resources\Services;

use App\Filament\Resources\Services\Pages\CreateServices;
use App\Filament\Resources\Services\Pages\EditServices;
use App\Filament\Resources\Services\Pages\ListServices;
use App\Filament\Resources\Services\RelationManagers\InventoryRelationManager;
use App\Filament\Resources\Services\Schemas\ServicesForm;
use App\Filament\Resources\Services\Tables\ServicesTable;
use App\Models\Services;
use BackedEnum;
use Carbon\Carbon;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ServicesResource extends Resource
{
    protected static ?string $model = Services::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::ListBullet;

    protected static ?string $recordTitleAttribute = 'Services';

    protected static ?string $pluralModelLabel = 'Productos';

    protected static ?string $modelLabel = 'Producto';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return ServicesForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ServicesTable::configure($table);
    }

    public static function canViewAny(): bool
    {
        // Solo pueden ver este recurso los usuarios que tengan un tenant asignado
        return auth()->user()->tenant_id !== null;
    }

    public static function getNavigationBadge(): ?string
    {
        $tenant = auth()->user()->tenant;

        if (! $tenant) {
            return null;
        }
        $diasAlerta = $tenant->settings['dias_alerta_vencimiento'] ?? 30;

        $fechaLimite = Carbon::now()->addDays((int) $diasAlerta);

        $cantidadAlertas = static::getModel()::query()
            ->whereNotNull('fecha_vencimiento')
            ->whereDate('fecha_vencimiento', '<=', $fechaLimite)
            ->whereDate('fecha_vencimiento', '>=', Carbon::now())
            ->count();

        return $cantidadAlertas > 0 ? (string) $cantidadAlertas : null;
    }

    public static function getRelations(): array
    {
        return [
            InventoryRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListServices::route('/'),
            'create' => CreateServices::route('/create'),
            'edit' => EditServices::route('/{record}/edit'),
        ];
    }
}
