<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUsers extends ListRecords
{
    // Specifies the associated resource for this page (UserResource)
    protected static string $resource = UserResource::class;

    // Defines the header actions available on this page (e.g., a button to create a new record)
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(), // Create button for adding new users
        ];
    }
}