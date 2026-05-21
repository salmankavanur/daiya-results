<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Result - {{ $result->name }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700|outfit:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #0f172a; }
        h1, h2, h3, h4, h5, h6 { font-family: 'Outfit', sans-serif; }
        
        /* Premium Background */
        .bg-mesh {
            background-image: 
                radial-gradient(at 100% 0%, hsla(253,16%,7%,1) 0, transparent 50%), 
                radial-gradient(at 0% 100%, hsla(225,39%,30%,0.15) 0, transparent 50%), 
                radial-gradient(at 50% 50%, hsla(339,49%,30%,0.1) 0, transparent 50%);
            background-color: #020617;
        }

        .glass-panel {
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(40px);
            -webkit-backdrop-filter: blur(40px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        .data-card {
            background: linear-gradient(145deg, rgba(30, 41, 59, 0.7) 0%, rgba(15, 23, 42, 0.4) 100%);
            border: 1px solid rgba(255, 255, 255, 0.03);
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.05);
        }

        .table-row-hover:hover {
            background: rgba(255, 255, 255, 0.02);
        }

        .print-hidden {
            @media print {
                display: none !important;
            }
        }
        
        @media print {
            @page { margin: 1cm; }
            body { background: white !important; color: black !important; }
            .glass-panel, .data-card { background: none !important; box-shadow: none !important; filter: none !important; backdrop-filter: none !important; }
            .glass-panel { border: none !important; }
            .data-card { border: 1px solid #e5e7eb !important; border-radius: 12px !important; }
            * { color: black !important; }
            .print-border { border: 1px solid #ddd !important; }
        }
    </style>
</head>
<body class="antialiased text-gray-200 min-h-screen bg-mesh flex flex-col relative overflow-x-hidden">
    
    <!-- Ambient Glow -->
    <div class="fixed top-[-20%] right-[-10%] w-[60%] h-[60%] rounded-full bg-indigo-900/20 blur-[120px] pointer-events-none z-0"></div>
    <div class="fixed bottom-[-20%] left-[-10%] w-[60%] h-[60%] rounded-full bg-purple-900/20 blur-[120px] pointer-events-none z-0"></div>

    <div class="relative z-10 flex flex-col min-h-screen">
        
        <!-- Header -->
        <header class="w-full px-8 py-6 flex justify-between items-center max-w-6xl mx-auto print-hidden relative z-50">
            <a href="{{ route('home') }}" class="flex items-center gap-3 group px-4 py-2 rounded-full bg-white/5 hover:bg-white/10 border border-white/5 transition-all duration-300">
                <svg class="w-5 h-5 text-gray-400 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                <span class="font-medium text-gray-300 group-hover:text-white transition-colors">Return</span>
            </a>
            
            <button onclick="window.print()" class="flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white rounded-full font-semibold shadow-lg shadow-indigo-500/20 transition-all transform hover:-translate-y-0.5">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Print Result
            </button>
        </header>

        <!-- Main Content -->
        <main class="flex-grow flex justify-center items-start px-4 py-8 print:py-4 print:px-4">
            <div class="w-full max-w-5xl print:max-w-none">
                <div class="glass-panel rounded-[2.5rem] p-8 sm:p-12 relative overflow-hidden print:p-8 print:rounded-none">
                    
                    <!-- Watermark -->
                    <div class="absolute inset-0 flex items-center justify-center opacity-[0.02] pointer-events-none select-none print-hidden">
                        <span class="text-[12rem] font-black tracking-tighter transform -rotate-12 text-white">DAIYA</span>
                    </div>

                    <!-- Report Header -->
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-end border-b border-white/10 pb-8 mb-10 print:pb-4 print:mb-6 print:border-gray-200 relative z-10">
                        <div>
                            <div class="inline-block px-3 py-1 rounded-full bg-indigo-500/20 border border-indigo-500/30 text-indigo-300 text-xs font-bold tracking-widest uppercase mb-4 print:bg-gray-100 print:text-gray-800 print:border-gray-300 print:mb-2">
                                Official Statement of Marks
                            </div>
                            <h1 class="text-4xl sm:text-5xl font-extrabold text-white tracking-tight mb-2 print:text-black print:text-3xl">Academic Result</h1>
                            <p class="text-xl text-gray-400 font-medium print:text-gray-600 print:text-lg">{{ $result->batch }}</p>
                        </div>
                        <div class="mt-6 md:mt-0 text-right">
                            <img src="data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><circle cx='50' cy='50' r='40' stroke='%236366f1' stroke-width='4' fill='none'/><path d='M30 50 L45 65 L70 35' stroke='%238b5cf6' stroke-width='6' stroke-linecap='round' stroke-linejoin='round' fill='none'/></svg>" class="w-20 h-20 ml-auto opacity-80 print-hidden" alt="Verified Seal">
                        </div>
                    </div>

                    <!-- Student Identification -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12 print:gap-4 print:mb-6 relative z-10">
                        <div class="data-card p-6 rounded-3xl flex items-center gap-5 print:p-4">
                            <div class="w-14 h-14 rounded-full bg-indigo-500/10 flex items-center justify-center border border-indigo-500/20 text-indigo-400 print:hidden">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-400 font-medium mb-1 uppercase tracking-wider print:text-gray-500 print:text-xs">Candidate Name</p>
                                <p class="text-2xl font-bold text-white print:text-black print:text-xl">{{ $result->name }}</p>
                            </div>
                        </div>
                        <div class="data-card p-6 rounded-3xl flex items-center gap-5 print:p-4">
                            <div class="w-14 h-14 rounded-full bg-purple-500/10 flex items-center justify-center border border-purple-500/20 text-purple-400 print:hidden">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-400 font-medium mb-1 uppercase tracking-wider print:text-gray-500 print:text-xs">Registration Number</p>
                                <p class="text-2xl font-bold text-white uppercase tracking-wider print:text-black print:text-xl">{{ $result->reg_no }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Marks Section -->
                    <div class="mb-12 print:mb-6 relative z-10">
                        <h3 class="text-xl font-bold text-white mb-6 print:mb-3 print:text-black flex items-center gap-3">
                            <svg class="w-6 h-6 text-indigo-400 print:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Detailed Subject Marks
                        </h3>
                        <div class="data-card rounded-3xl overflow-hidden print:rounded-xl">
                            <div class="overflow-x-auto">
                                <table class="w-full text-left border-collapse min-w-[500px]">
                                    <thead>
                                        <tr class="border-b border-white/5 bg-white/5 print:bg-gray-100 print:border-gray-200">
                                            <th class="py-4 px-5 sm:py-5 sm:px-8 print:py-2 print:px-4 font-semibold text-gray-300 print:text-gray-700 uppercase tracking-wider text-xs sm:text-sm print:text-xs">Subject</th>
                                            <th class="py-4 px-4 sm:py-5 sm:px-6 print:py-2 print:px-4 font-semibold text-gray-300 print:text-gray-700 uppercase tracking-wider text-xs sm:text-sm print:text-xs text-center">Term End (TE)</th>
                                            <th class="py-4 px-4 sm:py-5 sm:px-6 print:py-2 print:px-4 font-semibold text-gray-300 print:text-gray-700 uppercase tracking-wider text-xs sm:text-sm print:text-xs text-center">Cont. Eval (CE)</th>
                                            <th class="py-4 px-5 sm:py-5 sm:px-8 print:py-2 print:px-4 font-semibold text-indigo-300 print:text-black uppercase tracking-wider text-xs sm:text-sm print:text-xs text-center bg-indigo-500/10 print:bg-gray-200">Total</th>
                                        </tr>
                                    </thead>
                                <tbody>
                                    @foreach($result->marks_data as $subject => $marks)
                                        @php
                                            $te = is_numeric($marks['TE'] ?? 0) ? (float)($marks['TE'] ?? 0) : 0;
                                            $ce = is_numeric($marks['CE'] ?? 0) ? (float)($marks['CE'] ?? 0) : 0;
                                            $subTotal = $te + $ce;
                                        @endphp
                                        <tr class="border-b border-white/5 print:border-gray-200 table-row-hover transition-colors">
                                            <td class="py-4 px-5 sm:py-5 sm:px-8 print:py-2 print:px-4 font-medium text-gray-200 print:text-black text-sm print:text-sm">{{ $subject }}</td>
                                            <td class="py-4 px-4 sm:py-5 sm:px-6 print:py-2 print:px-4 text-center text-gray-400 print:text-gray-800 font-mono text-sm print:text-sm">{{ $marks['TE'] ?? '-' }}</td>
                                            <td class="py-4 px-4 sm:py-5 sm:px-6 print:py-2 print:px-4 text-center text-gray-400 print:text-gray-800 font-mono text-sm print:text-sm">{{ $marks['CE'] ?? '-' }}</td>
                                            <td class="py-4 px-5 sm:py-5 sm:px-8 print:py-2 print:px-4 text-center font-bold text-white print:text-black font-mono bg-indigo-500/5 print:bg-transparent text-sm print:text-sm">{{ $subTotal > 0 ? $subTotal : '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            </div>
                        </div>
                    </div>

                    <!-- Final Summary Metrics -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-5 print:gap-3 relative z-10">
                        @if($result->total_marks || $result->total_obt_marks)
                        <div class="data-card p-6 print:p-4 rounded-3xl print:rounded-xl bg-gradient-to-br from-slate-800 to-slate-900 border border-slate-700 relative overflow-hidden group">
                            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity print:hidden">
                                <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path></svg>
                            </div>
                            <p class="text-slate-400 print:text-gray-500 text-sm print:text-xs font-medium mb-2 print:mb-1 uppercase tracking-widest relative z-10">Total Marks</p>
                            <p class="text-3xl print:text-xl font-black text-white print:text-black relative z-10">{{ $result->total_obt_marks ?? '-' }} <span class="text-lg print:text-base font-medium text-slate-500 print:text-gray-500">/ {{ $result->total_marks ?? '-' }}</span></p>
                        </div>
                        @endif

                        @if($result->daiya_rank)
                        <div class="data-card p-6 print:p-4 rounded-3xl print:rounded-xl bg-gradient-to-br from-indigo-900 to-indigo-950 border border-indigo-800 relative overflow-hidden group">
                            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity print:hidden">
                                <svg class="w-12 h-12 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                            </div>
                            <p class="text-indigo-300 print:text-gray-500 text-sm print:text-xs font-medium mb-2 print:mb-1 uppercase tracking-widest relative z-10">Daiya Rank</p>
                            <p class="text-3xl print:text-xl font-black text-white print:text-black relative z-10">{{ is_numeric($result->daiya_rank) ? '#' : '' }}{{ $result->daiya_rank }}</p>
                        </div>
                        @endif

                        @if($result->college_rank)
                        <div class="data-card p-6 print:p-4 rounded-3xl print:rounded-xl bg-gradient-to-br from-purple-900 to-purple-950 border border-purple-800 relative overflow-hidden group">
                            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity print:hidden">
                                <svg class="w-12 h-12 text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m3-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            </div>
                            <p class="text-purple-300 print:text-gray-500 text-sm print:text-xs font-medium mb-2 print:mb-1 uppercase tracking-widest relative z-10">College Rank</p>
                            <p class="text-3xl print:text-xl font-black text-white print:text-black relative z-10">{{ is_numeric($result->college_rank) ? '#' : '' }}{{ $result->college_rank }}</p>
                        </div>
                        @endif

                        @if($result->status)
                        @php
                            $isPass = strtolower($result->status) == 'passed' || strtolower($result->status) == 'pass';
                            $statusGradient = $isPass ? 'from-emerald-900 to-emerald-950 border-emerald-800 text-emerald-300 print:text-gray-500' : 'from-rose-900 to-rose-950 border-rose-800 text-rose-300 print:text-gray-500';
                            $iconColor = $isPass ? 'text-emerald-300' : 'text-rose-300';
                        @endphp
                        <div class="data-card p-6 print:p-4 rounded-3xl print:rounded-xl bg-gradient-to-br {{ $statusGradient }} border relative overflow-hidden group">
                            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity print:hidden">
                                @if($isPass)
                                <svg class="w-12 h-12 {{ $iconColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                @else
                                <svg class="w-12 h-12 {{ $iconColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                @endif
                            </div>
                            <p class="{{ $isPass ? 'text-emerald-300/80 print:text-gray-500' : 'text-rose-300/80 print:text-gray-500' }} text-sm print:text-xs font-medium mb-2 print:mb-1 uppercase tracking-widest relative z-10">Final Status</p>
                            <p class="text-3xl print:text-xl font-black text-white print:text-black uppercase relative z-10 tracking-wide">{{ $result->status }}</p>
                        </div>
                        @endif
                    </div>

                </div>
            </div>
        </main>
        
    </div>
</body>
</html>
