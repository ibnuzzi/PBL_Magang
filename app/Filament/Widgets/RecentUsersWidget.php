<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Actions\Action;

class RecentUsersWidget extends BaseWidget
{
    protected static ?int $sort = 4;

    protected int | string | array $columnSpan = [
        'default' => 12,
        'xl' => 8,
    ];

    public function table(Table $table): Table
    {
        return $table
            ->query(User::latest()->limit(5))
            ->heading('Manajemen User')
            ->description('348 user terdaftar — tampil 5 terbaru')
            ->columns([
                TextColumn::make('name')
                    ->label('Nama / Email')
                    ->description(fn (User $record): string => $record->email)
                    ->searchable()
                    ->weight('bold'),
                TextColumn::make('role')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'admin' => 'danger',
                        'koordinator' => 'warning',
                        'mahasiswa' => 'info',
                        'dosen' => 'success',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),
                ToggleColumn::make('is_active')
                    ->label('Status'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->url(fn (User $record): string => "/admin/users/{$record->id}/edit")
                    ->button()
                    ->size('xs'),
                Action::make('deactivate')
                    ->label('Nonaktifkan')
                    ->color('danger')
                    ->size('xs')
                    ->button()
                    ->requiresConfirmation()
                    ->action(fn (User $record) => $record->update(['is_active' => false])),
            ])
            ->paginated(false);
    }
}
