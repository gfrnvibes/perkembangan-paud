<?php

namespace App\Filament\Resources\AsChecklists;

use App\Filament\Resources\AsChecklists\Pages\CreateAsChecklist;
use App\Filament\Resources\AsChecklists\Pages\EditAsChecklist;
use App\Filament\Resources\AsChecklists\Pages\ListAsChecklists;
use App\Filament\Resources\AsChecklists\Pages\ViewAsChecklist;
use App\Filament\Resources\AsChecklists\Schemas\AsChecklistForm;
use App\Filament\Resources\AsChecklists\Schemas\AsChecklistInfolist;
use App\Filament\Resources\AsChecklists\Tables\AsChecklistsTable;
use App\Models\Assessment\AsChecklist;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class AsChecklistResource extends Resource
{
    protected static ?string $model = AsChecklist::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::CheckCircle;
    protected static string | UnitEnum | null $navigationGroup = 'Assessment';
    protected static ?string $navigationLabel = 'Checklist';
    protected static ?string $slug = 'checklist';
    protected static ?string $pluralModelLabel = 'Cheklist';
    
    public static function form(Schema $schema): Schema
    {
        return AsChecklistForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return AsChecklistInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AsChecklistsTable::configure($table);
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
            'index' => ListAsChecklists::route('/'),
            'create' => CreateAsChecklist::route('/create'),
            'view' => ViewAsChecklist::route('/{record}'),
            // 'edit' => EditAsChecklist::route('/{record}/edit'),
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
