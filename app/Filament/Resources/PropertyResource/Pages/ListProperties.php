<?php

namespace App\Filament\Resources\PropertyResource\Pages;

use App\Filament\Resources\PropertyResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProperties extends ListRecords
{
    // Associates this page with PropertyResource
    protected static string $resource = PropertyResource::class;

    // Defines the actions in the header (top of the page)
    protected function getHeaderActions(): array
    {
        return [
            // Adds a "Create" button to add new properties
            Actions\CreateAction::make(),
        ];
    }
}