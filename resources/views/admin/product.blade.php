<x-layouts.admin>
    <div class="max-w-7xl mx-auto">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-white mb-2">Products</h1>
                    <p class="text-slate-400">
                        Manage your store inventory and product catalog
                    </p>
                </div>
                <button
                    class="px-6 py-3 bg-linear-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white rounded-xl font-semibold transition-all flex items-center space-x-2 shadow-lg shadow-emerald-500/30">
                    <i class="fas fa-plus"></i>
                    <span>Add New Product</span>
                </button>
            </div>

            <!-- Stats Overview -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-slate-800/50 border border-slate-700/50 rounded-xl p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-slate-400 text-sm mb-1">Total Products</p>
                            <p class="text-2xl font-bold text-white">892</p>
                        </div>
                        <div
                            class="w-12 h-12 bg-linear-to-br from-emerald-500/20 to-emerald-600/20 rounded-lg flex items-center justify-center">
                            <i class="fas fa-box text-xl text-emerald-400"></i>
                        </div>
                    </div>
                </div>
                <div class="bg-slate-800/50 border border-slate-700/50 rounded-xl p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-slate-400 text-sm mb-1">Active</p>
                            <p class="text-2xl font-bold text-white">745</p>
                        </div>
                        <div
                            class="w-12 h-12 bg-linear-to-br from-cyan-500/20 to-cyan-600/20 rounded-lg flex items-center justify-center">
                            <i class="fas fa-check-circle text-xl text-cyan-400"></i>
                        </div>
                    </div>
                </div>
                <div class="bg-slate-800/50 border border-slate-700/50 rounded-xl p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-slate-400 text-sm mb-1">Low Stock</p>
                            <p class="text-2xl font-bold text-white">23</p>
                        </div>
                        <div
                            class="w-12 h-12 bg-linear-to-br from-orange-500/20 to-orange-600/20 rounded-lg flex items-center justify-center">
                            <i class="fas fa-exclamation-triangle text-xl text-orange-400"></i>
                        </div>
                    </div>
                </div>
                <div class="bg-slate-800/50 border border-slate-700/50 rounded-xl p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-slate-400 text-sm mb-1">Out of Stock</p>
                            <p class="text-2xl font-bold text-white">12</p>
                        </div>
                        <div
                            class="w-12 h-12 bg-linear-to-br from-red-500/20 to-red-600/20 rounded-lg flex items-center justify-center">
                            <i class="fas fa-times-circle text-xl text-red-400"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters & Search -->
            <div class="bg-slate-800/50 border border-slate-700/50 rounded-xl p-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Search -->
                    <div class="md:col-span-2">
                        <div class="relative">
                            <i
                                class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-slate-400"></i>
                            <input type="text" placeholder="Search products..."
                                class="w-full bg-slate-700/50 border border-slate-600 rounded-lg pl-12 pr-4 py-3 text-white placeholder-slate-400 focus:outline-none focus:border-emerald-500 transition-all" />
                        </div>
                    </div>
                    <!-- Category Filter -->
                    <div>
                        <select
                            class="w-full bg-slate-700/50 border border-slate-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-emerald-500 transition-all">
                            <option>All Categories</option>
                            <option>Beverages</option>
                            <option>Food</option>
                            <option>Snacks</option>
                            <option>Desserts</option>
                        </select>
                    </div>
                    <!-- Status Filter -->
                    <div>
                        <select
                            class="w-full bg-slate-700/50 border border-slate-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-emerald-500 transition-all">
                            <option>All Status</option>
                            <option>Active</option>
                            <option>Inactive</option>
                            <option>Low Stock</option>
                            <option>Out of Stock</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-6">
            <!-- Product Card 1 -->
            <div
                class="bg-slate-800/50 border border-slate-700/50 rounded-xl overflow-hidden hover:border-emerald-500/50 transition-all group">
                <div class="relative">
                    <div
                        class="w-full h-48 bg-linear-to-br from-emerald-500/10 to-emerald-600/10 flex items-center justify-center">
                        <i class="fas fa-coffee text-6xl text-emerald-400/50"></i>
                    </div>
                    <div class="absolute top-3 right-3 flex space-x-2">
                        <span
                            class="px-3 py-1 bg-emerald-500/90 text-white rounded-full text-xs font-medium">Active</span>
                    </div>
                    <div class="absolute top-3 left-3">
                        <span
                            class="px-3 py-1 bg-slate-900/90 text-white rounded-full text-xs font-medium">Beverages</span>
                    </div>
                </div>
                <div class="p-4">
                    <h3 class="text-white font-semibold text-lg mb-1">
                        Premium Coffee
                    </h3>
                    <p class="text-slate-400 text-sm mb-3">SKU: PRD-001</p>
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <p class="text-slate-400 text-xs mb-1">Price</p>
                            <p class="text-emerald-400 font-bold text-xl">$4.99</p>
                        </div>
                        <div class="text-right">
                            <p class="text-slate-400 text-xs mb-1">Stock</p>
                            <p class="text-white font-semibold">245</p>
                        </div>
                    </div>
                    <div class="flex space-x-2">
                        <button
                            class="flex-1 bg-slate-700/50 hover:bg-slate-700 text-white py-2 rounded-lg transition-all flex items-center justify-center space-x-2">
                            <i class="fas fa-edit text-sm"></i>
                            <span class="text-sm">Edit</span>
                        </button>
                        <button
                            class="flex-1 bg-emerald-500/20 hover:bg-emerald-500/30 text-emerald-400 py-2 rounded-lg transition-all flex items-center justify-center space-x-2">
                            <i class="fas fa-eye text-sm"></i>
                            <span class="text-sm">View</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Product Card 2 -->
            <div
                class="bg-slate-800/50 border border-slate-700/50 rounded-xl overflow-hidden hover:border-emerald-500/50 transition-all group">
                <div class="relative">
                    <div
                        class="w-full h-48 bg-linear-to-br from-cyan-500/10 to-cyan-600/10 flex items-center justify-center">
                        <i class="fas fa-bread-slice text-6xl text-cyan-400/50"></i>
                    </div>
                    <div class="absolute top-3 right-3 flex space-x-2">
                        <span
                            class="px-3 py-1 bg-emerald-500/90 text-white rounded-full text-xs font-medium">Active</span>
                    </div>
                    <div class="absolute top-3 left-3">
                        <span class="px-3 py-1 bg-slate-900/90 text-white rounded-full text-xs font-medium">Food</span>
                    </div>
                </div>
                <div class="p-4">
                    <h3 class="text-white font-semibold text-lg mb-1">
                        Butter Croissant
                    </h3>
                    <p class="text-slate-400 text-sm mb-3">SKU: PRD-002</p>
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <p class="text-slate-400 text-xs mb-1">Price</p>
                            <p class="text-cyan-400 font-bold text-xl">$3.49</p>
                        </div>
                        <div class="text-right">
                            <p class="text-slate-400 text-xs mb-1">Stock</p>
                            <p class="text-white font-semibold">189</p>
                        </div>
                    </div>
                    <div class="flex space-x-2">
                        <button
                            class="flex-1 bg-slate-700/50 hover:bg-slate-700 text-white py-2 rounded-lg transition-all flex items-center justify-center space-x-2">
                            <i class="fas fa-edit text-sm"></i>
                            <span class="text-sm">Edit</span>
                        </button>
                        <button
                            class="flex-1 bg-cyan-500/20 hover:bg-cyan-500/30 text-cyan-400 py-2 rounded-lg transition-all flex items-center justify-center space-x-2">
                            <i class="fas fa-eye text-sm"></i>
                            <span class="text-sm">View</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Product Card 3 -->
            <div
                class="bg-slate-800/50 border border-slate-700/50 rounded-xl overflow-hidden hover:border-emerald-500/50 transition-all group">
                <div class="relative">
                    <div
                        class="w-full h-48 bg-linear-to-br from-purple-500/10 to-purple-600/10 flex items-center justify-center">
                        <i class="fas fa-hamburger text-6xl text-purple-400/50"></i>
                    </div>
                    <div class="absolute top-3 right-3 flex space-x-2">
                        <span
                            class="px-3 py-1 bg-emerald-500/90 text-white rounded-full text-xs font-medium">Active</span>
                    </div>
                    <div class="absolute top-3 left-3">
                        <span class="px-3 py-1 bg-slate-900/90 text-white rounded-full text-xs font-medium">Food</span>
                    </div>
                </div>
                <div class="p-4">
                    <h3 class="text-white font-semibold text-lg mb-1">
                        Club Sandwich
                    </h3>
                    <p class="text-slate-400 text-sm mb-3">SKU: PRD-003</p>
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <p class="text-slate-400 text-xs mb-1">Price</p>
                            <p class="text-purple-400 font-bold text-xl">$7.99</p>
                        </div>
                        <div class="text-right">
                            <p class="text-slate-400 text-xs mb-1">Stock</p>
                            <p class="text-white font-semibold">156</p>
                        </div>
                    </div>
                    <div class="flex space-x-2">
                        <button
                            class="flex-1 bg-slate-700/50 hover:bg-slate-700 text-white py-2 rounded-lg transition-all flex items-center justify-center space-x-2">
                            <i class="fas fa-edit text-sm"></i>
                            <span class="text-sm">Edit</span>
                        </button>
                        <button
                            class="flex-1 bg-purple-500/20 hover:bg-purple-500/30 text-purple-400 py-2 rounded-lg transition-all flex items-center justify-center space-x-2">
                            <i class="fas fa-eye text-sm"></i>
                            <span class="text-sm">View</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Product Card 4 -->
            <div
                class="bg-slate-800/50 border border-slate-700/50 rounded-xl overflow-hidden hover:border-emerald-500/50 transition-all group">
                <div class="relative">
                    <div
                        class="w-full h-48 bg-linear-to-br from-orange-500/10 to-orange-600/10 flex items-center justify-center">
                        <i class="fas fa-glass-whiskey text-6xl text-orange-400/50"></i>
                    </div>
                    <div class="absolute top-3 right-3 flex space-x-2">
                        <span class="px-3 py-1 bg-orange-500/90 text-white rounded-full text-xs font-medium">Low
                            Stock</span>
                    </div>
                    <div class="absolute top-3 left-3">
                        <span
                            class="px-3 py-1 bg-slate-900/90 text-white rounded-full text-xs font-medium">Beverages</span>
                    </div>
                </div>
                <div class="p-4">
                    <h3 class="text-white font-semibold text-lg mb-1">
                        Fresh Orange Juice
                    </h3>
                    <p class="text-slate-400 text-sm mb-3">SKU: PRD-004</p>
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <p class="text-slate-400 text-xs mb-1">Price</p>
                            <p class="text-orange-400 font-bold text-xl">$4.49</p>
                        </div>
                        <div class="text-right">
                            <p class="text-slate-400 text-xs mb-1">Stock</p>
                            <p class="text-orange-400 font-semibold">15</p>
                        </div>
                    </div>
                    <div class="flex space-x-2">
                        <button
                            class="flex-1 bg-slate-700/50 hover:bg-slate-700 text-white py-2 rounded-lg transition-all flex items-center justify-center space-x-2">
                            <i class="fas fa-edit text-sm"></i>
                            <span class="text-sm">Edit</span>
                        </button>
                        <button
                            class="flex-1 bg-orange-500/20 hover:bg-orange-500/30 text-orange-400 py-2 rounded-lg transition-all flex items-center justify-center space-x-2">
                            <i class="fas fa-eye text-sm"></i>
                            <span class="text-sm">View</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Product Card 5 -->
            <div
                class="bg-slate-800/50 border border-slate-700/50 rounded-xl overflow-hidden hover:border-emerald-500/50 transition-all group">
                <div class="relative">
                    <div
                        class="w-full h-48 bg-linear-to-br from-pink-500/10 to-pink-600/10 flex items-center justify-center">
                        <i class="fas fa-ice-cream text-6xl text-pink-400/50"></i>
                    </div>
                    <div class="absolute top-3 right-3 flex space-x-2">
                        <span
                            class="px-3 py-1 bg-emerald-500/90 text-white rounded-full text-xs font-medium">Active</span>
                    </div>
                    <div class="absolute top-3 left-3">
                        <span
                            class="px-3 py-1 bg-slate-900/90 text-white rounded-full text-xs font-medium">Desserts</span>
                    </div>
                </div>
                <div class="p-4">
                    <h3 class="text-white font-semibold text-lg mb-1">
                        Vanilla Ice Cream
                    </h3>
                    <p class="text-slate-400 text-sm mb-3">SKU: PRD-005</p>
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <p class="text-slate-400 text-xs mb-1">Price</p>
                            <p class="text-pink-400 font-bold text-xl">$5.99</p>
                        </div>
                        <div class="text-right">
                            <p class="text-slate-400 text-xs mb-1">Stock</p>
                            <p class="text-white font-semibold">98</p>
                        </div>
                    </div>
                    <div class="flex space-x-2">
                        <button
                            class="flex-1 bg-slate-700/50 hover:bg-slate-700 text-white py-2 rounded-lg transition-all flex items-center justify-center space-x-2">
                            <i class="fas fa-edit text-sm"></i>
                            <span class="text-sm">Edit</span>
                        </button>
                        <button
                            class="flex-1 bg-pink-500/20 hover:bg-pink-500/30 text-pink-400 py-2 rounded-lg transition-all flex items-center justify-center space-x-2">
                            <i class="fas fa-eye text-sm"></i>
                            <span class="text-sm">View</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Product Card 6 -->
            <div
                class="bg-slate-800/50 border border-slate-700/50 rounded-xl overflow-hidden hover:border-emerald-500/50 transition-all group">
                <div class="relative">
                    <div
                        class="w-full h-48 bg-linear-to-br from-blue-500/10 to-blue-600/10 flex items-center justify-center">
                        <i class="fas fa-cookie-bite text-6xl text-blue-400/50"></i>
                    </div>
                    <div class="absolute top-3 right-3 flex space-x-2">
                        <span
                            class="px-3 py-1 bg-emerald-500/90 text-white rounded-full text-xs font-medium">Active</span>
                    </div>
                    <div class="absolute top-3 left-3">
                        <span
                            class="px-3 py-1 bg-slate-900/90 text-white rounded-full text-xs font-medium">Snacks</span>
                    </div>
                </div>
                <div class="p-4">
                    <h3 class="text-white font-semibold text-lg mb-1">
                        Chocolate Chip Cookie
                    </h3>
                    <p class="text-slate-400 text-sm mb-3">SKU: PRD-006</p>
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <p class="text-slate-400 text-xs mb-1">Price</p>
                            <p class="text-blue-400 font-bold text-xl">$2.99</p>
                        </div>
                        <div class="text-right">
                            <p class="text-slate-400 text-xs mb-1">Stock</p>
                            <p class="text-white font-semibold">324</p>
                        </div>
                    </div>
                    <div class="flex space-x-2">
                        <button
                            class="flex-1 bg-slate-700/50 hover:bg-slate-700 text-white py-2 rounded-lg transition-all flex items-center justify-center space-x-2">
                            <i class="fas fa-edit text-sm"></i>
                            <span class="text-sm">Edit</span>
                        </button>
                        <button
                            class="flex-1 bg-blue-500/20 hover:bg-blue-500/30 text-blue-400 py-2 rounded-lg transition-all flex items-center justify-center space-x-2">
                            <i class="fas fa-eye text-sm"></i>
                            <span class="text-sm">View</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Product Card 7 -->
            <div
                class="bg-slate-800/50 border border-slate-700/50 rounded-xl overflow-hidden hover:border-emerald-500/50 transition-all group">
                <div class="relative">
                    <div
                        class="w-full h-48 bg-linear-to-br from-teal-500/10 to-teal-600/10 flex items-center justify-center">
                        <i class="fas fa-mug-hot text-6xl text-teal-400/50"></i>
                    </div>
                    <div class="absolute top-3 right-3 flex space-x-2">
                        <span class="px-3 py-1 bg-red-500/90 text-white rounded-full text-xs font-medium">Out of
                            Stock</span>
                    </div>
                    <div class="absolute top-3 left-3">
                        <span
                            class="px-3 py-1 bg-slate-900/90 text-white rounded-full text-xs font-medium">Beverages</span>
                    </div>
                </div>
                <div class="p-4">
                    <h3 class="text-white font-semibold text-lg mb-1">
                        Green Tea Latte
                    </h3>
                    <p class="text-slate-400 text-sm mb-3">SKU: PRD-007</p>
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <p class="text-slate-400 text-xs mb-1">Price</p>
                            <p class="text-teal-400 font-bold text-xl">$5.49</p>
                        </div>
                        <div class="text-right">
                            <p class="text-slate-400 text-xs mb-1">Stock</p>
                            <p class="text-red-400 font-semibold">0</p>
                        </div>
                    </div>
                    <div class="flex space-x-2">
                        <button
                            class="flex-1 bg-slate-700/50 hover:bg-slate-700 text-white py-2 rounded-lg transition-all flex items-center justify-center space-x-2">
                            <i class="fas fa-edit text-sm"></i>
                            <span class="text-sm">Edit</span>
                        </button>
                        <button
                            class="flex-1 bg-teal-500/20 hover:bg-teal-500/30 text-teal-400 py-2 rounded-lg transition-all flex items-center justify-center space-x-2">
                            <i class="fas fa-eye text-sm"></i>
                            <span class="text-sm">View</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Product Card 8 -->
            <div
                class="bg-slate-800/50 border border-slate-700/50 rounded-xl overflow-hidden hover:border-emerald-500/50 transition-all group">
                <div class="relative">
                    <div
                        class="w-full h-48 bg-linear-to-br from-yellow-500/10 to-yellow-600/10 flex items-center justify-center">
                        <i class="fas fa-pizza-slice text-6xl text-yellow-400/50"></i>
                    </div>
                    <div class="absolute top-3 right-3 flex space-x-2">
                        <span
                            class="px-3 py-1 bg-slate-500/90 text-white rounded-full text-xs font-medium">Inactive</span>
                    </div>
                    <div class="absolute top-3 left-3">
                        <span class="px-3 py-1 bg-slate-900/90 text-white rounded-full text-xs font-medium">Food</span>
                    </div>
                </div>
                <div class="p-4">
                    <h3 class="text-white font-semibold text-lg mb-1">
                        Margherita Pizza
                    </h3>
                    <p class="text-slate-400 text-sm mb-3">SKU: PRD-008</p>
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <p class="text-slate-400 text-xs mb-1">Price</p>
                            <p class="text-yellow-400 font-bold text-xl">$12.99</p>
                        </div>
                        <div class="text-right">
                            <p class="text-slate-400 text-xs mb-1">Stock</p>
                            <p class="text-white font-semibold">67</p>
                        </div>
                    </div>
                    <div class="flex space-x-2">
                        <button
                            class="flex-1 bg-slate-700/50 hover:bg-slate-700 text-white py-2 rounded-lg transition-all flex items-center justify-center space-x-2">
                            <i class="fas fa-edit text-sm"></i>
                            <span class="text-sm">Edit</span>
                        </button>
                        <button
                            class="flex-1 bg-yellow-500/20 hover:bg-yellow-500/30 text-yellow-400 py-2 rounded-lg transition-all flex items-center justify-center space-x-2">
                            <i class="fas fa-eye text-sm"></i>
                            <span class="text-sm">View</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div class="bg-slate-800/50 border border-slate-700/50 rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div class="text-slate-400 text-sm">
                    Showing <span class="text-white font-semibold">1-8</span> of
                    <span class="text-white font-semibold">892</span> products
                </div>
                <div class="flex items-center space-x-2">
                    <button
                        class="px-4 py-2 bg-slate-700/50 hover:bg-slate-700 text-slate-400 rounded-lg transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button class="px-4 py-2 bg-emerald-500 text-white rounded-lg font-semibold">
                        1
                    </button>
                    <button class="px-4 py-2 bg-slate-700/50 hover:bg-slate-700 text-white rounded-lg transition-all">
                        2
                    </button>
                    <button class="px-4 py-2 bg-slate-700/50 hover:bg-slate-700 text-white rounded-lg transition-all">
                        3
                    </button>
                    <span class="px-4 py-2 text-slate-400">...</span>
                    <button class="px-4 py-2 bg-slate-700/50 hover:bg-slate-700 text-white rounded-lg transition-all">
                        112
                    </button>
                    <button
                        class="px-4 py-2 bg-slate-700/50 hover:bg-slate-700 text-slate-400 rounded-lg transition-all">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Product Modal (Hidden by default) -->
    <x-add-product-modal />
</x-layouts.admin>
