<?php

namespace App\Filament\Resources\LowonganMagangResource\Schemas;

use App\Models\DokumenPendaftaran;
use App\Models\ProgramStudi;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class LowonganMagangForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Lowongan')
                    ->columns(2)
                    ->schema([
                        TextInput::make('judul')
                            ->label('Judul Lowongan')
                            ->required()
                            ->maxLength(200)
                            ->columnSpanFull(),

                        Select::make('mitra_id')
                            ->label('Mitra Perusahaan')
                            ->relationship('mitra', 'nama', fn ($query) => $query->verified())
                            ->searchable()
                            ->preload()
                            ->required()
                            ->native(false),

                        Select::make('jenis_magang')
                            ->label('Jenis Magang')
                            ->options([
                                'pilihan' => 'Pilihan',
                                'wajib' => 'Wajib',
                            ])
                            ->required()
                            ->native(false),

                        Textarea::make('deskripsi')
                            ->label('Deskripsi')
                            ->rows(4)
                            ->columnSpanFull(),
                    ]),

                Section::make('Kuota & Syarat')
                    ->columns(3)
                    ->schema([
                        TextInput::make('kuota')
                            ->label('Kuota Mahasiswa')
                            ->numeric()
                            ->required()
                            ->default(1)
                            ->minValue(1),

                        TextInput::make('syarat_ipk')
                            ->label('Syarat IPK Minimal')
                            ->numeric()
                            ->default(0.00)
                            ->step(0.01)
                            ->minValue(0)
                            ->maxValue(4.00),

                        TextInput::make('syarat_semester')
                            ->label('Syarat Semester Minimal')
                            ->numeric()
                            ->default(1)
                            ->minValue(1)
                            ->maxValue(8),

                        CheckboxList::make('syarat_prodi')
                            ->label('Prodi yang Boleh Daftar')
                            ->options(fn () => ProgramStudi::pluck('nama', 'id')->toArray())
                            ->columns(2)
                            ->columnSpanFull()
                            ->helperText('Kosongkan jika semua prodi boleh mendaftar'),
                    ]),

                Section::make('Dokumen yang Diminta')
                    ->schema([
                        CheckboxList::make('dokumen_required')
                            ->label('Dokumen Wajib Upload')
                            ->options(DokumenPendaftaran::jenisOptions())
                            ->columns(2)
                            ->columnSpanFull(),
                    ]),

                Section::make('Periode')
                    ->columns(2)
                    ->schema([
                        DatePicker::make('tanggal_buka')
                            ->label('Tanggal Buka Pendaftaran')
                            ->required()
                            ->native(false),

                        DatePicker::make('tanggal_tutup')
                            ->label('Tanggal Tutup Pendaftaran')
                            ->required()
                            ->native(false)
                            ->afterOrEqual('tanggal_buka'),

                        DatePicker::make('tanggal_mulai_magang')
                            ->label('Tanggal Mulai Magang')
                            ->native(false),

                        DatePicker::make('tanggal_selesai_magang')
                            ->label('Tanggal Selesai Magang')
                            ->native(false)
                            ->afterOrEqual('tanggal_mulai_magang'),
                    ]),

                Section::make('Publikasi')
                    ->schema([
                        Toggle::make('is_published')
                            ->label('Publikasikan Lowongan')
                            ->helperText('Jika diaktifkan, mahasiswa dapat melihat dan mendaftar ke lowongan ini')
                            ->default(false),
                    ]),
            ]);
    }
}
