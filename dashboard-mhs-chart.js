document.addEventListener("DOMContentLoaded", function () {
    const chartCanvas = document.getElementById('scholarhubMhsAkumulasiChart');
    if (!chartCanvas) return;

    const ctx = chartCanvas.getContext('2d');

    const totalPengajuan = parseInt(chartCanvas.getAttribute('data-total-pengajuan')) || 0;
    const totalDiterima  = parseInt(chartCanvas.getAttribute('data-total-diterima')) || 0;
    const totalDitolak   = parseInt(chartCanvas.getAttribute('data-total-ditolak')) || 0;

    const gradBiru = ctx.createLinearGradient(0, 0, 0, 240);
    gradBiru.addColorStop(0, '#2575fc'); 
    gradBiru.addColorStop(1, '#6a11cb');

    const gradHijau = ctx.createLinearGradient(0, 0, 0, 240);
    gradHijau.addColorStop(0, '#11998e'); 
    gradHijau.addColorStop(1, '#38ef7d');

    const gradMerah = ctx.createLinearGradient(0, 0, 0, 240);
    gradMerah.addColorStop(0, '#ff416c'); 
    gradMerah.addColorStop(1, '#ff4b2b');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Total Pengajuan', 'Diterima / Lulus', 'Ditolak'],
            datasets: [{
                label: 'Jumlah Berkas',
                data: [totalPengajuan, totalDiterima, totalDitolak],
                backgroundColor: [gradBiru, gradHijau, gradMerah],
                borderColor: ['#6a11cb', '#38ef7d', '#ff4b2b'],
                borderWidth: 1,
                borderRadius: 6,
                barPercentage: 0.5,
                categoryPercentage: 0.6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { 
                    display: false
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
                        stepSize: 1,
                        font: { family: 'Inter', size: 12 }
                    }
                }
            }
        }
    });
});