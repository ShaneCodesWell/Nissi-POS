<x-layouts.admin>
    <div class="max-w-7xl mx-auto">
        <!-- Welcome Section -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-white mb-2">
                Dashboard Overview
            </h1>
            <p class="text-slate-400">
                Welcome back! Here's what's happening with your store today.
            </p>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Sales Card -->
            <div
                class="bg-slate-800/50 border border-slate-700/50 rounded-2xl p-6 hover:border-emerald-500/50 transition-all">
                <div class="flex items-center justify-between mb-4">
                    <div
                        class="w-12 h-12 bg-linear-to-br from-emerald-500/20 to-emerald-600/20 rounded-xl flex items-center justify-center">
                        <i class="fas fa-dollar-sign text-2xl text-emerald-400"></i>
                    </div>
                    <span class="text-emerald-400 text-sm font-semibold flex items-center">
                        <i class="fas fa-arrow-up mr-1"></i> 12.5%
                    </span>
                </div>
                <h3 class="text-slate-400 text-sm font-medium mb-1">
                    Total Sales
                </h3>
                <p class="text-3xl font-bold text-white mb-1">$45,231</p>
                <p class="text-slate-500 text-xs">vs last month</p>
            </div>

            <!-- Total Orders Card -->
            <div
                class="bg-slate-800/50 border border-slate-700/50 rounded-2xl p-6 hover:border-cyan-500/50 transition-all">
                <div class="flex items-center justify-between mb-4">
                    <div
                        class="w-12 h-12 bg-linear-to-br from-cyan-500/20 to-cyan-600/20 rounded-xl flex items-center justify-center">
                        <i class="fas fa-shopping-cart text-2xl text-cyan-400"></i>
                    </div>
                    <span class="text-cyan-400 text-sm font-semibold flex items-center">
                        <i class="fas fa-arrow-up mr-1"></i> 8.2%
                    </span>
                </div>
                <h3 class="text-slate-400 text-sm font-medium mb-1">
                    Total Orders
                </h3>
                <p class="text-3xl font-bold text-white mb-1">1,842</p>
                <p class="text-slate-500 text-xs">vs last month</p>
            </div>

            <!-- Total Products Card -->
            <div
                class="bg-slate-800/50 border border-slate-700/50 rounded-2xl p-6 hover:border-purple-500/50 transition-all">
                <div class="flex items-center justify-between mb-4">
                    <div
                        class="w-12 h-12 bg-linear-to-br from-purple-500/20 to-purple-600/20 rounded-xl flex items-center justify-center">
                        <i class="fas fa-box text-2xl text-purple-400"></i>
                    </div>
                    <span class="text-slate-400 text-sm font-semibold flex items-center">
                        <i class="fas fa-minus mr-1"></i> 0%
                    </span>
                </div>
                <h3 class="text-slate-400 text-sm font-medium mb-1">
                    Total Products
                </h3>
                <p class="text-3xl font-bold text-white mb-1">892</p>
                <p class="text-slate-500 text-xs">in inventory</p>
            </div>

            <!-- Active Users Card -->
            <div
                class="bg-slate-800/50 border border-slate-700/50 rounded-2xl p-6 hover:border-orange-500/50 transition-all">
                <div class="flex items-center justify-between mb-4">
                    <div
                        class="w-12 h-12 bg-linear-to-br from-orange-500/20 to-orange-600/20 rounded-xl flex items-center justify-center">
                        <i class="fas fa-users text-2xl text-orange-400"></i>
                    </div>
                    <span class="text-orange-400 text-sm font-semibold flex items-center">
                        <i class="fas fa-arrow-up mr-1"></i> 15.3%
                    </span>
                </div>
                <h3 class="text-slate-400 text-sm font-medium mb-1">
                    Active Users
                </h3>
                <p class="text-3xl font-bold text-white mb-1">328</p>
                <p class="text-slate-500 text-xs">today's sessions</p>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- Sales Chart -->
            <div class="lg:col-span-2 bg-slate-800/50 border border-slate-700/50 rounded-2xl p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-xl font-bold text-white mb-1">
                            Sales Overview
                        </h3>
                        <p class="text-slate-400 text-sm">Monthly revenue trends</p>
                    </div>
                    <select
                        class="bg-slate-700/50 border border-slate-600 rounded-lg px-4 py-2 text-white text-sm focus:outline-none focus:border-emerald-500">
                        <option>Last 7 days</option>
                        <option>Last 30 days</option>
                        <option>Last 90 days</option>
                    </select>
                </div>
                <div class="h-64 flex items-end justify-between space-x-2">
                    <div class="flex-1 bg-linear-to-t from-emerald-500/80 to-emerald-500/40 rounded-t-lg hover:from-emerald-500 transition-all"
                        style="height: 45%"></div>
                    <div class="flex-1 bg-linear-to-t from-emerald-500/80 to-emerald-500/40 rounded-t-lg hover:from-emerald-500 transition-all"
                        style="height: 60%"></div>
                    <div class="flex-1 bg-linear-to-t from-emerald-500/80 to-emerald-500/40 rounded-t-lg hover:from-emerald-500 transition-all"
                        style="height: 75%"></div>
                    <div class="flex-1 bg-linear-to-t from-emerald-500/80 to-emerald-500/40 rounded-t-lg hover:from-emerald-500 transition-all"
                        style="height: 55%"></div>
                    <div class="flex-1 bg-linear-to-t from-emerald-500/80 to-emerald-500/40 rounded-t-lg hover:from-emerald-500 transition-all"
                        style="height: 85%"></div>
                    <div class="flex-1 bg-linear-to-t from-emerald-500/80 to-emerald-500/40 rounded-t-lg hover:from-emerald-500 transition-all"
                        style="height: 70%"></div>
                    <div class="flex-1 bg-linear-to-t from-emerald-500/80 to-emerald-500/40 rounded-t-lg hover:from-emerald-500 transition-all"
                        style="height: 90%"></div>
                </div>
                <div class="flex justify-between mt-4 text-slate-500 text-xs">
                    <span>Mon</span>
                    <span>Tue</span>
                    <span>Wed</span>
                    <span>Thu</span>
                    <span>Fri</span>
                    <span>Sat</span>
                    <span>Sun</span>
                </div>
            </div>

            <!-- Top Products -->
            <div class="bg-slate-800/50 border border-slate-700/50 rounded-2xl p-6">
                <h3 class="text-xl font-bold text-white mb-6">Top Products</h3>
                <div class="space-y-4">
                    <div
                        class="flex items-center justify-between p-3 bg-slate-700/30 rounded-lg hover:bg-slate-700/50 transition-all">
                        <div class="flex items-center space-x-3">
                            <div
                                class="w-10 h-10 bg-linear-to-br from-emerald-500 to-emerald-600 rounded-lg flex items-center justify-center text-white font-bold">
                                1
                            </div>
                            <div>
                                <p class="text-white font-medium text-sm">
                                    Premium Coffee
                                </p>
                                <p class="text-slate-400 text-xs">245 sold</p>
                            </div>
                        </div>
                        <span class="text-emerald-400 font-semibold text-sm">$2,450</span>
                    </div>
                    <div
                        class="flex items-center justify-between p-3 bg-slate-700/30 rounded-lg hover:bg-slate-700/50 transition-all">
                        <div class="flex items-center space-x-3">
                            <div
                                class="w-10 h-10 bg-linear-to-br from-cyan-500 to-cyan-600 rounded-lg flex items-center justify-center text-white font-bold">
                                2
                            </div>
                            <div>
                                <p class="text-white font-medium text-sm">Croissant</p>
                                <p class="text-slate-400 text-xs">198 sold</p>
                            </div>
                        </div>
                        <span class="text-cyan-400 font-semibold text-sm">$1,980</span>
                    </div>
                    <div
                        class="flex items-center justify-between p-3 bg-slate-700/30 rounded-lg hover:bg-slate-700/50 transition-all">
                        <div class="flex items-center space-x-3">
                            <div
                                class="w-10 h-10 bg-linear-to-br from-purple-500 to-purple-600 rounded-lg flex items-center justify-center text-white font-bold">
                                3
                            </div>
                            <div>
                                <p class="text-white font-medium text-sm">Sandwich</p>
                                <p class="text-slate-400 text-xs">156 sold</p>
                            </div>
                        </div>
                        <span class="text-purple-400 font-semibold text-sm">$1,560</span>
                    </div>
                    <div
                        class="flex items-center justify-between p-3 bg-slate-700/30 rounded-lg hover:bg-slate-700/50 transition-all">
                        <div class="flex items-center space-x-3">
                            <div
                                class="w-10 h-10 bg-linear-to-br from-orange-500 to-orange-600 rounded-lg flex items-center justify-center text-white font-bold">
                                4
                            </div>
                            <div>
                                <p class="text-white font-medium text-sm">Juice</p>
                                <p class="text-slate-400 text-xs">132 sold</p>
                            </div>
                        </div>
                        <span class="text-orange-400 font-semibold text-sm">$1,320</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Orders Table -->
        <div class="bg-slate-800/50 border border-slate-700/50 rounded-2xl p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-xl font-bold text-white mb-1">
                        Recent Orders
                    </h3>
                    <p class="text-slate-400 text-sm">
                        Latest transactions from your store
                    </p>
                </div>
                <button
                    class="px-4 py-2 bg-emerald-500/20 hover:bg-emerald-500/30 border border-emerald-500/50 rounded-lg text-emerald-400 text-sm font-medium transition-all">
                    View All
                </button>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-slate-700">
                            <th class="text-left py-3 px-4 text-slate-400 font-medium text-sm">
                                Order ID
                            </th>
                            <th class="text-left py-3 px-4 text-slate-400 font-medium text-sm">
                                Customer
                            </th>
                            <th class="text-left py-3 px-4 text-slate-400 font-medium text-sm">
                                Date
                            </th>
                            <th class="text-left py-3 px-4 text-slate-400 font-medium text-sm">
                                Total
                            </th>
                            <th class="text-left py-3 px-4 text-slate-400 font-medium text-sm">
                                Status
                            </th>
                            <th class="text-left py-3 px-4 text-slate-400 font-medium text-sm">
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b border-slate-700/50 hover:bg-slate-700/30 transition-all">
                            <td class="py-4 px-4 text-white font-mono text-sm">
                                #ORD-2847
                            </td>
                            <td class="py-4 px-4">
                                <div class="flex items-center space-x-3">
                                    <div
                                        class="w-8 h-8 bg-linear-to-br from-emerald-500 to-emerald-600 rounded-full flex items-center justify-center text-white text-xs font-bold">
                                        JD
                                    </div>
                                    <span class="text-white text-sm">John Doe</span>
                                </div>
                            </td>
                            <td class="py-4 px-4 text-slate-400 text-sm">
                                Oct 27, 2025
                            </td>
                            <td class="py-4 px-4 text-white font-semibold text-sm">
                                $124.50
                            </td>
                            <td class="py-4 px-4">
                                <span
                                    class="px-3 py-1 bg-emerald-500/20 text-emerald-400 rounded-full text-xs font-medium">Completed</span>
                            </td>
                            <td class="py-4 px-4">
                                <button class="text-cyan-400 hover:text-cyan-300 transition-colors">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                        <tr class="border-b border-slate-700/50 hover:bg-slate-700/30 transition-all">
                            <td class="py-4 px-4 text-white font-mono text-sm">
                                #ORD-2846
                            </td>
                            <td class="py-4 px-4">
                                <div class="flex items-center space-x-3">
                                    <div
                                        class="w-8 h-8 bg-linear-to-br from-cyan-500 to-cyan-600 rounded-full flex items-center justify-center text-white text-xs font-bold">
                                        AS
                                    </div>
                                    <span class="text-white text-sm">Alice Smith</span>
                                </div>
                            </td>
                            <td class="py-4 px-4 text-slate-400 text-sm">
                                Oct 27, 2025
                            </td>
                            <td class="py-4 px-4 text-white font-semibold text-sm">
                                $89.99
                            </td>
                            <td class="py-4 px-4">
                                <span
                                    class="px-3 py-1 bg-yellow-500/20 text-yellow-400 rounded-full text-xs font-medium">Pending</span>
                            </td>
                            <td class="py-4 px-4">
                                <button class="text-cyan-400 hover:text-cyan-300 transition-colors">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                        <tr class="border-b border-slate-700/50 hover:bg-slate-700/30 transition-all">
                            <td class="py-4 px-4 text-white font-mono text-sm">
                                #ORD-2845
                            </td>
                            <td class="py-4 px-4">
                                <div class="flex items-center space-x-3">
                                    <div
                                        class="w-8 h-8 bg-linear-to-br from-purple-500 to-purple-600 rounded-full flex items-center justify-center text-white text-xs font-bold">
                                        BJ
                                    </div>
                                    <span class="text-white text-sm">Bob Johnson</span>
                                </div>
                            </td>
                            <td class="py-4 px-4 text-slate-400 text-sm">
                                Oct 26, 2025
                            </td>
                            <td class="py-4 px-4 text-white font-semibold text-sm">
                                $156.75
                            </td>
                            <td class="py-4 px-4">
                                <span
                                    class="px-3 py-1 bg-emerald-500/20 text-emerald-400 rounded-full text-xs font-medium">Completed</span>
                            </td>
                            <td class="py-4 px-4">
                                <button class="text-cyan-400 hover:text-cyan-300 transition-colors">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                        <tr class="border-b border-slate-700/50 hover:bg-slate-700/30 transition-all">
                            <td class="py-4 px-4 text-white font-mono text-sm">
                                #ORD-2844
                            </td>
                            <td class="py-4 px-4">
                                <div class="flex items-center space-x-3">
                                    <div
                                        class="w-8 h-8 bg-linear-to-br from-orange-500 to-orange-600 rounded-full flex items-center justify-center text-white text-xs font-bold">
                                        EW
                                    </div>
                                    <span class="text-white text-sm">Emma Wilson</span>
                                </div>
                            </td>
                            <td class="py-4 px-4 text-slate-400 text-sm">
                                Oct 26, 2025
                            </td>
                            <td class="py-4 px-4 text-white font-semibold text-sm">
                                $67.25
                            </td>
                            <td class="py-4 px-4">
                                <span
                                    class="px-3 py-1 bg-red-500/20 text-red-400 rounded-full text-xs font-medium">Cancelled</span>
                            </td>
                            <td class="py-4 px-4">
                                <button class="text-cyan-400 hover:text-cyan-300 transition-colors">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                        <tr class="hover:bg-slate-700/30 transition-all">
                            <td class="py-4 px-4 text-white font-mono text-sm">
                                #ORD-2843
                            </td>
                            <td class="py-4 px-4">
                                <div class="flex items-center space-x-3">
                                    <div
                                        class="w-8 h-8 bg-linear-to-br from-pink-500 to-pink-600 rounded-full flex items-center justify-center text-white text-xs font-bold">
                                        MB
                                    </div>
                                    <span class="text-white text-sm">Michael Brown</span>
                                </div>
                            </td>
                            <td class="py-4 px-4 text-slate-400 text-sm">
                                Oct 25, 2025
                            </td>
                            <td class="py-4 px-4 text-white font-semibold text-sm">
                                $203.40
                            </td>
                            <td class="py-4 px-4">
                                <span
                                    class="px-3 py-1 bg-emerald-500/20 text-emerald-400 rounded-full text-xs font-medium">Completed</span>
                            </td>
                            <td class="py-4 px-4">
                                <button class="text-cyan-400 hover:text-cyan-300 transition-colors">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts.admin>
