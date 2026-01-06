<?php

namespace App\Filament\Imports;

use Illuminate\Support\Number;
use Filament\Actions\Imports\Importer;
use App\Models\Curriculum\CurriculumPlan;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Models\Import;

class CurriculumPlanImporter extends Importer
{
    protected static ?string $model = CurriculumPlan::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('academicYear')
                ->relationship()
                ->label('Tahun Ajaran'),
            ImportColumn::make('semester')
                ->label('Semester'),
            ImportColumn::make('week_number')
                ->label('Minggu Ke-'),
            ImportColumn::make('theme')
                ->label('Tema'),
        ];
    }

    public function resolveRecord(): CurriculumPlan
    {
        return CurriculumPlan::firstOrNew([
            'id' => $this->data['id'],
        ]);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your curriculum plan import has completed and ' . Number::format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
