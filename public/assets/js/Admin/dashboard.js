document.addEventListener("DOMContentLoaded", () => {
    // Sales Chart
    const salesChartEl = document.getElementById("salesChart");
    if (salesChartEl) {
        const salesLabels = JSON.parse(salesChartEl.dataset.labels || "[]");
        const salesData = JSON.parse(salesChartEl.dataset.values || "[]");

        new Chart(salesChartEl, {
            type: "line",
            data: {
                labels: salesLabels,
                datasets: [{
                    label: "Bookings",
                    data: salesData,
                    borderColor: "rgba(75, 192, 192, 1)",
                    backgroundColor: "rgba(75, 192, 192, 0.2)",
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: true } },
                scales: {
                    x: { title: { display: true, text: "Date" } },
                    y: { title: { display: true, text: "Bookings" }, beginAtZero: true }
                }
            }
        });
    }

    // Brand Chart
    const brandChartEl = document.getElementById("BrandChart");
    if (brandChartEl) {
        const brandLabels = JSON.parse(brandChartEl.dataset.labels || "[]");
        const brandData = JSON.parse(brandChartEl.dataset.values || "[]");

        new Chart(brandChartEl, {
            type: "pie",
            data: {
                labels: brandLabels,
                datasets: [{
                    label: "Bookings by Brand",
                    data: brandData,
                    backgroundColor: [
                        "rgba(255, 99, 132, 0.6)",
                        "rgba(54, 162, 235, 0.6)",
                        "rgba(255, 206, 86, 0.6)",
                        "rgba(75, 192, 192, 0.6)",
                        "rgba(153, 102, 255, 0.6)"
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { position: "bottom" } }
            }
        });
    }
});