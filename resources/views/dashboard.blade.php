<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Exam Results Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if(session('success'))
                <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Stats Card -->
                <div class="p-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Total Results</h3>
                    <p class="text-4xl font-bold text-indigo-600 dark:text-indigo-400">{{ $resultsCount }}</p>
                </div>
                
                <!-- Batches Card -->
                <div class="p-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 md:col-span-2">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Imported Batches</h3>
                    <div class="flex flex-wrap gap-2 mt-4">
                        @forelse($batches as $batch)
                            <span class="px-3 py-1 text-sm font-medium bg-indigo-100 text-indigo-800 rounded-full dark:bg-indigo-900 dark:text-indigo-300">
                                {{ $batch }}
                            </span>
                        @empty
                            <span class="text-sm text-gray-500 dark:text-gray-400">No batches imported yet.</span>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        {{ __('Import Excel File') }}
                    </h2>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400 mb-6">
                        {{ __("Upload the Daiya Examination Excel sheet to publish results dynamically.") }}
                    </p>

                    <form action="{{ route('import') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        <div>
                            <x-input-label for="excel_file" :value="__('Select Excel File')" />
                            <input type="file" name="excel_file" id="excel_file" class="mt-2 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 py-2 px-3" required accept=".xlsx, .xls, .csv">
                            <x-input-error :messages="$errors->get('excel_file')" class="mt-2" />
                        </div>
                        
                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Upload and Process') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Clear Results -->
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <h2 class="text-lg font-medium text-red-600 dark:text-red-400">
                        {{ __('Danger Zone') }}
                    </h2>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400 mb-6">
                        {{ __("This will delete all imported results. Make sure you want to do this.") }}
                    </p>
                    
                    <form action="{{ route('clear') }}" method="POST" onsubmit="return confirm('Are you sure you want to delete all results?');">
                        @csrf
                        @method('DELETE')
                        <x-danger-button>{{ __('Clear All Results') }}</x-danger-button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
