<?php

namespace App\Filament\Resources\LowonganMagangResource\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class LowonganMagangsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('judul')
                    ->label('Judul Lowongan')
                    ->searchable()
                    ->sortable()
                    ->limit(40),

                TextColumn::make('mitra.nama')
                    ->label('Mitra Perusahaan')
                    ->searchable()
                    ->sortable()
                    ->limit(30),

                TextColumn::make('jenis_magang')
                    ->label('Jenis')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pilihan' => 'primary',
                        'wajib' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),

                TextColumn::make('kuota_info')
                    ->label('Kuota')
                    ->state(fn ($record) => "{$record->kuota_terisi}/{$record->kuota}")
                    ->badge()
                    ->color(fn ($record) => $record->is_full ? 'danger' : 'success'),

                TextColumn::make('tanggal_buka')
                    ->label('Buka')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('tanggal_tutup')
                    ->label('Tutup')
                    ->date('d M Y')
                    ->sortable(),

                IconColumn::make('is_published')
                    ->label('Published')
                    ->boolean(),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('jenis_magang')
                    ->label('Jenis')
                    ->options([
                        'pilihan' => 'Pilihan',
                        'wajib' => 'Wajib',
                    ]),

                TernaryFilter::make('is_published')
                    ->label('Published'),

                TernaryFilter::make('is_full')
                    ->label('Kuota Penuh'),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
