<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Daiya Results Management</title>
        
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800|outfit:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            body { font-family: 'Inter', sans-serif; }
            h1, h2, h3, h4, h5, h6 { font-family: 'Outfit', sans-serif; }
            .glass {
                background: rgba(255, 255, 255, 0.7);
                backdrop-filter: blur(10px);
                -webkit-backdrop-filter: blur(10px);
                border: 1px solid rgba(255, 255, 255, 0.18);
            }
            .dark .glass {
                background: rgba(17, 24, 39, 0.7);
                border: 1px solid rgba(255, 255, 255, 0.05);
            }
            .bg-gradient-animate {
                background: linear-gradient(-45deg, #ee7752, #e73c7e, #23a6d5, #23d5ab);
                background-size: 400% 400%;
                animation: gradient 15s ease infinite;
            }
            @keyframes gradient {
                0% { background-position: 0% 50%; }
                50% { background-position: 100% 50%; }
                100% { background-position: 0% 50%; }
            }
        </style>
    </head>
    <body class="antialiased bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 min-h-screen relative overflow-hidden">
        
        <!-- Animated Background Orbs -->
        <div class="absolute top-0 -left-4 w-72 h-72 bg-purple-300 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob dark:bg-purple-900 dark:mix-blend-screen z-0"></div>
        <div class="absolute top-0 -right-4 w-72 h-72 bg-yellow-300 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-2000 dark:bg-yellow-900 dark:mix-blend-screen z-0"></div>
        <div class="absolute -bottom-8 left-20 w-72 h-72 bg-pink-300 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-4000 dark:bg-pink-900 dark:mix-blend-screen z-0"></div>

        <div class="relative z-10 flex flex-col min-h-screen">
            <!-- Header -->
            <header class="w-full p-6 flex justify-between items-center glass sticky top-0 z-50">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-600 to-purple-600 flex items-center justify-center text-white font-bold text-xl shadow-lg">
                        D
                    </div>
                    <h1 class="text-2xl font-bold tracking-tight text-gray-800 dark:text-gray-100">Daiya <span class="text-indigo-600 dark:text-indigo-400">Results</span></h1>
                </div>
                <div>
                    @if (Route::has('login'))
                        <div class="flex gap-4">
                            @auth
                                <a href="{{ url('/dashboard') }}" class="text-sm font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white transition-colors duration-200">Dashboard</a>
                            @else
                                <a href="{{ route('login') }}" class="text-sm font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white transition-colors duration-200">Admin Login</a>
                            @endauth
                        </div>
                    @endif
                </div>
            </header>

            <!-- Main Content -->
            <main class="flex-grow flex items-center justify-center p-6">
                <div class="w-full max-w-lg">
                    <div class="glass rounded-3xl p-8 shadow-2xl transform transition-all duration-300 hover:scale-[1.01]">
                        <div class="text-center mb-8">
                            <h2 class="text-3xl font-extrabold mb-2 bg-clip-text text-transparent bg-gradient-to-r from-indigo-500 to-purple-600">
                                Check Your Result
                            </h2>
                            <p class="text-gray-500 dark:text-gray-400 text-sm">
                                Enter your examination registration number to view your detailed marks and academic standing.
                            </p>
                        </div>

                        @if(session('error'))
                            <div class="mb-6 p-4 rounded-xl bg-red-50/80 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-600 dark:text-red-400 text-sm flex items-start gap-3 backdrop-blur-sm">
                                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <span>{{ session('error') }}</span>
                            </div>
                        @endif

                        <form action="{{ route('search') }}" method="POST" class="space-y-6">
                            @csrf
                            <div>
                                <label for="reg_no" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Registration Number</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 21h7a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v11m0 5l4.879-4.879m0 0a3 3 0 104.243-4.242 3 3 0 00-4.243 4.242z" />
                                        </svg>
                                    </div>
                                    <input type="text" name="reg_no" id="reg_no" class="block w-full pl-10 pr-3 py-4 border border-gray-200 dark:border-gray-700 rounded-xl leading-5 bg-white/50 dark:bg-gray-800/50 text-gray-900 dark:text-gray-100 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-all duration-200" placeholder="e.g. D1B901" required>
                                </div>
                                @error('reg_no')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <button type="submit" class="w-full flex justify-center py-4 px-4 border border-transparent rounded-xl shadow-lg text-sm font-bold text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transform transition-all duration-200 hover:-translate-y-1 active:translate-y-0">
                                View Result
                            </button>
                        </form>
                    </div>
                </div>
            </main>

            <!-- Footer -->
            <footer class="w-full p-6 text-center text-sm text-gray-500 dark:text-gray-400 glass mt-auto">
                &copy; {{ date('Y') }} Daiya Examination Board. All rights reserved.
            </footer>
        </div>
    </body>
</html>
