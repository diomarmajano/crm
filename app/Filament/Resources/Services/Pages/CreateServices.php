<?php

namespace App\Filament\Resources\Services\Pages;

use App\Filament\Resources\Services\ServicesResource;
use Filament\Resources\Pages\CreateRecord;

class CreateServices extends CreateRecord
{
    protected static string $resource = ServicesResource::class;

    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
