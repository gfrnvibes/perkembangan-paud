<?php

namespace App\Filament\Resources\Users;

use UnitEnum;
use BackedEnum;
use App\Models\User;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Resource;
use Filament\Actions\DeleteAction;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;
use Filament\Actions\BulkActionGroup;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Icon;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\TextEntry;
use Filament\Forms\Components\DateTimePicker;
use App\Filament\Resources\Users\Pages\ManageUsers;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::UserGroup;
    protected static string | UnitEnum | null $navigationGroup = 'Master';

    public static function getNavigationLabel(): string
    {
        $user = auth()->user();

        if ($user && $user->hasRole('super_admin')) {
            return 'Users';
        }

        return 'Orang Tua Siswa';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                // DateTimePicker::make('email_verified_at'),
                TextInput::make('password')
                    ->password()
                    // ->required()
                    ,
                Select::make('roles')
                    ->relationship('roles', 'name')
                    ->multiple()
                    ->preload()
                    ->visible(fn () => Auth::user()?->hasRole('super_admin'))
                    ->default('parent' )
                    ->searchable(),
                Select::make('children')
                    ->label('Nama Anak')
                    ->relationship('children', 'name')
                    ->preload()
                    ->multiple()
                    ->native(false)
                    ->afterLabel([
                        Icon::make(Heroicon::ExclamationTriangle),
                        'Mohon isi dengan teliti!'
                    ])
                    ->helperText('1 Ortu bisa saja memiliki lebih dari 1 anak.')
                    ->visible(fn () => Auth::user()?->hasRole('teacher'))
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name')
                    ->label('Nama'),
                TextEntry::make('email')
                    ->label('Email'),
                // TextEntry::make('roles.name')
                //     ->badge()
                //     ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email address')
                    ->copyable()
                    ->copyMessage('Email address copied')
                    ->copyMessageDuration(1500)
                    ->searchable(),
                TextColumn::make('children.name')
                    ->label('Nama Anak')
                    ->placeholder('Belum diatur')
                    ->searchable()
                    ->visible(fn () => Auth::user()?->hasRole('teacher'))
                    ->bulleted(),
                TextColumn::make('roles.name')
                    ->visible(fn () => ! auth()->user()?->hasRole('teacher'))
                    ->badge()
                    ->placeholder('Belum diatur'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageUsers::route('/'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        $user = Auth::user();

        // Kalau yang login teacher → cuma lihat parent
        if ($user?->hasRole('teacher')) {
            $query->whereHas('roles', function ($q) {
                $q->where('name', 'parent');
            });
        }

        return $query;
    }
}
