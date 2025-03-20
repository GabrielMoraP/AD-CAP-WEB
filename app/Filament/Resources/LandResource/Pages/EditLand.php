<?php

namespace App\Filament\Resources\LandResource\Pages;

use App\Filament\Resources\LandResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLand extends EditRecord
{
    // Associates this page with the LandResource
    protected static string $resource = LandResource::class;

    // Redirect the user after editing a land record
    protected function getRedirectUrl(): string
    {
        // Redirects to the previous page if it exists, otherwise to the Land index page
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }
}