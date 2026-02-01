<x-layouts.admin>
    <div class="max-w-7xl mx-auto pb-8">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-white mb-2">
                        Business Reports
                    </h1>
                    <p class="text-slate-400">
                        Comprehensive analytics and business insights
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <button
                        class="px-6 py-3 bg-slate-700 hover:bg-slate-600 text-white rounded-xl font-semibold transition-all flex items-center space-x-2">
                        <i class="fas fa-download"></i>
                        <span>Export PDF</span>
                    </button>
                    <button
                        class="px-6 py-3 bg-linear-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white rounded-xl font-semibold transition-all flex items-center space-x-2 shadow-lg shadow-emerald-500/30">
                        <i class="fas fa-sync-alt"></i>
                        <span>Refresh Data</span>
                    </button>
                </div>
            </div>

            <!-- Date Range & Filters -->
            <div class="bg-slate-800/50 border border-slate-700/50 rounded-xl p-6 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Date Range -->
                    <div>
                        <label class="text-white font-medium mb-2 block">Date Range</label>
                        <select
                            class="w-full bg-slate-700/50 border border-slate-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-emerald-500 transition-all">
                            <option>Last 7 Days</option>
                            <option>Last 30 Days</option>
                            <option>Last 90 Days</option>
                            <option>This Month</option>
                            <option>Last Month</option>
                            <option>Custom Range</option>
                        </select>
                    </div>
                    <!-- Report Type -->
                    <div>
                        <label class="text-white font-medium mb-2 block">Report Type</label>
                        <select
                            class="w-full bg-slate-700/50 border border-slate-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-emerald-500 transition-all">
                            <option>Sales Overview</option>
                            <option>Product Performance</option>
                            <option>Customer Analytics</option>
                            <option>Inventory Report</option>
                            <option>Financial Summary</option>
                        </select>
                    </div>
                    <!-- Metrics -->
                    <div>
                        <label class="text-white font-medium mb-2 block">Metrics</label>
                        <select
                            class="w-full bg-slate-700/50 border border-slate-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-emerald-500 transition-all">
                            <option>Revenue & Growth</option>
                            <option>Units Sold</option>
                            <option>Profit Margin</option>
                            <option>Customer Count</option>
                        </select>
                    </div>
                    <!-- Comparison -->
                    <div>
                        <label class="text-white font-medium mb-2 block">Compare With</label>
                        <select
                            class="w-full bg-slate-700/50 border border-slate-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-emerald-500 transition-all">
                            <option>Previous Period</option>
                            <option>Same Period Last Year</option>
                            <option>Target Goals</option>
                            <option>No Comparison</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Key Metrics Overview -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-slate-800/50 border border-slate-700/50 rounded-xl p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-slate-400 text-sm mb-1">Total Revenue</p>
                            <p class="text-2xl font-bold text-white">$24,847</p>
                            <p class="text-emerald-400 text-xs font-medium">
                                +15.2% vs last period
                            </p>
                        </div>
                        <div
                            class="w-12 h-12 bg-linear-to-br from-emerald-500/20 to-emerald-600/20 rounded-lg flex items-center justify-center">
                            <i class="fas fa-chart-line text-xl text-emerald-400"></i>
                        </div>
                    </div>
                </div>
                <div class="bg-slate-800/50 border border-slate-700/50 rounded-xl p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-slate-400 text-sm mb-1">Orders Processed</p>
                            <p class="text-2xl font-bold text-white">1,248</p>
                            <p class="text-cyan-400 text-xs font-medium">
                                +8.7% vs last period
                            </p>
                        </div>
                        <div
                            class="w-12 h-12 bg-linear-to-br from-cyan-500/20 to-cyan-600/20 rounded-lg flex items-center justify-center">
                            <i class="fas fa-shopping-bag text-xl text-cyan-400"></i>
                        </div>
                    </div>
                </div>
                <div class="bg-slate-800/50 border border-slate-700/50 rounded-xl p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-slate-400 text-sm mb-1">Avg. Order Value</p>
                            <p class="text-2xl font-bold text-white">$19.91</p>
                            <p class="text-purple-400 text-xs font-medium">
                                +5.3% vs last period
                            </p>
                        </div>
                        <div
                            class="w-12 h-12 bg-linear-to-br from-purple-500/20 to-purple-600/20 rounded-lg flex items-center justify-center">
                            <i class="fas fa-dollar-sign text-xl text-purple-400"></i>
                        </div>
                    </div>
                </div>
                <div class="bg-slate-800/50 border border-slate-700/50 rounded-xl p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-slate-400 text-sm mb-1">Customer Growth</p>
                            <p class="text-2xl font-bold text-white">+187</p>
                            <p class="text-orange-400 text-xs font-medium">
                                +12.4% vs last period
                            </p>
                        </div>
                        <div
                            class="w-12 h-12 bg-linear-to-br from-orange-500/20 to-orange-600/20 rounded-lg flex items-center justify-center">
                            <i class="fas fa-users text-xl text-orange-400"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts & Graphs Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Revenue Chart -->
            <div class="bg-slate-800/50 border border-slate-700/50 rounded-xl">
                <div class="p-6 border-b border-slate-700/50">
                    <h2 class="text-xl font-bold text-white">Revenue Trend</h2>
                    <p class="text-slate-400 text-sm">Last 30 days performance</p>
                </div>
                <div class="p-6 h-80">
                    <!-- Chart.js will render here -->
                </div>
            </div>

            <!-- Product Performance -->
            <div class="bg-slate-800/50 border border-slate-700/50 rounded-xl">
                <div class="p-6 border-b border-slate-700/50">
                    <h2 class="text-xl font-bold text-white">Top Categories</h2>
                    <p class="text-slate-400 text-sm">
                        Revenue by product category
                    </p>
                </div>
                <div class="p-6 h-80">
                    <!-- Chart.js will render here -->
                </div>
            </div>
        </div>

        <!-- Detailed Reports Tables -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Sales Performance -->
            <div class="bg-slate-800/50 border border-slate-700/50 rounded-xl">
                <div class="p-6 border-b border-slate-700/50">
                    <h2 class="text-xl font-bold text-white">Sales Performance</h2>
                    <p class="text-slate-400 text-sm">Weekly revenue comparison</p>
                </div>
                <div class="p-6 h-80">
                    <!-- Chart.js will render here -->
                </div>
            </div>

            <!-- Customer Insights -->
            <div class="bg-slate-800/50 border border-slate-700/50 rounded-xl">
                <div class="p-6 border-b border-slate-700/50">
                    <h2 class="text-xl font-bold text-white">Customer Insights</h2>
                    <p class="text-slate-400 text-sm">Behavior metrics analysis</p>
                </div>
                <div class="p-6 h-80">
                    <!-- Chart.js will render here -->
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="bg-slate-800/50 border border-slate-700/50 rounded-xl p-6 mb-6">
            <h2 class="text-xl font-bold text-white mb-4">Quick Statistics</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="text-center">
                    <p class="text-2xl font-bold text-emerald-400">78%</p>
                    <p class="text-slate-400 text-sm">Peak Hours Efficiency</p>
                </div>
                <div class="text-center">
                    <p class="text-2xl font-bold text-cyan-400">92%</p>
                    <p class="text-slate-400 text-sm">Customer Satisfaction</p>
                </div>
                <div class="text-center">
                    <p class="text-2xl font-bold text-purple-400">14.2%</p>
                    <p class="text-slate-400 text-sm">Profit Margin</p>
                </div>
                <div class="text-center">
                    <p class="text-2xl font-bold text-orange-400">2.8 min</p>
                    <p class="text-slate-400 text-sm">Avg. Service Time</p>
                </div>
            </div>
        </div>

        <!-- Report Actions -->
        <div class="bg-slate-800/50 border border-slate-700/50 rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div class="text-slate-400 text-sm">
                    Report generated:
                    <span class="text-white font-semibold">Dec 15, 2023 14:32 PM</span>
                </div>
                <div class="flex items-center space-x-3">
                    <button
                        class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-white rounded-lg transition-all flex items-center space-x-2">
                        <i class="fas fa-print"></i>
                        <span>Print</span>
                    </button>
                    <button
                        class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-white rounded-lg transition-all flex items-center space-x-2">
                        <i class="fas fa-share-alt"></i>
                        <span>Share</span>
                    </button>
                    <button
                        class="px-4 py-2 bg-linear-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white rounded-lg transition-all flex items-center space-x-2">
                        <i class="fas fa-file-export"></i>
                        <span>Export</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    
</x-layouts.admin>
