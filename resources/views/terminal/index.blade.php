<x-layouts.terminal>
    <div class="h-full flex gap-6">
        <!-- Products Grid - Left Side -->
        <div class="flex-1 flex flex-col">
            <!-- Category Filters -->
            <div class="flex gap-2 mb-6 overflow-x-auto pb-2">
                <button
                    class="category-btn px-4 py-2 bg-emerald-500/20 border border-emerald-500/50 rounded-lg text-emerald-400 text-sm font-medium whitespace-nowrap hover:bg-emerald-500/30 transition-all"
                    data-category="all">
                    All Items
                </button>
                <button
                    class="category-btn px-4 py-2 bg-slate-800/50 border border-slate-700/50 rounded-lg text-slate-300 text-sm font-medium whitespace-nowrap hover:bg-slate-700/50 transition-all"
                    data-category="beverages">
                    Beverages
                </button>
                <button
                    class="category-btn px-4 py-2 bg-slate-800/50 border border-slate-700/50 rounded-lg text-slate-300 text-sm font-medium whitespace-nowrap hover:bg-slate-700/50 transition-all"
                    data-category="food">
                    Food
                </button>
                <button
                    class="category-btn px-4 py-2 bg-slate-800/50 border border-slate-700/50 rounded-lg text-slate-300 text-sm font-medium whitespace-nowrap hover:bg-slate-700/50 transition-all"
                    data-category="snacks">
                    Snacks
                </button>
                <button
                    class="category-btn px-4 py-2 bg-slate-800/50 border border-slate-700/50 rounded-lg text-slate-300 text-sm font-medium whitespace-nowrap hover:bg-slate-700/50 transition-all"
                    data-category="desserts">
                    Desserts
                </button>
            </div>

            <!-- Products Grid -->
            <div id="productsGrid"
                class="flex-1 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 auto-rows-max gap-4 overflow-y-auto">
                <!-- Products will be dynamically inserted here -->
            </div>
        </div>

        <!-- Cart & Checkout - Right Side -->
        <div class="w-96 flex flex-col bg-slate-800/50 border border-slate-700/50 rounded-2xl">
            <!-- Cart Header -->
            <div class="p-6 border-b border-slate-700/50">
                <div class="flex justify-between items-center mb-2">
                    <h2 class="text-xl font-bold text-white">Current Order</h2>
                    <button id="newSaleBtn" class="text-red-400 hover:text-red-300 transition-colors text-sm">
                        <i class="fas fa-times mr-1"></i>New Sale
                    </button>
                </div>
                <p class="text-slate-400 text-sm">
                    <span id="itemCount">0</span> items
                </p>
            </div>

            <!-- Discount Display -->
            <div id="discountDisplay"
                class="mx-6 mt-4 mb-2 p-3 bg-yellow-500/10 border border-yellow-500/30 rounded-lg hidden">
                <div class="flex justify-between items-center text-sm">
                    <span class="text-yellow-400">
                        <i class="fas fa-tag mr-1"></i>
                        <span id="discountText">Discount Applied</span>
                    </span>
                    <button id="removeDiscountBtn" class="text-yellow-400 hover:text-yellow-300">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>

            <!-- Cart Items -->
            <div id="cartItems" class="flex-1 overflow-y-auto p-6 space-y-3">
                <div class="text-center py-12">
                    <i class="fas fa-shopping-cart text-slate-600 text-5xl mb-4"></i>
                    <p class="text-slate-500 text-sm">Cart is empty</p>
                    <p class="text-slate-600 text-xs mt-1">Add items to get started</p>
                </div>
            </div>

            <!-- Cart Summary -->
            <div class="p-6 border-t border-slate-700/50 space-y-4">
                <!-- Subtotal -->
                <div class="flex justify-between items-center">
                    <span class="text-slate-400 text-sm">Subtotal</span>
                    <span class="text-white font-semibold" id="subtotal">$0.00</span>
                </div>

                <!-- Discount -->
                <div class="flex justify-between items-center text-yellow-400" id="discountRow" style="display: none">
                    <span class="text-sm">Discount</span>
                    <span class="font-semibold" id="discountAmount">-$0.00</span>
                </div>

                <!-- Tax -->
                <div class="flex justify-between items-center">
                    <span class="text-slate-400 text-sm">Tax (10%)</span>
                    <span class="text-white font-semibold" id="tax">$0.00</span>
                </div>

                <!-- Total -->
                <div class="flex justify-between items-center pt-4 border-t border-slate-700/50">
                    <span class="text-white font-bold text-lg">Total</span>
                    <span class="text-emerald-400 font-bold text-2xl" id="total">$0.00</span>
                </div>

                <!-- Action Buttons -->
                <div class="grid grid-cols-3 gap-3 pt-4">
                    <button id="discountBtn"
                        class="px-4 py-3 bg-yellow-500/20 hover:bg-yellow-500/30 border border-yellow-500/50 rounded-xl text-yellow-400 text-sm font-medium transition-all">
                        <i class="fas fa-tag mr-1"></i>Discount
                    </button>
                    <button id="clearCart"
                        class="px-4 py-3 bg-red-500/20 hover:bg-red-500/30 border border-red-500/50 rounded-xl text-red-400 text-sm font-medium transition-all">
                        <i class="fas fa-trash mr-1"></i>Clear
                    </button>
                    <button id="checkoutBtn"
                        class="px-4 py-3 bg-linear-to-br from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 rounded-xl text-white text-sm font-bold transition-all shadow-lg shadow-emerald-500/20">
                        <i class="fas fa-credit-card mr-1"></i>Pay
                    </button>
                </div>
            </div>
        </div>
    </div>
    <x-checkout-modal />
    <x-cash-payment-modal />
</x-layouts.terminal>
