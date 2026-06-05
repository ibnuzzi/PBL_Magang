<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Auth\Pages\EditProfile as BaseEditProfile;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class EditProfile extends BaseEditProfile
{
    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Grid::make(2)
                    ->schema([
                        // ── Kolom Kiri ──
                        Section::make('Informasi Umum')
                            ->description('Data diri Anda.')
                            ->schema([
                                $this->getNameFormComponent(),
                                $this->getEmailFormComponent(),
                                TextInput::make('no_hp')
                                    ->label('Nomor HP')
                                    ->tel()
                                    ->maxLength(20),
                            ]),

                        // ── Kolom Kanan ──
                        Section::make('Foto Profil')
                            ->schema([
                                FileUpload::make('foto')
                                    ->label('Foto')
                                    ->image()
                                    ->disk('public')
                                    ->directory('users/foto')
                                    ->avatar()
                                    ->maxSize(2048),
                            ]),
                    ]),

                Grid::make(2)
                    ->schema([
                        Section::make('Ubah Password')
                            ->description('Kosongkan jika tidak ingin mengubah password.')
                            ->schema([
                                $this->getPasswordFormComponent(),
                                $this->getPasswordConfirmationFormComponent(),
                            ]),
                    ]),

                // ── Bagian khusus Mahasiswa ──
                Grid::make(2)
                    ->schema([
                        Section::make('Berkas Mahasiswa')
                            ->description('Upload link Google Drive.')
                            ->schema([
                                TextInput::make('cv_link')
                                    ->label('Link CV (Google Drive)')
                                    ->url()
                                    ->maxLength(255),
                                TextInput::make('khs_link')
                                    ->label('Link KHS (Google Drive)')
                                    ->url()
                                    ->maxLength(255),
                            ]),

                        Section::make('Kompetensi')
                            ->description('Keahlian yang Anda miliki.')
                            ->schema([
                                Textarea::make('kompetensi')
                                    ->label('Kompetensi / Skill')
                                    ->rows(4)
                                    ->placeholder('Contoh: PHP, Laravel, MySQL, React, dll.'),
                            ]),
                    ])
                    ->visible(fn () => auth()->user()?->role === 'mahasiswa'),
            ]);
    }
}
