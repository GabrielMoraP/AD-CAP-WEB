<?php

namespace App\Filament\Resources\LandResource\Pages;

use App\Filament\Resources\LandResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLands extends ListRecords
{
    // Associates this page with the LandResource
    protected static string $resource = LandResource::class;

    // Adds actions to the header, like creating new records
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(), // Adds a "Create" button to the header
        ];
    }
}