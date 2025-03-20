<?php

namespace App\Filament\Resources\UbicationResource\Pages;

use App\Filament\Resources\UbicationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUbications extends ListRecords
{
    // Associates this page with UbicationResource
    protected static string $resource = UbicationResource::class;

    // Customizes header actions for this page (e.g., adding a 'Create' button)
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(), // Add a button for creating new Ubications
        ];
    }
}