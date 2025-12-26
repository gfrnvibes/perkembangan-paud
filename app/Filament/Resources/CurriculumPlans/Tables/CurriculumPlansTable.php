<?php

namespace App\Filament\Resources\CurriculumPlans\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;

class CurriculumPlansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->groups([
                'semester',
                Group::make('academicYear.year_range')
                    ->label('Tahun Ajaran')
            ])
            ->defaultGroup('semester')
            ->columns([
                TextColumn::make('academicYear.year_range')
                    ->label('T. A')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                TextColumn::make('semester')
                    ->label('Semester')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        '1' => 'info',
                        '2' => 'success',
                    })
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('week_number')
                    ->label('Minggu')
                    ->sortable()
                    ->formatStateUsing(fn ($state) => "M-{$state}"),
                
                TextColumn::make('cpElements.name')
                    ->label('Elemen CP')
                    ->bulleted()
                    ->color('gray'),

                TextColumn::make('theme')
                    ->label('Tema')
                    ->searchable()
                    ->wrap(),

                TextColumn::make('topics.name')
                    ->label('Topik')
                    ->bulleted()
                    ->limitList(3)
                    ->searchable()
                    ->expandableLimitedList(),

                TextColumn::make('learningObjectives.description')
                    ->label('TP')
                    ->bulleted()
                    ->limitList(3)
                    ->searchable()
                    ->expandableLimitedList(),
            ])
            ->filters([
                TrashedFilter::make(),
                SelectFilter::make('semester')
                    ->options([
                        1 => 'Semester 1',
                        2 => 'Semester 2',
                    ]),
                SelectFilter::make('academic_year_id')
                    ->relationship('academicYear', 'year_range')
                    ->label('Tahun Ajaran'),
            ])
            ->recordActions([
                ActionGroup::make([
                    EditAction::make(),
                    ViewAction::make()
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
