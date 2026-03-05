<?php

namespace App\Filament\Resources\ShippingBranchResource\Pages;

use App\Filament\Resources\ShippingBranchResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListShippingBranches extends ListRecords
{
    protected static string $resource = ShippingBranchResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
