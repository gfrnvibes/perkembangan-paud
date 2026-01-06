<?php

namespace App\Filament\Resources\AsChecklists\Tables;

use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\ActionGroup;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Grouping\Group;
use Filament\Actions\BulkActionGroup;
use Filament\Forms\Components\Select;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\ForceDeleteBulkAction;

class AsChecklistsTable
{
    public function getHeading(): string
{
    return __('Custom Page Heading');
}

    public static function configure(Table $table): Table
    {
        return $table
            ->groups([
                Group::make('learningObjective.curriculumPlan.academicYear.year_range')
                    ->label('Tahun Ajaran'),
                Group::make('learningObjective.curriculumPlan.semester')
                    ->label('Semester'),
                Group::make('learningObjective.curriculumPlan.week_number')
                    ->label('Minggu'),
            ])
            ->defaultGroup('learningObjective.curriculumPlan.week_number')
            ->columns([
                TextColumn::make('learningObjective.curriculumPlan.academicYear.year_range')
                    ->label('T.A.')
                    ->toggleable(isToggledHiddenByDefault:true),
                TextColumn::make('learningObjective.curriculumPlan.semester')
                    ->label('SMT')
                    ->badge()
                    ->color('danger')
                    ->toggleable(),
                TextColumn::make('learningObjective.curriculumPlan.week_number')
                    ->label('Mingu ke')
                    ->badge()
                    ->toggleable(),
                TextColumn::make('date')
                    ->label('Tanggal')
                    ->sortable()
                    ->date('d/m/y'),
                TextColumn::make('student.name')
                    ->label('Nama Siswa')
                    ->searchable(),
                TextColumn::make('learningObjective.description')
                    ->label('Tujuan Pembelajaran (TP)')
                    ->limit(100)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();

                        if (strlen($state) <= $column->getCharacterLimit()) {
                                return null;
                        }

                        // Only render the tooltip if the column contents exceeds the length limit.
                        return $state;
                    }),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'muncul' => 'Muncul',
                        'tidak_muncul' => 'Belum Muncul',
                        'perlu_bimbingan' => 'Perlu Bimbingan',
                    })
                    ->icon(fn(string $state): string => match ($state) {
                        'muncul' => 'heroicon-s-check-circle',
                        'tidak_muncul' => 'heroicon-s-x-circle',
                        'perlu_bimbingan' => 'heroicon-s-exclamation-triangle',
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'muncul' => 'success',
                        'tidak_muncul' => 'danger',
                        'perlu_bimbingan' => 'warning',
                    }),
            ])
            ->filters([
                TrashedFilter::make(),
                Filter::make('advance')
                    ->schema([
                        Select::make('status')
                            ->options([
                                'muncul' => 'Muncul',
                                'tidak_muncul' => 'Belum Muncul',
                                'perlu_bimbingan' => 'Perlum Bimbingan',
                            ])->native(false),
                        DatePicker::make('date')
                                ->native(false)
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['date'] ?? null,
                                fn(Builder $query, $date): Builder => $query->where('date', $date),
                            )
                            ->when(
                                $data['status'] ?? null,
                                fn(Builder $query, $status): Builder => $query->where('status', $status),
                            );
                    }),
            ])
            ->recordActions([
                // EditAction::make(),
                ActionGroup::make([
                    // ViewAction::make(),
                ])
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
