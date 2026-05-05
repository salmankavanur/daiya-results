<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-extrabold text-2xl text-white leading-tight tracking-wide flex items-center gap-3">
                <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                {{ __('Add Subject Configuration') }}
            </h2>
            <a href="{{ route('subjects.index') }}" class="px-4 py-2 bg-white/5 hover:bg-white/10 text-white rounded-xl text-sm font-medium border border-white/10 transition-colors">
                Back to Subjects
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <form method="POST" action="{{ route('subjects.store') }}" class="space-y-6">
                @csrf
                
                <div class="glass-panel rounded-3xl p-8">
                    <h3 class="text-xl font-bold text-white mb-6 border-b border-white/10 pb-3">Subject Settings</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Batch -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-400 mb-2">Batch / Sheet Name</label>
                            <input type="text" name="batch" value="{{ old('batch') }}" list="batch-list" class="w-full bg-slate-900/50 border border-white/10 text-white rounded-xl focus:ring-indigo-500 focus:border-indigo-500" placeholder="e.g. D1" required>
                            <datalist id="batch-list">
                                @foreach($allBatches as $batch)
                                    <option value="{{ $batch }}">
                                @endforeach
                            </datalist>
                            @error('batch') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Name -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-400 mb-2">Subject Name</label>
                            <input type="text" name="name" value="{{ old('name') }}" class="w-full bg-slate-900/50 border border-white/10 text-white rounded-xl focus:ring-indigo-500 focus:border-indigo-500" placeholder="e.g. FIQH" required>
                            @error('name') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Max TE -->
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-2">Max Term End (TE)</label>
                            <input type="number" name="max_te" value="{{ old('max_te', 100) }}" min="0" class="w-full bg-slate-900/50 border border-white/10 text-white rounded-xl focus:ring-indigo-500 focus:border-indigo-500" required>
                            @error('max_te') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Max CE -->
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-2">Max Cont. Eval (CE)</label>
                            <input type="number" name="max_ce" value="{{ old('max_ce', 0) }}" min="0" class="w-full bg-slate-900/50 border border-white/10 text-white rounded-xl focus:ring-indigo-500 focus:border-indigo-500" required>
                            @error('max_ce') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Pass Mark -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-400 mb-2">Total Passing Mark</label>
                            <input type="number" name="pass_mark" value="{{ old('pass_mark', 35) }}" min="0" class="w-full bg-slate-900/50 border border-white/10 text-white rounded-xl focus:ring-indigo-500 focus:border-indigo-500" required>
                            @error('pass_mark') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-4">
                    <a href="{{ route('subjects.index') }}" class="px-6 py-3 bg-slate-800 hover:bg-slate-700 text-white rounded-xl font-medium border border-slate-600 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="px-8 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white rounded-xl font-bold shadow-lg shadow-indigo-500/25 transition-all transform hover:-translate-y-0.5">
                        Add Subject
                    </button>
                </div>
            </form>
            
        </div>
    </div>
</x-app-layout>
