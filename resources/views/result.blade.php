<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Result - {{ $result->name }}</title>
        
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800|outfit:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            body { font-family: 'Inter', sans-serif; }
            h1, h2, h3, h4, h5, h6 { font-family: 'Outfit', sans-serif; }
            .glass {
                background: rgba(255, 255, 255, 0.85);
                backdrop-filter: blur(12px);
                -webkit-backdrop-filter: blur(12px);
                border: 1px solid rgba(255, 255, 255, 0.3);
            }
            .dark .glass {
                background: rgba(17, 24, 39, 0.85);
                border: 1px solid rgba(255, 255, 255, 0.1);
            }
            .print-hidden {
                @media print {
                    display: none !important;
                }
            }
        </style>
    </head>
    <body class="antialiased bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 min-h-screen relative">
        
        <!-- Animated Background Gradient -->
        <div class="fixed inset-0 z-0 opacity-40 dark:opacity-20 pointer-events-none" style="background: radial-gradient(circle at 50% -20%, #4f46e5 0%, transparent 50%), radial-gradient(circle at 100% 50%, #ec4899 0%, transparent 50%);"></div>

        <div class="relative z-10 flex flex-col min-h-screen">
            <!-- Header -->
            <header class="w-full p-6 flex justify-between items-center glass sticky top-0 z-50 print-hidden shadow-sm">
                <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-600 to-purple-600 flex items-center justify-center text-white font-bold text-xl shadow-lg group-hover:shadow-xl transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    </div>
                    <span class="font-semibold text-gray-700 dark:text-gray-200 group-hover:text-indigo-600 transition-colors">Back to Search</span>
                </a>
                <button onclick="window.print()" class="flex items-center gap-2 px-4 py-2 bg-gray-900 dark:bg-white text-white dark:text-gray-900 rounded-lg font-medium shadow hover:shadow-lg transition-all transform hover:-translate-y-0.5">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    Print Result
                </button>
            </header>

            <!-- Main Content -->
            <main class="flex-grow p-4 sm:p-8 flex justify-center items-start">
                <div class="w-full max-w-4xl">
                    <div class="glass rounded-3xl p-6 sm:p-10 shadow-2xl border border-gray-200 dark:border-gray-700 overflow-hidden relative">
                        
                        <!-- Watermark -->
                        <div class="absolute inset-0 flex items-center justify-center opacity-[0.03] dark:opacity-[0.02] pointer-events-none">
                            <span class="text-9xl font-black transform -rotate-45">DAIYA</span>
                        </div>

                        <!-- Result Header -->
                        <div class="border-b border-gray-200 dark:border-gray-700 pb-8 mb-8 text-center relative z-10">
                            <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 dark:text-white tracking-tight mb-2 uppercase">Official Mark Sheet</h1>
                            <p class="text-lg text-indigo-600 dark:text-indigo-400 font-medium">{{ $result->batch }}</p>
                        </div>

                        <!-- Student Info Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-10 relative z-10">
                            <div class="bg-gray-50 dark:bg-gray-800/50 p-5 rounded-2xl border border-gray-100 dark:border-gray-700">
                                <p class="text-sm text-gray-500 dark:text-gray-400 font-medium mb-1">Student Name</p>
                                <p class="text-xl font-bold text-gray-900 dark:text-white">{{ $result->name }}</p>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-800/50 p-5 rounded-2xl border border-gray-100 dark:border-gray-700">
                                <p class="text-sm text-gray-500 dark:text-gray-400 font-medium mb-1">Registration Number</p>
                                <p class="text-xl font-bold text-gray-900 dark:text-white uppercase">{{ $result->reg_no }}</p>
                            </div>
                        </div>

                        <!-- Marks Table -->
                        <div class="mb-10 relative z-10 overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr>
                                        <th class="py-4 px-6 bg-indigo-50 dark:bg-indigo-900/30 font-bold text-indigo-900 dark:text-indigo-200 rounded-tl-xl border-b border-indigo-100 dark:border-indigo-800">Subject</th>
                                        <th class="py-4 px-6 bg-indigo-50 dark:bg-indigo-900/30 font-bold text-indigo-900 dark:text-indigo-200 border-b border-indigo-100 dark:border-indigo-800 text-center">TE</th>
                                        <th class="py-4 px-6 bg-indigo-50 dark:bg-indigo-900/30 font-bold text-indigo-900 dark:text-indigo-200 border-b border-indigo-100 dark:border-indigo-800 text-center">CE</th>
                                        <th class="py-4 px-6 bg-indigo-50 dark:bg-indigo-900/30 font-bold text-indigo-900 dark:text-indigo-200 rounded-tr-xl border-b border-indigo-100 dark:border-indigo-800 text-center">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $totalTe = 0;
                                        $totalCe = 0;
                                    @endphp
                                    @foreach($result->marks_data as $subject => $marks)
                                        @php
                                            $te = is_numeric($marks['TE'] ?? 0) ? (float)($marks['TE'] ?? 0) : 0;
                                            $ce = is_numeric($marks['CE'] ?? 0) ? (float)($marks['CE'] ?? 0) : 0;
                                            $subTotal = $te + $ce;
                                            $totalTe += $te;
                                            $totalCe += $ce;
                                        @endphp
                                        <tr class="border-b border-gray-100 dark:border-gray-800 hover:bg-gray-50/50 dark:hover:bg-gray-800/50 transition-colors">
                                            <td class="py-4 px-6 font-medium text-gray-800 dark:text-gray-200">{{ $subject }}</td>
                                            <td class="py-4 px-6 text-center text-gray-600 dark:text-gray-400">{{ $marks['TE'] ?? '-' }}</td>
                                            <td class="py-4 px-6 text-center text-gray-600 dark:text-gray-400">{{ $marks['CE'] ?? '-' }}</td>
                                            <td class="py-4 px-6 text-center font-bold text-indigo-600 dark:text-indigo-400">{{ $subTotal > 0 ? $subTotal : '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Summary Cards -->
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 relative z-10">
                            @if($result->total_marks || $result->total_obt_marks)
                            <div class="p-4 rounded-2xl bg-gradient-to-br from-blue-500 to-blue-600 text-white shadow-lg">
                                <p class="text-blue-100 text-sm font-medium mb-1">Total Marks</p>
                                <p class="text-2xl font-bold">{{ $result->total_obt_marks ?? '-' }} <span class="text-sm font-normal text-blue-200">/ {{ $result->total_marks ?? '-' }}</span></p>
                            </div>
                            @endif

                            @if($result->daiya_rank)
                            <div class="p-4 rounded-2xl bg-gradient-to-br from-purple-500 to-purple-600 text-white shadow-lg">
                                <p class="text-purple-100 text-sm font-medium mb-1">Daiya Rank</p>
                                <p class="text-2xl font-bold">{{ $result->daiya_rank }}</p>
                            </div>
                            @endif

                            @if($result->college_rank)
                            <div class="p-4 rounded-2xl bg-gradient-to-br from-pink-500 to-pink-600 text-white shadow-lg">
                                <p class="text-pink-100 text-sm font-medium mb-1">College Rank</p>
                                <p class="text-2xl font-bold">{{ $result->college_rank }}</p>
                            </div>
                            @endif

                            @if($result->status)
                            <div class="p-4 rounded-2xl bg-gradient-to-br {{ strtolower($result->status) == 'passed' || strtolower($result->status) == 'pass' ? 'from-green-500 to-green-600' : 'from-orange-500 to-orange-600' }} text-white shadow-lg">
                                <p class="text-white/80 text-sm font-medium mb-1">Status</p>
                                <p class="text-2xl font-bold uppercase">{{ $result->status }}</p>
                            </div>
                            @endif
                        </div>

                    </div>
                </div>
            </main>
        </div>
    </body>
</html>
