<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-2xl text-white leading-tight tracking-wide">
            {{ __('Admin Command Center') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            @if(session('success'))
                <div class="p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center gap-3 backdrop-blur-md shadow-lg" role="alert">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="p-4 rounded-2xl bg-rose-500/10 border border-rose-500/20 text-rose-400 flex items-center gap-3 backdrop-blur-md shadow-lg" role="alert">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="font-medium">{{ session('error') }}</span>
                </div>
            @endif

            <!-- Top Metric Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Stats Card -->
                <div class="glass-panel rounded-3xl p-8 relative overflow-hidden group">
                    <div class="absolute -right-6 -top-6 w-32 h-32 bg-indigo-500/10 rounded-full blur-2xl group-hover:bg-indigo-500/20 transition-all"></div>
                    <div class="flex items-center gap-4 mb-4 relative z-10">
                        <div class="p-3 rounded-2xl bg-indigo-500/20 border border-indigo-500/30 text-indigo-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-300">Total Results Processed</h3>
                    </div>
                    <p class="text-5xl font-black text-white relative z-10">{{ number_format($resultsCount) }}</p>
                </div>
                
                <!-- Batches Card -->
                <div class="glass-panel rounded-3xl p-8 md:col-span-2 relative overflow-hidden group">
                    <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-purple-500/10 rounded-full blur-2xl group-hover:bg-purple-500/20 transition-all"></div>
                    <div class="flex items-center gap-4 mb-6 relative z-10">
                        <div class="p-3 rounded-2xl bg-purple-500/20 border border-purple-500/30 text-purple-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-300">Active Imported Batches</h3>
                    </div>
                    <div class="flex flex-wrap gap-3 relative z-10">
                        @forelse($batches as $batch)
                            <span class="px-4 py-2 text-sm font-semibold bg-white/5 border border-white/10 text-gray-200 rounded-xl hover:bg-white/10 transition-colors shadow-sm">
                                {{ $batch }}
                            </span>
                        @empty
                            <span class="px-4 py-2 text-sm text-gray-500 bg-gray-900/50 rounded-xl border border-gray-800">
                                No batches imported yet. Awaiting initial upload.
                            </span>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Upload Section -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <div class="glass-panel rounded-3xl p-8 lg:col-span-2">
                    <div class="max-w-xl">
                        <h2 class="text-2xl font-bold text-white mb-2 flex items-center gap-3">
                            <svg class="w-7 h-7 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                            {{ __('Import Master Excel') }}
                        </h2>
                        <p class="text-gray-400 mb-8">
                            {{ __("Upload the Daiya Examination Excel sheet to publish results dynamically. The system will automatically detect subjects, TE/CE structures, and totals.") }}
                        </p>

                        <form action="{{ route('import') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                            @csrf
                            <div class="relative group">
                                <label for="excel_file" class="block w-full cursor-pointer">
                                    <div class="border-2 border-dashed border-gray-600 rounded-2xl p-10 text-center hover:border-indigo-500 hover:bg-indigo-500/5 transition-all duration-300">
                                        <svg class="w-12 h-12 text-gray-500 group-hover:text-indigo-400 mx-auto mb-4 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        <p class="text-gray-300 font-medium text-lg">Click to select or drag & drop</p>
                                        <p class="text-gray-500 text-sm mt-1">Accepts .xlsx, .xls, .csv</p>
                                    </div>
                                    <input type="file" name="excel_file" id="excel_file" class="sr-only" required accept=".xlsx, .xls, .csv" onchange="document.getElementById('file-name').textContent = this.files[0].name">
                                </label>
                                <p id="file-name" class="mt-3 text-sm text-indigo-400 font-medium text-center"></p>
                                @error('excel_file')
                                    <p class="mt-2 text-sm text-red-400 text-center">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <button type="submit" class="w-full sm:w-auto px-8 py-3.5 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white rounded-xl font-bold shadow-lg shadow-indigo-500/25 transition-all transform hover:-translate-y-0.5 focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 focus:ring-offset-slate-900 flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                Process & Publish Results
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Danger Zone -->
                <div class="glass-panel rounded-3xl p-8 border-rose-500/20 bg-rose-500/5">
                    <h2 class="text-xl font-bold text-rose-400 mb-2 flex items-center gap-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        {{ __('Danger Zone') }}
                    </h2>
                    <p class="text-gray-400 text-sm mb-8">
                        {{ __("Permanently delete all imported exam results from the database. This action cannot be undone. Make sure you have backups.") }}
                    </p>
                    
                    <form action="{{ route('clear') }}" method="POST" onsubmit="return confirm('CRITICAL WARNING:\n\nAre you absolutely sure you want to PERMANENTLY DELETE all results?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full px-6 py-3 bg-rose-500/10 hover:bg-rose-500/20 border border-rose-500/30 text-rose-400 rounded-xl font-bold transition-colors flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            Purge All Data
                        </button>
                    </form>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
