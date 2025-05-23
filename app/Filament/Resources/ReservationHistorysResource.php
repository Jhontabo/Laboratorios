<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReservationHistorysResource\Pages;
use App\Models\Booking;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ReservationHistorysResource extends Resource
{
    protected static ?string $model = Booking::class;
    protected static ?string $navigationIcon = 'heroicon-o-clock';
    protected static ?string $navigationLabel = 'Historial Reservas';
    protected static ?string $navigationGroup = 'Gestion de Reservas';
    protected static ?string $modelLabel = 'Horario';
    protected static ?string $pluralLabel = 'Mis reservas';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('user_id', Auth::id())->count();
    }

    public static function getNavigationBadgeColor(): string
    {
        $pending = static::getModel()::where('user_id', Auth::id())
            ->where('status', 'pending')
            ->count();

        return $pending > 0 ? 'warning' : 'success';
    }

    public static function query(Builder $query): Builder
    {
        return $query->where('user_id', Auth::id())
            ->with(['schedule', 'laboratory'])
            ->latest();
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('laboratory.name')
                    ->label('Laboratorio')
                    ->description(fn($record) => $record->laboratory?->location ?? 'No location')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-o-building-office'),

                TextColumn::make('interval')
                    ->label('Horario')
                    ->getStateUsing(function ($record) {
                        if (!$record->schedule) {
                            return 'Not assigned';
                        }
                        $start = $record->schedule->start_at->format('d M Y, H:i');
                        $end = $record->schedule->end_at->format('H:i');
                        return "$start - $end";
                    })
                    ->description(fn($record) => $record->schedule?->description ?? 'No description')
                    ->sortable()
                    ->icon('heroicon-o-clock'),

                BadgeColumn::make('status')
                    ->label('Estado')
                    ->formatStateUsing(fn($state) => match ($state) {
                        'pending' => 'Pending Approval',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                        default => ucfirst($state),
                    })
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'approved',
                        'danger' => 'rejected',
                    ])
                    ->icon(fn($state) => match ($state) {
                        'pending' => 'heroicon-o-clock',
                        'approved' => 'heroicon-o-check-circle',
                        'rejected' => 'heroicon-o-x-circle',
                        default => null,
                    })
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Rechazado')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->icon('heroicon-o-calendar'),

                TextColumn::make('updated_at')
                    ->label('Ultima actualizacion')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ])
                    ->label('Estado de la reserva'),

                SelectFilter::make('laboratory')
                    ->relationship('laboratory', 'name')
                    ->label('Filtrar por laboratorio'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->label('Ver detalles'),
            ])
            ->bulkActions([])
            ->emptyStateHeading('No reservations yet')
            ->emptyStateDescription('Your reservations will appear here once you create them.')
            ->emptyStateIcon('heroicon-o-calendar');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReservationHistories::route('/'),
            'view' => Pages\ViewReservationHistory::route('/{record}'),
        ];
    }
}
