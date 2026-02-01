@extends('layouts.app')
@section('title', 'Coach Dashboard')

@section('content')

<!-- =======================
     Charts Section
======================= -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    <!-- ========= Donut Chart ========= -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex flex-col lg:flex-row items-center justify-between">
            <div class="w-80 h-80">
                <canvas id="donutChart"></canvas>
            </div>
            
            <!-- Donut Legend -->
            <div class="ml-0 lg:ml-8 mt-6 lg:mt-0 space-y-4 text-sm">
                <div class="flex justify-between gap-6">
                    <span><span class="w-3 h-3 bg-green-500 inline-block mr-2 rounded-full"></span>Athletes</span>
                    <span class="font-semibold">{{ $donutData['athletes'] ?? 0 }}</span>
                </div>
                <div class="flex justify-between gap-6">
                    <span><span class="w-3 h-3 bg-yellow-500 inline-block mr-2 rounded-full"></span>Performance</span>
                    <span class="font-semibold">{{ $donutData['performance'] ?? 0 }}</span>
                </div>
                <div class="flex justify-between gap-6">
                    <span><span class="w-3 h-3 bg-blue-500 inline-block mr-2 rounded-full"></span>Events</span>
                    <span class="font-semibold">{{ $donutData['events'] ?? 0 }}</span>
                </div>
                <div class="flex justify-between gap-6">
                    <span><span class="w-3 h-3 bg-red-500 inline-block mr-2 rounded-full"></span>Attendance</span>
                    <span class="font-semibold">{{ $donutData['attendance'] ?? 0 }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- ========= Bar Chart ========= -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="h-80">
            <canvas id="barChart"></canvas>
        </div>
    </div>
</div>

<!-- =======================
     Metric Cards (Clickable)
======================= -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    <!-- Athletes -->
    <a href="{{ url('/coach/athletes') }}">
        <div class="bg-green-500 p-6 rounded-lg text-white shadow-lg hover:scale-105 transition">
            <h3 class="text-center font-bold">ATHLETES</h3>
            <p class="text-3xl text-center font-bold">{{ $athletesCount }}</p>
        </div>
    </a>

    <!-- Performance Notes -->
    <a href="{{ url('/coach/reports/performance') }}">
        <div class="bg-yellow-500 p-6 rounded-lg text-white shadow-lg hover:scale-105 transition">
            <h3 class="text-center font-bold">PERFORMANCE</h3>
            <p class="text-3xl text-center font-bold">{{ $performanceCount }}</p>
        </div>
    </a>

    <!-- Events -->
    <a href="{{ url('/events') }}">
        <div class="bg-blue-500 p-6 rounded-lg text-white shadow-lg hover:scale-105 transition">
            <h3 class="text-center font-bold">EVENTS</h3>
            <p class="text-3xl text-center font-bold">{{ $eventsCount }}</p>
        </div>
    </a>

    <!-- Attendance -->
    <a href="{{ url('/coach/reports/attendance') }}">
        <div class="bg-red-500 p-6 rounded-lg text-white shadow-lg hover:scale-105 transition">
            <h3 class="text-center font-bold">ATTENDANCE</h3>
            <p class="text-3xl text-center font-bold">{{ $attendanceCount }}</p>
        </div>
    </a>
</div>

<!-- =======================
     Chart Scripts
======================= -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
/* ===== Percentage Plugin (FIXED CENTERING) ===== */
const insidePercentagePlugin = {
    id: 'insidePercentagePlugin',
    afterDatasetsDraw(chart) {
        const { ctx } = chart;
        const dataset = chart.data.datasets[0];
        const total = dataset.data.reduce((a, b) => a + b, 0);

        chart.getDatasetMeta(0).data.forEach((el, i) => {
            // Hide zero values
            if (dataset.data[i] === 0) return;

            const percent = total
                ? ((dataset.data[i] / total) * 100).toFixed(1) + '%'
                : '0%';

            let x, y;

            // ✅ BAR CHART — PERFECT CENTER
            if (chart.config.type === 'bar') {
                x = el.x;
                y = el.y + (el.base - el.y) / 2;
            }
            // ✅ DONUT CHART
            else {
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
            hoverBackgroundColor: ['#0DA271','#E68A00','#2C75E0','#D93838'],
            borderColor: '#ffffff',
            borderWidth: 2,
            cutout: '60%',
            borderRadius: 8,
            spacing: 2
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: { 
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        const label = context.label || '';
                        const value = context.raw || 0;
                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                        const percentage = total ? ((value / total) * 100).toFixed(1) + '%' : '0%';
                        return `${label}: ${value} (${percentage})`;
                    }
                }
            }
        },
        animation: {
            animateScale: true,
            animateRotate: true
        }
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
            hoverBackgroundColor: ['#0DA271','#E68A00','#2C75E0','#D93838'],
            borderRadius: 8,
            borderSkipped: false,
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: { 
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return `${context.label}: ${context.raw}`;
                    }
                }
            }
        },
        scales: {
            y: { 
                beginAtZero: true,
                grid: {
                    color: 'rgba(0, 0, 0, 0.05)',
                    drawBorder: false
                },
                ticks: {
                    color: '#6B7280',
                    font: {
                        size: 12
                    }
                }
            },
            x: { 
                grid: { display: false },
                ticks: {
                    color: '#6B7280',
                    font: {
                        size: 12
                    }
                }
            }
        },
        animation: {
            duration: 1000,
            easing: 'easeOutQuart'
        }
    },
    plugins: [insidePercentagePlugin]
});
</script>

<style>
/* Responsive adjustments for the donut chart layout */
@media (max-width: 1024px) {
    .flex-col.lg\:flex-row {
        flex-direction: column;
    }
    
    .ml-0.lg\:ml-8 {
        margin-left: 0;
    }
    
    .mt-6.lg\:mt-0 {
        margin-top: 1.5rem;
    }
    
    .w-80.h-80 {
        width: 280px;
        height: 280px;
        margin: 0 auto;
    }
    
    .h-80 {
        height: 280px;
    }
}

@media (max-width: 640px) {
    .w-80.h-80 {
        width: 240px;
        height: 240px;
    }
    
    .h-80 {
        height: 240px;
    }
    
    .text-3xl {
        font-size: 1.875rem;
        line-height: 2.25rem;
    }
}
</style>
@endsection