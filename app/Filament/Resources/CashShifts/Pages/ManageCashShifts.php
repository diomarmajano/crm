<?php

namespace App\Filament\Resources\CashShifts\Pages;

use App\Filament\Resources\CashShifts\CashShiftResource;
use App\Models\CashShift;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageCashShifts extends ManageRecords
{
    protected static string $resource = CashShiftResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Abrir Nueva Caja')
                ->icon('heroicon-o-play')
                // Verifica si hay una caja abierta para este tenant/usuario
                ->hidden(fn () => CashShift::where('tenant_id', auth()->user()?->tenant_id)
                    ->where('estado', 'abierta')
                    ->exists())
                // Al crear, inicializamos el saldo_esperado igual al inicial
                ->mutateFormDataUsing(function (array $data): array {
                    $data['saldo_esperado'] = $data['saldo_inicial'];

                    return $data;
                }),
        ];
    }
}
