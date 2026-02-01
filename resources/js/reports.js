import Chart from 'chart.js/auto';

document.addEventListener("DOMContentLoaded", () => {
    // Theme toggle
    document.getElementById("themeToggle").addEventListener("click", () => {
        const body = document.body;
        const themeToggle = document.getElementById("themeToggle");

        if (body.classList.contains("bg-gray-900")) {
            body.classList.remove("bg-gray-900");
            body.classList.add("bg-white");
            themeToggle.innerHTML = '<i class="fas fa-sun text-xl"></i>';
            // Update charts for light theme
            updateChartsForLightTheme();
        } else {
            body.classList.remove("bg-white");
            body.classList.add("bg-gray-900");
            themeToggle.innerHTML = '<i class="fas fa-moon text-xl"></i>';
            // Update charts for dark theme
            updateChartsForDarkTheme();
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

    // Chart initialization
    initializeCharts(); 

    function initializeCharts() {
        // Revenue Trend Chart (Line Chart)
        const revenueCtx = document.createElement("canvas");
        revenueCtx.id = "revenueChart";
        const revenueChartContainer = document.querySelector(
            ".bg-slate-800\\/50.border .h-80",
        );
        if (revenueChartContainer) {
            revenueChartContainer.innerHTML = "";
            revenueChartContainer.appendChild(revenueCtx);

            new Chart(revenueCtx, {
                type: "line",
                data: {
                    labels: ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"],
                    datasets: [
                        {
                            label: "Current Week",
                            data: [1200, 1900, 1500, 2200, 1800, 2500, 2000],
                            borderColor: "#10b981",
                            backgroundColor: "rgba(16, 185, 129, 0.1)",
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                        },
                        {
                            label: "Previous Week",
                            data: [1000, 1700, 1300, 2000, 1600, 2300, 1800],
                            borderColor: "#6b7280",
                            backgroundColor: "rgba(107, 114, 128, 0.1)",
                            borderWidth: 2,
                            fill: true,
                            tension: 0.4,
                            borderDash: [5, 5],
                        },
                    ],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: "top",
                            labels: {
                                color: "#d1d5db",
                                font: {
                                    size: 12,
                                },
                            },
                        },
                        tooltip: {
                            mode: "index",
                            intersect: false,
                            backgroundColor: "rgba(15, 23, 42, 0.9)",
                            titleColor: "#d1d5db",
                            bodyColor: "#d1d5db",
                            borderColor: "#374151",
                            borderWidth: 1,
                        },
                    },
                    scales: {
                        x: {
                            grid: {
                                color: "rgba(100, 116, 139, 0.2)",
                            },
                            ticks: {
                                color: "#9ca3af",
                            },
                        },
                        y: {
                            grid: {
                                color: "rgba(100, 116, 139, 0.2)",
                            },
                            ticks: {
                                color: "#9ca3af",
                                callback: function (value) {
                                    return "$" + value;
                                },
                            },
                        },
                    },
                },
            });
        }

        // Category Performance Chart (Doughnut)
        const categoryCtx = document.createElement("canvas");
        categoryCtx.id = "categoryChart";
        const categoryChartContainer = document.querySelectorAll(
            ".bg-slate-800\\/50.border .p-6",
        )[3];
        if (categoryChartContainer) {
            categoryChartContainer.innerHTML = "";
            categoryChartContainer.appendChild(categoryCtx);

            new Chart(categoryCtx, {
                type: "doughnut",
                data: {
                    labels: [
                        "Beverages",
                        "Food Items",
                        "Snacks",
                        "Desserts",
                        "Others",
                    ],
                    datasets: [
                        {
                            data: [45, 35, 12, 6, 2],
                            backgroundColor: [
                                "#10b981",
                                "#06b6d4",
                                "#8b5cf6",
                                "#f59e0b",
                                "#6b7280",
                            ],
                            borderColor: [
                                "#0d966c",
                                "#0891b2",
                                "#7c3aed",
                                "#d97706",
                                "#4b5563",
                            ],
                            borderWidth: 2,
                        },
                    ],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: "bottom",
                            labels: {
                                color: "#d1d5db",
                                font: {
                                    size: 11,
                                },
                                padding: 15,
                            },
                        },
                        tooltip: {
                            backgroundColor: "rgba(15, 23, 42, 0.9)",
                            titleColor: "#d1d5db",
                            bodyColor: "#d1d5db",
                            borderColor: "#374151",
                            borderWidth: 1,
                            callbacks: {
                                label: function (context) {
                                    return (
                                        context.label +
                                        ": " +
                                        context.parsed +
                                        "%"
                                    );
                                },
                            },
                        },
                    },
                    cutout: "65%",
                },
            });
        }

        // Sales Performance Chart (Bar Chart)
        const salesCtx = document.createElement("canvas");
        salesCtx.id = "salesChart";
        const salesChartContainer = document.querySelectorAll(
            ".bg-slate-800\\/50.border .p-6",
        )[5];
        if (salesChartContainer) {
            salesChartContainer.innerHTML = "";
            salesChartContainer.appendChild(salesCtx);

            new Chart(salesCtx, {
                type: "bar",
                data: {
                    labels: [
                        "Week 46",
                        "Week 47",
                        "Week 48",
                        "Week 49",
                        "Week 50",
                    ],
                    datasets: [
                        {
                            label: "Revenue",
                            data: [4803, 5196, 5847, 5200, 6100],
                            backgroundColor: [
                                "rgba(16, 185, 129, 0.7)",
                                "rgba(16, 185, 129, 0.7)",
                                "rgba(16, 185, 129, 0.9)",
                                "rgba(16, 185, 129, 0.7)",
                                "rgba(16, 185, 129, 0.7)",
                            ],
                            borderColor: [
                                "#10b981",
                                "#10b981",
                                "#10b981",
                                "#10b981",
                                "#10b981",
                            ],
                            borderWidth: 1,
                        },
                    ],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false,
                        },
                        tooltip: {
                            backgroundColor: "rgba(15, 23, 42, 0.9)",
                            titleColor: "#d1d5db",
                            bodyColor: "#d1d5db",
                            borderColor: "#374151",
                            borderWidth: 1,
                            callbacks: {
                                label: function (context) {
                                    return "Revenue: $" + context.parsed.y;
                                },
                            },
                        },
                    },
                    scales: {
                        x: {
                            grid: {
                                color: "rgba(100, 116, 139, 0.2)",
                            },
                            ticks: {
                                color: "#9ca3af",
                            },
                        },
                        y: {
                            grid: {
                                color: "rgba(100, 116, 139, 0.2)",
                            },
                            ticks: {
                                color: "#9ca3af",
                                callback: function (value) {
                                    return "$" + value;
                                },
                            },
                        },
                    },
                },
            });
        }

        // Customer Metrics Chart (Radar Chart)
        const customerCtx = document.createElement("canvas");
        customerCtx.id = "customerChart";
        const customerChartContainer = document.querySelectorAll(
            ".bg-slate-800\\/50.border .p-6",
        )[7];
        if (customerChartContainer) {
            customerChartContainer.innerHTML = "";
            customerChartContainer.appendChild(customerCtx);

            new Chart(customerCtx, {
                type: "radar",
                data: {
                    labels: [
                        "Satisfaction",
                        "Retention",
                        "Frequency",
                        "Spending",
                        "Engagement",
                    ],
                    datasets: [
                        {
                            label: "Current Month",
                            data: [92, 78, 85, 88, 76],
                            backgroundColor: "rgba(6, 182, 212, 0.2)",
                            borderColor: "#06b6d4",
                            borderWidth: 2,
                            pointBackgroundColor: "#06b6d4",
                            pointBorderColor: "#fff",
                            pointHoverBackgroundColor: "#fff",
                            pointHoverBorderColor: "#06b6d4",
                        },
                        {
                            label: "Previous Month",
                            data: [85, 72, 80, 82, 70],
                            backgroundColor: "rgba(100, 116, 139, 0.2)",
                            borderColor: "#64748b",
                            borderWidth: 2,
                            pointBackgroundColor: "#64748b",
                            pointBorderColor: "#fff",
                            pointHoverBackgroundColor: "#fff",
                            pointHoverBorderColor: "#64748b",
                        },
                    ],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: "top",
                            labels: {
                                color: "#d1d5db",
                                font: {
                                    size: 11,
                                },
                            },
                        },
                        tooltip: {
                            backgroundColor: "rgba(15, 23, 42, 0.9)",
                            titleColor: "#d1d5db",
                            bodyColor: "#d1d5db",
                            borderColor: "#374151",
                            borderWidth: 1,
                        },
                    },
                    scales: {
                        r: {
                            angleLines: {
                                color: "rgba(100, 116, 139, 0.3)",
                            },
                            grid: {
                                color: "rgba(100, 116, 139, 0.2)",
                            },
                            pointLabels: {
                                color: "#9ca3af",
                                font: {
                                    size: 11,
                                },
                            },
                            ticks: {
                                color: "#9ca3af",
                                backdropColor: "transparent",
                            },
                            min: 0,
                            max: 100,
                        },
                    },
                },
            });
        }
    }

    function updateChartsForDarkTheme() {
        // Update all charts for dark theme
        Chart.helpers.each(Chart.instances, function (chart) {
            chart.options.scales.x.ticks.color = "#9ca3af";
            chart.options.scales.x.grid.color = "rgba(100, 116, 139, 0.2)";
            chart.options.scales.y.ticks.color = "#9ca3af";
            chart.options.scales.y.grid.color = "rgba(100, 116, 139, 0.2)";
            if (chart.options.plugins.legend) {
                chart.options.plugins.legend.labels.color = "#d1d5db";
            }
            if (chart.options.plugins.tooltip) {
                chart.options.plugins.tooltip.backgroundColor =
                    "rgba(15, 23, 42, 0.9)";
                chart.options.plugins.tooltip.titleColor = "#d1d5db";
                chart.options.plugins.tooltip.bodyColor = "#d1d5db";
            }
            chart.update();
        });
    }

    function updateChartsForLightTheme() {
        // Update all charts for light theme
        Chart.helpers.each(Chart.instances, function (chart) {
            chart.options.scales.x.ticks.color = "#6b7280";
            chart.options.scales.x.grid.color = "rgba(209, 213, 219, 0.8)";
            chart.options.scales.y.ticks.color = "#6b7280";
            chart.options.scales.y.grid.color = "rgba(209, 213, 219, 0.8)";
            if (chart.options.plugins.legend) {
                chart.options.plugins.legend.labels.color = "#374151";
            }
            if (chart.options.plugins.tooltip) {
                chart.options.plugins.tooltip.backgroundColor =
                    "rgba(255, 255, 255, 0.9)";
                chart.options.plugins.tooltip.titleColor = "#1f2937";
                chart.options.plugins.tooltip.bodyColor = "#1f2937";
            }
            chart.update();
        });
    }

    // Refresh charts when refresh button is clicked
    document.addEventListener("click", function (e) {
        if (
            e.target.closest("button") &&
            e.target.closest("button").textContent.includes("Refresh Data")
        ) {
            // Simulate data refresh by reinitializing charts with slightly different data
            setTimeout(() => {
                initializeCharts();
            }, 500);
        }
    });
});
