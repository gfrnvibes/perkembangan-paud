<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageUsers extends ManageRecords
{
    protected static string $resource = UserResource::class;
    protected ?string $heading = 'Orang Tua Siswa';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->modalHeading('Buat Data Orang Tua Siswa Baru'),
        ];
    }
}
