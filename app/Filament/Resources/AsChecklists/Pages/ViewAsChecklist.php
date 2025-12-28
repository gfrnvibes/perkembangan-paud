<?php

namespace App\Filament\Resources\AsChecklists\Pages;

use App\Filament\Resources\AsChecklists\AsChecklistResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewAsChecklist extends ViewRecord
{
    protected static string $resource = AsChecklistResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // EditAction::make(),
        ];
    }
}
