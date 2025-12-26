<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Log in - {{ config('app.name', 'Sindi') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=outfit:400,500,600,700|inter:400,500,600" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased bg-gray-900 text-white font-sans selection:bg-indigo-500 selection:text-white">
    <div class="relative min-h-screen flex flex-col justify-center items-center overflow-hidden px-6 py-12">
        <!-- Background Effects -->
        <div class="fixed inset-0 z-0 pointer-events-none">
            <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] rounded-full bg-indigo-600/20 blur-[120px]">
            </div>
            <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] rounded-full bg-purple-600/20 blur-[120px]">
            </div>
        </div>

        <div class="relative z-10 w-full max-w-md">
            <!-- Logo -->
            <div class="flex justify-center mb-8">
                <a href="/" class="flex items-center gap-2 group">
                    <div
                        class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-500/30 group-hover:scale-105 transition-transform">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                    </div>
                    <span class="text-2xl font-bold font-heading tracking-tight">Sindi</span>
                </a>
            </div>

            <!-- Login Card -->
            <div class="bg-gray-800/50 border border-white/10 rounded-2xl p-8 backdrop-blur-xl shadow-2xl">
                <h2 class="text-2xl font-bold font-heading mb-6 text-center">Welcome Back</h2>

                <x-validation-errors class="mb-4" />

                @session('status')
                    <div class="mb-4 font-medium text-sm text-green-400">
                        {{ $value }}
                    </div>
                @endsession

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="space-y-5">
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-400 mb-1">Email
                                Address</label>
                            <input id="email"
                                class="w-full bg-gray-900/50 border border-gray-700 rounded-lg px-4 py-3 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                                type="email" name="email" :value="old('email')" required autofocus
                                autocomplete="username" placeholder="you@example.com" />
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-400 mb-1">Password</label>
                            <input id="password"
                                class="w-full bg-gray-900/50 border border-gray-700 rounded-lg px-4 py-3 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                                type="password" name="password" required autocomplete="current-password"
                                placeholder="••••••••" />
                        </div>

                        <div class="flex items-center justify-between">
                            <label for="remember_me" class="flex items-center">
                                <input id="remember_me" type="checkbox"
                                    class="rounded bg-gray-900 border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                    name="remember">
                                <span class="ms-2 text-sm text-gray-400">Remember me</span>
                            </label>

                            @if (Route::has('password.request'))
                                <a class="text-sm text-indigo-400 hover:text-indigo-300 transition-colors"
                                    href="{{ route('password.request') }}">
                                    Forgot password?
                                </a>
                            @endif
                        </div>

                        <button type="submit"
                            class="w-full py-3 px-4 bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg font-semibold shadow-lg shadow-indigo-600/30 transition-all hover:scale-[1.02]">
                            Log in
                        </button>
                    </div>
                </form>
            </div>

            <p class="text-center mt-6 text-gray-500 text-sm">
                Don't have an account?
                <a href="{{ route('register') }}"
                    class="text-indigo-400 hover:text-indigo-300 font-medium transition-colors">Sign up</a>
            </p>
        </div>
    </div>
</body>

</html>