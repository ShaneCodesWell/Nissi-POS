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
                <p class="text-xs text-slate-400">Terminal</p>
            </div>
        </div>
    </div>

    <!-- Navigation Section -->
    <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
        <a href="#" id="nav-terminal"
            class="nav-item active-link flex items-center gap-3 p-3 rounded-xl transition-all duration-300 group">
            <div
                class="icon-wrapper w-9 h-9 bg-linear-to-br from-emerald-500/20 to-cyan-500/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-cash-register text-emerald-400 text-sm"></i>
            </div>
            <span class="font-medium text-sm text-white">Terminal</span>
        </a>

        <a href="#" id="nav-products"
            class="nav-item hover:bg-slate-800/50 flex items-center gap-3 p-3 rounded-xl transition-all duration-300 group">
            <div class="icon-wrapper w-9 h-9 bg-slate-800/50 rounded-lg flex items-center justify-center">
                <i class="fas fa-box-open text-slate-400 text-sm group-hover:text-cyan-400 transition-colors"></i>
            </div>
            <span class="font-medium text-sm text-slate-300 group-hover:text-white transition-colors">Products</span>
        </a>

        <a href="#" id="nav-sales"
            class="nav-item hover:bg-slate-800/50 flex items-center gap-3 p-3 rounded-xl transition-all duration-300 group">
            <div class="icon-wrapper w-9 h-9 bg-slate-800/50 rounded-lg flex items-center justify-center">
                <i class="fas fa-shopping-cart text-slate-400 text-sm group-hover:text-cyan-400 transition-colors"></i>
            </div>
            <span class="font-medium text-sm text-slate-300 group-hover:text-white transition-colors">Sales</span>
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

<script>
    // Terminal Sidebar Active State Handler
    document.addEventListener('DOMContentLoaded', function() {
        const navLinks = document.querySelectorAll('.nav-item');
        
        navLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                // Remove active state from all links
                navLinks.forEach(nav => {
                    nav.classList.remove('active-link');
                    nav.classList.add('hover:bg-slate-800/50');
                    
                    // Reset icon wrapper
                    const iconWrapper = nav.querySelector('.icon-wrapper');
                    iconWrapper.classList.remove('bg-linear-to-br', 'from-emerald-500/20', 'to-cyan-500/20');
                    iconWrapper.classList.add('bg-slate-800/50');
                    
                    // Reset icon color
                    const icon = nav.querySelector('i');
                    icon.classList.remove('text-emerald-400');
                    icon.classList.add('text-slate-400', 'group-hover:text-cyan-400');
                    
                    // Reset text color
                    const span = nav.querySelector('span');
                    span.classList.remove('text-white');
                    span.classList.add('text-slate-300', 'group-hover:text-white');
                });
                
                // Add active state to clicked link
                this.classList.add('active-link');
                this.classList.remove('hover:bg-slate-800/50');
                
                // Update icon wrapper
                const iconWrapper = this.querySelector('.icon-wrapper');
                iconWrapper.classList.add('bg-linear-to-br', 'from-emerald-500/20', 'to-cyan-500/20');
                iconWrapper.classList.remove('bg-slate-800/50');
                
                // Update icon color
                const icon = this.querySelector('i');
                icon.classList.add('text-emerald-400');
                icon.classList.remove('text-slate-400', 'group-hover:text-cyan-400');
                
                // Update text color
                const span = this.querySelector('span');
                span.classList.add('text-white');
                span.classList.remove('text-slate-300', 'group-hover:text-white');
            });
        });
    });
</script>