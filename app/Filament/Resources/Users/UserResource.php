<?php

namespace App\Filament\Resources\Users;

use UnitEnum;
use BackedEnum;
use App\Models\User;
use Filament\Tables\Table;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Resource;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\ImportAction;
use Filament\Actions\RestoreAction;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Filament\Actions\BulkActionGroup;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Icon;
use App\Filament\Imports\UserImporter;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\ForceDeleteBulkAction;
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
                    Section::make('Data Akun')
                        ->description('Informasi untuk login')
                        ->schema([
                            Grid::make(1) // force atas-bawah
                                ->schema([
                                    TextInput::make('name')
                                        ->label('Nama')
                                        ->required(),

                                    TextInput::make('email')
                                        ->label('Email address')
                                        ->email()
                                        ->required(),

                                    TextInput::make('password')
                                        ->password()
                                        ->label('Password')
                                        ->required(fn (string $operation) => $operation === 'create')
                                        ->dehydrated(fn ($state) => filled($state))
                                        ->dehydrateStateUsing(fn ($state) => Hash::make($state)),
                                    
                                    Select::make('roles')
                                        ->relationship('roles', 'name')
                                        ->multiple()
                                        ->preload()
                                        ->searchable()
                                        ->default('parent')
                                        ->visible(fn () => Auth::user()?->hasRole('super_admin')),
                                ]),
                        ]),

                    Section::make('Data Tambahan')
                        ->description('Data personal & relasi')
                        ->schema([
                            Grid::make(1) // juga atas-bawah
                                ->schema([
                                    TextInput::make('phone')
                                        ->label('No. Telepon')
                                        ->numeric()
                                        ->required(),

                                    TextInput::make('address')
                                        ->label('Alamat')
                                        ->required(),

                                    Select::make('children')
                                        ->label('Nama Anak')
                                        ->relationship('children', 'name')
                                        ->multiple()
                                        ->preload()
                                        ->native(false)
                                        ->helperText('1 Ortu bisa memiliki lebih dari 1 anak.')
                                        ->visible(fn () => Auth::user()?->hasRole('teacher')),
                                ]),
                        ]),
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
                TextEntry::make('phone')
                    ->label('No. Telepon')
                    ->placeholder('-'),
                TextEntry::make('address')
                    ->label('Alamat')
                    ->placeholder('-'),
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
            ->headerActions([
                ImportAction::make()
                    ->importer(UserImporter::class)
            ])
            ->columns([
                TextColumn::make('roles.name')
                    ->visible(fn () => ! auth()->user()?->hasRole('teacher'))
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'super_admin' => 'danger', 
                        'teacher'     => 'warning',
                        'parent'      => 'success',
                        default       => 'gray',
                    })
                    ->placeholder('Belum diatur'),

                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable(),

                TextColumn::make('email')
                    ->label('Email address')
                    ->copyable()
                    ->copyMessage('Email disalin')
                    ->copyMessageDuration(1500)
                    ->searchable(),

                // Tambahkan kolom virtual untuk password awal
                // TextColumn::make('initial_password')
                //     ->label('Password Awal')
                //     ->getStateUsing(function ($record) {
                //         if (!$record->phone || !$record->name) {
                //             return '-';
                //         }

                //         // Rumus yang sama dengan di Importer
                //         $firstWord = Str::of($record->name)->before(' ')->lower();
                //         $lastDigits = substr($record->phone, -3);

                //         return $firstWord . $lastDigits;
                //     })
                //     ->copyable() // Agar admin bisa copy password dengan sekali klik
                //     ->color('gray'),

                TextColumn::make('phone')
                    ->label('No. Telepon')
                    ->copyable()
                    ->copyMessage('No. Telepon disalin')
                    ->placeholder('Belum diatur')
                    ->copyMessageDuration(1500)
                    ->searchable(),

                TextColumn::make('children.name')
                    ->label('Anak')
                    ->placeholder('Belum diatur')
                    ->searchable()
                    ->visible(fn () => Auth::user()?->hasRole('teacher'))
                    ->bulleted(),

                TextColumn::make('address')
                    ->label('Alamat')
                    ->copyable()
                    ->copyMessage('Alamat disalin')
                    ->copyMessageDuration(1500)
                    ->placeholder('Belum diatur')
                    ->searchable(),
                
                // IconColumn::make('email_verified_at')
                //     ->label('Verifikasi')
                //     ->boolean() // Secara otomatis menganggap NOT NULL sebagai true dan NULL sebagai false
                //     ->trueIcon('heroicon-o-check-circle')
                //     ->falseIcon('heroicon-o-x-circle')
                //     ->trueColor('success')
                //     ->falseColor('danger')
                //     ->alignCenter(), // Mengatur posisi ikon di tengah

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
                TrashedFilter::make(),
            ])
            ->recordActions([
                ForceDeleteAction::make(),
                RestoreAction::make(),
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
                ]),
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
