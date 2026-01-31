<div id="cashModal" class="fixed inset-0 bg-black/70 backdrop-blur-sm hidden items-center justify-center z-50">
    <div class="bg-slate-800 rounded-2xl border border-slate-700/50 w-full max-w-md m-4 shadow-2xl">
        <!-- Modal Header -->
        <div class="p-6 border-b border-slate-700/50">
            <div class="flex items-center justify-between">
                <h3 class="text-2xl font-bold text-white">Cash Payment</h3>
                <button id="closeCashModal" class="text-slate-400 hover:text-white transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>

        <!-- Modal Body -->
        <div class="p-6">
            <div class="text-center mb-4">
                <div class="text-2xl font-bold text-white" id="cashAmountDue">$0.00</div>
                <div class="text-slate-400 text-sm">Amount Due</div>
            </div>

            <div class="mb-4">
                <label class="block text-slate-400 text-sm mb-2">Amount Tendered</label>
                <input type="number" id="cashTendered"
                    class="w-full bg-slate-700/50 border border-slate-600 rounded-xl px-4 py-3 text-white text-2xl text-right focus:outline-none focus:border-emerald-500"
                    placeholder="0.00" step="0.01" />
            </div>

            <div class="text-center mb-6">
                <div class="text-xl font-bold text-emerald-400" id="cashChange">$0.00</div>
                <div class="text-slate-400 text-sm">Change</div>
            </div>

            <button id="completeCashPayment"
                class="w-full bg-linear-to-br from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white py-4 rounded-xl font-bold transition-all shadow-lg shadow-emerald-500/20">
                Complete Sale
            </button>
        </div>
    </div>
</div>
