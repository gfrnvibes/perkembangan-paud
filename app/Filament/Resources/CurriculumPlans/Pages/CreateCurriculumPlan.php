<?php

namespace App\Filament\Resources\CurriculumPlans\Pages;

use App\Filament\Resources\CurriculumPlans\CurriculumPlanResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCurriculumPlan extends CreateRecord
{
    protected static string $resource = CurriculumPlanResource::class;

    public function getHeading(): string
    {
        return 'Buat RPP';
    }
}
