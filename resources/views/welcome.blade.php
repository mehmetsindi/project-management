<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Sindi') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=outfit:400,500,600,700|inter:400,500,600" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased bg-gray-900 text-white font-sans selection:bg-indigo-500 selection:text-white">
    <div class="relative min-h-screen flex flex-col overflow-hidden">
        <!-- Background Effects -->
        <div class="fixed inset-0 z-0 pointer-events-none">
            <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] rounded-full bg-indigo-600/20 blur-[120px]">
            </div>
            <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] rounded-full bg-purple-600/20 blur-[120px]">
            </div>
        </div>

        <!-- Navbar -->
        <nav class="relative z-10 w-full max-w-7xl mx-auto px-6 py-6 flex justify-between items-center">
            <div class="flex items-center gap-2">
                <div
                    class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-500/30">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                </div>
                <span class="text-2xl font-bold font-heading tracking-tight">Sindi</span>
            </div>
            <div class="flex items-center gap-6">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}"
                            class="text-sm font-medium hover:text-indigo-400 transition-colors">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-medium hover:text-indigo-400 transition-colors">Log
                            in</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                                class="px-5 py-2.5 text-sm font-medium bg-white text-gray-900 rounded-lg hover:bg-gray-100 transition-all shadow-lg shadow-white/10">Get
                                Started</a>
                        @endif
                    @endauth
                @endif
            </div>
        </nav>

        <!-- Hero Section -->
        <main class="relative z-10 flex-grow flex flex-col justify-center items-center text-center px-6 py-20">
            <div
                class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/5 border border-white/10 backdrop-blur-sm mb-8 animate-fade-in-up">
                <span class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></span>
                <span class="text-sm font-medium text-gray-300">v2.0 Now Available with Multi-Project Support</span>
            </div>

            <h1
                class="text-5xl md:text-7xl font-bold font-heading mb-6 leading-tight tracking-tight max-w-4xl bg-clip-text text-transparent bg-gradient-to-b from-white to-gray-400">
                Manage Projects with <br />
                <span class="text-indigo-400">Unmatched Clarity</span>
            </h1>

            <p class="text-lg md:text-xl text-gray-400 mb-10 max-w-2xl leading-relaxed">
                Sindi is the premium project management tool designed for teams who value aesthetics and efficiency.
                Organize tasks, track time, and collaborate seamlessly.
            </p>

            <div class="flex flex-col sm:flex-row gap-4 w-full justify-center">
                @auth
                    <a href="{{ url('/dashboard') }}"
                        class="px-8 py-4 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl font-semibold text-lg transition-all shadow-xl shadow-indigo-600/30 flex items-center justify-center gap-2 group">
                        Go to Dashboard
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </a>
                @else
                    <a href="{{ route('register') }}"
                        class="px-8 py-4 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl font-semibold text-lg transition-all shadow-xl shadow-indigo-600/30 flex items-center justify-center gap-2 group">
                        Start for Free
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </a>
                    <a href="{{ route('login') }}"
                        class="px-8 py-4 bg-white/5 hover:bg-white/10 border border-white/10 text-white rounded-xl font-semibold text-lg transition-all backdrop-blur-sm">
                        Existing User?
                    </a>
                @endauth
            </div>

            <!-- UI Preview / Glassmorphism Card -->
            <div class="mt-20 relative w-full max-w-5xl mx-auto perspective-1000">
                <div
                    class="relative bg-gray-800/50 border border-white/10 rounded-2xl p-2 backdrop-blur-xl shadow-2xl transform rotate-x-12 hover:rotate-x-0 transition-transform duration-700 ease-out group">
                    <div
                        class="absolute inset-0 bg-gradient-to-tr from-indigo-500/10 to-purple-500/10 rounded-2xl pointer-events-none">
                    </div>

                    <!-- Simulated Dashboard Interface -->
                    <div class="w-full bg-gray-900 rounded-xl overflow-hidden border border-gray-700/50"
                        style="min-height: 500px;">
                        <!-- Top Bar -->
                        <div
                            class="h-14 bg-gray-800/50 border-b border-gray-700/50 flex items-center px-4 justify-between">
                            <div class="flex gap-2">
                                <div class="w-3 h-3 rounded-full bg-red-500"></div>
                                <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                                <div class="w-3 h-3 rounded-full bg-green-500"></div>
                            </div>
                            <div class="w-1/3 h-2 bg-gray-700 rounded-full"></div>
                            <div class="w-8 h-8 rounded-full bg-indigo-500/20"></div>
                        </div>

                        <!-- Content -->
                        <div class="p-6 flex gap-6">
                            <!-- Sidebar -->
                            <div class="w-64 hidden md:block space-y-4">
                                <div class="h-8 w-3/4 bg-gray-800 rounded animate-pulse"></div>
                                <div class="h-4 w-1/2 bg-gray-800/50 rounded animate-pulse"></div>
                                <div class="h-4 w-2/3 bg-gray-800/50 rounded animate-pulse"></div>
                                <div class="h-4 w-1/2 bg-gray-800/50 rounded animate-pulse"></div>
                                <div class="mt-8 h-32 bg-gray-800/30 rounded-lg"></div>
                            </div>

                            <!-- Kanban Board -->
                            <div class="flex-1 flex gap-4 overflow-hidden">
                                <div class="flex-1 bg-gray-800/30 rounded-lg p-4 space-y-3">
                                    <div class="flex justify-between items-center mb-4">
                                        <div class="h-4 w-16 bg-gray-700 rounded"></div>
                                        <div class="h-4 w-6 bg-gray-700 rounded-full"></div>
                                    </div>
                                    <div class="h-24 bg-gray-700/50 rounded-lg border-l-4 border-indigo-500 p-3">
                                        <div class="h-3 w-3/4 bg-gray-600 rounded mb-2"></div>
                                        <div class="h-2 w-full bg-gray-600/50 rounded"></div>
                                    </div>
                                    <div class="h-24 bg-gray-700/50 rounded-lg border-l-4 border-indigo-500 p-3">
                                        <div class="h-3 w-1/2 bg-gray-600 rounded mb-2"></div>
                                        <div class="h-2 w-full bg-gray-600/50 rounded"></div>
                                    </div>
                                </div>
                                <div class="flex-1 bg-gray-800/30 rounded-lg p-4 space-y-3">
                                    <div class="flex justify-between items-center mb-4">
                                        <div class="h-4 w-20 bg-gray-700 rounded"></div>
                                        <div class="h-4 w-6 bg-gray-700 rounded-full"></div>
                                    </div>
                                    <div class="h-24 bg-gray-700/50 rounded-lg border-l-4 border-yellow-500 p-3">
                                        <div class="h-3 w-2/3 bg-gray-600 rounded mb-2"></div>
                                        <div class="h-2 w-full bg-gray-600/50 rounded"></div>
                                    </div>
                                </div>
                                <div class="flex-1 bg-gray-800/30 rounded-lg p-4 space-y-3 hidden lg:block">
                                    <div class="flex justify-between items-center mb-4">
                                        <div class="h-4 w-16 bg-gray-700 rounded"></div>
                                        <div class="h-4 w-6 bg-gray-700 rounded-full"></div>
                                    </div>
                                    <div class="h-24 bg-gray-700/50 rounded-lg border-l-4 border-green-500 p-3">
                                        <div class="h-3 w-3/4 bg-gray-600 rounded mb-2"></div>
                                        <div class="h-2 w-full bg-gray-600/50 rounded"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <footer class="relative z-10 py-8 text-center text-gray-500 text-sm">
            <p>&copy; {{ date('Y') }} Sindi. Crafted with <span class="text-red-500">♥</span> for builders.</p>
        </footer>
    </div>
</body>

</html>