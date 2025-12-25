<?php

namespace App\Filament\Resources\AsArtworks;

use UnitEnum;
use BackedEnum;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use App\Models\Master\Student;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Resource;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\RestoreAction;
use App\Models\Assessment\AsArtwork;
use Filament\Support\Icons\Heroicon;
use Filament\Actions\BulkActionGroup;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Grid;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Infolists\Components\TextEntry;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use App\Filament\Resources\AsArtworks\Pages\ManageAsArtworks;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;

class AsArtworkResource extends Resource
{
    protected static ?string $model = AsArtwork::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::LightBulb;
    protected static string | UnitEnum | null $navigationGroup = 'Assessment';
    protected static ?string $navigationLabel = 'Hasil Karya';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(2)->schema([
                    Select::make('student_id')
                        ->options(Student::query()->pluck('name', 'id'))
                        ->label('Nama Siswa')
                        ->required()
                        ->searchable()
                        ->native(false),
                    DatePicker::make('date')
                            ->required()
                            ->default(now())
                            ->displayFormat('d/m/Y'),
                ])->columnSpanFull(),
                Textarea::make('child_speech')
                    ->required()
                    ->columnSpanFull(),
                Textarea::make('teacher_analysis')
                    ->required()
                    ->columnSpanFull(),
                Select::make('cpElements')
                    ->label('Tag Elemen CP')
                    ->multiple()
                    ->relationship('cpElements', 'name')
                    ->preload() // Memuat data CP di awal agar pencarian cepat
                    ->native(false)
                    ->required(),
                SpatieMediaLibraryFileUpload::make('image')
                    ->multiple()
                    ->image()
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(4)->schema([
                    TextEntry::make('student.name'),
                    TextEntry::make('date'),
                ])->columnSpanFull(),
                TextEntry::make('child_speech')
                    ->columnSpanFull(),
                TextEntry::make('teacher_analysis')
                    ->columnSpanFull(),
                TextEntry::make('cpElements.name')
                    ->badge()
                    ->bulleted(),
                SpatieMediaLibraryImageEntry::make('image')
                    ->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('student.name')
                    ->searchable(),
                TextColumn::make('date')
                    ->toggleable(),
                TextColumn::make('child_speech'),
                TextColumn::make('teacher_analysis'),
                TextColumn::make('cpElements.name')
                    ->badge()
                    ->bulleted(),
                SpatieMediaLibraryImageColumn::make('image')
                    ->stacked()
                    ->circular()
                    ->limit(3)
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
                    ForceDeleteAction::make(),
                    RestoreAction::make(),
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

    public static function getPages(): array
    {
        return [
            'index' => ManageAsArtworks::route('/'),
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
