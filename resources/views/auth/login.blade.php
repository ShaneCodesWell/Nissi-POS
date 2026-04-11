<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign In — {{ config('app.name', 'Nissi POS') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    @vite(['resources/css/app.css'])
</head>

<body class="bg-gray-900 min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md">

        {{-- Logo / App name --}}
        <div class="text-center mb-8">
            <div
                class="w-16 h-16 bg-emerald-500/20 border border-emerald-500/30 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-cash-register text-emerald-400 text-2xl"></i>
            </div>
            <h1 class="text-2xl font-bold text-white">{{ config('app.name', 'Nissi POS') }}</h1>
            <p class="text-slate-400 text-sm mt-1">Sign in to continue</p>
        </div>

        {{-- Card --}}
        <div class="bg-slate-800/50 border border-slate-700/50 rounded-2xl p-8">

            {{-- Error alert --}}
            @if (session('error'))
                <div class="mb-6 p-4 bg-red-500/10 border border-red-500/30 rounded-xl text-red-400 text-sm">
                    <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-slate-300 text-sm font-medium mb-2">
                        Email address
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-slate-500 text-sm"></i>
                        </div>
                        <input type="email" id="email" name="email" value="{{ old('email') }}"
                            autocomplete="email" autofocus
                            class="w-full bg-slate-700/50 border @error('email') border-red-500/50 @else border-slate-600/50 @enderror
                                   rounded-xl pl-10 pr-4 py-3 text-white placeholder-slate-500 text-sm
                                   focus:outline-none focus:border-emerald-500/50 focus:ring-1 focus:ring-emerald-500/30
                                   transition-colors"
                            placeholder="you@example.com" />
                    </div>
                    @error('email')
                        <p class="mt-2 text-red-400 text-xs">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block text-slate-300 text-sm font-medium mb-2">
                        Password
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-slate-500 text-sm"></i>
                        </div>
                        <input type="password" id="password" name="password" autocomplete="current-password"
                            class="w-full bg-slate-700/50 border @error('password') border-red-500/50 @else border-slate-600/50 @enderror
                                   rounded-xl pl-10 pr-4 py-3 text-white placeholder-slate-500 text-sm
                                   focus:outline-none focus:border-emerald-500/50 focus:ring-1 focus:ring-emerald-500/30
                                   transition-colors"
                            placeholder="••••••••" />
                    </div>
                    @error('password')
                        <p class="mt-2 text-red-400 text-xs">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Remember me --}}
                <div class="flex items-center">
                    <input type="checkbox" id="remember" name="remember"
                        class="w-4 h-4 rounded border-slate-600 bg-slate-700 text-emerald-500
                               focus:ring-emerald-500/30 focus:ring-offset-slate-800" />
                    <label for="remember" class="ml-2 text-slate-400 text-sm">
                        Keep me signed in
                    </label>
                </div>

                {{-- Submit --}}
                <button type="submit"
                    class="w-full bg-linear-to-br from-emerald-500 to-emerald-600
                           hover:from-emerald-600 hover:to-emerald-700
                           text-white font-semibold py-3 rounded-xl
                           transition-all shadow-lg shadow-emerald-500/20
                           focus:outline-none focus:ring-2 focus:ring-emerald-500/50">
                    <i class="fas fa-sign-in-alt mr-2"></i>Sign In
                </button>
            </form>
        </div>

        {{-- Footer note --}}
        <p class="text-center text-slate-600 text-xs mt-6">
            {{ config('app.name', 'Nissi POS') }} &mdash; Point of Sale System
        </p>
    </div>

</body>

</html>
