<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Select Terminal — {{ config('app.name', 'Nissi POS') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    @vite(['resources/css/app.css'])
</head>

<body class="bg-gray-900 min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-lg">

        {{-- Header --}}
        <div class="text-center mb-8">
            <div
                class="w-16 h-16 bg-emerald-500/20 border border-emerald-500/30 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-desktop text-emerald-400 text-2xl"></i>
            </div>
            <h1 class="text-2xl font-bold text-white">Select Your Terminal</h1>
            <p class="text-slate-400 text-sm mt-1">
                Welcome, {{ Auth::user()->name }}. Choose a terminal to begin.
            </p>
        </div>

        {{-- Location & terminal list --}}
        @forelse ($locations as $location)
            <div class="mb-6">
                <h2 class="text-slate-400 text-xs font-semibold uppercase tracking-wider mb-3 px-1">
                    <i class="fas fa-map-marker-alt mr-1"></i>{{ $location->name }}
                </h2>

                <div class="grid grid-cols-1 gap-3">
                    @forelse ($location->activeTerminals as $terminal)
                        <a href="{{ route('pos.index', $terminal) }}"
                            class="flex items-center justify-between bg-slate-800/50 border border-slate-700/50
                                  hover:border-emerald-500/50 rounded-xl p-4 transition-all group">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 bg-emerald-500/20 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-cash-register text-emerald-400"></i>
                                </div>
                                <div>
                                    <p class="text-white font-medium text-sm">{{ $terminal->name }}</p>
                                    <p class="text-slate-500 text-xs mt-0.5">
                                        {{ $terminal->identifier ?? 'No identifier' }}</p>
                                </div>
                            </div>
                            <i
                                class="fas fa-chevron-right text-slate-600 group-hover:text-emerald-400 transition-colors"></i>
                        </a>
                    @empty
                        <p class="text-slate-500 text-sm px-1">No active terminals at this location.</p>
                    @endforelse
                </div>
            </div>
        @empty
            <div class="text-center py-12 bg-slate-800/50 border border-slate-700/50 rounded-2xl">
                <i class="fas fa-exclamation-circle text-slate-600 text-4xl mb-4"></i>
                <p class="text-slate-400 text-sm">You are not assigned to any active locations.</p>
                <p class="text-slate-500 text-xs mt-1">Please contact your manager.</p>
            </div>
        @endforelse

        {{-- Sign out link --}}
        <div class="text-center mt-8">
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit" class="text-slate-500 hover:text-slate-400 text-sm transition-colors">
                    <i class="fas fa-sign-out-alt mr-1"></i>Sign out
                </button>
            </form>
        </div>

    </div>

</body>

</html>
