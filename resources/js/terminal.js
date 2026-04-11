document.addEventListener('DOMContentLoaded', async () => {

    // =========================================================================
    // State
    // =========================================================================

    let products     = [];       // Loaded from API on boot
    let categories   = [];       // Loaded from API on boot
    let currentSale  = null;     // The active sale object from the backend
    let currentFilter = 'all';   // Active category filter

    // =========================================================================
    // API helper
    // Centralises all fetch calls so we have one place to handle
    // headers, CSRF, and error responses.
    // =========================================================================

    const api = {
        baseUrl: `/terminal/${TERMINAL_ID}`,

        headers() {
            return {
                'Content-Type': 'application/json',
                'Accept':       'application/json',
                'X-CSRF-TOKEN': CSRF_TOKEN,
            };
        },

        async get(path) {
            const res = await fetch(`${this.baseUrl}${path}`, {
                method:  'GET',
                headers: this.headers(),
            });
            return this.handle(res);
        },

        async post(path, body = {}) {
            const res = await fetch(`${this.baseUrl}${path}`, {
                method:  'POST',
                headers: this.headers(),
                body:    JSON.stringify(body),
            });
            return this.handle(res);
        },

        async patch(path, body = {}) {
            const res = await fetch(`${this.baseUrl}${path}`, {
                method:  'PATCH',
                headers: this.headers(),
                body:    JSON.stringify(body),
            });
            return this.handle(res);
        },

        async delete(path) {
            const res = await fetch(`${this.baseUrl}${path}`, {
                method:  'DELETE',
                headers: this.headers(),
            });
            return this.handle(res);
        },

        async handle(res) {
            const data = await res.json();
            if (!res.ok) {
                // Surface the backend message so SweetAlert can show it
                const error    = new Error(data.message || 'An error occurred.');
                error.errors   = data.errors || {};
                error.status   = res.status;
                throw error;
            }
            return data;
        },
    };

    // =========================================================================
    // Boot — load session data from the backend
    // =========================================================================

    async function boot() {
        showLoadingOverlay('Loading terminal...');

        try {
            const data = await api.get('/session');

            products   = flattenVariants(data.products);
            categories = data.categories;

            renderCategories();
            renderProducts();

            // Check for any pending sales that need to be resumed
            await checkForPendingSales();

        } catch (err) {
            showError('Failed to load terminal', err.message);
        } finally {
            hideLoadingOverlay();
        }
    }

    // =========================================================================
    // Data transformation
    // =========================================================================

    /**
     * The API returns products with nested variants.
     * Flatten them into a single array of sellable items for the grid —
     * each entry represents one purchasable variant.
     */
    function flattenVariants(apiProducts) {
        const items = [];

        apiProducts.forEach(product => {
            const variants = product.active_variants || [];

            if (variants.length === 1 || !product.has_variants) {
                // Single variant — show as a simple product card
                const v = variants[0];
                if (!v) return;

                items.push({
                    variantId:   v.id,
                    productId:   product.id,
                    name:        product.name,
                    variantName: null,
                    sku:         v.sku,
                    price:       parseFloat(v.price),
                    category:    product.category?.slug || 'uncategorized',
                    categoryId:  product.category?.id,
                    stock:       stockFor(v),
                });
            } else {
                // Multi-variant — show one card per variant
                variants.forEach(v => {
                    items.push({
                        variantId:   v.id,
                        productId:   product.id,
                        name:        product.name,
                        variantName: v.name,
                        sku:         v.sku,
                        price:       parseFloat(v.price),
                        category:    product.category?.slug || 'uncategorized',
                        categoryId:  product.category?.id,
                        stock:       stockFor(v),
                    });
                });
            }
        });

        return items;
    }

    /**
     * Extract the stock quantity for a variant at the current location.
     * The API returns inventory filtered to this location already.
     */
    function stockFor(variant) {
        const inv = variant.inventory?.[0];
        return inv ? parseInt(inv.quantity_on_hand) : 0;
    }

    // =========================================================================
    // Category rendering
    // =========================================================================

    function renderCategories() {
        const container = document.querySelector('.flex.gap-2.mb-6');
        if (!container) return;

        // Keep the "All Items" button, replace the rest
        const allBtn = container.querySelector('[data-category="all"]');
        container.innerHTML = '';
        container.appendChild(allBtn);

        categories.forEach(cat => {
            const btn = document.createElement('button');
            btn.className = 'category-btn px-4 py-2 bg-slate-800/50 border border-slate-700/50 rounded-lg text-slate-300 text-sm font-medium whitespace-nowrap hover:bg-slate-700/50 transition-all';
            btn.dataset.category = cat.slug;
            btn.textContent = cat.name;
            container.appendChild(btn);

            // Also add sub-categories
            (cat.children || []).forEach(child => {
                const subBtn = document.createElement('button');
                subBtn.className = 'category-btn px-4 py-2 bg-slate-800/50 border border-slate-700/50 rounded-lg text-slate-300 text-sm font-medium whitespace-nowrap hover:bg-slate-700/50 transition-all';
                subBtn.dataset.category = child.slug;
                subBtn.textContent = `↳ ${child.name}`;
                container.appendChild(subBtn);
            });
        });

        setupCategoryListeners();
    }

    // =========================================================================
    // Product grid rendering
    // =========================================================================

    function renderProducts(filter = 'all') {
        currentFilter = filter;
        const grid = document.getElementById('productsGrid');

        const visible = filter === 'all'
            ? products
            : products.filter(p => p.category === filter);

        if (visible.length === 0) {
            grid.innerHTML = `
                <div class="col-span-4 text-center py-12">
                    <i class="fas fa-box-open text-slate-600 text-5xl mb-4"></i>
                    <p class="text-slate-500 text-sm">No products in this category</p>
                </div>`;
            return;
        }

        grid.innerHTML = visible.map(product => {
            const outOfStock = product.stock <= 0;
            const lowStock   = product.stock > 0 && product.stock <= 5;

            return `
                <div class="product-card bg-slate-800/50 border border-slate-700/50 rounded-xl p-4
                    ${outOfStock ? 'opacity-50 cursor-not-allowed' : 'hover:border-emerald-500/50 cursor-pointer'}
                    transition-all relative"
                    data-variant-id="${product.variantId}"
                    data-out-of-stock="${outOfStock}">

                    ${lowStock ? `<span class="absolute top-2 right-2 text-xs bg-yellow-500/20 text-yellow-400 border border-yellow-500/30 px-2 py-0.5 rounded-full">Low</span>` : ''}
                    ${outOfStock ? `<span class="absolute top-2 right-2 text-xs bg-red-500/20 text-red-400 border border-red-500/30 px-2 py-0.5 rounded-full">Out</span>` : ''}

                    <div class="w-16 h-16 bg-linear-to-br from-emerald-500/20 to-cyan-500/20 rounded-xl flex items-center justify-center mb-3 mx-auto">
                        <i class="fas fa-box text-3xl text-emerald-400"></i>
                    </div>

                    <h3 class="text-white font-semibold text-sm text-center mb-1">${product.name}</h3>
                    ${product.variantName ? `<p class="text-slate-400 text-xs text-center mb-2">${product.variantName}</p>` : '<div class="mb-2"></div>'}
                    <p class="text-emerald-400 font-bold text-center text-lg">GHS ${product.price.toFixed(2)}</p>
                    <p class="text-slate-500 text-xs text-center mt-1">${product.stock} in stock</p>
                </div>`;
        }).join('');

        // Click handlers
        document.querySelectorAll('.product-card').forEach(card => {
            if (card.dataset.outOfStock === 'true') return;
            card.addEventListener('click', () => {
                addItem(parseInt(card.dataset.variantId));
            });
        });
    }

    // =========================================================================
    // Sale management
    // =========================================================================

    /**
     * Open a new sale on the backend if one isn't already open.
     * Called automatically when the first item is added.
     */
    async function ensureSaleOpen() {
        if (currentSale) return;

        try {
            const data  = await api.post('/sales', {});
            currentSale = data.sale;
        } catch (err) {
            throw new Error(`Could not open sale: ${err.message}`);
        }
    }

    /**
     * Add a variant to the current sale.
     * Opens a new sale first if none is active.
     */
    async function addItem(variantId) {
        const product = products.find(p => p.variantId === variantId);
        if (!product) return;

        try {
            await ensureSaleOpen();

            const data  = await api.post(`/sales/${currentSale.id}/items`, {
                product_variant_id: variantId,
                quantity:           1,
            });
            currentSale = data.sale;

            renderCart();

            Swal.fire({
                icon:             'success',
                title:            'Item Added',
                text:             `${product.name}${product.variantName ? ' — ' + product.variantName : ''} added`,
                toast:            true,
                position:         'top-end',
                showConfirmButton: false,
                timer:            1200,
                timerProgressBar: true,
            });

        } catch (err) {
            showError('Could not add item', err.message);
        }
    }

    /**
     * Update the quantity of a line item.
     * Passing 0 removes the item entirely.
     */
    async function updateQuantity(saleItemId, quantity) {
        if (!currentSale) return;

        try {
            const data  = await api.patch(`/sales/${currentSale.id}/items/${saleItemId}`, { quantity });
            currentSale = data.sale;
            renderCart();
        } catch (err) {
            showError('Could not update item', err.message);
        }
    }

    /**
     * Remove a line item from the sale.
     */
    async function removeItem(saleItemId) {
        if (!currentSale) return;

        const result = await Swal.fire({
            title:              'Remove Item?',
            text:               'This item will be removed from the order.',
            icon:               'warning',
            showCancelButton:   true,
            confirmButtonColor: '#d33',
            cancelButtonColor:  '#3085d6',
            confirmButtonText:  'Yes, remove it!',
            cancelButtonText:   'Cancel',
        });

        if (!result.isConfirmed) return;

        try {
            const data  = await api.delete(`/sales/${currentSale.id}/items/${saleItemId}`);
            currentSale = data.sale;
            renderCart();

            Swal.fire({
                icon:             'success',
                title:            'Removed',
                toast:            true,
                position:         'top-end',
                showConfirmButton: false,
                timer:            1200,
            });
        } catch (err) {
            showError('Could not remove item', err.message);
        }
    }

    /**
     * Void the current sale and reset the terminal.
     */
    async function voidSale(reason = '') {
        if (!currentSale) return;

        try {
            await api.post(`/sales/${currentSale.id}/void`, { reason });
            currentSale = null;
            renderCart();
        } catch (err) {
            showError('Could not void sale', err.message);
        }
    }

    // =========================================================================
    // Cart rendering
    // Reads from currentSale.items (backend data), not a local array.
    // =========================================================================

    function renderCart() {
        const cartItems = document.getElementById('cartItems');
        const items     = currentSale?.items || [];

        if (items.length === 0) {
            cartItems.innerHTML = `
                <div class="text-center py-12">
                    <i class="fas fa-shopping-cart text-slate-600 text-5xl mb-4"></i>
                    <p class="text-slate-500 text-sm">Cart is empty</p>
                    <p class="text-slate-600 text-xs mt-1">Add items to get started</p>
                </div>`;
        } else {
            cartItems.innerHTML = items.map(item => `
                <div class="cart-item bg-slate-700/30 rounded-xl p-3">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-white font-medium text-sm">
                            ${item.product_name}
                            ${item.variant_name ? `<span class="text-slate-400 text-xs ml-1">${item.variant_name}</span>` : ''}
                        </span>
                        <button class="text-red-400 hover:text-red-300 transition-colors"
                            onclick="window.__removeItem(${item.id})">
                            <i class="fas fa-trash text-xs"></i>
                        </button>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <button class="w-6 h-6 bg-slate-600 hover:bg-slate-500 rounded text-white text-xs transition-all"
                                onclick="window.__updateQty(${item.id}, ${item.quantity - 1})">
                                <i class="fas fa-minus"></i>
                            </button>
                            <span class="text-slate-300 font-semibold text-sm w-8 text-center">${item.quantity}</span>
                            <button class="w-6 h-6 bg-emerald-500 hover:bg-emerald-600 rounded text-white text-xs transition-all"
                                onclick="window.__updateQty(${item.id}, ${item.quantity + 1})">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                        <span class="text-emerald-400 font-bold text-sm">GHS ${parseFloat(item.line_total).toFixed(2)}</span>
                    </div>
                </div>`).join('');
        }

        updateTotals();
    }

    // Expose handlers to inline onclick attributes in the cart HTML
    window.__updateQty    = updateQuantity;
    window.__removeItem   = removeItem;

    // =========================================================================
    // Totals display
    // Reads from currentSale totals returned by the backend.
    // The backend is the source of truth — we don't recalculate here.
    // =========================================================================

    function updateTotals() {
        const sale           = currentSale;
        const subtotal       = sale ? parseFloat(sale.subtotal)        : 0;
        const discountAmount = sale ? parseFloat(sale.discount_amount) : 0;
        const tax            = sale ? parseFloat(sale.tax_amount)      : 0;
        const total          = sale ? parseFloat(sale.total_amount)    : 0;
        const itemCount      = sale ? (sale.items || []).reduce((s, i) => s + i.quantity, 0) : 0;

        document.getElementById('itemCount').textContent  = itemCount;
        document.getElementById('subtotal').textContent   = `GHS ${subtotal.toFixed(2)}`;
        document.getElementById('tax').textContent        = `GHS ${tax.toFixed(2)}`;
        document.getElementById('total').textContent      = `GHS ${total.toFixed(2)}`;

        const discountDisplay = document.getElementById('discountDisplay');
        const discountRow     = document.getElementById('discountRow');
        const discountEl      = document.getElementById('discountAmount');
        const discountText    = document.getElementById('discountText');

        if (discountAmount > 0 && sale?.discount_code) {
            discountDisplay.classList.remove('hidden');
            discountRow.style.display    = 'flex';
            discountEl.textContent       = `-GHS ${discountAmount.toFixed(2)}`;
            discountText.textContent     = `Code: ${sale.discount_code}`;
        } else {
            discountDisplay.classList.add('hidden');
            discountRow.style.display = 'none';
        }
    }

    // =========================================================================
    // Discount
    // =========================================================================

    async function applyDiscount() {
        if (!currentSale || !currentSale.items?.length) {
            Swal.fire({
                icon:               'warning',
                title:              'Empty Cart',
                text:               'Please add items before applying a discount.',
                confirmButtonColor: '#3085d6',
            });
            return;
        }

        const result = await Swal.fire({
            title:           'Apply Discount Code',
            input:           'text',
            inputLabel:      'Enter your discount code',
            inputPlaceholder:'e.g. WELCOME10',
            showCancelButton: true,
            confirmButtonText:'Preview',
            confirmButtonColor:'#f59e0b',
            inputAttributes: { autocomplete: 'off', style: 'text-transform:uppercase' },
            preConfirm: (code) => {
                if (!code?.trim()) {
                    Swal.showValidationMessage('Please enter a discount code.');
                    return false;
                }
                return code.trim().toUpperCase();
            },
        });

        if (!result.isConfirmed) return;

        // Preview the discount before applying
        try {
            const preview = await api.post(`/sales/${currentSale.id}/discount/preview`, {
                code: result.value,
            });

            if (!preview.valid) {
                Swal.fire({ icon: 'error', title: 'Invalid Code', text: preview.message });
                return;
            }

            // Show the preview and ask for confirmation
            const confirm = await Swal.fire({
                icon:               'info',
                title:              'Discount Preview',
                text:               preview.message,
                showCancelButton:   true,
                confirmButtonText:  'Apply',
                confirmButtonColor: '#f59e0b',
                cancelButtonText:   'Cancel',
            });

            if (!confirm.isConfirmed) return;

            // Apply the discount
            const data  = await api.post(`/sales/${currentSale.id}/discount`, {
                code: result.value,
            });
            currentSale = data.sale;
            renderCart();

            Swal.fire({
                icon:             'success',
                title:            'Discount Applied!',
                toast:            true,
                position:         'top-end',
                showConfirmButton: false,
                timer:            1500,
            });

        } catch (err) {
            showError('Discount Error', err.message);
        }
    }

    async function removeDiscount() {
        const result = await Swal.fire({
            title:              'Remove Discount?',
            text:               'The discount will be removed from this sale.',
            icon:               'warning',
            showCancelButton:   true,
            confirmButtonColor: '#f59e0b',
            confirmButtonText:  'Yes, remove it!',
        });

        if (!result.isConfirmed) return;

        try {
            const data  = await api.delete(`/sales/${currentSale.id}/discount`);
            currentSale = data.sale;
            renderCart();
        } catch (err) {
            showError('Could not remove discount', err.message);
        }
    }

    // =========================================================================
    // Checkout modal
    // =========================================================================

    function openCheckoutModal() {
        if (!currentSale || !currentSale.items?.length) {
            Swal.fire({
                icon:               'warning',
                title:              'Empty Cart',
                text:               'Please add items before payment.',
                confirmButtonColor: '#3085d6',
            });
            return;
        }

        const total = parseFloat(currentSale.total_amount);
        document.getElementById('modalTotal').textContent = `GHS ${total.toFixed(2)}`;
        document.getElementById('checkoutModal').classList.remove('hidden');
        document.getElementById('checkoutModal').classList.add('flex');
    }

    function closeCheckoutModal() {
        document.getElementById('checkoutModal').classList.add('hidden');
        document.getElementById('checkoutModal').classList.remove('flex');
    }

    // =========================================================================
    // Cash payment modal
    // =========================================================================

    function openCashModal() {
        const total = parseFloat(currentSale.total_amount);
        document.getElementById('cashAmountDue').textContent = `GHS ${total.toFixed(2)}`;
        document.getElementById('cashTendered').value        = '';
        document.getElementById('cashChange').textContent    = 'GHS 0.00';
        document.getElementById('cashModal').classList.remove('hidden');
        document.getElementById('cashModal').classList.add('flex');
    }

    function closeCashModal() {
        document.getElementById('cashModal').classList.add('hidden');
        document.getElementById('cashModal').classList.remove('flex');
    }

    /**
     * Process a cash payment:
     * 1. Record the cash payment on the backend
     * 2. Complete the sale
     * 3. Show the receipt
     */
    async function processCashPayment() {
        const tendered = parseFloat(document.getElementById('cashTendered').value) || 0;
        const total    = parseFloat(currentSale.total_amount);

        if (tendered < total) {
            Swal.fire({
                icon:               'error',
                title:              'Insufficient Amount',
                text:               `Please enter at least GHS ${total.toFixed(2)}`,
                confirmButtonColor: '#3085d6',
            });
            return;
        }

        try {
            // Step 1 — record the cash payment
            const paymentData = await api.post(`/sales/${currentSale.id}/payments/cash`, {
                amount: tendered,
            });

            // Step 2 — complete the sale
            const saleData  = await api.post(`/sales/${currentSale.id}/complete`, {});
            currentSale     = saleData.sale;

            closeCashModal();
            closeCheckoutModal();

            // Step 3 — show receipt and offer to print
            await showReceipt(paymentData.change_due);

        } catch (err) {
            showError('Payment Failed', err.message);
        }
    }

    // =========================================================================
    // Receipt
    // =========================================================================

    async function showReceipt(changeDue = 0) {
        if (!currentSale?.receipt) return;

        const receipt  = currentSale.receipt;
        const snapshot = receipt.snapshot;
        const items    = snapshot.items || [];
        const payments = snapshot.payments || [];

        const itemsHtml = items.map(i => `
            <div class="flex justify-between text-sm">
                <span>${i.product_name}${i.variant_name ? ' — ' + i.variant_name : ''} x${i.quantity}</span>
                <span>GHS ${parseFloat(i.line_total).toFixed(2)}</span>
            </div>`).join('');

        const paymentsHtml = payments.map(p => `
            <div class="flex justify-between text-sm">
                <span>${p.method.replace('_', ' ')}</span>
                <span>GHS ${parseFloat(p.amount).toFixed(2)}</span>
            </div>`).join('');

        const result = await Swal.fire({
            title:           `Receipt — ${receipt.receipt_number}`,
            html: `
                <div class="text-left space-y-3">
                    <div class="space-y-1">${itemsHtml}</div>
                    <hr class="border-gray-200">
                    <div class="flex justify-between text-sm">
                        <span>Subtotal</span>
                        <span>GHS ${parseFloat(snapshot.totals.subtotal).toFixed(2)}</span>
                    </div>
                    ${snapshot.totals.discount_amount > 0 ? `
                    <div class="flex justify-between text-sm text-yellow-600">
                        <span>Discount (${snapshot.totals.discount_code})</span>
                        <span>-GHS ${parseFloat(snapshot.totals.discount_amount).toFixed(2)}</span>
                    </div>` : ''}
                    <div class="flex justify-between font-bold">
                        <span>Total</span>
                        <span>GHS ${parseFloat(snapshot.totals.total_amount).toFixed(2)}</span>
                    </div>
                    <hr class="border-gray-200">
                    <div class="space-y-1">${paymentsHtml}</div>
                    ${changeDue > 0 ? `
                    <div class="flex justify-between text-sm text-green-600 font-bold">
                        <span>Change</span>
                        <span>GHS ${parseFloat(changeDue).toFixed(2)}</span>
                    </div>` : ''}
                </div>`,
            confirmButtonText:  'Print Receipt',
            showCancelButton:   true,
            cancelButtonText:   'No Receipt',
            confirmButtonColor: '#10b981',
        });

        if (result.isConfirmed) {
            try {
                await api.post(`/sales/${currentSale.id}/receipt/print`, {});
                Swal.fire({
                    icon:             'info',
                    title:            'Receipt Sent to Printer',
                    toast:            true,
                    position:         'top-end',
                    showConfirmButton: false,
                    timer:            2000,
                });
            } catch (err) {
                // Non-critical — don't block the cashier
                console.warn('Print request failed:', err.message);
            }
        }

        // Reset terminal for the next sale
        currentSale = null;
        renderCart();
    }

    // =========================================================================
    // Pending sale recovery
    // =========================================================================

    async function checkForPendingSales() {
        try {
            const data  = await api.get('/pending-sales');
            const sales = data.sales || [];

            if (sales.length === 0) return;

            const result = await Swal.fire({
                title:              'Resume Pending Sale?',
                text:               `There is ${sales.length} pending sale on this terminal. Would you like to resume it?`,
                icon:               'question',
                showCancelButton:   true,
                confirmButtonText:  'Resume Sale',
                cancelButtonText:   'Start Fresh',
                confirmButtonColor: '#10b981',
            });

            if (result.isConfirmed) {
                currentSale = sales[0];
                renderCart();
            }
        } catch (err) {
            // Non-critical — terminal can still operate without this
            console.warn('Could not check for pending sales:', err.message);
        }
    }

    // =========================================================================
    // New sale / clear cart
    // =========================================================================

    async function startNewSale() {
        if (!currentSale || !currentSale.items?.length) return;

        const result = await Swal.fire({
            title:              'Start New Sale?',
            text:               'The current sale will be voided.',
            icon:               'question',
            showCancelButton:   true,
            confirmButtonColor: '#3085d6',
            confirmButtonText:  'Yes, new sale!',
            cancelButtonText:   'Continue current sale',
        });

        if (!result.isConfirmed) return;

        await voidSale('Cancelled by cashier');

        Swal.fire({
            icon:             'success',
            title:            'New Sale Started',
            toast:            true,
            position:         'top-end',
            showConfirmButton: false,
            timer:            1500,
        });
    }

    async function clearCart() {
        if (!currentSale || !currentSale.items?.length) return;

        const result = await Swal.fire({
            title:              'Clear Cart?',
            text:               'All items will be removed and the sale will be voided.',
            icon:               'warning',
            showCancelButton:   true,
            confirmButtonColor: '#d33',
            confirmButtonText:  'Yes, clear it!',
        });

        if (!result.isConfirmed) return;

        await voidSale('Cart cleared by cashier');

        Swal.fire({
            icon:             'success',
            title:            'Cart Cleared',
            toast:            true,
            position:         'top-end',
            showConfirmButton: false,
            timer:            1500,
        });
    }

    // =========================================================================
    // Product search
    // =========================================================================

    function setupSearch() {
        const searchInput = document.getElementById('searchProduct');
        if (!searchInput) return;

        searchInput.addEventListener('input', (e) => {
            const term = e.target.value.toLowerCase().trim();

            if (!term) {
                renderProducts(currentFilter);
                return;
            }

            const grid     = document.getElementById('productsGrid');
            const filtered = products.filter(p =>
                p.name.toLowerCase().includes(term) ||
                p.sku.toLowerCase().includes(term) ||
                (p.variantName && p.variantName.toLowerCase().includes(term))
            );

            if (filtered.length === 0) {
                grid.innerHTML = `
                    <div class="col-span-4 text-center py-12">
                        <i class="fas fa-search text-slate-600 text-5xl mb-4"></i>
                        <p class="text-slate-500 text-sm">No products match "${e.target.value}"</p>
                    </div>`;
                return;
            }

            // Temporarily render search results
            const prev    = currentFilter;
            currentFilter = '__search__';
            const grid2   = document.getElementById('productsGrid');
            grid2.innerHTML = filtered.map(product => {
                const outOfStock = product.stock <= 0;
                return `
                    <div class="product-card bg-slate-800/50 border border-slate-700/50 rounded-xl p-4
                        ${outOfStock ? 'opacity-50 cursor-not-allowed' : 'hover:border-emerald-500/50 cursor-pointer'}
                        transition-all"
                        data-variant-id="${product.variantId}"
                        data-out-of-stock="${outOfStock}">
                        <div class="w-16 h-16 bg-linear-to-br from-emerald-500/20 to-cyan-500/20 rounded-xl flex items-center justify-center mb-3 mx-auto">
                            <i class="fas fa-box text-3xl text-emerald-400"></i>
                        </div>
                        <h3 class="text-white font-semibold text-sm text-center mb-1">${product.name}</h3>
                        ${product.variantName ? `<p class="text-slate-400 text-xs text-center mb-2">${product.variantName}</p>` : '<div class="mb-2"></div>'}
                        <p class="text-emerald-400 font-bold text-center text-lg">GHS ${product.price.toFixed(2)}</p>
                    </div>`;
            }).join('');

            document.querySelectorAll('.product-card').forEach(card => {
                if (card.dataset.outOfStock === 'true') return;
                card.addEventListener('click', () => addItem(parseInt(card.dataset.variantId)));
            });
        });
    }

    // =========================================================================
    // Category filter listeners
    // =========================================================================

    function setupCategoryListeners() {
        document.querySelectorAll('.category-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('.category-btn').forEach(b => {
                    b.classList.remove('bg-emerald-500/20', 'border-emerald-500/50', 'text-emerald-400');
                    b.classList.add('bg-slate-800/50', 'border-slate-700/50', 'text-slate-300');
                });
                btn.classList.remove('bg-slate-800/50', 'border-slate-700/50', 'text-slate-300');
                btn.classList.add('bg-emerald-500/20', 'border-emerald-500/50', 'text-emerald-400');
                renderProducts(btn.dataset.category);
            });
        });
    }

    // =========================================================================
    // Loading overlay helpers
    // =========================================================================

    function showLoadingOverlay(message = 'Loading...') {
        const overlay = document.createElement('div');
        overlay.id    = 'loadingOverlay';
        overlay.className = 'fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center';
        overlay.innerHTML = `
            <div class="text-center">
                <i class="fas fa-spinner fa-spin text-emerald-400 text-4xl mb-4"></i>
                <p class="text-white font-medium">${message}</p>
            </div>`;
        document.body.appendChild(overlay);
    }

    function hideLoadingOverlay() {
        document.getElementById('loadingOverlay')?.remove();
    }

    function showError(title, message) {
        Swal.fire({
            icon:               'error',
            title:              title,
            text:               message,
            confirmButtonColor: '#3085d6',
        });
    }

    // =========================================================================
    // Event listeners
    // =========================================================================

    function setupEventListeners() {
        document.getElementById('newSaleBtn')     .addEventListener('click', startNewSale);
        document.getElementById('discountBtn')    .addEventListener('click', applyDiscount);
        document.getElementById('removeDiscountBtn').addEventListener('click', removeDiscount);
        document.getElementById('clearCart')      .addEventListener('click', clearCart);
        document.getElementById('checkoutBtn')    .addEventListener('click', openCheckoutModal);
        document.getElementById('closeModal')     .addEventListener('click', closeCheckoutModal);
        document.getElementById('closeCashModal') .addEventListener('click', closeCashModal);
        document.getElementById('completeCashPayment').addEventListener('click', processCashPayment);

        // Cash tendered — live change calculation
        document.getElementById('cashTendered').addEventListener('input', function () {
            const tendered = parseFloat(this.value) || 0;
            const total    = currentSale ? parseFloat(currentSale.total_amount) : 0;
            const change   = Math.max(0, tendered - total);
            document.getElementById('cashChange').textContent = `GHS ${change.toFixed(2)}`;
        });

        // Cash payment button inside checkout modal
        document.getElementById('cashPaymentBtn').addEventListener('click', () => {
            closeCheckoutModal();
            openCashModal();
        });

        // Card and MoMo — placeholders for now
        document.querySelectorAll('.payment-method').forEach(btn => {
            const text = btn.textContent.trim();
            if (text.includes('Card') || text.includes('Split') || text.includes('Gift')) {
                btn.addEventListener('click', () => {
                    Swal.fire({
                        icon:               'info',
                        title:              `${text} Payment`,
                        text:               'This payment method will be wired up next.',
                        confirmButtonColor: '#3085d6',
                    });
                });
            }
        });

        setupSearch();
        setupCategoryListeners();
    }

    // =========================================================================
    // Start
    // =========================================================================

    setupEventListeners();
    await boot();
});