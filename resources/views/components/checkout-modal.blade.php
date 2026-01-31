<div id="checkoutModal" class="fixed inset-0 bg-black/70 backdrop-blur-sm hidden items-center justify-center z-50">
    <div class="bg-slate-800 rounded-2xl border border-slate-700/50 w-full max-w-md m-4 shadow-2xl">
        <!-- Modal Header -->
        <div class="p-6 border-b border-slate-700/50">
            <div class="flex items-center justify-between">
                <h3 class="text-2xl font-bold text-white">Process Payment</h3>
                <button id="closeModal" class="text-slate-400 hover:text-white transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <p class="text-slate-400 text-sm mt-2">Total: <span id="modalTotal"
                    class="text-emerald-400 font-bold">$0.00</span></p>
        </div>

        <!-- Modal Body -->
        <div class="p-6">
            <!-- Payment Methods -->
            <div class="grid grid-cols-2 gap-3 mb-4">
                <button id="cashPaymentBtn"
                    class="payment-method bg-blue-500/20 border-2 border-blue-500/50 py-4 rounded-xl hover:bg-blue-500/30 transition-all text-blue-400 font-medium">
                    <i class="fas fa-money-bill-wave mr-2"></i>Cash
                </button>
                <button
                    class="payment-method bg-emerald-500/20 border-2 border-emerald-500/50 py-4 rounded-xl hover:bg-emerald-500/30 transition-all text-emerald-400 font-medium">
                    <i class="fas fa-credit-card mr-2"></i>Card
                </button>
                <button
                    class="payment-method bg-purple-500/20 border-2 border-purple-500/50 py-4 rounded-xl hover:bg-purple-500/30 transition-all text-purple-400 font-medium">
                    <i class="fas fa-gift mr-2"></i>Gift Card
                </button>
                <button
                    class="payment-method bg-yellow-500/20 border-2 border-yellow-500/50 py-4 rounded-xl hover:bg-yellow-500/30 transition-all text-yellow-400 font-medium">
                    <i class="fas fa-money-check mr-2"></i>Split
                </button>
            </div>
        </div>
    </div>
</div>
