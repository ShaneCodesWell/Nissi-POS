<div id="addCustomerModal"
    class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-slate-800 border border-slate-700 rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-slate-700 flex items-center justify-between sticky top-0 bg-slate-800 z-10">
            <h2 class="text-2xl font-bold text-white">Add New Customer</h2>
            <button class="text-slate-400 hover:text-white transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div class="p-6 space-y-6">
            <!-- Personal Information -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-white font-medium mb-2 block">First Name</label>
                    <input type="text" placeholder="Enter first name"
                        class="w-full bg-slate-700/50 border border-slate-600 rounded-lg px-4 py-3 text-white placeholder-slate-400 focus:outline-none focus:border-emerald-500 transition-all" />
                </div>
                <div>
                    <label class="text-white font-medium mb-2 block">Last Name</label>
                    <input type="text" placeholder="Enter last name"
                        class="w-full bg-slate-700/50 border border-slate-600 rounded-lg px-4 py-3 text-white placeholder-slate-400 focus:outline-none focus:border-emerald-500 transition-all" />
                </div>
            </div>

            <!-- Contact Information -->
            <div>
                <label class="text-white font-medium mb-2 block">Email Address</label>
                <input type="email" placeholder="Enter email address"
                    class="w-full bg-slate-700/50 border border-slate-600 rounded-lg px-4 py-3 text-white placeholder-slate-400 focus:outline-none focus:border-emerald-500 transition-all" />
            </div>

            <div>
                <label class="text-white font-medium mb-2 block">Phone Number</label>
                <input type="tel" placeholder="Enter phone number"
                    class="w-full bg-slate-700/50 border border-slate-600 rounded-lg px-4 py-3 text-white placeholder-slate-400 focus:outline-none focus:border-emerald-500 transition-all" />
            </div>

            <!-- Customer Type -->
            <div>
                <label class="text-white font-medium mb-2 block">Customer Type</label>
                <select
                    class="w-full bg-slate-700/50 border border-slate-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-emerald-500 transition-all">
                    <option>Regular</option>
                    <option>VIP</option>
                    <option>New</option>
                </select>
            </div>

            <!-- Address -->
            <div>
                <label class="text-white font-medium mb-2 block">Address</label>
                <textarea rows="3" placeholder="Enter customer address"
                    class="w-full bg-slate-700/50 border border-slate-600 rounded-lg px-4 py-3 text-white placeholder-slate-400 focus:outline-none focus:border-emerald-500 transition-all resize-none"></textarea>
            </div>

            <!-- Notes -->
            <div>
                <label class="text-white font-medium mb-2 block">Additional Notes</label>
                <textarea rows="3" placeholder="Enter any additional notes"
                    class="w-full bg-slate-700/50 border border-slate-600 rounded-lg px-4 py-3 text-white placeholder-slate-400 focus:outline-none focus:border-emerald-500 transition-all resize-none"></textarea>
            </div>
        </div>
        <div class="p-6 border-t border-slate-700 flex space-x-4">
            <button
                class="flex-1 px-6 py-3 bg-slate-700 hover:bg-slate-600 text-white rounded-xl font-semibold transition-all">
                Cancel
            </button>
            <button
                class="flex-1 px-6 py-3 bg-linear-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white rounded-xl font-semibold transition-all shadow-lg shadow-emerald-500/30">
                Add Customer
            </button>
        </div>
    </div>
</div>
