<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-extrabold text-2xl text-white leading-tight tracking-wide flex items-center gap-3">
                <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                {{ __('Edit Student Record') }}
            </h2>
            <a href="{{ route('results.index') }}" class="px-4 py-2 bg-white/5 hover:bg-white/10 text-white rounded-xl text-sm font-medium border border-white/10 transition-colors">
                Back to List
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <form method="POST" action="{{ route('results.update', $result->id) }}" class="space-y-6">
                @csrf
                @method('PUT')
                
                <div class="glass-panel rounded-3xl p-8">
                    <h3 class="text-xl font-bold text-white mb-6 border-b border-white/10 pb-3">Basic Information</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-2">Candidate Name</label>
                            <input type="text" name="name" value="{{ old('name', $result->name) }}" class="w-full bg-slate-900/50 border border-white/10 text-white rounded-xl focus:ring-indigo-500 focus:border-indigo-500" required>
                            @error('name') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Reg No -->
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-2">Registration Number</label>
                            <input type="text" name="reg_no" value="{{ old('reg_no', $result->reg_no) }}" class="w-full bg-slate-900/50 border border-white/10 text-white rounded-xl focus:ring-indigo-500 focus:border-indigo-500" required>
                            @error('reg_no') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Batch -->
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-2">Batch / Sheet Name</label>
                            <input type="text" name="batch" value="{{ old('batch', $result->batch) }}" class="w-full bg-slate-900/50 border border-white/10 text-white rounded-xl focus:ring-indigo-500 focus:border-indigo-500" required>
                            @error('batch') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-2">Status</label>
                            <select name="status" class="w-full bg-slate-900/50 border border-white/10 text-white rounded-xl focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="PASSED EXAM" {{ strtoupper(old('status', $result->status)) == 'PASSED EXAM' ? 'selected' : '' }}>Pass: PASSED EXAM</option>
                                <option value="NOT ELIGIBLE" {{ strtoupper(old('status', $result->status)) == 'NOT ELIGIBLE' ? 'selected' : '' }}>Fail: NOT ELIGIBLE</option>
                                <option value="MALLICIOUS ACTIVITY BY THE STUDENT LIKE DOING COPY IN B/W EXAM" {{ strtoupper(old('status', $result->status)) == 'MALLICIOUS ACTIVITY BY THE STUDENT LIKE DOING COPY IN B/W EXAM' ? 'selected' : '' }}>Debar: MALLICIOUS ACTIVITY...</option>
                                <option value="NO CE MARK FOR SUBJECTS OR NOT COMPLETED TEACHING PRACTICE/THESIS IF THE STUDENT IS A FINAL YEAR STUDENT" {{ strtoupper(old('status', $result->status)) == 'NO CE MARK FOR SUBJECTS OR NOT COMPLETED TEACHING PRACTICE/THESIS IF THE STUDENT IS A FINAL YEAR STUDENT' ? 'selected' : '' }}>Withheld: NO CE MARK...</option>
                            </select>
                            @error('status') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Daiya Rank -->
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-2">Daiya Rank</label>
                            <input type="text" name="daiya_rank" value="{{ old('daiya_rank', $result->daiya_rank) }}" class="w-full bg-slate-900/50 border border-white/10 text-white rounded-xl focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <!-- College Rank -->
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-2">College Rank</label>
                            <input type="text" name="college_rank" value="{{ old('college_rank', $result->college_rank) }}" class="w-full bg-slate-900/50 border border-white/10 text-white rounded-xl focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>
                </div>

                <div class="glass-panel rounded-3xl p-8">
                    <div class="flex justify-between items-center border-b border-white/10 pb-3 mb-6">
                        <h3 class="text-xl font-bold text-white">Subject Marks</h3>
                        <span class="text-sm text-indigo-400">Totals are auto-calculated on save</span>
                    </div>

                    <div class="space-y-4">
                        @foreach($result->marks_data as $subject => $marks)
                            <div class="p-4 bg-white/5 border border-white/10 rounded-2xl flex flex-col md:flex-row items-center gap-4">
                                <div class="w-full md:w-1/3">
                                    <span class="font-semibold text-gray-300 uppercase tracking-wider text-sm">{{ $subject }}</span>
                                </div>
                                <div class="w-full md:w-1/3 flex items-center gap-3">
                                    <label class="text-xs text-gray-500 uppercase w-10">TE:</label>
                                    <input type="number" step="0.01" name="marks_data[{{ $subject }}][TE]" value="{{ $marks['TE'] ?? '' }}" class="w-full bg-slate-900/50 border border-white/10 text-white rounded-lg focus:ring-indigo-500 focus:border-indigo-500 px-3 py-2 text-center" placeholder="Term End">
                                </div>
                                <div class="w-full md:w-1/3 flex items-center gap-3">
                                    <label class="text-xs text-gray-500 uppercase w-10">CE:</label>
                                    <input type="number" step="0.01" name="marks_data[{{ $subject }}][CE]" value="{{ $marks['CE'] ?? '' }}" class="w-full bg-slate-900/50 border border-white/10 text-white rounded-lg focus:ring-indigo-500 focus:border-indigo-500 px-3 py-2 text-center" placeholder="Cont. Eval">
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="flex justify-end gap-4">
                    <a href="{{ route('results.index') }}" class="px-6 py-3 bg-slate-800 hover:bg-slate-700 text-white rounded-xl font-medium border border-slate-600 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="px-8 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white rounded-xl font-bold shadow-lg shadow-indigo-500/25 transition-all transform hover:-translate-y-0.5">
                        Save Changes
                    </button>
                </div>
            </form>
            
        </div>
    </div>
</x-app-layout>
