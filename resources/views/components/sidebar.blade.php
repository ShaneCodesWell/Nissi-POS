<div
    class="w-64 bg-linear-to-br from-slate-900 via-slate-800 to-slate-900 text-white flex flex-col shrink-0 border-r border-slate-700/50">
    <!-- Header Section -->
    <div class="p-3 border-b border-slate-700/50">
        <div class="flex items-center gap-3">
            <div class="relative">
                <div class="absolute inset-0 bg-emerald-500/20 blur-xl logo-glow rounded-full"></div>
                <div
                    class="relative w-12 h-12 bg-linear-to-br from-emerald-400 to-cyan-500 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-cash-register text-xl text-white"></i>
                </div>
            </div>
            <div>
                <p class="text-lg font-bold bg-linear-to-r from-emerald-400 to-cyan-400 bg-clip-text text-transparent">
                    Nissi POS
                </p>
                <p class="text-xs text-slate-400">Admin Dashboard</p>
            </div>
        </div>
    </div>

    <!-- Navigation Section -->
    <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
        <a href="{{ route('pos.index') }}"
            class="nav-item {{ request()->routeIs('pos.index') ? 'active-link' : 'hover:bg-slate-800/50' }} flex items-center gap-3 p-3 rounded-xl transition-all duration-300 group">
            <div class="icon-wrapper w-9 h-9 {{ request()->routeIs('pos.index') ? 'bg-linear-to-br from-emerald-500/20 to-cyan-500/20' : 'bg-slate-800/50' }} rounded-lg flex items-center justify-center">
                <i class="fas fa-cash-register {{ request()->routeIs('pos.index') ? 'text-emerald-400' : 'text-slate-400 group-hover:text-cyan-400' }} text-sm transition-colors"></i>
            </div>
            <span class="font-medium text-sm {{ request()->routeIs('pos.index') ? 'text-white' : 'text-slate-300 group-hover:text-white' }} transition-colors">Terminal</span>
        </a>
        
        <a href="{{ route('admin.index') }}"
            class="nav-item {{ request()->routeIs('admin.index') ? 'active-link' : 'hover:bg-slate-800/50' }} flex items-center gap-3 p-3 rounded-xl transition-all duration-300 group">
            <div
                class="icon-wrapper w-9 h-9 {{ request()->routeIs('admin.index') ? 'bg-linear-to-br from-emerald-500/20 to-cyan-500/20' : 'bg-slate-800/50' }} rounded-lg flex items-center justify-center">
                <i class="fas fa-home {{ request()->routeIs('admin.index') ? 'text-emerald-400' : 'text-slate-400 group-hover:text-cyan-400' }} text-sm transition-colors"></i>
            </div>
            <span class="font-medium text-sm {{ request()->routeIs('admin.index') ? 'text-white' : 'text-slate-300 group-hover:text-white' }} transition-colors">Dashboard</span>
        </a>

        <a href="{{ route('admin.product') }}"
            class="nav-item {{ request()->routeIs('admin.product') ? 'active-link' : 'hover:bg-slate-800/50' }} flex items-center gap-3 p-3 rounded-xl transition-all duration-300 group">
            <div class="icon-wrapper w-9 h-9 {{ request()->routeIs('admin.product') ? 'bg-linear-to-br from-emerald-500/20 to-cyan-500/20' : 'bg-slate-800/50' }} rounded-lg flex items-center justify-center">
                <i class="fas fa-box-open {{ request()->routeIs('admin.product') ? 'text-emerald-400' : 'text-slate-400 group-hover:text-cyan-400' }} text-sm transition-colors"></i>
            </div>
            <span class="font-medium text-sm {{ request()->routeIs('admin.product') ? 'text-white' : 'text-slate-300 group-hover:text-white' }} transition-colors">Products</span>
        </a>

        <a href="{{ route('admin.sales') }}"
            class="nav-item {{ request()->routeIs('admin.sales') ? 'active-link' : 'hover:bg-slate-800/50' }} flex items-center gap-3 p-3 rounded-xl transition-all duration-300 group">
            <div class="icon-wrapper w-9 h-9 {{ request()->routeIs('admin.sales') ? 'bg-linear-to-br from-emerald-500/20 to-cyan-500/20' : 'bg-slate-800/50' }} rounded-lg flex items-center justify-center">
                <i class="fas fa-shopping-cart {{ request()->routeIs('admin.sales') ? 'text-emerald-400' : 'text-slate-400 group-hover:text-cyan-400' }} text-sm transition-colors"></i>
            </div>
            <span class="font-medium text-sm {{ request()->routeIs('admin.sales') ? 'text-white' : 'text-slate-300 group-hover:text-white' }} transition-colors">Sales</span>
        </a>

        <a href="{{ route('admin.crm') }}"
            class="nav-item {{ request()->routeIs('admin.crm') ? 'active-link' : 'hover:bg-slate-800/50' }} flex items-center gap-3 p-3 rounded-xl transition-all duration-300 group">
            <div class="icon-wrapper w-9 h-9 {{ request()->routeIs('admin.crm') ? 'bg-linear-to-br from-emerald-500/20 to-cyan-500/20' : 'bg-slate-800/50' }} rounded-lg flex items-center justify-center">
                <i class="fas fa-users {{ request()->routeIs('admin.crm') ? 'text-emerald-400' : 'text-slate-400 group-hover:text-cyan-400' }} text-sm transition-colors"></i>
            </div>
            <span class="font-medium text-sm {{ request()->routeIs('admin.crm') ? 'text-white' : 'text-slate-300 group-hover:text-white' }} transition-colors">CRM</span>
        </a>

        <a href="{{ route('admin.reports') }}"
            class="nav-item {{ request()->routeIs('admin.reports') ? 'active-link' : 'hover:bg-slate-800/50' }} flex items-center gap-3 p-3 rounded-xl transition-all duration-300 group">
            <div class="icon-wrapper w-9 h-9 {{ request()->routeIs('admin.reports') ? 'bg-linear-to-br from-emerald-500/20 to-cyan-500/20' : 'bg-slate-800/50' }} rounded-lg flex items-center justify-center">
                <i class="fas fa-chart-line {{ request()->routeIs('admin.reports') ? 'text-emerald-400' : 'text-slate-400 group-hover:text-cyan-400' }} text-sm transition-colors"></i>
            </div>
            <span class="font-medium text-sm {{ request()->routeIs('admin.reports') ? 'text-white' : 'text-slate-300 group-hover:text-white' }} transition-colors">Reports</span>
        </a>
    </nav>

    <!-- Footer Section -->
    <div class="p-2 border-t border-slate-700/50 space-y-1">
        <a href="#"
            class="flex items-center gap-3 p-3 rounded-xl hover:bg-slate-800/50 transition-all duration-300 group">
            <div class="icon-wrapper w-9 h-9 bg-slate-800/50 rounded-lg flex items-center justify-center">
                <i class="fas fa-cog text-slate-400 text-sm group-hover:text-cyan-400 transition-colors"></i>
            </div>
            <span class="font-medium text-sm text-slate-300 group-hover:text-white transition-colors">Settings</span>
        </a>

        <a href="#"
            class="flex items-center gap-3 p-3 rounded-xl hover:bg-red-500/10 transition-all duration-300 group">
            <div
                class="icon-wrapper w-9 h-9 bg-slate-800/50 rounded-lg flex items-center justify-center group-hover:bg-red-500/20">
                <i class="fas fa-sign-out-alt text-slate-400 text-sm group-hover:text-red-400 transition-colors"></i>
            </div>
            <span class="font-medium text-sm text-slate-300 group-hover:text-red-400 transition-colors">Logout</span>
        </a>
    </div>
</div>