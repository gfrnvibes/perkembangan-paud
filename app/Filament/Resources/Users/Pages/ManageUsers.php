<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageUsers extends ManageRecords
{
    protected static string $resource = UserResource::class;
    public function getHeading(): string
    {
        $user = auth()->user();

        if ($user && $user->hasRole('super_admin')) {
            return 'Users';
        }

        return 'Orang Tua Siswa';
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Tambah Ortu Siswa')
                ->modalHeading('Buat Data Orang Tua Siswa Baru'),
        ];
    }
}
