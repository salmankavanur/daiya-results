<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Daiya Results Admin') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700|outfit:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            body { font-family: 'Inter', sans-serif; background-color: #0f172a; }
            h1, h2, h3, h4, h5, h6 { font-family: 'Outfit', sans-serif; }
            
            .bg-mesh {
                background-image: 
                    radial-gradient(at 0% 0%, hsla(253,16%,7%,1) 0, transparent 50%), 
                    radial-gradient(at 50% 0%, hsla(225,39%,30%,0.2) 0, transparent 50%), 
                    radial-gradient(at 100% 0%, hsla(339,49%,30%,0.2) 0, transparent 50%);
                background-color: #020617;
            }
            
            .glass-panel {
                background: rgba(15, 23, 42, 0.6);
                backdrop-filter: blur(20px);
                -webkit-backdrop-filter: blur(20px);
                border: 1px solid rgba(255, 255, 255, 0.05);
                box-shadow: 0 4px 30px rgba(0, 0, 0, 0.5);
            }
        </style>
    </head>
    <body class="font-sans text-gray-100 antialiased bg-mesh min-h-screen relative overflow-hidden flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
        
        <!-- Ambient Glow -->
        <div class="fixed top-[-20%] left-[-10%] w-[50%] h-[50%] rounded-full bg-indigo-900/20 blur-[120px] pointer-events-none z-0"></div>
        <div class="fixed bottom-[-20%] right-[-10%] w-[50%] h-[50%] rounded-full bg-purple-900/20 blur-[120px] pointer-events-none z-0"></div>

        <div class="relative z-10 w-full sm:max-w-md mt-6 px-6 py-8 glass-panel rounded-[2rem] overflow-hidden">
            <div class="flex justify-center mb-8">
                <a href="/" class="flex flex-col items-center gap-2">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center shadow-lg shadow-indigo-500/30">
                        <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 21l9-5-9-5-9 5 9 5z" />
                        </svg>
                    </div>
                    <span class="text-2xl font-bold tracking-wide text-white">Daiya <span class="text-indigo-400">Admin</span></span>
                </a>
            </div>

            {{ $slot }}
        </div>
        
        <div class="relative z-10 mt-8 text-center text-sm text-gray-500">
            <a href="/" class="hover:text-indigo-400 transition-colors">&larr; Back to Public Portal</a>
        </div>
    </body>
</html>
