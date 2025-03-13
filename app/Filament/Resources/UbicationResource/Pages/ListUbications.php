<?php

namespace App\Filament\Resources\UbicationResource\Pages;

use App\Filament\Resources\UbicationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUbications extends ListRecords
{
    protected static string $resource = UbicationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
