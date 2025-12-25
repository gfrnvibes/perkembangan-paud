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
                        ->required(),
                ])->columnSpanFull(),
                Grid::make(2)->schema([
                    DatePicker::make('date')
                        ->required()
                        ->default(now())
                        ->displayFormat('d/m/Y'),
                    TimePicker::make('time')
                        ->default(now())
                        ->seconds(false)
                        ->required(),
                ])->columnSpanFull(),
                Textarea::make('description')
                    ->required()
                    // ->placeholder('Gunakan Speech to Text bawaan keyboard untuk mempercepat pengisian')
                    ->columnSpanFull(),
                Textarea::make('teacher_analysis')
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
                    TextEntry::make('time'),
                    TextEntry::make('location'),
                ])->columnSpanFull(),
                TextEntry::make('description')
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
                TextColumn::make('time')
                    ->toggleable(),
                TextColumn::make('location')
                    ->toggleable(),
                TextColumn::make('description')
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
}
