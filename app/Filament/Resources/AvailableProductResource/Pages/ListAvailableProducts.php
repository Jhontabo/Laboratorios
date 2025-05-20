<?php

namespace App\Filament\Resources\AvailableProductResource\pages;


use App\Filament\Resources\AvailableProductResource;
use Filament\Resources\Pages\ListRecords;

class ListAvailableProducts extends ListRecords
{
    protected static string $resource = AvailableProductResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
