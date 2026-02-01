document.addEventListener("DOMContentLoaded", () => {
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
});
