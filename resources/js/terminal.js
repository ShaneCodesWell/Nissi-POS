document.addEventListener('DOMContentLoaded', () => {
    // Products Data
      const products = [
        { id: 1, name: "Espresso", price: 3.50, category: "beverages", icon: "coffee" },
        { id: 2, name: "Cappuccino", price: 4.50, category: "beverages", icon: "coffee" },
        { id: 3, name: "Latte", price: 4.75, category: "beverages", icon: "coffee" },
        { id: 4, name: "Americano", price: 3.25, category: "beverages", icon: "coffee" },
        { id: 5, name: "Croissant", price: 3.00, category: "food", icon: "bread-slice" },
        { id: 6, name: "Sandwich", price: 7.50, category: "food", icon: "hamburger" },
        { id: 7, name: "Bagel", price: 2.50, category: "food", icon: "bread-slice" },
        { id: 8, name: "Muffin", price: 3.25, category: "snacks", icon: "cookie" },
        { id: 9, name: "Cookie", price: 2.00, category: "snacks", icon: "cookie" },
        { id: 10, name: "Brownie", price: 3.50, category: "desserts", icon: "cookie-bite" },
        { id: 11, name: "Cheesecake", price: 5.50, category: "desserts", icon: "cake-candles" },
        { id: 12, name: "Orange Juice", price: 4.00, category: "beverages", icon: "glass-water" },
        { id: 13, name: "Smoothie", price: 5.50, category: "beverages", icon: "blender" },
        { id: 14, name: "Tea", price: 2.75, category: "beverages", icon: "mug-hot" },
        { id: 15, name: "Donut", price: 2.25, category: "desserts", icon: "cookie" },
        { id: 16, name: "Chips", price: 1.50, category: "snacks", icon: "cookie" },
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
        const filteredProducts = filter === "all" 
          ? products 
          : products.filter(p => p.category === filter);

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
          cart.push({ ...product, quantity: 1 });
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
                document.getElementById("previewOriginal").textContent = `$${subtotal.toFixed(2)}`;
                document.getElementById("previewDiscount").textContent = `-$${discountAmount.toFixed(2)}`;
                document.getElementById("previewNewTotal").textContent = `$${newTotal.toFixed(2)}`;
              } else {
                previewElement.classList.add("hidden");
              }
            };

            discountValueInput.addEventListener("input", updatePreview);
            discountTypeSelect.addEventListener("change", updatePreview);

            discountTypeSelect.addEventListener("change", function () {
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

            return { type, value };
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
              b.classList.remove("bg-emerald-500/20", "border-emerald-500/50", "text-emerald-400");
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
            button.addEventListener("click", function () {
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
        document.getElementById("cashTendered").addEventListener("input", function () {
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
              <div class="w-16 h-16 bg-gradient-to-br from-emerald-500/20 to-cyan-500/20 rounded-xl flex items-center justify-center mb-3 mx-auto">
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

      init();
});
