<?php

namespace App\Filament\Resources\AsChecklists\Pages;

use App\Models\Assessment\AsChecklist;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\AsChecklists\AsChecklistResource;

class CreateAsChecklist extends CreateRecord
{
    protected static string $resource = AsChecklistResource::class;


    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        $assessments = $data['assessments']; // Ambil data dari repeater
        $date = $data['date'];

        foreach ($assessments as $item) {
            $studentId = $item['student_id'];

            // Loop setiap field yang berawalan 'tp_'
            foreach ($item as $key => $status) {
                if (str_starts_with($key, 'tp_')) {
                    $tpId = str_replace('tp_', '', $key);

                    // Simpan atau update penilaian per TP per siswa
                    AsChecklist::updateOrCreate(
                        [
                            'student_id' => $studentId,
                            'learning_objective_id' => $tpId,
                            'date' => $date,
                        ],
                        ['status' => $status]
                    );
                }
            }
        }

        // Return record terakhir atau dummy record karena Filament mewajibkan return model
        return AsChecklist::latest()->first();
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
