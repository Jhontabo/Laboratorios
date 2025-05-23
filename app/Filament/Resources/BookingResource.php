<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookingResource\Pages\BookingResourceCalendar;
use App\Models\Booking;
use Filament\Resources\Resource;

class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $navigationLabel = 'Reservar Espacio';
    protected static ?string $navigationGroup = 'Gestion de Reservas';
    protected static ?string $modelLabel = 'Horario';
    protected static ?string $pluralLabel = 'Reservar espacio';

    public static function getNavigationBadgeColor(): string
    {
        return static::getModel()::where('status', 'pending')->count() > 0
            ? 'warning'
            : 'success';
    }

    public static function getNavigationBadgeTooltip(): string
    {
        return 'Pending bookings for review';
    }

    // public static function canViewAny(): bool
    // {
    //     return Auth::user()?->can('view booking panel') ?? false;
    // }

    public static function getPages(): array
    {
        return [
            'index' => BookingResourceCalendar::route('/'),
        ];
    }
}
