<div id="addProductModal"
    class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-slate-800 border border-slate-700 rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-slate-700 flex items-center justify-between sticky top-0 bg-slate-800 z-10">
            <h2 class="text-2xl font-bold text-white">Add New Product</h2>
            <button class="text-slate-400 hover:text-white transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div class="p-6 space-y-6">
            <!-- Product Image Upload -->
            <div>
                <label class="text-white font-medium mb-2 block">Product Image</label>
                <div
                    class="border-2 border-dashed border-slate-600 rounded-xl p-8 text-center hover:border-emerald-500 transition-all cursor-pointer">
                    <i class="fas fa-cloud-upload-alt text-4xl text-slate-400 mb-2"></i>
                    <p class="text-slate-400 text-sm">
                        Click to upload or drag and drop
                    </p>
                    <p class="text-slate-500 text-xs mt-1">PNG, JPG up to 5MB</p>
                </div>
            </div>

            <!-- Product Name -->
            <div>
                <label class="text-white font-medium mb-2 block">Product Name</label>
                <input type="text" placeholder="Enter product name"
                    class="w-full bg-slate-700/50 border border-slate-600 rounded-lg px-4 py-3 text-white placeholder-slate-400 focus:outline-none focus:border-emerald-500 transition-all" />
            </div>

            <!-- SKU -->
            <div>
                <label class="text-white font-medium mb-2 block">SKU</label>
                <input type="text" placeholder="Enter SKU"
                    class="w-full bg-slate-700/50 border border-slate-600 rounded-lg px-4 py-3 text-white placeholder-slate-400 focus:outline-none focus:border-emerald-500 transition-all" />
            </div>

            <!-- Category and Price Row -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-white font-medium mb-2 block">Category</label>
                    <select
                        class="w-full bg-slate-700/50 border border-slate-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-emerald-500 transition-all">
                        <option>Select category</option>
                        <option>Beverages</option>
                        <option>Food</option>
                        <option>Snacks</option>
                        <option>Desserts</option>
                    </select>
                </div>
                <div>
                    <label class="text-white font-medium mb-2 block">Price</label>
                    <input type="number" step="0.01" placeholder="0.00"
                        class="w-full bg-slate-700/50 border border-slate-600 rounded-lg px-4 py-3 text-white placeholder-slate-400 focus:outline-none focus:border-emerald-500 transition-all" />
                </div>
            </div>

            <!-- Stock and Status Row -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-white font-medium mb-2 block">Initial Stock</label>
                    <input type="number" placeholder="0"
                        class="w-full bg-slate-700/50 border border-slate-600 rounded-lg px-4 py-3 text-white placeholder-slate-400 focus:outline-none focus:border-emerald-500 transition-all" />
                </div>
                <div>
                    <label class="text-white font-medium mb-2 block">Status</label>
                    <select
                        class="w-full bg-slate-700/50 border border-slate-600 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-emerald-500 transition-all">
                        <option>Active</option>
                        <option>Inactive</option>
                    </select>
                </div>
            </div>

            <!-- Description -->
            <div>
                <label class="text-white font-medium mb-2 block">Description</label>
                <textarea rows="4" placeholder="Enter product description"
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
                Add Product
            </button>
        </div>
    </div>
</div>
