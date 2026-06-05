<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Akun')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama Lengkap')
                            ->required()
                            ->maxLength(150),

                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(150),

                        TextInput::make('password')
                            ->label('Password')
                            ->password()
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->dehydrated(fn (?string $state) => filled($state))
                            ->maxLength(255),

                        Select::make('role')
                            ->label('Role')
                            ->options([
                                'admin' => 'Admin',
                                'kajur' => 'Ketua Jurusan',
                                'kps' => 'Ketua Program Studi',
                                'koordinator' => 'Koordinator',
                                'dosen' => 'Dosen Pembimbing',
                                'mahasiswa' => 'Mahasiswa',
                            ])
                            ->required()
                            ->native(false),
                    ]),

                Section::make('Data Pribadi')
                    ->columns(2)
                    ->schema([
                        TextInput::make('nim')
                            ->label('NIM')
                            ->maxLength(10)
                            ->unique(ignoreRecord: true)
                            ->visible(fn (callable $get) => $get('role') === 'mahasiswa'),

                        TextInput::make('nip')
                            ->label('NIP')
                            ->maxLength(20)
                            ->visible(fn (callable $get) => in_array($get('role'), ['dosen', 'koordinator', 'kps', 'kajur', 'wadir1', 'admin'])),

                        TextInput::make('angkatan')
                            ->label('Angkatan')
                            ->maxLength(4)
                            ->visible(fn (callable $get) => $get('role') === 'mahasiswa'),

                        TextInput::make('ipk')
                            ->label('IPK')
                            ->numeric()
                            ->inputMode('decimal')
                            ->minValue(0.00)
                            ->maxValue(4.00)
                            ->visible(fn (callable $get) => $get('role') === 'mahasiswa'),

                        TextInput::make('semester')
                            ->label('Semester')
                            ->numeric()
                            ->integer()
                            ->minValue(1)
                            ->maxValue(14)
                            ->visible(fn (callable $get) => $get('role') === 'mahasiswa'),

                        Select::make('program_studi_id')
                            ->label('Program Studi')
                            ->relationship('programStudi', 'nama')
                            ->searchable()
                            ->preload()
                            ->native(false),

                        TextInput::make('no_hp')
                            ->label('No. HP')
                            ->tel()
                            ->maxLength(20),

                        FileUpload::make('foto')
                            ->label('Foto')
                            ->image()
                            ->directory('users/foto')
                            ->maxSize(2048),

                        Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true),
                    ]),
            ]);
    }
}
