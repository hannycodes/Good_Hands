document.addEventListener('DOMContentLoaded', () => {
    // 1. Initialize Impact Chart
    const ctx = document.getElementById('impactChart').getContext('2d');
    
    // Create Gradient for the line
    const gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(79, 209, 181, 0.4)');
    gradient.addColorStop(1, 'rgba(79, 209, 181, 0)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
                label: 'Donations ($)',
                data: [50, 150, 100, 300, 250, 420], // This will come from PHP
                borderColor: '#4FD1B5',
                borderWidth: 3,
                fill: true,
                backgroundColor: gradient,
                tension: 0.4, // Smooth curves
                pointRadius: 4,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#4FD1B5',
                pointBorderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { display: false },
                    ticks: { color: '#64748b' }
                },
                x: {
                    grid: { display: false },
                    ticks: { color: '#64748b' }
                }
            }
        }
    });
});
