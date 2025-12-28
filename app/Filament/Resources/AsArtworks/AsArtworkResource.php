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
    protected static ?string $slug = 'hasil-karya';

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
                        ->label('Tanggal')
                            ->required()
                            ->default(now())
                            ->displayFormat('d/m/Y'),
                ])->columnSpanFull(),
                Textarea::make('child_speech')
                    ->label('Celoteh Anak')
                    ->required()
                    ->columnSpanFull(),
                Textarea::make('teacher_analysis')
                    ->label('Analisis Guru')
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
                    ->label('Dokumentasi')
                    ->collection('artwork_image')
                    ->disk('local')
                    ->multiple()
                    ->image()
                    ->conversion('preview')
                    ->conversionsDisk('local')
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(4)->schema([
                    TextEntry::make('student.name')
                        ->label('Nama Siswa'),
                    TextEntry::make('date')
                        ->label('Tanggal'),
                ])->columnSpanFull(),
                TextEntry::make('child_speech')
                    ->label('Celoteh Anak')
                    ->columnSpanFull(),
                TextEntry::make('teacher_analysis')
                    ->label('Analisis Guru')
                    ->columnSpanFull(),
                TextEntry::make('cpElements.name')
                    ->label('Element CP')
                    ->badge()
                    ->bulleted(),
                SpatieMediaLibraryImageEntry::make('image')
                    ->label('Dokumentasi Hasil Karya')
                    ->collection('artwork_image')
                    // ->conversion('preview')
                    ->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('student.name')
                    ->label('Nama Siswa')
                    ->searchable(),
                TextColumn::make('date')
                    ->label('Tanggal')
                    ->toggleable(),
                TextColumn::make('child_speech')
                    ->label('Celoteh Anak')
                    ->limit(20)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();

                        if (strlen($state) <= $column->getCharacterLimit()) {
                                return null;
                        }

                        // Only render the tooltip if the column contents exceeds the length limit.
                        return $state;
                    }),
                TextColumn::make('teacher_analysis')
                    ->label('Analisis Guru')
                    ->limit(20)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();

                        if (strlen($state) <= $column->getCharacterLimit()) {
                                return null;
                        }

                        // Only render the tooltip if the column contents exceeds the length limit.
                        return $state;
                    }),
                TextColumn::make('cpElements.name')
                    ->label('Element CP')
                    ->badge()
                    ->bulleted(),
                SpatieMediaLibraryImageColumn::make('image')
                    ->label('Dokumentasi')
                    ->placeholder('Tidak ada dokumentasi')
                    ->collection('artwork_image')
                    // ->conversion('preview')
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
