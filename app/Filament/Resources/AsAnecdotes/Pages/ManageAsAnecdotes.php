<?php

namespace App\Filament\Resources\AsAnecdotes\Pages;

use App\Filament\Resources\AsAnecdotes\AsAnecdoteResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageAsAnecdotes extends ManageRecords
{
    protected static string $resource = AsAnecdoteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
