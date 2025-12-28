<?php

namespace App\Filament\Resources\AsAnecdotes;

use UnitEnum;
use BackedEnum;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use App\Models\Master\Student;
use App\Models\Master\CpElement;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Resource;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Support\Icons\Heroicon;
use App\Models\Assessment\AsAnecdote;
use Filament\Actions\BulkActionGroup;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Grid;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TimePicker;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Infolists\Components\TextEntry;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use App\Filament\Resources\AsAnecdotes\Pages\ManageAsAnecdotes;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;

class AsAnecdoteResource extends Resource
{
    protected static ?string $model = AsAnecdote::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::DocumentText;
    protected static string | UnitEnum | null $navigationGroup = 'Assessment';
    protected static ?string $navigationLabel = 'Anekdot';
    protected static ?string $slug = 'anekdot';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(2)->schema([
                    Select::make('student_id')
                        ->relationship('student', 'name')
                        ->label('Nama Siswa')
                        ->required()
                        ->preload()
                        ->searchable()
                        ->native(false),
                    TextInput::make('location')
                        ->label('Lokasi')
                        ->required(),
                ])->columnSpanFull(),
                Grid::make(2)->schema([
                    DatePicker::make('date')
                        ->label('Tanggal')
                        ->required()
                        ->default(now())
                        ->displayFormat('d/m/Y'),
                    TimePicker::make('time')
                        ->label('Waktu')
                        ->default(now())
                        ->seconds(false)
                        ->required(),
                ])->columnSpanFull(),
                Textarea::make('description')
                    ->label('Deskripsi')
                    ->required()
                    // ->placeholder('Gunakan Speech to Text bawaan keyboard untuk mempercepat pengisian')
                    ->columnSpanFull(),
                Textarea::make('teacher_analysis')
                    ->label('Analisis Guru')
                    ->required()
                    ->columnSpanFull(),
                Select::make('cpElements')
                    ->label('Tag Elemen CP')
                    ->relationship('cpElements', 'name')
                    ->multiple()
                    ->preload() // Memuat data CP di awal agar pencarian cepat
                    ->native(false)
                    ->required(),
                SpatieMediaLibraryFileUpload::make('image')
                    ->label('Dokumentasi')
                    ->collection('anecdote_image')
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
                    TextEntry::make('time')
                        ->label('Waktu'),
                    TextEntry::make('location')
                        ->label('Lokasi'),
                ])->columnSpanFull(),
                TextEntry::make('description')
                    ->label('Deskripsi')
                    ->columnSpanFull(),
                TextEntry::make('teacher_analysis')
                    ->label('Analisis Guru')
                    ->columnSpanFull(),
                TextEntry::make('cpElements.name')
                    ->label('Element CP')
                    ->badge()
                    ->bulleted(),
                SpatieMediaLibraryImageEntry::make('image')
                    ->label('Dokumentasi')
                    ->collection('anecdote_image')
                    ->conversion('preview')
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
                TextColumn::make('time')
                    ->label('Waktu')
                    ->toggleable(isToggledHiddenByDefault:true),
                TextColumn::make('location')
                    ->label('Lokasi')
                    ->toggleable(),
                TextColumn::make('description')
                    ->label('Deskripsi')
                    ->limit(40)
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
                    ->collection('anecdote_image')
                    ->conversion('preview')
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
            'index' => ManageAsAnecdotes::route('/'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = auth()->user();

        // Kalau bukan parent, biarin default (misal admin)
        if (! $user?->hasRole('parent')) {
            return $query;
        }

        return $query->whereHas('student.parents', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        });
    }
}
