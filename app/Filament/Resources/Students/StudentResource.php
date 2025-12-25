<?php

namespace App\Filament\Resources\Students;

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
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\Students\Pages\ManageStudents;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::AcademicCap;
    protected static string | UnitEnum | null $navigationGroup = 'Master';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama Siswa')
                    ->required(),
                TextInput::make('nisn')
                    ->label('NISN')
                    ->required(),
                DatePicker::make('dob')
                    ->label('Tanggal Lahir')
                    ->required()
                    ->native(false)
                    ->displayFormat('d/m/Y'),
                Select::make('classrooms')
                    ->label('Kelas')
                    ->relationship('classrooms', 'name')
                    ->preload()
                    ->required()
                    ->native(false)
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama Siswa'),
                TextInput::make('nisn')
                    ->label('NISN'),
                TextInput::make('dob')
                    ->label('Tanggal Lahir'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->groups([
                '',
            ])
            ->columns([
                TextColumn::make('nisn')
                    ->label('NISN'),
                TextColumn::make('name')
                    ->label('Nama Siswa')
                    ->searchable(),
                TextColumn::make('classrooms.name')
                    ->label('Kelas')
                    ->sortable(),
                TextColumn::make('dob')
                    ->label('Tanggal Lahir'),
            ])
            ->filters([
                // TrashedFilter::make(),
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
}
