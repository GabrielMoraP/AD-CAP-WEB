<?php

namespace App\Filament\Resources\UbicationResource\Pages;

use App\Filament\Resources\UbicationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUbication extends EditRecord
{
    protected static string $resource = UbicationResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }
}
