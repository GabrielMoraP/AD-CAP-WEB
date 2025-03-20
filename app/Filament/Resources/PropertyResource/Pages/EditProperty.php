<?php

namespace App\Filament\Resources\PropertyResource\Pages;

use App\Filament\Resources\PropertyResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProperty extends EditRecord
{
    // Associates this page with PropertyResource
    protected static string $resource = PropertyResource::class;

    // Redirects after editing a record
    protected function getRedirectUrl(): string
    {
        // Redirects to the previous page or the index page if no previous URL is available
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }
}