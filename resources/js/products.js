document.addEventListener("DOMContentLoaded", () => {
    // Theme toggle
    document.getElementById("themeToggle").addEventListener("click", () => {
        const body = document.body;
        const themeToggle = document.getElementById("themeToggle");

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

    // Modal functionality - simple approach
    document.addEventListener("click", function (e) {
        const modal = document.getElementById("addProductModal");

        // Open modal
        if (
            e.target.closest("button") &&
            e.target.closest("button").textContent.includes("Add New Product")
        ) {
            modal.classList.remove("hidden");
            document.body.style.overflow = "hidden";
        }

        // Close modal
        if (
            (e.target.closest("button") &&
                e.target.closest("button").textContent.includes("Cancel")) ||
            (e.target.closest("button") &&
                e.target.closest("button").querySelector(".fa-times")) ||
            e.target === modal
        ) {
            modal.classList.add("hidden");
            document.body.style.overflow = "";
        }
    });

    // Close modal with Escape key
    document.addEventListener("keydown", function (e) {
        if (e.key === "Escape") {
            document.getElementById("addProductModal").classList.add("hidden");
            document.body.style.overflow = "";
        }
    });
});
