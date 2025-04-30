<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                Product Details
            </h2>
        </div>

        <!-- Information Card -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 p-6">
                <!-- Column 1: Image and Basic Information -->
                <div class="md:col-span-1">
                    <div class="flex flex-col items-center space-y-4">
                        @if ($record->image)
                            <img src="{{ Storage::url($record->image) }}" alt="Product Image"
                                class="w-48 h-48 rounded-lg object-cover border border-gray-200 dark:border-gray-700">
                        @else
                            <div
                                class="w-48 h-48 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                                <svg class="w-20 h-20 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        @endif

                        <div class="text-center space-y-1">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                {{ $record->name }}
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $record->product_type }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Column 2: Main Details -->
                <div class="md:col-span-2 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Description</p>
                            <p class="text-gray-900 dark:text-white">{{ $record->description ?? 'N/A' }}</p>
                        </div>

                        <div class="space-y-1">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Condition</p>
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if ($record->product_condition == 'new') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                @elseif($record->product_condition == 'used') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 @endif">
                                {{ ucfirst($record->product_condition) }}
                            </span>
                        </div>

                        <div class="space-y-1">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Available Stock</p>
                            <p class="text-gray-900 dark:text-white flex items-center">
                                {{ $record->available_quantity }}
                                @if ($record->available_quantity > 10)
                                    <svg class="ml-1 h-4 w-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd" />
                                    </svg>
                                @elseif($record->available_quantity > 0)
                                    <svg class="ml-1 h-4 w-4 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                @else
                                    <svg class="ml-1 h-4 w-4 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                            clip-rule="evenodd" />
                                    </svg>
                                @endif
                            </p>
                        </div>

                        <div class="space-y-1">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Price</p>
                            <p class="text-gray-900 dark:text-white">
                                {{ number_format($record->unit_cost, 2, ',', '.') }} COP</p>
                        </div>

                        <div class="space-y-1">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Serial Number</p>
                            <p class="text-gray-900 dark:text-white font-mono">{{ $record->serial_number ?? 'N/A' }}</p>
                        </div>
                    </div>

                    <!-- Additional Information -->
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-1">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Location</p>
                                <p class="text-gray-900 dark:text-white">{{ $record->laboratory->location ?? 'N/A' }}
                                </p>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
