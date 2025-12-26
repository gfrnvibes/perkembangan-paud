<?php

namespace App\Filament\Resources\Classrooms\Pages;

use App\Filament\Resources\Classrooms\ClassroomResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageClassrooms extends ManageRecords
{
    protected static string $resource = ClassroomResource::class;
    protected ?string $heading = 'Ruang Kelas';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->modalHeading('Buat Ruang Kelas'),
        ];
    }
}
