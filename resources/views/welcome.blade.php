<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daiya Results Portal - Check Daiya Islamic Academy Exam Results</title>
    <meta name="description" content="Official examination results portal for Daiya Islamic Academy for Women. Check your academic performance, marks, and ranks securely online.">
    <meta name="keywords" content="daiya result, Daiya Islamic Academy for Women results, Daiya exams, academic performance, marksheet">
    
    <!-- Open Graph / Social Media -->
    <meta property="og:title" content="Daiya Results Portal - Check Exam Results">
    <meta property="og:description" content="Official examination results portal for Daiya Islamic Academy for Women. Check your academic performance, marks, and ranks securely online.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('/') }}">
    
    <link rel="canonical" href="{{ url('/') }}">
    
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
                radial-gradient(at 0% 0%, hsla(253,16%,7%,1) 0, transparent 50%), 
                radial-gradient(at 50% 0%, hsla(225,39%,30%,0.2) 0, transparent 50%), 
                radial-gradient(at 100% 0%, hsla(339,49%,30%,0.2) 0, transparent 50%);
            background-color: #020617;
        }

        .glass-card {
            background: rgba(15, 23, 42, 0.4);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.5);
        }

        .glowing-input {
            transition: all 0.3s ease;
        }
        .glowing-input:focus-within {
            box-shadow: 0 0 20px rgba(99, 102, 241, 0.3);
            border-color: rgba(99, 102, 241, 0.5);
        }
        
        .animated-text-gradient {
            background-size: 200% auto;
            background-clip: text;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: shine 4s linear infinite;
            background-image: linear-gradient(to right, #818cf8, #c084fc, #818cf8);
        }
        
        @keyframes shine {
            to { background-position: 200% center; }
        }
    </style>
</head>
<body class="antialiased text-gray-200 min-h-screen bg-mesh flex flex-col relative overflow-hidden">
    
    <!-- Decorative Elements -->
    <div class="absolute top-[-20%] left-[-10%] w-[50%] h-[50%] rounded-full bg-indigo-600/20 blur-[120px] pointer-events-none"></div>
    <div class="absolute bottom-[-20%] right-[-10%] w-[50%] h-[50%] rounded-full bg-purple-600/20 blur-[120px] pointer-events-none"></div>

    <!-- Top Navigation -->
    <nav class="w-full relative z-20 px-8 py-6 flex justify-between items-center max-w-7xl mx-auto">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center shadow-lg shadow-indigo-500/30">
                <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 21l9-5-9-5-9 5 9 5z" />
                </svg>
            </div>
            <span class="text-2xl font-bold tracking-wide text-white">Daiya <span class="text-indigo-400">Edu</span></span>
        </div>
        
        <div>
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="px-5 py-2.5 text-sm font-semibold rounded-full bg-white/5 hover:bg-white/10 border border-white/10 transition-all duration-300">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="px-5 py-2.5 text-sm font-semibold rounded-full bg-white/5 hover:bg-white/10 border border-white/10 transition-all duration-300 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                        Admin Portal
                    </a>
                @endauth
            @endif
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow flex items-center justify-center px-4 relative z-10">
        <div class="w-full max-w-md">
            
            <div class="text-center mb-10">
                <div class="inline-block px-4 py-1.5 rounded-full border border-indigo-500/30 bg-indigo-500/10 text-indigo-300 text-sm font-medium mb-6">
                    Examination Results Portal
                </div>
                <h1 class="text-5xl font-extrabold mb-4 tracking-tight text-white">
                    Check Your <br/> <span class="animated-text-gradient">Performance</span>
                </h1>
                <p class="text-gray-400 text-lg">Enter your unique registration number below to securely access your official mark sheet.</p>
            </div>

            <div class="glass-card rounded-3xl p-8 transform transition-all duration-500 hover:-translate-y-1">
                @if(session('error'))
                    <div class="mb-6 p-4 rounded-2xl bg-red-500/10 border border-red-500/20 text-red-400 flex items-start gap-3">
                        <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="text-sm font-medium">{{ session('error') }}</span>
                    </div>
                @endif

                <form action="{{ route('search') }}" method="POST" class="space-y-6">
                    @csrf
                    <div>
                        <div class="relative glowing-input rounded-2xl bg-gray-900/50 border border-gray-700">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 21h7a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v11m0 5l4.879-4.879m0 0a3 3 0 104.243-4.242 3 3 0 00-4.243 4.242z" />
                                </svg>
                            </div>
                            <input type="text" name="reg_no" id="reg_no" class="block w-full pl-12 pr-4 py-4 bg-transparent border-none text-white placeholder-gray-500 focus:ring-0 sm:text-lg font-medium" placeholder="e.g. D1B901" required autocomplete="off">
                        </div>
                        @error('reg_no')
                            <p class="mt-2 text-sm text-red-400 pl-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <div class="relative glowing-input rounded-2xl bg-gray-900/50 border border-gray-700">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <input type="date" name="dob" id="dob" class="block w-full pl-12 pr-4 py-4 bg-transparent border-none text-white placeholder-gray-500 focus:ring-0 sm:text-lg font-medium [color-scheme:dark]" required>
                        </div>
                        @error('dob')
                            <p class="mt-2 text-sm text-red-400 pl-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="w-full flex items-center justify-center gap-2 py-4 px-6 rounded-2xl text-white font-bold text-lg bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 focus:ring-offset-gray-900 transition-all duration-300 shadow-lg shadow-indigo-500/25 group">
                        Access Result
                        <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </button>
                </form>
            </div>

        </div>
    </main>

    <!-- Footer -->
    <footer class="w-full py-6 text-center text-gray-500 text-sm relative z-10 border-t border-white/5 mt-12">
        <p>&copy; {{ date('Y') }} Daiya Examination Board. System perfectly crafted for modern results.</p>
    </footer>
    
</body>
</html>
