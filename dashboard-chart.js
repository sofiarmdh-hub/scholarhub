document.addEventListener("DOMContentLoaded", function () {
    const chartCanvas = document.getElementById('scholarhubAnalisisChart');
    if (!chartCanvas) return;

    const ctx = chartCanvas.getContext('2d');

    const p1Pendaftar = parseInt(chartCanvas.getAttribute('data-p1-pendaftar')) || 0;
    const p1Diterima  = parseInt(chartCanvas.getAttribute('data-p1-diterima')) || 0;
    const p1Ditolak   = parseInt(chartCanvas.getAttribute('data-p1-ditolak')) || 0;

    const p2Pendaftar = parseInt(chartCanvas.getAttribute('data-p2-pendaftar')) || 0;
    const p2Diterima  = parseInt(chartCanvas.getAttribute('data-p2-diterima')) || 0;
    const p2Ditolak   = parseInt(chartCanvas.getAttribute('data-p2-ditolak')) || 0;

    const gradBiru = ctx.createLinearGradient(0, 0, 0, 240);
    gradBiru.addColorStop(0, '#2575fc'); gradBiru.addColorStop(1, '#6a11cb');

    const gradHijau = ctx.createLinearGradient(0, 0, 0, 240);
    gradHijau.addColorStop(0, '#11998e'); gradHijau.addColorStop(1, '#38ef7d');

    const gradMerah = ctx.createLinearGradient(0, 0, 0, 240);
    gradMerah.addColorStop(0, '#ff416c'); gradMerah.addColorStop(1, '#ff4b2b');

    const myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Paruh Pertama (Jan - Jun)', 'Paruh Kedua (Jul - Des)'],
            datasets: [
                {
                    label: 'Total Pendaftar',
                    data: [p1Pendaftar, p2Pendaftar],
                    backgroundColor: gradBiru,
                    borderRadius: 6,
                    barPercentage: 0.7,
                    categoryPercentage: 0.6
                },
                {
                    label: 'Diterima',
                    data: [p1Diterima, p2Diterima],  
                    backgroundColor: gradHijau,
                    borderRadius: 6,
                    barPercentage: 0.7,
                    categoryPercentage: 0.6
                },
                {
                    label: 'Ditolak',
                    data: [p1Ditolak, p2Ditolak],    
                    backgroundColor: gradMerah,
                    borderRadius: 6,
                    barPercentage: 0.7,
                    categoryPercentage: 0.6
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    align: 'end',
                    labels: {
                        boxWidth: 10,
                        usePointStyle: true,
                        pointStyle: 'circle',
                        font: { family: 'Inter', size: 12, weight: '500' }
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: {
                        font: { family: 'Inter', size: 13, weight: '600' }
                    }
                },
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(226, 232, 240, 0.6)' },
                    ticks: {
                        font: { family: 'Inter', size: 12 }
                    }
                }
            }
        }
    });
});