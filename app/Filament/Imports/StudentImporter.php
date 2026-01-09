<?php

namespace App\Filament\Imports;

use App\Models\User;
use Illuminate\Support\Str;
use App\Models\Master\Student;
use Illuminate\Support\Carbon;
use Illuminate\Support\Number;
use Illuminate\Support\Facades\Hash;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Models\Import;

class StudentImporter extends Importer
{
    protected static ?string $model = Student::class;

public static function getColumns(): array
    {
        return [
            // Kolom untuk Model Student
            ImportColumn::make('nisn')
                ->requiredMapping(),
            ImportColumn::make('name')
                ->label('Nama Siswa'),
            ImportColumn::make('dob')
                ->label('Tanggal Lahir'),
                
            ImportColumn::make('gender'),

            // Kolom VIRTUAL untuk data Orang Tua (User)
            // fillRecordUsing(null) agar tidak mencoba masuk ke tabel students
            ImportColumn::make('parent_name')
                ->fillRecordUsing(fn ($record, $state) => null)
                ->label('Nama Orang Tua'),
            ImportColumn::make('parent_email')
                ->fillRecordUsing(fn ($record, $state) => null)
                ->label('Email Orang Tua'),
            ImportColumn::make('parent_phone')
                ->fillRecordUsing(fn ($record, $state) => null)
                ->label('No. HP Orang Tua'),
        ];
    }

    public function resolveRecord(): Student
    {
        \Illuminate\Support\Facades\Log::info('Memproses NISN: ' . $this->data['nisn']);

        return Student::firstOrNew([
            'nisn' => $this->data['nisn'],
        ]);
    }

    protected function afterSave(): void
    {
        // Jalankan logika jika data email orang tua ada di CSV
        if (! empty($this->data['parent_email'])) {
            
            // 1. Cari atau buat User (Parent)
            $parent = User::firstOrNew([
                'email' => $this->data['parent_email'],
            ]);

            if (! $parent->exists) {
                $parent->name = $this->data['parent_name'] ?? 'Parent of ' . $this->record->name;
                $parent->phone = $this->data['parent_phone'] ?? null;
                
                // Logika Password: Kata pertama nama + 3 digit terakhir HP
                $firstWord = Str::of($parent->name)->before(' ')->lower();
                $lastDigits = substr($parent->phone ?? '000', -3);
                
                $parent->password = Hash::make($firstWord . $lastDigits);
                $parent->email_verified_at = now();
                $parent->save();

                // 2. Berikan role 'parent' (Spatie)
                $parent->assignRole('parent');
            }

            // 3. Hubungkan Siswa dengan Orang Tua di tabel pivot 'student_parent'
            // syncWithoutDetaching agar tidak menghapus hubungan orang tua lain jika ada
            $this->record->parents()->syncWithoutDetaching([$parent->id]);
        }
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your student import has completed and ' . Number::format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
