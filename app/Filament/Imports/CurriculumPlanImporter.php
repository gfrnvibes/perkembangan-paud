<?php

namespace App\Filament\Imports;

use Illuminate\Support\Number;
use App\Models\Master\AcademicYear;
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
            ImportColumn::make('academic_year')->relationship('academicYear', 'year_range')->label('Tahun Ajaran'),

            ImportColumn::make('semester'),

            ImportColumn::make('week_number'),

            ImportColumn::make('theme'),

            ImportColumn::make('cpElements')
                // ->relationship('cpElements', 'name')
                // ->multiple(',')
                ->fillRecordUsing(fn($record, $state) => null)
                ->label('Elemen CP'),

            ImportColumn::make('topics')
                // ->multiple(',')
                ->fillRecordUsing(fn($record, $state) => null)
                ->label('Topik'),

            ImportColumn::make('learningObjectives')
                // ->multiple(',')
                ->fillRecordUsing(fn($record, $state) => null)
                ->label('Tujuan Pembelajaran'),
        ];
    }

    public function resolveRecord(): CurriculumPlan
    {
        $academicYearId = $this->getAcademicYearId();

        if (!$academicYearId) {
            // Logika tambahan jika tahun ajaran tidak ketemu
            throw new \Exception("Tahun Ajaran {$this->data['academic_year']} tidak ditemukan.");
        }

        return CurriculumPlan::firstOrNew(
            [
                'academic_year_id' => $academicYearId,
                'semester' => $this->data['semester'],
                'week_number' => $this->data['week_number'],
            ],
            [
                'theme' => $this->data['theme'],
            ],
        );
    }

    protected function getAcademicYearId(): ?int
    {
        $search = str_replace('-', '/', $this->data['academic_year']);
        return AcademicYear::where('year_range', $search)->orWhere('year_range', str_replace('/', '-', $search))->value('id');
    }

    protected function afterSave(): void
    {
        /** 1. Handle cpElements (BelongsToMany) **/
        if (!empty($this->data['cpElements'])) {
            // Pecah berdasarkan titik koma (;) sesuai CSV terbaru
            $names = explode(';', $this->data['cpElements']);
            $ids = [];

            foreach ($names as $name) {
                $name = trim($name);
                if (empty($name)) {
                    continue;
                }

                // Buat elemen jika belum ada
                $element = \App\Models\Master\CpElement::firstOrCreate(['name' => $name]);
                $ids[] = $element->id;
            }
            // Sinkronisasi ke tabel pivot
            $this->record->cpElements()->sync($ids);
        }

        /** 2. Handle Topics (HasMany) **/
        if (!empty($this->data['topics'])) {
            $this->record->topics()->delete();
            $topics = explode(';', $this->data['topics']);
            foreach ($topics as $t) {
                if (trim($t)) {
                    $this->record->topics()->create(['name' => trim($t)]);
                }
            }
        }

        /** 3. Handle Learning Objectives (HasMany) **/
        if (!empty($this->data['learningObjectives'])) {
            $this->record->learningObjectives()->delete();
            $objectives = explode(';', $this->data['learningObjectives']);
            foreach ($objectives as $o) {
                if (trim($o)) {
                    $this->record->learningObjectives()->create(['description' => trim($o)]);
                }
            }
        }
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
