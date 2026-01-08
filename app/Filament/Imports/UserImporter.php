<?php

namespace App\Filament\Imports;

use App\Models\User;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Number;

class UserImporter extends Importer
{
    protected static ?string $model = User::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('name')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('email')
                ->requiredMapping()
                ->rules(['required', 'email', 'max:255']),
            ImportColumn::make('phone')
                ->rules(['nullable', 'max:255']),
            ImportColumn::make('address')
                ->rules(['nullable', 'max:255']),
            // Kolom Role (Virtual: tidak masuk ke tabel users langsung)
            ImportColumn::make('role')
                ->fillRecordUsing(fn ($record, $state) => null)
                ->label('Role (Spatie)'),
        ];
    }

    public function resolveRecord(): User
    {
        // Cari user berdasarkan email (lebih aman untuk import user)
        $user = User::firstOrNew([
            'email' => $this->data['email'],
        ]);

        // Jika user baru (bukan update), generate password dan verifikasi email
        if (! $user->exists) {
            // Ambil kata pertama dari nama
            $firstWord = str($this->data['name'])->before(' ')->lower();
            
            // Ambil 3 angka terakhir nomor telepon (default 000 jika kosong)
            $phone = $this->data['phone'] ?? '000';
            $lastDigits = substr($phone, -3);
            
            // Gabungkan menjadi password
            $user->password = Hash::make($firstWord . $lastDigits);
            
            // Set email_verified_at sama dengan waktu pembuatan
            $user->email_verified_at = now();
        }

        return $user;
    }

    protected function afterSave(): void
    {
        // Assign Role dari Spatie Permission
        // Ambil dari CSV, jika kosong gunakan default "parent"
        $roleName = $this->data['role'] ?? 'parent';

        // Pastikan role tersebut ada di sistem, lalu assign
        $this->record->assignRole($roleName);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Import user selesai. ' . Number::format($import->successful_rows) . ' berhasil diimport.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' gagal.';
        }

        return $body;
    }
}