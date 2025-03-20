<?php

namespace App\Filament\Resources\UbicationResource\Pages;

use App\Filament\Resources\UbicationResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateUbication extends CreateRecord
{
    // Associates this page with UbicationResource
    protected static string $resource = UbicationResource::class;

    // Redirects back to the index page (list) after creating a new record
    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }
}