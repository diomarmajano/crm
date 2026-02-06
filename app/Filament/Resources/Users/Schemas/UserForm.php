<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Models\Tenant;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // 1. Selector de Tenant (Solo visible para ti)
                Select::make('tenant_id')
                    ->label('Lavandería')
                    ->options(Tenant::all()->pluck('name', 'id'))
                    ->searchable()
                    ->required()
                    ->visible(fn () => auth()->user()->hasRole('super_admin')), // <--- Seguridad Clave

                // 2. Datos del Usuario
                TextInput::make('name')->required(),
                TextInput::make('email')->email()->required(),
                TextInput::make('password')->password()->required()->hiddenOn('edit'),

                // 3. Selector de Roles
                Select::make('roles')
                    ->relationship('roles', 'name')
                    ->multiple()
                    ->preload()
                    ->required(),
            ]);
    }
}
