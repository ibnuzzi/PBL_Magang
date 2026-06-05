<?php

namespace App\Filament\Imports;

use App\Models\User;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Placeholder;
use Illuminate\Support\HtmlString;

class MahasiswaImporter extends Importer
{
    protected static ?string $model = User::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('name')
                ->label('Nama Lengkap')
                ->requiredMapping()
                ->rules(['required', 'max:150']),
            ImportColumn::make('nim')
                ->label('NIM')
                ->requiredMapping()
                ->rules(['required', 'max:12']),
            ImportColumn::make('email')
                ->label('Email')
                ->requiredMapping()
                ->rules(['required', 'email', 'max:150']),
            ImportColumn::make('angkatan')
                ->label('Angkatan')
                ->rules(['max:4']),
            ImportColumn::make('programStudi')
                ->label('Program Studi')
                ->relationship(resolveUsing: ['nama', 'kode'])
                ->rules(['nullable']),
            ImportColumn::make('semester')
                ->label('Semester')
                ->numeric()
                ->rules(['nullable', 'min:1', 'max:14']),
            ImportColumn::make('ipk')
                ->label('IPK')
                ->numeric()
                ->rules(['nullable', 'min:0', 'max:4']),
        ];
    }

    public static function getOptionsFormComponents(): array
    {
        return [
            Placeholder::make('panduan')
                ->label('Panduan Import')
                ->content(new HtmlString('
                    <ul style="list-style-type: disc; padding-left: 20px; font-size: 0.875rem; color: #6b7280;">
                        <li>Gunakan file <b>CSV</b>. Anda bisa mengunduh <i>template</i> CSV melalui tautan <b>"Download sample CSV file"</b> di atas tombol <i>Upload</i>.</li>
                        <li>Kolom yang <b>wajib</b> diisi di file: <code>name</code>, <code>nim</code>, dan <code>email</code>.</li>
                        <li>Kolom <b>opsional</b>: <code>angkatan</code>, <code>semester</code>, <code>ipk</code>, dan <code>programStudi</code> (isi dengan Nama Prodi atau Kode Prodi).</li>
                        <li><i>Password</i> akan otomatis diset menjadi <code>password123</code> untuk semua mahasiswa yang diimport.</li>
                    </ul>
                ')),
        ];
    }

    public function resolveRecord(): ?User
    {
        // Using email or nim as unique identifier
        return User::firstOrNew([
            'email' => $this->data['email'],
        ]);
    }

    protected function beforeSave(): void
    {
        $this->record->role = 'mahasiswa';
        if (!$this->record->password) {
            $this->record->password = Hash::make('password123'); // Default password
        }
        $this->record->is_active = true;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Import data mahasiswa selesai. ' . Number::format($import->successful_rows) . ' ' . str('baris')->plural($import->successful_rows) . ' berhasil diimpor.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('baris')->plural($failedRowsCount) . ' gagal diimpor.';
        }

        return $body;
    }
}
