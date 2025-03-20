<?php

namespace App\Filament\Resources\PropertyResource\Pages;

use App\Filament\Resources\PropertyResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateProperty extends CreateRecord
{
    // Associates this page with PropertyResource
    protected static string $resource = PropertyResource::class;

    // Redirects after creating a record
    protected function getRedirectUrl(): string
    {
        // Redirects to the previous page or the index page if no previous URL is available
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }
}
