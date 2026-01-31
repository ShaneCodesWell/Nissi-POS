<x-layouts.admin>
    <div class="max-w-7xl mx-auto pb-8">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-white mb-2">
                        Sales Overview
                    </h1>
                    <p class="text-slate-400">
                        Monitor your sales performance and transactions
                    </p>
                </div>
                <button
                    class="px-6 py-3 bg-linear-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white rounded-xl font-semibold transition-all flex items-center space-x-2 shadow-lg shadow-emerald-500/30">
                    <i class="fas fa-plus"></i>
                    <span>New Sale</span>
                </button>
            </div>

            <!-- Stats Overview -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-slate-800/50 border border-slate-700/50 rounded-xl p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-slate-400 text-sm mb-1">Today's Revenue</p>
                            <p class="text-2xl font-bold text-white">$2,847</p>
                            <p class="text-emerald-400 text-xs font-medium">+12.5%</p>
                        </div>
                        <div
                            class="w-12 h-12 bg-linear-to-br from-emerald-500/20 to-emerald-600/20 rounded-lg flex items-center justify-center">
                            <i class="fas fa-dollar-sign text-xl text-emerald-400"></i>
                        </div>
                    </div>
                </div>
                <div class="bg-slate-800/50 border border-slate-700/50 rounded-xl p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-slate-400 text-sm mb-1">Total Orders</p>
                            <p class="text-2xl font-bold text-white">156</p>
                            <p class="text-cyan-400 text-xs font-medium">+8.2%</p>
                        </div>
                        <div
                            class="w-12 h-12 bg-linear-to-br from-cyan-500/20 to-cyan-600/20 rounded-lg flex items-center justify-center">
                            <i class="fas fa-shopping-cart text-xl text-cyan-400"></i>
                        </div>
                    </div>
                </div>
                <div class="bg-slate-800/50 border border-slate-700/50 rounded-xl p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-slate-400 text-sm mb-1">Avg. Order Value</p>
                            <p class="text-2xl font-bold text-white">$18.25</p>
                            <p class="text-purple-400 text-xs font-medium">+3.1%</p>
                        </div>
                        <div
                            class="w-12 h-12 bg-linear-to-br from-purple-500/20 to-purple-600/20 rounded-lg flex items-center justify-center">
                            <i class="fas fa-chart-bar text-xl text-purple-400"></i>
                        </div>
                    </div>
                </div>
                <div class="bg-slate-800/50 border border-slate-700/50 rounded-xl p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-slate-400 text-sm mb-1">Refunds</p>
                            <p class="text-2xl font-bold text-white">$247</p>
                            <p class="text-red-400 text-xs font-medium">-2.3%</p>
                        </div>
                        <div
                            class="w-12 h-12 bg-linear-to-br from-red-500/20 to-red-600/20 rounded-lg flex items-center justify-center">
                            <i class="fas fa-exchange-alt text-xl text-red-400"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters & Date Range -->
            <div class="bg-slate-800/50 border border-slate-700/50 rounded-xl p-6">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <!-- Search -->
                    <div class="md:col-span-2">
                        <div class="relative">
                            <i
                                class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-slate-400"></i>
                            <input type="text" placeholder="Search transactions..."
                                class="w-full bg-slate-700/50 border border-slate-600 rounded-lg pl-12 pr-4 py-3 text-white placeholder-slate-400 focus:outline-none focus:border-emerald-500 transition-all" />
                        </div>
                    </div>
                    <!-- Date Range -->
                    <div>
                        <select
                            class="w-full bg-slate-700/50 border border-slate-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-emerald-500 transition-all">
                            <option>Today</option>
                            <option>Yesterday</option>
                            <option>Last 7 Days</option>
                            <option>Last 30 Days</option>
                            <option>Custom Range</option>
                        </select>
                    </div>
                    <!-- Status Filter -->
                    <div>
                        <select
                            class="w-full bg-slate-700/50 border border-slate-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-emerald-500 transition-all">
                            <option>All Status</option>
                            <option>Completed</option>
                            <option>Pending</option>
                            <option>Refunded</option>
                            <option>Cancelled</option>
                        </select>
                    </div>
                    <!-- Payment Method -->
                    <div>
                        <select
                            class="w-full bg-slate-700/50 border border-slate-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-emerald-500 transition-all">
                            <option>All Methods</option>
                            <option>Cash</option>
                            <option>Card</option>
                            <option>Digital Wallet</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Sales Table -->
        <div class="bg-slate-800/50 border border-slate-700/50 rounded-xl overflow-hidden mb-6">
            <div class="p-6 border-b border-slate-700/50">
                <h2 class="text-xl font-bold text-white">Recent Transactions</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-slate-700/50">
                            <th class="text-left py-4 px-6 text-slate-400 font-medium text-sm">
                                Order ID
                            </th>
                            <th class="text-left py-4 px-6 text-slate-400 font-medium text-sm">
                                Customer
                            </th>
                            <th class="text-left py-4 px-6 text-slate-400 font-medium text-sm">
                                Date
                            </th>
                            <th class="text-left py-4 px-6 text-slate-400 font-medium text-sm">
                                Amount
                            </th>
                            <th class="text-left py-4 px-6 text-slate-400 font-medium text-sm">
                                Payment
                            </th>
                            <th class="text-left py-4 px-6 text-slate-400 font-medium text-sm">
                                Status
                            </th>
                            <th class="text-left py-4 px-6 text-slate-400 font-medium text-sm">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Sale 1 -->
                        <tr class="border-b border-slate-700/50 hover:bg-slate-700/30 transition-all">
                            <td class="py-4 px-6">
                                <span class="text-white font-medium">#ORD-7842</span>
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-8 h-8 bg-emerald-500/20 rounded-full flex items-center justify-center">
                                        <i class="fas fa-user text-emerald-400 text-xs"></i>
                                    </div>
                                    <span class="text-white">John Smith</span>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <span class="text-slate-300">Dec 15, 2023</span>
                                <p class="text-slate-500 text-xs">10:24 AM</p>
                            </td>
                            <td class="py-4 px-6">
                                <span class="text-white font-bold">$42.50</span>
                            </td>
                            <td class="py-4 px-6">
                                <span
                                    class="px-3 py-1 bg-blue-500/20 text-blue-400 rounded-full text-xs font-medium">Card</span>
                            </td>
                            <td class="py-4 px-6">
                                <span
                                    class="px-3 py-1 bg-emerald-500/20 text-emerald-400 rounded-full text-xs font-medium">Completed</span>
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-2">
                                    <button class="p-2 text-slate-400 hover:text-cyan-400 transition-colors">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="p-2 text-slate-400 hover:text-emerald-400 transition-colors">
                                        <i class="fas fa-receipt"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <!-- Sale 2 -->
                        <tr class="border-b border-slate-700/50 hover:bg-slate-700/30 transition-all">
                            <td class="py-4 px-6">
                                <span class="text-white font-medium">#ORD-7841</span>
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-purple-500/20 rounded-full flex items-center justify-center">
                                        <i class="fas fa-user text-purple-400 text-xs"></i>
                                    </div>
                                    <span class="text-white">Sarah Johnson</span>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <span class="text-slate-300">Dec 15, 2023</span>
                                <p class="text-slate-500 text-xs">09:45 AM</p>
                            </td>
                            <td class="py-4 px-6">
                                <span class="text-white font-bold">$28.75</span>
                            </td>
                            <td class="py-4 px-6">
                                <span
                                    class="px-3 py-1 bg-green-500/20 text-green-400 rounded-full text-xs font-medium">Cash</span>
                            </td>
                            <td class="py-4 px-6">
                                <span
                                    class="px-3 py-1 bg-emerald-500/20 text-emerald-400 rounded-full text-xs font-medium">Completed</span>
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-2">
                                    <button class="p-2 text-slate-400 hover:text-cyan-400 transition-colors">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="p-2 text-slate-400 hover:text-emerald-400 transition-colors">
                                        <i class="fas fa-receipt"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <!-- Sale 3 -->
                        <tr class="border-b border-slate-700/50 hover:bg-slate-700/30 transition-all">
                            <td class="py-4 px-6">
                                <span class="text-white font-medium">#ORD-7840</span>
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-8 h-8 bg-orange-500/20 rounded-full flex items-center justify-center">
                                        <i class="fas fa-user text-orange-400 text-xs"></i>
                                    </div>
                                    <span class="text-white">Mike Chen</span>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <span class="text-slate-300">Dec 14, 2023</span>
                                <p class="text-slate-500 text-xs">4:32 PM</p>
                            </td>
                            <td class="py-4 px-6">
                                <span class="text-white font-bold">$65.20</span>
                            </td>
                            <td class="py-4 px-6">
                                <span
                                    class="px-3 py-1 bg-yellow-500/20 text-yellow-400 rounded-full text-xs font-medium">Digital</span>
                            </td>
                            <td class="py-4 px-6">
                                <span
                                    class="px-3 py-1 bg-amber-500/20 text-amber-400 rounded-full text-xs font-medium">Pending</span>
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-2">
                                    <button class="p-2 text-slate-400 hover:text-cyan-400 transition-colors">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="p-2 text-slate-400 hover:text-emerald-400 transition-colors">
                                        <i class="fas fa-receipt"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <!-- Sale 4 -->
                        <tr class="border-b border-slate-700/50 hover:bg-slate-700/30 transition-all">
                            <td class="py-4 px-6">
                                <span class="text-white font-medium">#ORD-7839</span>
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-red-500/20 rounded-full flex items-center justify-center">
                                        <i class="fas fa-user text-red-400 text-xs"></i>
                                    </div>
                                    <span class="text-white">Emma Davis</span>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <span class="text-slate-300">Dec 14, 2023</span>
                                <p class="text-slate-500 text-xs">2:15 PM</p>
                            </td>
                            <td class="py-4 px-6">
                                <span class="text-white font-bold">$18.90</span>
                            </td>
                            <td class="py-4 px-6">
                                <span
                                    class="px-3 py-1 bg-blue-500/20 text-blue-400 rounded-full text-xs font-medium">Card</span>
                            </td>
                            <td class="py-4 px-6">
                                <span
                                    class="px-3 py-1 bg-red-500/20 text-red-400 rounded-full text-xs font-medium">Refunded</span>
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-2">
                                    <button class="p-2 text-slate-400 hover:text-cyan-400 transition-colors">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="p-2 text-slate-400 hover:text-emerald-400 transition-colors">
                                        <i class="fas fa-receipt"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <!-- Sale 5 -->
                        <tr class="hover:bg-slate-700/30 transition-all">
                            <td class="py-4 px-6">
                                <span class="text-white font-medium">#ORD-7838</span>
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-cyan-500/20 rounded-full flex items-center justify-center">
                                        <i class="fas fa-user text-cyan-400 text-xs"></i>
                                    </div>
                                    <span class="text-white">Alex Rodriguez</span>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <span class="text-slate-300">Dec 14, 2023</span>
                                <p class="text-slate-500 text-xs">11:08 AM</p>
                            </td>
                            <td class="py-4 px-6">
                                <span class="text-white font-bold">$34.50</span>
                            </td>
                            <td class="py-4 px-6">
                                <span
                                    class="px-3 py-1 bg-green-500/20 text-green-400 rounded-full text-xs font-medium">Cash</span>
                            </td>
                            <td class="py-4 px-6">
                                <span
                                    class="px-3 py-1 bg-slate-500/20 text-slate-400 rounded-full text-xs font-medium">Cancelled</span>
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-2">
                                    <button class="p-2 text-slate-400 hover:text-cyan-400 transition-colors">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="p-2 text-slate-400 hover:text-emerald-400 transition-colors">
                                        <i class="fas fa-receipt"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Top Selling Products -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Top Products -->
            <div class="bg-slate-800/50 border border-slate-700/50 rounded-xl">
                <div class="p-6 border-b border-slate-700/50">
                    <h2 class="text-xl font-bold text-white">
                        Top Selling Products
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    <!-- Product 1 -->
                    <div class="flex items-center justify-between p-3 hover:bg-slate-700/30 rounded-lg transition-all">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-emerald-500/20 rounded-lg flex items-center justify-center">
                                <i class="fas fa-coffee text-emerald-400"></i>
                            </div>
                            <div>
                                <p class="text-white font-medium">Premium Coffee</p>
                                <p class="text-slate-400 text-xs">45 sales</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-emerald-400 font-bold">$224.55</p>
                            <p class="text-slate-400 text-xs">Revenue</p>
                        </div>
                    </div>
                    <!-- Product 2 -->
                    <div class="flex items-center justify-between p-3 hover:bg-slate-700/30 rounded-lg transition-all">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-cyan-500/20 rounded-lg flex items-center justify-center">
                                <i class="fas fa-bread-slice text-cyan-400"></i>
                            </div>
                            <div>
                                <p class="text-white font-medium">Butter Croissant</p>
                                <p class="text-slate-400 text-xs">38 sales</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-cyan-400 font-bold">$132.62</p>
                            <p class="text-slate-400 text-xs">Revenue</p>
                        </div>
                    </div>
                    <!-- Product 3 -->
                    <div class="flex items-center justify-between p-3 hover:bg-slate-700/30 rounded-lg transition-all">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-purple-500/20 rounded-lg flex items-center justify-center">
                                <i class="fas fa-hamburger text-purple-400"></i>
                            </div>
                            <div>
                                <p class="text-white font-medium">Club Sandwich</p>
                                <p class="text-slate-400 text-xs">32 sales</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-purple-400 font-bold">$255.68</p>
                            <p class="text-slate-400 text-xs">Revenue</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sales Chart Placeholder -->
            <div class="bg-slate-800/50 border border-slate-700/50 rounded-xl">
                <div class="p-6 border-b border-slate-700/50">
                    <h2 class="text-xl font-bold text-white">Sales Trend</h2>
                </div>
                <div class="p-6 h-64 flex items-center justify-center">
                    <div class="text-center">
                        <i class="fas fa-chart-line text-4xl text-slate-400 mb-4"></i>
                        <p class="text-slate-400">Sales chart visualization</p>
                        <p class="text-slate-500 text-sm">
                            Interactive chart would be displayed here
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div class="bg-slate-800/50 border border-slate-700/50 rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div class="text-slate-400 text-sm">
                    Showing <span class="text-white font-semibold">1-5</span> of
                    <span class="text-white font-semibold">156</span> transactions
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
                        32
                    </button>
                    <button
                        class="px-4 py-2 bg-slate-700/50 hover:bg-slate-700 text-slate-400 rounded-lg transition-all">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>
