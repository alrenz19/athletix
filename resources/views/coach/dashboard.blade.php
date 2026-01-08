@extends('layouts.app')
@section('title', 'Coach Dashboard')

@section('content')
<!-- =======================
     Metric Cards
======================= -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <!-- Athletes -->
    <a href="{{ url('/coach/athletes') }}" class="block">
        <div class="bg-green-500 rounded-lg p-6 text-white shadow-lg hover:scale-105 transition">
            <div class="flex items-center justify-center mb-4">
                <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.9 2 2 2zm6-6v-5c0-3.07-1.64-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.63 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z"/>
                    </svg>
                </div>
            </div>
            <h3 class="text-xl font-bold text-center mb-2">Athletes</h3>
            <p class="text-3xl font-bold text-center">{{ $athletesCount }}</p>
        </div>
    </a>

    <!-- Performance Notes -->
    <a href="{{ url('/coach/reports/performance') }}" class="block">
        <div class="bg-yellow-500 rounded-lg p-6 text-white shadow-lg hover:scale-105 transition">
            <div class="flex items-center justify-center mb-4">
                <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M16 6l2.29 2.29-4.88 4.88-4-4L2 16.59 3.41 18l6-6 4 4 6.3-6.29L22 12V6z"/>
                    </svg>
                </div>
            </div>
            <h3 class="text-xl font-bold text-center mb-2">Performance Notes</h3>
            <p class="text-3xl font-bold text-center">{{ $performanceCount }}</p>
        </div>
    </a>

    <!-- Events -->
    <a href="{{ url('/events') }}" class="block">
        <div class="bg-blue-500 rounded-lg p-6 text-white shadow-lg hover:scale-105 transition">
            <div class="flex items-center justify-center mb-4">
                <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11zM7 10h5v5H7z"/>
                    </svg>
                </div>
            </div>
            <h3 class="text-xl font-bold text-center mb-2">Events</h3>
            <p class="text-3xl font-bold text-center">{{ $eventsCount }}</p>
        </div>
    </a>

    <!-- Attendance -->
    <a href="{{ url('/coach/reports/attendance') }}" class="block">
        <div class="bg-red-500 rounded-lg p-6 text-white shadow-lg hover:scale-105 transition">
            <div class="flex items-center justify-center mb-4">
                <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <h3 class="text-xl font-bold text-center mb-2">Attendance</h3>
            <p class="text-3xl font-bold text-center">{{ $attendanceCount }}</p>
        </div>
    </a>
</div>

<!-- =======================
     Charts
======================= -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Donut Chart -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <canvas id="donutChart"></canvas>
    </div>

    <!-- Bar Chart -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <canvas id="barChart"></canvas>
    </div>
</div>

<!-- =======================
     Chart Scripts
======================= -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
/* ===== Plugin: Percentages inside chart (Hide 0 values) ===== */
const insidePercentagePlugin = {
    id: 'insidePercentagePlugin',
    afterDatasetsDraw(chart) {
        const { ctx } = chart;
        const dataset = chart.data.datasets[0];
        const total = dataset.data.reduce((a, b) => a + b, 0);

        chart.getDatasetMeta(0).data.forEach((el, i) => {
            if (dataset.data[i] === 0) return; // Hide zero values

            const percent = total ? ((dataset.data[i] / total) * 100).toFixed(1) + '%' : '0%';
            let x, y;

            if (chart.config.type === 'bar') {
                x = el.x;
                y = el.y + (el.base - el.y) / 2;
            } else {
                const pos = el.tooltipPosition();
                x = pos.x;
                y = pos.y;
            }

            ctx.save();
            ctx.fillStyle = '#ffffff';
            ctx.font = 'bold 12px sans-serif';
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';
            ctx.fillText(percent, x, y);
            ctx.restore();
        });
    }
};

/* ===== Donut Chart ===== */
new Chart(document.getElementById('donutChart'), {
    type: 'doughnut',
    data: {
        labels: ['Athletes', 'Performance', 'Events', 'Attendance'],
        datasets: [{
            data: [
                {{ $donutData['athletes'] ?? 0 }},
                {{ $donutData['performance'] ?? 0 }},
                {{ $donutData['events'] ?? 0 }},
                {{ $donutData['attendance'] ?? 0 }}
            ],
            backgroundColor: ['#10B981','#F59E0B','#3B82F6','#EF4444'],
            cutout: '60%',
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } }
    },
    plugins: [insidePercentagePlugin]
});

/* ===== Bar Chart ===== */
new Chart(document.getElementById('barChart'), {
    type: 'bar',
    data: {
        labels: ['Athletes', 'Performance', 'Events', 'Attendance'],
        datasets: [{
            data: [
                {{ $barData['athletes'] ?? 0 }},
                {{ $barData['performance'] ?? 0 }},
                {{ $barData['events'] ?? 0 }},
                {{ $barData['attendance'] ?? 0 }}
            ],
            backgroundColor: ['#10B981','#F59E0B','#3B82F6','#EF4444'],
            borderRadius: 8,
            borderSkipped: false
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: { beginAtZero: true },
            x: { grid: { display: false } }
        },
        plugins: { legend: { display: false } }
    },
    plugins: [insidePercentagePlugin]
});
</script>
@endsection
