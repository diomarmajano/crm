<?php

namespace App\Filament\Resources\Pedidos\Pages;

use App\Filament\Resources\Pedidos\PedidosResource;
use App\Livewire\PedidosArqueo;
use Filament\Actions\CreateAction;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListPedidos extends ListRecords
{
    use ExposesTableToWidgets;

    protected static string $resource = PedidosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'Todos' => Tab::make(),
            'Efectivo' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('medio_pago', 'efectivo')),
            'Transferencia' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('medio_pago', 'transferencia')),
            'Transbank' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('medio_pago', 'transbank')),
            'Otro' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('medio_pago', 'otro')),
        ];
    }

    public function updatedActiveTab(): void
    {
        // Esto le dice explícitamente a Livewire: "Oye, cambié de tab, avisa a todos"
        $this->dispatch('refreshStats');
    }

    protected function getHeaderWidgets(): array
    {
        return [
            PedidosArqueo::class,
        ];
    }
}
