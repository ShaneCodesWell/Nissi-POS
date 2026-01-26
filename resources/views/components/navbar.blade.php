<div class="bg-slate-800 border-b border-slate-700/50 px-6 py-4">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <button class="lg:hidden text-slate-400 hover:text-white transition-colors">
                <i class="fas fa-bars text-xl"></i>
            </button>
            <h1 class="text-xl font-bold text-white">Dashboard</h1>
        </div>

        <div class="flex items-center gap-4">
            <!-- Search -->
            <div class="hidden md:flex items-center bg-slate-700/50 rounded-lg px-4 py-2 gap-2">
                <i class="fas fa-search text-slate-400 text-sm"></i>
                <input type="text" placeholder="Search..."
                    class="bg-transparent border-none outline-none text-slate-300 text-sm placeholder-slate-500 w-64" />
            </div>

            <!-- Theme Toggle -->
            <button id="themeToggle" class="text-slate-400 hover:text-white transition-colors">
                <i class="fas fa-moon text-xl"></i>
            </button>

            <!-- Notifications -->
            <button class="relative text-slate-400 hover:text-white transition-colors">
                <i class="fas fa-bell text-xl"></i>
                <span class="absolute -top-1 -right-1 w-2 h-2 bg-emerald-500 rounded-full"></span>
            </button>

            <!-- User Profile -->
            <div class="flex items-center gap-3 pl-4 border-l border-slate-700">
                <div class="text-right hidden sm:block">
                    <p class="text-sm font-medium text-white">Admin User</p>
                    <p class="text-xs text-slate-400">admin@pospay.com</p>
                </div>
                <div
                    class="w-10 h-10 bg-linear-to-br from-emerald-400 to-cyan-500 rounded-full flex items-center justify-center text-white font-bold">
                    AU
                </div>
            </div>
        </div>
    </div>
</div>
