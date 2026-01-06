<?php

namespace App\Filament\Resources\Students;

use UnitEnum;
use BackedEnum;
use App\Models\User;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use App\Models\Master\Student;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Resource;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Support\Icons\Heroicon;
use Filament\Actions\BulkActionGroup;
use Filament\Forms\Components\Select;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Infolists\Components\TextEntry;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\Students\Pages\ManageStudents;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::AcademicCap;
    protected static string | UnitEnum | null $navigationGroup = 'Master';
    protected static ?string $navigationLabel = 'Siswa';
    protected static ?string $slug = 'siswa';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama Siswa')
                    ->required(),
                TextInput::make('nisn')
                    ->label('NISN')
                    ->numeric()
                    ->required(),
                Select::make('gender')
                    ->label('Jenis Kelamin')
                    ->options([
                        'L' => 'Laki-laki',
                        'P' => 'Perempuan',
                    ])
                    ->native(false),
                DatePicker::make('dob')
                    ->label('Tanggal Lahir')
                    ->required()
                    ->native(false)
                    ->displayFormat('d/m/Y'),
                Select::make('classrooms')
                    ->label('Kelas')
                    ->createOptionForm([
                        Select::make('academic_year_id')
                            ->relationship('academicYear', 'year_range')
                            ->required()
                            ->label('Tahun Ajaran')
                            ->createOptionForm([
                                TextInput::make('year_range')
                                    ->label('Tahun Ajaran')
                                    ->required(),
                            ])
                            ->native(false),
                        TextInput::make('name')
                            ->label('Nama Kelas')
                            ->required(),
                    ])
                    ->relationship('classrooms', 'name')
                    ->preload()
                    ->multiple()
                    ->required()
                    ->native(false),
                // Select::make('parents')
                //     ->label('Nama Orang Tua')
                //     ->relationship('parents', 'name', fn (Builder $query) =>
                //             $query->whereHas('roles', fn ($q) => $q->where('name', 'parent')))
                //     ->preload()
                //     // ->multiple()
                //     ->native(false)
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name')
                    ->label('Nama Siswa'),
                TextEntry::make('nisn')
                    ->label('NISN'),
                TextEntry::make('dob')
                    ->label('Tanggal Lahir'),
                TextEntry::make('gender')
                    ->label('Jenis Kelamin'),
                TextEntry::make('classrooms.name')
                    ->label('Kelas'),
                TextEntry::make('parents.name')
                    ->label('Orang Tua'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nisn')
                    ->label('NISN'),
                TextColumn::make('name')
                    ->label('Nama Siswa')
                    ->searchable(),
                TextColumn::make('gender')
                    ->label('Gender')
                    ->badge()
                    ->sortable(),
                TextColumn::make('classrooms.name')
                    ->label('Kelas')
                    ->badge()
                    ->sortable(),
                TextColumn::make('dob')
                    ->label('Tanggal Lahir'),
                TextColumn::make('parents.name')
                    ->placeholder('Belum diatur')
                    ->label('Orang Tua'),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make()
                        ->modalHeading('Lihat Data Siswa'),
                    EditAction::make()
                        ->modalHeading('Perbarui Data Siswa'),
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
            'index' => ManageStudents::route('/'),
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

        return $query->whereHas('parents', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        });
    }
}
