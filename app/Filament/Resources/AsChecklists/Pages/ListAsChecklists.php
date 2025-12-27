<?php

namespace App\Filament\Resources\AsChecklists\Pages;

use App\Filament\Resources\AsChecklists\AsChecklistResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAsChecklists extends ListRecords
{
    protected static string $resource = AsChecklistResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Tambah Checklist'),
        ];
    }
}
