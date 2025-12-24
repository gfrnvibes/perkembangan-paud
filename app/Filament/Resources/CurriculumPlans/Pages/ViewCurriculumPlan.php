<?php

namespace App\Filament\Resources\CurriculumPlans\Pages;

use App\Filament\Resources\CurriculumPlans\CurriculumPlanResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewCurriculumPlan extends ViewRecord
{
    protected static string $resource = CurriculumPlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
