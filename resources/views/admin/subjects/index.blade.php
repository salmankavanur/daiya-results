<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-extrabold text-2xl text-white leading-tight tracking-wide flex items-center gap-3">
                <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                {{ __('Subject Management') }}
            </h2>
            <a href="{{ route('subjects.create') }}" class="px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white rounded-xl font-bold shadow-lg shadow-indigo-500/25 transition-all flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Add Subject
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if(session('success'))
                <div class="p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center gap-3 backdrop-blur-md shadow-lg" role="alert">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            @endif

            <div class="glass-panel rounded-3xl p-6 relative overflow-hidden">
                <!-- Filters -->
                <form method="GET" action="{{ route('subjects.index') }}" class="flex flex-col md:flex-row gap-4 mb-8">
                    <div class="flex-grow">
                        <select name="batch" class="block w-full pl-4 pr-10 py-3 bg-slate-900/50 border border-white/10 text-white rounded-2xl focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">All Batches</option>
                            @foreach($allBatches as $batch)
                                <option value="{{ $batch }}" {{ request('batch') == $batch ? 'selected' : '' }}>{{ $batch }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <button type="submit" class="w-full md:w-auto px-6 py-3 bg-indigo-600 hover:bg-indigo-500 text-white rounded-2xl font-semibold shadow-lg shadow-indigo-500/25 transition-all">
                            Filter Subjects
                        </button>
                    </div>
                </form>

                <!-- Data Table -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-white/10 bg-white/5">
                                <th class="py-4 px-6 font-semibold text-gray-400 text-sm tracking-wider uppercase">Batch</th>
                                <th class="py-4 px-6 font-semibold text-gray-400 text-sm tracking-wider uppercase">Subject Name</th>
                                <th class="py-4 px-6 font-semibold text-gray-400 text-sm tracking-wider uppercase text-center">Max TE</th>
                                <th class="py-4 px-6 font-semibold text-gray-400 text-sm tracking-wider uppercase text-center">Max CE</th>
                                <th class="py-4 px-6 font-semibold text-gray-400 text-sm tracking-wider uppercase text-center">Pass Mark</th>
                                <th class="py-4 px-6 font-semibold text-gray-400 text-sm tracking-wider uppercase text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @forelse($subjects as $subject)
                                <tr class="hover:bg-white/5 transition-colors">
                                    <td class="py-4 px-6 text-indigo-300 font-medium">{{ $subject->batch }}</td>
                                    <td class="py-4 px-6 font-bold text-gray-200">{{ $subject->name }}</td>
                                    <td class="py-4 px-6 text-center text-white font-mono">{{ $subject->max_te }}</td>
                                    <td class="py-4 px-6 text-center text-white font-mono">{{ $subject->max_ce }}</td>
                                    <td class="py-4 px-6 text-center text-emerald-300 font-mono font-bold">{{ $subject->pass_mark }}</td>
                                    <td class="py-4 px-6 text-right">
                                        <div class="flex items-center justify-end gap-3">
                                            <a href="{{ route('subjects.edit', $subject->id) }}" class="p-2 bg-indigo-500/10 hover:bg-indigo-500/20 text-indigo-400 rounded-lg transition-colors border border-indigo-500/20" title="Edit">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            </a>
                                            <form action="{{ route('subjects.destroy', $subject->id) }}" method="POST" onsubmit="return confirm('Delete this subject configuration?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2 bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 rounded-lg transition-colors border border-rose-500/20" title="Delete">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-12 text-center text-gray-500">
                                        No subjects configured yet. Add subjects to define max marks and pass marks.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
