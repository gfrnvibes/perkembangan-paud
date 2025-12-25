<?php

namespace App\Filament\Resources\AsArtworks\Pages;

use App\Filament\Resources\AsArtworks\AsArtworkResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageAsArtworks extends ManageRecords
{
    protected static string $resource = AsArtworkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
