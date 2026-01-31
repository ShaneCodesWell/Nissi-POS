<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>POS Terminal - POS PAY</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.6;
            }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .nav-item {
            animation: slideIn 0.4s ease-out forwards;
            position: relative;
            overflow: hidden;
        }

        .nav-item::before {
            content: "";
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 3px;
            background: linear-gradient(180deg, #10b981, #06b6d4);
            transform: scaleY(0);
            transition: transform 0.3s ease;
        }

        .nav-item:hover::before {
            transform: scaleY(1);
        }

        .nav-item:nth-child(1) {
            animation-delay: 0.1s;
            opacity: 0;
        }

        .nav-item:nth-child(2) {
            animation-delay: 0.15s;
            opacity: 0;
        }

        .nav-item:nth-child(3) {
            animation-delay: 0.2s;
            opacity: 0;
        }

        .nav-item:nth-child(4) {
            animation-delay: 0.25s;
            opacity: 0;
        }

        .icon-wrapper {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .nav-item:hover .icon-wrapper {
            transform: translateX(4px) scale(1.1);
        }

        .logo-glow {
            animation: pulse 3s ease-in-out infinite;
        }

        .active-link {
            background: linear-gradient(135deg,
                    rgba(16, 185, 129, 0.2),
                    rgba(6, 182, 212, 0.2));
            border-left: 3px solid #10b981;
        }

        .product-card {
            animation: slideUp 0.3s ease-out forwards;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .product-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 30px rgba(16, 185, 129, 0.2);
        }

        .cart-item {
            animation: slideUp 0.2s ease-out;
        }

        .numpad-btn {
            transition: all 0.2s ease;
        }

        .numpad-btn:active {
            transform: scale(0.95);
        }
    </style>
</head>

<body class="bg-gray-900 min-h-screen">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside
            class="w-64 bg-linear-to-br from-slate-900 via-slate-800 to-slate-900 text-white flex flex-col shrink-0 border-r border-slate-700/50">
            <!-- Header Section -->
            <div class="p-3 border-b border-slate-700/50">
                <div class="flex items-center gap-3">
                    <div class="relative">
                        <div class="absolute inset-0 bg-emerald-500/20 blur-xl logo-glow rounded-full"></div>
                        <div
                            class="relative w-12 h-12 bg-linear-to-br from-emerald-400 to-cyan-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-cash-register text-xl text-white"></i>
                        </div>
                    </div>
                    <div>
                        <p
                            class="text-lg font-bold bg-linear-to-r from-emerald-400 to-cyan-400 bg-clip-text text-transparent">
                            POS PAY
                        </p>
                        <p class="text-xs text-slate-400">Admin Dashboard</p>
                    </div>
                </div>
            </div>

            <!-- Navigation Section -->
            <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
                <a href="index.html"
                    class="nav-item flex items-center gap-3 p-3 rounded-xl hover:bg-slate-800/50 transition-all duration-300 group">
                    <div class="icon-wrapper w-9 h-9 bg-slate-800/50 rounded-lg flex items-center justify-center">
                        <i class="fas fa-home text-slate-400 text-sm group-hover:text-cyan-400 transition-colors"></i>
                    </div>
                    <span
                        class="font-medium text-sm text-slate-300 group-hover:text-white transition-colors">Dashboard</span>
                </a>

                <a href="#"
                    class="nav-item active-link flex items-center gap-3 p-3 rounded-xl transition-all duration-300 group">
                    <div
                        class="icon-wrapper w-9 h-9 bg-linear-to-br from-emerald-500/20 to-cyan-500/20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-box-open text-emerald-400 text-sm"></i>
                    </div>
                    <span class="font-medium text-sm">Products</span>
                </a>

                <a href="#"
                    class="nav-item flex items-center gap-3 p-3 rounded-xl hover:bg-slate-800/50 transition-all duration-300 group">
                    <div class="icon-wrapper w-9 h-9 bg-slate-800/50 rounded-lg flex items-center justify-center">
                        <i
                            class="fas fa-shopping-cart text-slate-400 text-sm group-hover:text-cyan-400 transition-colors"></i>
                    </div>
                    <span
                        class="font-medium text-sm text-slate-300 group-hover:text-white transition-colors">Sales</span>
                </a>

                <a href="#"
                    class="nav-item flex items-center gap-3 p-3 rounded-xl hover:bg-slate-800/50 transition-all duration-300 group">
                    <div class="icon-wrapper w-9 h-9 bg-slate-800/50 rounded-lg flex items-center justify-center">
                        <i
                            class="fas fa-chart-line text-slate-400 text-sm group-hover:text-cyan-400 transition-colors"></i>
                    </div>
                    <span
                        class="font-medium text-sm text-slate-300 group-hover:text-white transition-colors">Reports</span>
                </a>
            </nav>

            <!-- Footer Section -->
            <div class="p-2 border-t border-slate-700/50 space-y-1">
                <a href="#"
                    class="flex items-center gap-3 p-3 rounded-xl hover:bg-slate-800/50 transition-all duration-300 group">
                    <div class="icon-wrapper w-9 h-9 bg-slate-800/50 rounded-lg flex items-center justify-center">
                        <i class="fas fa-cog text-slate-400 text-sm group-hover:text-cyan-400 transition-colors"></i>
                    </div>
                    <span
                        class="font-medium text-sm text-slate-300 group-hover:text-white transition-colors">Settings</span>
                </a>

                <a href="#"
                    class="flex items-center gap-3 p-3 rounded-xl hover:bg-red-500/10 transition-all duration-300 group">
                    <div
                        class="icon-wrapper w-9 h-9 bg-slate-800/50 rounded-lg flex items-center justify-center group-hover:bg-red-500/20">
                        <i
                            class="fas fa-sign-out-alt text-slate-400 text-sm group-hover:text-red-400 transition-colors"></i>
                    </div>
                    <span
                        class="font-medium text-sm text-slate-300 group-hover:text-red-400 transition-colors">Logout</span>
                </a>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Navbar -->
            <header class="bg-slate-800 border-b border-slate-700/50 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <button class="lg:hidden text-slate-400 hover:text-white transition-colors">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        <h1 class="text-xl font-bold text-white">POS Terminal</h1>
                    </div>

                    <div class="flex items-center gap-4">
                        <!-- Search -->
                        <div class="hidden md:flex items-center bg-slate-700/50 rounded-lg px-4 py-2 gap-2">
                            <i class="fas fa-search text-slate-400 text-sm"></i>
                            <input type="text" id="searchProduct" placeholder="Search products..."
                                class="bg-transparent border-none outline-none text-slate-300 text-sm placeholder-slate-500 w-64" />
                        </div>

                        <!-- Theme Toggle -->
                        <button id="themeToggle" class="text-slate-400 hover:text-white transition-colors">
                            <i class="fas fa-moon text-xl"></i>
                        </button>

                        <!-- Notifications -->
                        <button class="relative text-slate-400 hover:text-white transition-colors">
                            <i class="fas fa-bell text-xl"></i>
                            <span class="absolute -top-1 -right-1 w-2 h-2 bg-emerald-500 rounded-full"></span>
                        </button>

                        <!-- User Profile -->
                        <div class="flex items-center gap-3 pl-4 border-l border-slate-700">
                            <div class="text-right hidden sm:block">
                                <p class="text-sm font-medium text-white">Admin User</p>
                                <p class="text-xs text-slate-400">admin@pospay.com</p>
                            </div>
                            <div
                                class="w-10 h-10 bg-linear-to-br from-emerald-400 to-cyan-500 rounded-full flex items-center justify-center text-white font-bold">
                                AU
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content -->
            <main class="flex-1 overflow-y-auto bg-gray-900 p-6">
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
                            class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 overflow-y-auto flex-1">
                            <!-- Products will be dynamically inserted here -->
                        </div>
                    </div>

                    <!-- Cart & Checkout - Right Side -->
                    <div class="w-96 flex flex-col bg-slate-800/50 border border-slate-700/50 rounded-2xl">
                        <!-- Cart Header -->
                        <div class="p-6 border-b border-slate-700/50">
                            <div class="flex justify-between items-center mb-2">
                                <h2 class="text-xl font-bold text-white">Current Order</h2>
                                <button id="newSaleBtn"
                                    class="text-red-400 hover:text-red-300 transition-colors text-sm">
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
                            <div class="flex justify-between items-center text-yellow-400" id="discountRow"
                                style="display: none">
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
            </main>
        </div>
    </div>

    <!-- Checkout Modal -->
    <div id="checkoutModal"
        class="fixed inset-0 bg-black/70 backdrop-blur-sm hidden items-center justify-center z-50">
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

    <!-- Cash Payment Modal -->
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

    <script>
        // Products Data
        const products = [{
                id: 1,
                name: "Espresso",
                price: 3.50,
                category: "beverages",
                icon: "coffee"
            },
            {
                id: 2,
                name: "Cappuccino",
                price: 4.50,
                category: "beverages",
                icon: "coffee"
            },
            {
                id: 3,
                name: "Latte",
                price: 4.75,
                category: "beverages",
                icon: "coffee"
            },
            {
                id: 4,
                name: "Americano",
                price: 3.25,
                category: "beverages",
                icon: "coffee"
            },
            {
                id: 5,
                name: "Croissant",
                price: 3.00,
                category: "food",
                icon: "bread-slice"
            },
            {
                id: 6,
                name: "Sandwich",
                price: 7.50,
                category: "food",
                icon: "hamburger"
            },
            {
                id: 7,
                name: "Bagel",
                price: 2.50,
                category: "food",
                icon: "bread-slice"
            },
            {
                id: 8,
                name: "Muffin",
                price: 3.25,
                category: "snacks",
                icon: "cookie"
            },
            {
                id: 9,
                name: "Cookie",
                price: 2.00,
                category: "snacks",
                icon: "cookie"
            },
            {
                id: 10,
                name: "Brownie",
                price: 3.50,
                category: "desserts",
                icon: "cookie-bite"
            },
            {
                id: 11,
                name: "Cheesecake",
                price: 5.50,
                category: "desserts",
                icon: "cake-candles"
            },
            {
                id: 12,
                name: "Orange Juice",
                price: 4.00,
                category: "beverages",
                icon: "glass-water"
            },
            {
                id: 13,
                name: "Smoothie",
                price: 5.50,
                category: "beverages",
                icon: "blender"
            },
            {
                id: 14,
                name: "Tea",
                price: 2.75,
                category: "beverages",
                icon: "mug-hot"
            },
            {
                id: 15,
                name: "Donut",
                price: 2.25,
                category: "desserts",
                icon: "cookie"
            },
            {
                id: 16,
                name: "Chips",
                price: 1.50,
                category: "snacks",
                icon: "cookie"
            },
        ];

        // Cart State
        let cart = [];
        let currentAmount = "0";
        let currentDiscount = null;

        // Initialize
        function init() {
            renderProducts();
            setupEventListeners();
        }

        // Render Products
        function renderProducts(filter = "all") {
            const grid = document.getElementById("productsGrid");
            const filteredProducts = filter === "all" ?
                products :
                products.filter(p => p.category === filter);

            grid.innerHTML = filteredProducts
                .map(
                    (product) => `
          <div class="product-card bg-slate-800/50 border border-slate-700/50 rounded-xl p-4 hover:border-emerald-500/50" data-id="${product.id}">
            <div class="w-16 h-16 bg-linear-to-br from-emerald-500/20 to-cyan-500/20 rounded-xl flex items-center justify-center mb-3 mx-auto">
              <i class="fas fa-${product.icon} text-3xl text-emerald-400"></i>
            </div>
            <h3 class="text-white font-semibold text-sm text-center mb-2">${product.name}</h3>
            <p class="text-emerald-400 font-bold text-center text-lg">$${product.price.toFixed(2)}</p>
          </div>
        `
                )
                .join("");

            // Add click handlers
            document.querySelectorAll(".product-card").forEach((card) => {
                card.addEventListener("click", () => {
                    addToCart(parseInt(card.dataset.id));
                });
            });
        }

        // Add to Cart
        function addToCart(productId) {
            const product = products.find((p) => p.id === productId);
            const existingItem = cart.find((item) => item.id === productId);

            if (existingItem) {
                existingItem.quantity++;
            } else {
                cart.push({
                    ...product,
                    quantity: 1
                });
            }

            renderCart();

            // Show SweetAlert confirmation
            Swal.fire({
                icon: "success",
                title: "Item Added",
                text: `${product.name} added to cart`,
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 1500,
                timerProgressBar: true,
            });
        }

        // Render Cart
        function renderCart() {
            const cartItems = document.getElementById("cartItems");

            if (cart.length === 0) {
                cartItems.innerHTML = `
            <div class="text-center py-12">
              <i class="fas fa-shopping-cart text-slate-600 text-5xl mb-4"></i>
              <p class="text-slate-500 text-sm">Cart is empty</p>
              <p class="text-slate-600 text-xs mt-1">Add items to get started</p>
            </div>
          `;
            } else {
                cartItems.innerHTML = cart
                    .map(
                        (item) => `
            <div class="cart-item bg-slate-700/30 rounded-xl p-3">
              <div class="flex items-center justify-between mb-2">
                <span class="text-white font-medium text-sm">${item.name}</span>
                <button class="text-red-400 hover:text-red-300 transition-colors" onclick="removeFromCart(${item.id})">
                  <i class="fas fa-trash text-xs"></i>
                </button>
              </div>
              <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                  <button class="w-6 h-6 bg-slate-600 hover:bg-slate-500 rounded text-white text-xs transition-all" onclick="updateQuantity(${item.id}, -1)">
                    <i class="fas fa-minus"></i>
                  </button>
                  <span class="text-slate-300 font-semibold text-sm w-8 text-center">${item.quantity}</span>
                  <button class="w-6 h-6 bg-emerald-500 hover:bg-emerald-600 rounded text-white text-xs transition-all" onclick="updateQuantity(${item.id}, 1)">
                    <i class="fas fa-plus"></i>
                  </button>
                </div>
                <span class="text-emerald-400 font-bold text-sm">$${(item.price * item.quantity).toFixed(2)}</span>
              </div>
            </div>
          `
                    )
                    .join("");
            }

            updateTotals();
        }

        // Update Quantity
        function updateQuantity(productId, change) {
            const item = cart.find((i) => i.id === productId);
            if (item) {
                item.quantity += change;
                if (item.quantity <= 0) {
                    removeFromCart(productId);
                } else {
                    renderCart();
                }
            }
        }

        // Remove from Cart
        function removeFromCart(productId) {
            Swal.fire({
                title: "Remove Item?",
                text: "This item will be removed from the cart",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, remove it!",
                cancelButtonText: "Cancel",
            }).then((result) => {
                if (result.isConfirmed) {
                    cart = cart.filter((item) => item.id !== productId);
                    renderCart();

                    Swal.fire({
                        icon: "success",
                        title: "Removed",
                        text: "Item removed from cart",
                        toast: true,
                        position: "top-end",
                        showConfirmButton: false,
                        timer: 1500,
                    });
                }
            });
        }

        // Update Totals
        function updateTotals() {
            const subtotal = cart.reduce((sum, item) => sum + item.price * item.quantity, 0);
            const discountValue = currentDiscount ? calculateDiscount(subtotal) : 0;
            const taxableAmount = subtotal - discountValue;
            const tax = taxableAmount * 0.1;
            const total = taxableAmount + tax;

            document.getElementById("itemCount").textContent = cart.reduce((sum, item) => sum + item.quantity, 0);
            document.getElementById("subtotal").textContent = `$${subtotal.toFixed(2)}`;
            document.getElementById("tax").textContent = `$${tax.toFixed(2)}`;
            document.getElementById("total").textContent = `$${total.toFixed(2)}`;

            // Update discount display
            const discountDisplay = document.getElementById("discountDisplay");
            const discountRow = document.getElementById("discountRow");
            const discountAmount = document.getElementById("discountAmount");
            const discountText = document.getElementById("discountText");

            if (currentDiscount) {
                if (discountDisplay) {
                    discountDisplay.classList.remove("hidden");
                    discountRow.style.display = "flex";
                    discountAmount.textContent = `-$${discountValue.toFixed(2)}`;

                    if (currentDiscount.type === "percentage") {
                        discountText.textContent = `${currentDiscount.value}% Discount Applied`;
                    } else {
                        discountText.textContent = `$${currentDiscount.value.toFixed(2)} Discount Applied`;
                    }
                }
            } else {
                if (discountDisplay) {
                    discountDisplay.classList.add("hidden");
                    discountRow.style.display = "none";
                }
            }
        }

        function calculateDiscount(subtotal) {
            if (!currentDiscount) return 0;

            if (currentDiscount.type === "percentage") {
                return subtotal * (currentDiscount.value / 100);
            } else {
                return Math.min(currentDiscount.value, subtotal);
            }
        }

        // Discount functionality
        function applyDiscount() {
            if (cart.length === 0) {
                Swal.fire({
                    icon: "warning",
                    title: "Empty Cart",
                    text: "Please add items to cart before applying discount",
                    confirmButtonColor: "#3085d6",
                });
                return;
            }

            const subtotal = cart.reduce((sum, item) => sum + item.price * item.quantity, 0);

            Swal.fire({
                title: "Apply Discount",
                html: `
            <div class="text-left">
              <div class="mb-4">
                <label class="block text-gray-700 mb-2">Discount Type</label>
                <select id="discountType" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                  <option value="percentage">Percentage (%)</option>
                  <option value="fixed">Fixed Amount ($)</option>
                </select>
              </div>
              <div class="mb-4">
                <label class="block text-gray-700 mb-2">Discount Value</label>
                <input type="number" id="discountValue" class="w-full border border-gray-300 rounded-lg px-3 py-2" placeholder="0.00" step="0.01" min="0">
              </div>
              <div id="discountPreview" class="p-3 bg-gray-50 rounded-lg text-sm hidden">
                <div class="flex justify-between"><span>Original Total:</span><span id="previewOriginal">$0.00</span></div>
                <div class="flex justify-between"><span>Discount:</span><span id="previewDiscount">-$0.00</span></div>
                <div class="flex justify-between font-bold"><span>New Total:</span><span id="previewNewTotal">$0.00</span></div>
              </div>
            </div>
          `,
                showCancelButton: true,
                confirmButtonText: "Apply Discount",
                cancelButtonText: "Cancel",
                confirmButtonColor: "#f59e0b",
                didOpen: () => {
                    const discountValueInput = document.getElementById("discountValue");
                    const discountTypeSelect = document.getElementById("discountType");
                    const previewElement = document.getElementById("discountPreview");

                    const updatePreview = () => {
                        const type = discountTypeSelect.value;
                        const value = parseFloat(discountValueInput.value) || 0;
                        let discountAmount = 0;

                        if (type === "percentage") {
                            discountAmount = subtotal * (value / 100);
                        } else {
                            discountAmount = Math.min(value, subtotal);
                        }

                        const tax = (subtotal - discountAmount) * 0.1;
                        const newTotal = subtotal - discountAmount + tax;

                        if (value > 0) {
                            previewElement.classList.remove("hidden");
                            document.getElementById("previewOriginal").textContent =
                                `$${subtotal.toFixed(2)}`;
                            document.getElementById("previewDiscount").textContent =
                                `-$${discountAmount.toFixed(2)}`;
                            document.getElementById("previewNewTotal").textContent =
                                `$${newTotal.toFixed(2)}`;
                        } else {
                            previewElement.classList.add("hidden");
                        }
                    };

                    discountValueInput.addEventListener("input", updatePreview);
                    discountTypeSelect.addEventListener("change", updatePreview);

                    discountTypeSelect.addEventListener("change", function() {
                        if (this.value === "percentage") {
                            discountValueInput.max = 100;
                            discountValueInput.placeholder = "0";
                        } else {
                            discountValueInput.removeAttribute("max");
                            discountValueInput.placeholder = "0.00";
                        }
                    });
                },
                preConfirm: () => {
                    const type = document.getElementById("discountType").value;
                    const value = parseFloat(document.getElementById("discountValue").value);

                    if (!value || value <= 0) {
                        Swal.showValidationMessage("Please enter a valid discount value");
                        return false;
                    }

                    if (type === "percentage" && value > 100) {
                        Swal.showValidationMessage("Percentage discount cannot exceed 100%");
                        return false;
                    }

                    return {
                        type,
                        value
                    };
                },
            }).then((result) => {
                if (result.isConfirmed) {
                    currentDiscount = result.value;
                    updateTotals();

                    Swal.fire({
                        icon: "success",
                        title: "Discount Applied!",
                        text: `Discount of ${
                result.value.type === "percentage"
                  ? result.value.value + "%"
                  : "$" + result.value.value.toFixed(2)
              } has been applied`,
                        confirmButtonColor: "#f59e0b",
                    });
                }
            });
        }

        function removeDiscount() {
            Swal.fire({
                title: "Remove Discount?",
                text: "This discount will be removed from the sale",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#f59e0b",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, remove it!",
                cancelButtonText: "Keep Discount",
            }).then((result) => {
                if (result.isConfirmed) {
                    currentDiscount = null;
                    updateTotals();

                    Swal.fire({
                        icon: "success",
                        title: "Discount Removed",
                        text: "Discount has been removed from the sale",
                        toast: true,
                        position: "top-end",
                        showConfirmButton: false,
                        timer: 1500,
                    });
                }
            });
        }

        // Setup Event Listeners
        function setupEventListeners() {
            // Category filters
            document.querySelectorAll(".category-btn").forEach((btn) => {
                btn.addEventListener("click", () => {
                    document.querySelectorAll(".category-btn").forEach((b) => {
                        b.classList.remove("bg-emerald-500/20", "border-emerald-500/50",
                            "text-emerald-400");
                        b.classList.add("bg-slate-800/50", "border-slate-700/50", "text-slate-300");
                    });
                    btn.classList.remove("bg-slate-800/50", "border-slate-700/50", "text-slate-300");
                    btn.classList.add("bg-emerald-500/20", "border-emerald-500/50", "text-emerald-400");
                    renderProducts(btn.dataset.category);
                });
            });

            // New sale
            document.getElementById("newSaleBtn").addEventListener("click", () => {
                if (cart.length > 0) {
                    Swal.fire({
                        title: "Start New Sale?",
                        text: "Current cart will be cleared",
                        icon: "question",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Yes, new sale!",
                        cancelButtonText: "Continue current sale",
                    }).then((result) => {
                        if (result.isConfirmed) {
                            cart = [];
                            currentDiscount = null;
                            renderCart();

                            Swal.fire({
                                icon: "success",
                                title: "New Sale Started!",
                                toast: true,
                                position: "top-end",
                                showConfirmButton: false,
                                timer: 1500,
                            });
                        }
                    });
                }
            });

            // Discount button
            document.getElementById("discountBtn").addEventListener("click", applyDiscount);

            // Remove discount
            document.getElementById("removeDiscountBtn").addEventListener("click", removeDiscount);

            // Clear cart
            document.getElementById("clearCart").addEventListener("click", () => {
                if (cart.length === 0) return;

                Swal.fire({
                    title: "Clear Cart?",
                    text: "All items will be removed",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Yes, clear it!",
                    cancelButtonText: "Cancel",
                }).then((result) => {
                    if (result.isConfirmed) {
                        cart = [];
                        currentDiscount = null;
                        renderCart();

                        Swal.fire({
                            icon: "success",
                            title: "Cart Cleared",
                            toast: true,
                            position: "top-end",
                            showConfirmButton: false,
                            timer: 1500,
                        });
                    }
                });
            });

            // Checkout
            document.getElementById("checkoutBtn").addEventListener("click", () => {
                if (cart.length === 0) {
                    Swal.fire({
                        icon: "warning",
                        title: "Empty Cart",
                        text: "Please add items to cart before payment",
                        confirmButtonColor: "#3085d6",
                    });
                    return;
                }
                openCheckoutModal();
            });

            // Modal close buttons
            document.getElementById("closeModal").addEventListener("click", closeCheckoutModal);
            document.getElementById("closeCashModal").addEventListener("click", closeCashModal);

            // Cash payment button
            document.getElementById("cashPaymentBtn").addEventListener("click", () => {
                closeCheckoutModal();
                openCashModal();
            });

            // Payment method buttons
            document.querySelectorAll(".payment-method").forEach((button, index) => {
                if (index > 0) { // Skip cash button (already handled)
                    button.addEventListener("click", function() {
                        const method = this.textContent.trim();
                        Swal.fire({
                            icon: "info",
                            title: `${method} Payment`,
                            text: "This would integrate with payment processor",
                            confirmButtonColor: "#3085d6",
                        });
                    });
                }
            });

            // Complete cash payment
            document.getElementById("completeCashPayment").addEventListener("click", processCashPayment);

            // Cash tendered real-time calculation
            document.getElementById("cashTendered").addEventListener("input", function() {
                const tendered = parseFloat(this.value) || 0;
                const subtotal = cart.reduce((sum, item) => sum + item.price * item.quantity, 0);
                const discountValue = currentDiscount ? calculateDiscount(subtotal) : 0;
                const taxableAmount = subtotal - discountValue;
                const total = taxableAmount + taxableAmount * 0.1;
                const change = tendered - total;

                document.getElementById("cashChange").textContent = `$${change >= 0 ? change.toFixed(2) : "0.00"}`;
            });

            // Search
            document.getElementById("searchProduct").addEventListener("input", (e) => {
                const search = e.target.value.toLowerCase();
                const filtered = products.filter(p => p.name.toLowerCase().includes(search));
                const grid = document.getElementById("productsGrid");

                if (search === "") {
                    renderProducts();
                    return;
                }

                grid.innerHTML = filtered
                    .map(
                        (product) => `
            <div class="product-card bg-slate-800/50 border border-slate-700/50 rounded-xl p-4 hover:border-emerald-500/50" data-id="${product.id}">
              <div class="w-16 h-16 bg-linear-to-br from-emerald-500/20 to-cyan-500/20 rounded-xl flex items-center justify-center mb-3 mx-auto">
                <i class="fas fa-${product.icon} text-3xl text-emerald-400"></i>
              </div>
              <h3 class="text-white font-semibold text-sm text-center mb-2">${product.name}</h3>
              <p class="text-emerald-400 font-bold text-center text-lg">$${product.price.toFixed(2)}</p>
            </div>
          `
                    )
                    .join("");

                document.querySelectorAll(".product-card").forEach((card) => {
                    card.addEventListener("click", () => {
                        addToCart(parseInt(card.dataset.id));
                    });
                });
            });
        }

        // Open Checkout Modal
        function openCheckoutModal() {
            const subtotal = cart.reduce((sum, item) => sum + item.price * item.quantity, 0);
            const discountValue = currentDiscount ? calculateDiscount(subtotal) : 0;
            const taxableAmount = subtotal - discountValue;
            const tax = taxableAmount * 0.1;
            const total = taxableAmount + tax;

            document.getElementById("modalTotal").textContent = `$${total.toFixed(2)}`;
            document.getElementById("checkoutModal").classList.remove("hidden");
            document.getElementById("checkoutModal").classList.add("flex");
        }

        // Close Checkout Modal
        function closeCheckoutModal() {
            document.getElementById("checkoutModal").classList.add("hidden");
            document.getElementById("checkoutModal").classList.remove("flex");
        }

        // Open Cash Modal
        function openCashModal() {
            const subtotal = cart.reduce((sum, item) => sum + item.price * item.quantity, 0);
            const discountValue = currentDiscount ? calculateDiscount(subtotal) : 0;
            const taxableAmount = subtotal - discountValue;
            const tax = taxableAmount * 0.1;
            const total = taxableAmount + tax;

            document.getElementById("cashAmountDue").textContent = `$${total.toFixed(2)}`;
            document.getElementById("cashTendered").value = "";
            document.getElementById("cashChange").textContent = "$0.00";
            document.getElementById("cashModal").classList.remove("hidden");
            document.getElementById("cashModal").classList.add("flex");
        }

        // Close Cash Modal
        function closeCashModal() {
            document.getElementById("cashModal").classList.add("hidden");
            document.getElementById("cashModal").classList.remove("flex");
        }

        // Process Cash Payment
        function processCashPayment() {
            const tendered = parseFloat(document.getElementById("cashTendered").value) || 0;
            const subtotal = cart.reduce((sum, item) => sum + item.price * item.quantity, 0);
            const discountValue = currentDiscount ? calculateDiscount(subtotal) : 0;
            const taxableAmount = subtotal - discountValue;
            const total = taxableAmount + taxableAmount * 0.1;

            if (tendered >= total) {
                const change = tendered - total;

                Swal.fire({
                    icon: "success",
                    title: "Payment Processed!",
                    html: `
              <div class="text-left space-y-2">
                <div class="flex justify-between"><span>Amount Due:</span><span>$${total.toFixed(2)}</span></div>
                <div class="flex justify-between"><span>Amount Tendered:</span><span>$${tendered.toFixed(2)}</span></div>
                <div class="flex justify-between font-bold text-green-600"><span>Change:</span><span>$${change.toFixed(2)}</span></div>
              </div>
            `,
                    confirmButtonText: "Print Receipt",
                    showCancelButton: true,
                    cancelButtonText: "No Receipt",
                }).then((result) => {
                    cart = [];
                    currentDiscount = null;
                    renderCart();
                    closeCashModal();
                    closeCheckoutModal();

                    if (result.isConfirmed) {
                        Swal.fire({
                            icon: "info",
                            title: "Receipt Printed",
                            text: "Receipt has been sent to printer",
                            toast: true,
                            position: "top-end",
                            showConfirmButton: false,
                            timer: 2000,
                        });
                    }
                });
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Insufficient Amount",
                    text: `Please enter at least $${total.toFixed(2)}`,
                    confirmButtonColor: "#3085d6",
                });
            }
        }

        // Theme Toggle
        const themeToggle = document.getElementById("themeToggle");
        const body = document.body;

        themeToggle.addEventListener("click", () => {
            if (body.classList.contains("bg-gray-900")) {
                body.classList.remove("bg-gray-900");
                body.classList.add("bg-white");
                themeToggle.innerHTML = '<i class="fas fa-sun text-xl"></i>';
            } else {
                body.classList.remove("bg-white");
                body.classList.add("bg-gray-900");
                themeToggle.innerHTML = '<i class="fas fa-moon text-xl"></i>';
            }
        });

        // Start the app
        init();
    </script>
</body>

</html>
