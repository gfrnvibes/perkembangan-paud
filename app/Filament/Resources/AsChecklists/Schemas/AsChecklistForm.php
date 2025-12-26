<?php

namespace App\Filament\Resources\AsChecklists\Schemas;

use Filament\Schemas\Schema;
use App\Models\Master\Classroom;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use App\Models\Curriculum\CurriculumPlan;
use Filament\Forms\Components\DatePicker;

class AsChecklistForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Filter Penilaian')
                    ->schema([
                        Grid::make(4)
                            ->schema([
                                DatePicker::make('date')
                                    ->label('Tanggal')
                                    ->default(now())
                                    ->required(),

                            Select::make('classroom_id')
                                ->label('Kelas')
                                ->options(Classroom::all()->pluck('name', 'id'))
                                ->required()
                                ->live() // WAJIB: Agar form merespon perubahan secara instan
                                ->native(false)
                                ->afterStateUpdated(function ($get, $set) {
                                    $classId = $get('classroom_id');

                                    // Jika pilihan kelas dikosongkan, hapus isi repeater
                                    if (!$classId) {
                                        $set('assessments', []);
                                        return;
                                    }

                                    // Ambil data kelas beserta siswanya
                                    $classroom = Classroom::with('students')->find($classId);

                                    if ($classroom) {
                                        // Mapping data siswa ke format yang dikenali oleh Repeater
                                        $studentData = $classroom->students->map(function ($student) {
                                            return [
                                                'student_id'   => $student->id,
                                                'student_name' => $student->name,
                                                // Status TP akan kosong secara default sampai diisi guru
                                            ];
                                        })->toArray();

                                        // "Suntikkan" data ke dalam field repeater bernama 'assessments'
                                        $set('assessments', $studentData);
                                    }
                                }),

                                Select::make('semester')
                                    ->options([1 => 'Semester 1', 2 => 'Semester 2'])
                                    ->live()
                                    ->native(false)
                                    ->required(),

                                Select::make('week_number')
                                    ->label('Minggu Ke-')
                                    ->options(collect(range(1, 17))->mapWithKeys(fn ($i) => [$i => "Minggu {$i}"]))
                                    ->live()
                                    ->native(false)
                                    ->required(),
                            ]),
                    ])->columnSpanFull(),

                // Bagian Matrix Penilaian
                Section::make('Matrix Penilaian Checklist')
                    ->description('Pilih status perkembangan untuk setiap Tujuan Pembelajaran (TP)')
                    ->visible(fn ($get) => $get('classroom_id') && $get('week_number'))
                    ->schema([
                        // Kita gunakan Repeater untuk daftar Siswa
                        Repeater::make('assessments')
                             ->itemNumbers()
                            ->label('Daftar Siswa')
                            ->addable(false) // Guru tidak bisa tambah baris manual
                            ->deletable(false)
                            ->reorderable(false)
                            ->schema(function ($get) {
                                // 1. Ambil TP yang aktif di minggu & semester tsb
                                $plan = CurriculumPlan::where('week_number', $get('week_number'))
                                    ->where('semester', $get('semester'))
                                    ->first();
                                
                                $tps = $plan ? $plan->learningObjectives : collect();

                                // 2. Siapkan input untuk setiap TP
                                $tpInputs = [];
                                foreach ($tps as $index => $tp) {
                                    $tpInputs[] = Radio::make("tp_{$tp->id}")
                                        ->label("TP " . ($index + 1) . ": " . $tp->description)
                                        ->options([
                                            'muncul' => 'Muncul',
                                            'tidak_muncul' => 'Belum Muncul',
                                            'perlu_bimbingan' => 'Perlu Bimbingan',
                                        ])
                                        // ->inline()
                                        // ->helperText($tp->description) // Menampilkan bunyi TP
                                            // ->aboveLabel([
                                            //     'tp' => $tp->description
                                            // ])
                                        ->columnSpan(1);
                                }

                                return [
                                    Grid::make(count($tpInputs) + 1)
                                        ->schema([
                                            TextInput::make('student_name')
                                                ->label('Nama Siswa')
                                                ->disabled()
                                                ->columnSpan(1),
                                            
                                            ...$tpInputs
                                        ]),
                                ];
                            })
                    ])->columnSpanFull(),
            ]);
    }
}
