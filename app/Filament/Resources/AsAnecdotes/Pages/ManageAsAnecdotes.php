<?php

namespace App\Filament\Resources\AsAnecdotes\Pages;

use App\Filament\Resources\AsAnecdotes\AsAnecdoteResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageAsAnecdotes extends ManageRecords
{
    protected static string $resource = AsAnecdoteResource::class;
    protected ?string $heading = 'Catatan Anekdot';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Tambah Catatan Anekdot')
                ->modalHeading('Buat Catatan Anekdot Siswa'),
        ];
    }
}
