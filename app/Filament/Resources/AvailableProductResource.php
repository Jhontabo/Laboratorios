<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AvailableProductResource\Pages;
use App\Models\Loan;
use App\Models\AvailableProduct;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Notifications\Notification;

class AvailableProductResource extends Resource
{
    protected static ?string $model = AvailableProduct::class;
    protected static ?string $navigationIcon = 'heroicon-m-shopping-cart';
    protected static ?string $navigationGroup = 'Prestamos';
    protected static ?string $navigationLabel = 'Solicitar préstamo';
    protected static ?string $modelLabel = 'producto';
    protected static ?string $pluralLabel = 'Productos para préstamos';

    public static function getEloquentQuery(): Builder
    {
        // Solo productos nuevos y disponibles para préstamo
        return parent::getEloquentQuery()
            ->where('available_for_loan', true)
            ->whereIn('status', ['new', 'used']);
    }

    public static function canViewAny(): bool
    {
        $user = auth()->user();
        return $user &&
            !$user->hasRole('LABORATORISTA') &&
            !$user->hasRole('COORDINADOR');
    }
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->label('Imagen')
                    ->size(50)
                    ->circular()
                    ->toggleable(),

                TextColumn::make('name')
                    ->label('Producto')
                    ->searchable()
                    ->sortable()
                    ->description(fn($record) => substr($record->description, 0, 50) . '...')
                    ->weight('medium')
                    ->color('primary'),

                TextColumn::make('available_quantity')
                    ->label('Cantidad disponible')
                    ->sortable()
                    ->alignCenter()
                    ->color(fn($record) => $record->available_quantity > 10 ? 'success' : ($record->available_quantity > 0 ? 'warning' : 'danger'))
                    ->icon(fn($record) => $record->available_quantity > 10 ? 'heroicon-o-check-circle' : ($record->available_quantity > 0 ? 'heroicon-o-exclamation-circle' : 'heroicon-o-x-circle')),

                TextColumn::make('product_type')
                    ->label('Tipo')
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        'equipment' => 'info',
                        'supply' => 'primary',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn($state) => ucfirst($state))
                    ->sortable(),

                TextColumn::make('laboratory.name')
                    ->label('Laboratorio')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Ver')
                    ->icon('heroicon-o-eye')
                    ->modalHeading(fn($record) => "Detalles del producto: {$record->name}")
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Cerrar')
                    ->modalContent(function ($record) {
                        return view('filament.pages.view-AvailableProduct', [
                            'product' => $record
                        ]);
                    }),

                Tables\Actions\Action::make('requestLoan')
                    ->label('Solicitar')
                    ->icon('heroicon-o-clipboard-document-list')
                    ->requiresConfirmation()
                    ->modalHeading('Confirmar solicitud')
                    ->modalDescription(fn(AvailableProduct $record) => "¿Confirma la solicitud del producto '{$record->name}'?")
                    ->action(function (AvailableProduct $record) {
                        // Validar cantidad mínima
                        if ($record->available_quantity < 5) {
                            Notification::make()
                                ->title("{$record->name}: cantidad insuficiente")
                                ->body('Debe haber al menos 5 unidades disponibles para solicitar este producto.')
                                ->danger()
                                ->send();
                            return;
                        }

                        Loan::create([
                            'product_id'   => $record->id,
                            'user_id'      => auth()->id(),
                            'status'       => 'pending',
                            'requested_at' => now(),
                        ]);

                        Notification::make()
                            ->title('Solicitud registrada')
                            ->success()
                            ->body("Producto solicitado: {$record->name}")
                            ->send();
                    }),
            ])
            ->headerActions([
                Tables\Actions\Action::make('infoSelection')
                    ->label('Cómo pedir un producto')
                    ->color('gray')
                    ->icon('heroicon-o-question-mark-circle')
                    ->modalContent(view('filament.pages.instructions-request'))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Entendido'),
            ])
            // Sin bulkActions
            ->emptyState(view('filament.pages.empty-state-products'))
            ->persistFiltersInSession()
            ->persistSearchInSession();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAvailableProducts::route('/'),
        ];
    }
}
