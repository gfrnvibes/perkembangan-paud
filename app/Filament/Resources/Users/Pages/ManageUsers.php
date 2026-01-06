<?php

namespace App\Filament\Resources\Users\Pages;

use Filament\Actions\CreateAction;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\ManageRecords;
use App\Filament\Resources\Users\UserResource;

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
        $isSuperAdmin = auth()->user()?->hasRole('super_admin');

        return [
            CreateAction::make()
                ->label($isSuperAdmin ? 'Buat User' : 'Tambah Ortu Siswa')
                ->modalHeading($isSuperAdmin ? 'Buat User Baru' : 'Buat Data Orang Tua Siswa Baru'),
        ];
    }

    protected function handleRecordCreation(array $data): Model
    {
        $user = static::getModel()::create($data);

        if (auth()->user()->hasRole('teacher')) {
            $user->assignRole('parent');
        }

        return $user;
    }

}
