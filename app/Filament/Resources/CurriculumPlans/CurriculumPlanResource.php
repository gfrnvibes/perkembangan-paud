<?php

namespace App\Filament\Resources\CurriculumPlans;

use App\Filament\Resources\CurriculumPlans\Pages\CreateCurriculumPlan;
use App\Filament\Resources\CurriculumPlans\Pages\EditCurriculumPlan;
use App\Filament\Resources\CurriculumPlans\Pages\ListCurriculumPlans;
use App\Filament\Resources\CurriculumPlans\Pages\ViewCurriculumPlan;
use App\Filament\Resources\CurriculumPlans\Schemas\CurriculumPlanForm;
use App\Filament\Resources\CurriculumPlans\Schemas\CurriculumPlanInfolist;
use App\Filament\Resources\CurriculumPlans\Tables\CurriculumPlansTable;
use App\Models\Curriculum\CurriculumPlan;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class CurriculumPlanResource extends Resource
{
    protected static ?string $model = CurriculumPlan::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::BookOpen;
    protected static ?string $navigationLabel = 'Kelola ATP';
    protected static string | UnitEnum | null $navigationGroup = 'Kurikulum';
    protected static ?string $slug = 'kelola-atp';
    protected static ?string $pluralModelLabel = 'Alur Tujuan Pembelajaran';

    public static function form(Schema $schema): Schema
    {
        return CurriculumPlanForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return CurriculumPlanInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CurriculumPlansTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCurriculumPlans::route('/'),
            'create' => CreateCurriculumPlan::route('/create'),
            'view' => ViewCurriculumPlan::route('/{record}'),
            'edit' => EditCurriculumPlan::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
