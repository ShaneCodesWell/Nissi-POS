<x-layouts.admin>
    <div class="max-w-7xl mx-auto pb-8">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-white mb-2">Customers</h1>
                    <p class="text-slate-400">
                        Manage your customer relationships and interactions
                    </p>
                </div>
                <button
                    class="px-6 py-3 bg-linear-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white rounded-xl font-semibold transition-all flex items-center space-x-2 shadow-lg shadow-emerald-500/30">
                    <i class="fas fa-user-plus"></i>
                    <span>Add New Customer</span>
                </button>
            </div>

            <!-- Customer Stats Overview -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-slate-800/50 border border-slate-700/50 rounded-xl p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-slate-400 text-sm mb-1">Total Customers</p>
                            <p class="text-2xl font-bold text-white">2,847</p>
                            <p class="text-emerald-400 text-xs font-medium">
                                +124 this month
                            </p>
                        </div>
                        <div
                            class="w-12 h-12 bg-linear-to-br from-emerald-500/20 to-emerald-600/20 rounded-lg flex items-center justify-center">
                            <i class="fas fa-users text-xl text-emerald-400"></i>
                        </div>
                    </div>
                </div>
                <div class="bg-slate-800/50 border border-slate-700/50 rounded-xl p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-slate-400 text-sm mb-1">
                                Active Customers
                            </p>
                            <p class="text-2xl font-bold text-white">1,924</p>
                            <p class="text-cyan-400 text-xs font-medium">
                                67.5% of total
                            </p>
                        </div>
                        <div
                            class="w-12 h-12 bg-linear-to-br from-cyan-500/20 to-cyan-600/20 rounded-lg flex items-center justify-center">
                            <i class="fas fa-user-check text-xl text-cyan-400"></i>
                        </div>
                    </div>
                </div>
                <div class="bg-slate-800/50 border border-slate-700/50 rounded-xl p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-slate-400 text-sm mb-1">
                                Avg. Order Value
                            </p>
                            <p class="text-2xl font-bold text-white">$42.50</p>
                            <p class="text-purple-400 text-xs font-medium">
                                +8.2% growth
                            </p>
                        </div>
                        <div
                            class="w-12 h-12 bg-linear-to-br from-purple-500/20 to-purple-600/20 rounded-lg flex items-center justify-center">
                            <i class="fas fa-shopping-bag text-xl text-purple-400"></i>
                        </div>
                    </div>
                </div>
                <div class="bg-slate-800/50 border border-slate-700/50 rounded-xl p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-slate-400 text-sm mb-1">
                                Satisfaction Rate
                            </p>
                            <p class="text-2xl font-bold text-white">94%</p>
                            <p class="text-orange-400 text-xs font-medium">
                                +3.1% this month
                            </p>
                        </div>
                        <div
                            class="w-12 h-12 bg-linear-to-br from-orange-500/20 to-orange-600/20 rounded-lg flex items-center justify-center">
                            <i class="fas fa-star text-xl text-orange-400"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters & Search -->
            <div class="bg-slate-800/50 border border-slate-700/50 rounded-xl p-6">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <!-- Search -->
                    <div class="md:col-span-2">
                        <div class="relative">
                            <i
                                class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-slate-400"></i>
                            <input type="text" placeholder="Search customers by name, email, or phone..."
                                class="w-full bg-slate-700/50 border border-slate-600 rounded-lg pl-12 pr-4 py-3 text-white placeholder-slate-400 focus:outline-none focus:border-emerald-500 transition-all" />
                        </div>
                    </div>
                    <!-- Customer Type -->
                    <div>
                        <select
                            class="w-full bg-slate-700/50 border border-slate-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-emerald-500 transition-all">
                            <option>All Types</option>
                            <option>Regular</option>
                            <option>VIP</option>
                            <option>New</option>
                            <option>Inactive</option>
                        </select>
                    </div>
                    <!-- Location -->
                    <div>
                        <select
                            class="w-full bg-slate-700/50 border border-slate-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-emerald-500 transition-all">
                            <option>All Locations</option>
                            <option>Local</option>
                            <option>Regional</option>
                            <option>National</option>
                        </select>
                    </div>
                    <!-- Sort By -->
                    <div>
                        <select
                            class="w-full bg-slate-700/50 border border-slate-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-emerald-500 transition-all">
                            <option>Recently Added</option>
                            <option>Name A-Z</option>
                            <option>Total Spent</option>
                            <option>Last Purchase</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customers Grid/List -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-6">
            <!-- Customer Card 1 -->
            <div
                class="bg-slate-800/50 border border-slate-700/50 rounded-xl overflow-hidden hover:border-emerald-500/50 transition-all group">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div
                            class="w-16 h-16 bg-linear-to-br from-emerald-500 to-cyan-500 rounded-full flex items-center justify-center text-white font-bold text-xl">
                            JS
                        </div>
                        <span
                            class="px-3 py-1 bg-emerald-500/20 text-emerald-400 rounded-full text-xs font-medium">VIP</span>
                    </div>
                    <h3 class="text-white font-semibold text-lg mb-1">
                        John Smith
                    </h3>
                    <p class="text-slate-400 text-sm mb-3">
                        john.smith@email.com
                    </p>
                    <div class="space-y-2 mb-4">
                        <div class="flex justify-between">
                            <span class="text-slate-400 text-sm">Phone:</span>
                            <span class="text-white text-sm">+1 (555) 123-4567</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-400 text-sm">Total Spent:</span>
                            <span class="text-emerald-400 font-bold">$2,847</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-400 text-sm">Last Order:</span>
                            <span class="text-white text-sm">2 days ago</span>
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

            <!-- Customer Card 2 -->
            <div
                class="bg-slate-800/50 border border-slate-700/50 rounded-xl overflow-hidden hover:border-cyan-500/50 transition-all group">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div
                            class="w-16 h-16 bg-linear-to-br from-cyan-500 to-blue-500 rounded-full flex items-center justify-center text-white font-bold text-xl">
                            SJ
                        </div>
                        <span
                            class="px-3 py-1 bg-cyan-500/20 text-cyan-400 rounded-full text-xs font-medium">Regular</span>
                    </div>
                    <h3 class="text-white font-semibold text-lg mb-1">
                        Sarah Johnson
                    </h3>
                    <p class="text-slate-400 text-sm mb-3">sarah.j@email.com</p>
                    <div class="space-y-2 mb-4">
                        <div class="flex justify-between">
                            <span class="text-slate-400 text-sm">Phone:</span>
                            <span class="text-white text-sm">+1 (555) 987-6543</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-400 text-sm">Total Spent:</span>
                            <span class="text-cyan-400 font-bold">$1,245</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-400 text-sm">Last Order:</span>
                            <span class="text-white text-sm">1 week ago</span>
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

            <!-- Customer Card 3 -->
            <div
                class="bg-slate-800/50 border border-slate-700/50 rounded-xl overflow-hidden hover:border-purple-500/50 transition-all group">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div
                            class="w-16 h-16 bg-linear-to-br from-purple-500 to-pink-500 rounded-full flex items-center justify-center text-white font-bold text-xl">
                            MC
                        </div>
                        <span
                            class="px-3 py-1 bg-purple-500/20 text-purple-400 rounded-full text-xs font-medium">New</span>
                    </div>
                    <h3 class="text-white font-semibold text-lg mb-1">
                        Mike Chen
                    </h3>
                    <p class="text-slate-400 text-sm mb-3">mike.chen@email.com</p>
                    <div class="space-y-2 mb-4">
                        <div class="flex justify-between">
                            <span class="text-slate-400 text-sm">Phone:</span>
                            <span class="text-white text-sm">+1 (555) 456-7890</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-400 text-sm">Total Spent:</span>
                            <span class="text-purple-400 font-bold">$348</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-400 text-sm">Last Order:</span>
                            <span class="text-white text-sm">Yesterday</span>
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

            <!-- Customer Card 4 -->
            <div
                class="bg-slate-800/50 border border-slate-700/50 rounded-xl overflow-hidden hover:border-orange-500/50 transition-all group">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div
                            class="w-16 h-16 bg-linear-to-br from-orange-500 to-red-500 rounded-full flex items-center justify-center text-white font-bold text-xl">
                            ED
                        </div>
                        <span
                            class="px-3 py-1 bg-orange-500/20 text-orange-400 rounded-full text-xs font-medium">Regular</span>
                    </div>
                    <h3 class="text-white font-semibold text-lg mb-1">
                        Emma Davis
                    </h3>
                    <p class="text-slate-400 text-sm mb-3">
                        emma.davis@email.com
                    </p>
                    <div class="space-y-2 mb-4">
                        <div class="flex justify-between">
                            <span class="text-slate-400 text-sm">Phone:</span>
                            <span class="text-white text-sm">+1 (555) 234-5678</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-400 text-sm">Total Spent:</span>
                            <span class="text-orange-400 font-bold">$892</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-400 text-sm">Last Order:</span>
                            <span class="text-white text-sm">3 days ago</span>
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
        </div>

        <!-- Customer Activity & Insights -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Recent Activity -->
            <div class="bg-slate-800/50 border border-slate-700/50 rounded-xl">
                <div class="p-6 border-b border-slate-700/50">
                    <h2 class="text-xl font-bold text-white">
                        Recent Customer Activity
                    </h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <!-- Activity 1 -->
                        <div class="flex items-center gap-4 p-3 hover:bg-slate-700/30 rounded-lg transition-all">
                            <div class="w-10 h-10 bg-emerald-500/20 rounded-full flex items-center justify-center">
                                <i class="fas fa-shopping-cart text-emerald-400"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-white font-medium">New order placed</p>
                                <p class="text-slate-400 text-sm">
                                    John Smith - Order #7842 - $42.50
                                </p>
                            </div>
                            <span class="text-slate-400 text-sm">2 hours ago</span>
                        </div>
                        <!-- Activity 2 -->
                        <div class="flex items-center gap-4 p-3 hover:bg-slate-700/30 rounded-lg transition-all">
                            <div class="w-10 h-10 bg-cyan-500/20 rounded-full flex items-center justify-center">
                                <i class="fas fa-user-plus text-cyan-400"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-white font-medium">
                                    New customer registered
                                </p>
                                <p class="text-slate-400 text-sm">
                                    Lisa Brown joined as VIP member
                                </p>
                            </div>
                            <span class="text-slate-400 text-sm">5 hours ago</span>
                        </div>
                        <!-- Activity 3 -->
                        <div class="flex items-center gap-4 p-3 hover:bg-slate-700/30 rounded-lg transition-all">
                            <div class="w-10 h-10 bg-purple-500/20 rounded-full flex items-center justify-center">
                                <i class="fas fa-star text-purple-400"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-white font-medium">Review submitted</p>
                                <p class="text-slate-400 text-sm">
                                    Sarah Johnson rated 5 stars
                                </p>
                            </div>
                            <span class="text-slate-400 text-sm">1 day ago</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customer Segments -->
            <div class="bg-slate-800/50 border border-slate-700/50 rounded-xl">
                <div class="p-6 border-b border-slate-700/50">
                    <h2 class="text-xl font-bold text-white">
                        Customer Segments
                    </h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex justify-between items-center p-3 bg-slate-700/30 rounded-lg">
                            <div class="flex items-center gap-3">
                                <div class="w-3 h-3 bg-emerald-500 rounded-full"></div>
                                <span class="text-white">VIP Customers</span>
                            </div>
                            <span class="text-emerald-400 font-bold">324</span>
                        </div>
                        <div class="flex justify-between items-center p-3 bg-slate-700/30 rounded-lg">
                            <div class="flex items-center gap-3">
                                <div class="w-3 h-3 bg-cyan-500 rounded-full"></div>
                                <span class="text-white">Regular Customers</span>
                            </div>
                            <span class="text-cyan-400 font-bold">1,482</span>
                        </div>
                        <div class="flex justify-between items-center p-3 bg-slate-700/30 rounded-lg">
                            <div class="flex items-center gap-3">
                                <div class="w-3 h-3 bg-purple-500 rounded-full"></div>
                                <span class="text-white">New Customers</span>
                            </div>
                            <span class="text-purple-400 font-bold">187</span>
                        </div>
                        <div class="flex justify-between items-center p-3 bg-slate-700/30 rounded-lg">
                            <div class="flex items-center gap-3">
                                <div class="w-3 h-3 bg-orange-500 rounded-full"></div>
                                <span class="text-white">At Risk</span>
                            </div>
                            <span class="text-orange-400 font-bold">56</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div class="bg-slate-800/50 border border-slate-700/50 rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div class="text-slate-400 text-sm">
                    Showing <span class="text-white font-semibold">1-4</span> of
                    <span class="text-white font-semibold">2,847</span> customers
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
                        712
                    </button>
                    <button
                        class="px-4 py-2 bg-slate-700/50 hover:bg-slate-700 text-slate-400 rounded-lg transition-all">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Customer Modal -->
    <x-add-customer-modal />
</x-layouts.admin>
