<?php

namespace App\Filament\Resources\CurriculumPlans\Schemas;

use Filament\Schemas\Schema;
use App\Models\Master\AcademicYear;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use App\Models\Curriculum\CurriculumPlan;
use Filament\Forms\Components\Repeater\TableColumn;
use Filament\Forms\Get;

class CurriculumPlanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Dasar')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                Select::make('academic_year_id')
                                    ->relationship('academicYear', 'year_range')
                                    ->required()
                                    ->label('Tahun Ajaran')
                                    ->createOptionForm([
                                        TextInput::make('year_range')
                                            ->required(),
                                    ])
                                    ->live()
                                    ->native(false),
                                    
                                Select::make('semester')
                                    ->options([
                                        1 => 'Semester 1',
                                        2 => 'Semester 2',
                                    ])
                                    ->required()
                                    ->label('Semester')
                                    ->live()
                                    ->native(false),

                                Select::make('week_number')
                                ->label('Minggu Ke-')
                                ->required()
                                ->placeholder(fn ($get) => 
                                    !$get('semester') || !$get('academic_year_id') 
                                        ? 'Pilih T.A. & Smt dulu' 
                                        : 'Pilih Minggu'
                                )
                                ->options(function ($get, $record) {
                                    $yearId = $get('academic_year_id');
                                    $semester = $get('semester');

                                    // Jika tahun atau semester belum dipilih, jangan tampilkan opsi
                                    if (!$yearId || !$semester) {
                                        return [];
                                    }

                                    // Ambil daftar minggu yang SUDAH digunakan pada tahun & semester tsb
                                    $usedWeeks = CurriculumPlan::where('academic_year_id', $yearId)
                                        ->where('semester', $semester)
                                        // Jika sedang mode EDIT, kecualikan ID record yang sedang dibuka
                                        ->when($record, fn ($query) => $query->where('id', '!=', $record->id))
                                        ->pluck('week_number')
                                        ->toArray();

                                    // Generate array 1-17, lalu filter yang belum digunakan
                                    return collect(range(1, 17))
                                        ->filter(fn ($week) => !in_array($week, $usedWeeks))
                                        ->mapWithKeys(fn ($week) => [$week => "Minggu Ke-{$week}"])
                                        ->toArray();
                                })
                                // Validasi tambahan agar tidak terjadi duplikasi di tingkat database
                                ->unique(ignoreRecord: true, modifyRuleUsing: function ($rule, $get) {
                                    return $rule->where('academic_year_id', $get('academic_year_id'))
                                                ->where('semester', $get('semester'));
                                }),
                            ]),

                            Grid::make(2)->schema([
                                Select::make('cp_elements')
                                    ->relationship('cpElements', 'name')
                                    ->multiple()
                                    ->preload()
                                    ->required()
                                    ->label('Elemen CP')
                                    ->helperText('Pilih satu atau lebih Elemen CP untuk minggu ini.'),
        
                                TextInput::make('theme')
                                    ->required()
                                    ->maxLength(255)
                                    ->label('Tema Utama'),
                            ])

                    ])->columnSpanFull(),


                        // Repeater untuk Topik
                        Section::make('Daftar Topik')
                            ->description('Input beberapa topik yang sesuai dengan tema minggu ini.')
                            ->columnSpan(1)
                            ->schema([
                                Repeater::make('topics')
                                    ->table([
                                        TableColumn::make('Nama Topik'),
                                    ])
                                    ->relationship() // Mengacu ke relasi hasMany di Model
                                    ->schema([
                                        TextInput::make('name')
                                            ->label('Nama Topik')
                                            ->required(),
                                    ])
                                    ->addActionlabel('Tambah Topik')
                                    ->grid(1),
                            ]),

                        // Repeater untuk Tujuan Pembelajaran (TP)
                        Section::make('Tujuan Pembelajaran (TP)')
                            ->columnSpan(1)
                            ->schema([
                                Repeater::make('learningObjectives')
                                    ->table([
                                        TableColumn::make('Butir TP'),
                                    ])
                                    ->relationship() // Mengacu ke relasi hasMany di Model
                                    ->schema([
                                        Textarea::make('description')
                                            ->label('Butir TP')
                                            ->required()
                                            ->rows(2),
                                    ])
                                    ->addActionLabel('Tambah TP'),
                            ]),
                    
            ]);
    }
}
