<x-filament::page>
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                    Detallles de Reserva #{{ $record->id }}
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Created on {{ $record->created_at->format('d/m/Y H:i') }}
                </p>
            </div>

            <x-filament::badge :color="match ($record->status) {
                'pending' => 'warning',
                'accepted' => 'success',
                'rejected' => 'danger',
            }" class="text-sm">
                {{ match ($record->status) {
                    'pending' => 'Pending Approval',
                    'accepted' => 'Approved',
                    'rejected' => 'Rejected',
                } }}
            </x-filament::badge>
        </div>

        <!-- Main Information Section -->
        <x-filament::card>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Laboratory Information -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                        <x-heroicon-o-building-office class="w-5 h-5 inline mr-2" />
                        Informacion de laboratorio
                    </h3>

                    <div class="space-y-2">
                        <p>
                            <span class="font-medium">Nombre:</span>
                            {{ $record->laboratory->name ?? 'Not specified' }}
                        </p>
                        <p>
                            <span class="font-medium">Localizacion:</span>
                            {{ $record->laboratory->location ?? 'Not specified' }}
                        </p>
                        <p>
                            <span class="font-medium">Capacidad:</span>
                            {{ $record->laboratory->capacity ?? 'N/A' }}
                        </p>
                    </div>
                </div>

                <!-- Reservation Schedule -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                        <x-heroicon-o-clock class="w-5 h-5 inline mr-2" />
                        Horario Reservado
                    </h3>

                    <div class="space-y-2">
                        <p>
                            <span class="font-medium">Fecha:</span>
                            {{ $record->schedule->start_at->format('d/m/Y') ?? 'Not specified' }}
                        </p>
                        <p>
                            <span class="font-medium">Hora:</span>
                            {{ $record->schedule->start_at->format('H:i') ?? '00:00' }} -
                            {{ $record->schedule->end_at->format('H:i') ?? '00:00' }}
                        </p>
                        <p>
                            <span class="font-medium">Duracion:</span>
                            {{ $record->schedule->start_at->diffInHours($record->schedule->end_at) }} hours
                        </p>
                    </div>
                </div>
            </div>
        </x-filament::card>

        <!-- Applicant Information Section -->
        <x-filament::card>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                <x-heroicon-o-user class="w-5 h-5 inline mr-2" />
                Applicant Information
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <p class="font-medium">Nombre:</p>
                    <!-- Corrected to access the 'user' relationship -->
                    <p>{{ $record->first_name ?? 'No first name provided' }}</p>
                </div>

                <div>
                    <p class="font-medium">Apellido:</p>
                    <!-- Corrected to access the 'user' relationship -->
                    <p>{{ $record->last_name ?? 'No last name provided' }}</p>
                </div>

                <div>
                    <p class="font-medium">Email:</p>
                    <!-- Corrected to access the 'user' relationship -->
                    <p>{{ $record->email ?? 'No email provided' }}</p>
                </div>
            </div>
        </x-filament::card>

        <!-- Status and Comments Section -->
        <x-filament::card>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                        <x-heroicon-o-document-text class="w-5 h-5 inline mr-2" />
                        Detalles Adicionales
                    </h3>

                    <div class="space-y-2">
                        <p>
                            <span class="font-medium">Fecha de peticion:</span>
                            {{ $record->created_at->format('d/m/Y H:i') }}
                        </p>
                        <p>
                            <span class="font-medium">Ultima actualizacion:</span>
                            {{ $record->updated_at->format('d/m/Y H:i') }}
                        </p>
                    </div>
                </div>

                @if ($record->status === 'rejected' && $record->rejection_reason)
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                            <x-heroicon-o-x-circle class="w-5 h-5 inline mr-2 text-danger-500" />
                            Reason for Rejection
                        </h3>
                        <p class="bg-danger-50 dark:bg-danger-900/50 p-4 rounded-lg">
                            {{ $record->rejection_reason }}
                        </p>
                    </div>
                @endif
            </div>
        </x-filament::card>
    </div>
</x-filament::page>
