<?php

namespace App\Filament\Resources\CpElements\Pages;

use App\Filament\Resources\CpElements\CpElementResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageCpElements extends ManageRecords
{
    protected static string $resource = CpElementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
