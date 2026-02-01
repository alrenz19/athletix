@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<!-- =======================
     Charts Section
======================= -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">

  <!-- ========= Donut Chart ========= -->
  <div class="bg-white rounded-lg shadow-lg p-6">
    <div class="flex items-center justify-between mb-6">

      <div class="w-80 h-80">
        <canvas id="donutChart"></canvas>
      </div>

      <!-- Donut Legend -->
      <div class="ml-8 space-y-4 text-sm">
        <div class="flex justify-between gap-6">
          <span><span class="w-3 h-3 bg-green-500 inline-block mr-2 rounded-full"></span>Athletes</span>
          <span class="font-semibold">{{ $donutData['athletes'] }}</span>
        </div>
        <div class="flex justify-between gap-6">
          <span><span class="w-3 h-3 bg-yellow-500 inline-block mr-2 rounded-full"></span>announce..</span>
          <span class="font-semibold">{{ $donutData['announcement'] }}</span>
        </div>
        <div class="flex justify-between gap-6">
          <span><span class="w-3 h-3 bg-blue-500 inline-block mr-2 rounded-full"></span>Events</span>
          <span class="font-semibold">{{ $donutData['events'] }}</span>
        </div>
        <div class="flex justify-between gap-6">
          <span><span class="w-3 h-3 bg-red-500 inline-block mr-2 rounded-full"></span>Pending</span>
          <span class="font-semibold">{{ $donutData['pendings'] }}</span>
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

  <a href="{{ url('/staff/athletes') }}">
    <div class="bg-green-500 p-6 rounded-lg text-white shadow-lg hover:scale-105 transition">
      <h3 class="text-center font-bold">ATHLETES</h3>
      <p class="text-3xl text-center font-bold">{{ $athleteCount }}</p>
    </div>
  </a>

  <a href="{{ url('/announcements') }}">
    <div class="bg-yellow-500 p-6 rounded-lg text-white shadow-lg hover:scale-105 transition">
      <h3 class="text-center font-bold">ANNOUNCE..</h3>
      <p class="text-3xl text-center font-bold">{{ $notificationsCount }}</p>
    </div>
  </a>

  <a href="{{ url('/events') }}">
    <div class="bg-blue-500 p-6 rounded-lg text-white shadow-lg hover:scale-105 transition">
      <h3 class="text-center font-bold">EVENTS</h3>
      <p class="text-3xl text-center font-bold">{{ $eventsCount }}</p>
    </div>
  </a>

  <a href="{{ url('/staff/registration-approval') }}">
    <div class="bg-red-500 p-6 rounded-lg text-white shadow-lg hover:scale-105 transition">
      <h3 class="text-center font-bold">PENDING</h3>
      <p class="text-3xl text-center font-bold">{{ $PendingRegistrationCount }}</p>
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

    // If total is 0, don't show any percentages
    if (total === 0) return;

    chart.getDatasetMeta(0).data.forEach((el, i) => {
      const value = dataset.data[i];
      
      // Only show percentage if value is greater than 0
      if (value <= 0) return;
      
      const percent = ((value / total) * 100).toFixed(1) + '%';

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
    labels: ['Athletes', 'Announcement', 'Events', 'Pending Approval'],
    datasets: [{
      data: [
        {{ $donutData['athletes'] }},
        {{ $donutData['announcement'] }},
        {{ $donutData['events'] }},
        {{ $donutData['pendings'] }}
      ],
      backgroundColor: ['#10B981','#F59E0B','#3B82F6','#EF4444'],
      cutout: '60%'
    }]
  },
  options: {
    plugins: { 
      legend: { display: false },
      tooltip: {
        callbacks: {
          label: function(context) {
            const label = context.label || '';
            const value = context.raw || 0;
            const total = context.dataset.data.reduce((a, b) => a + b, 0);
            
            if (total === 0) return `${label}: ${value}`;
            
            const percentage = ((value / total) * 100).toFixed(1);
            return `${label}: ${value} (${percentage}%)`;
          }
        }
      }
    }
  },
  plugins: [insidePercentagePlugin]
});

/* ===== Bar Chart ===== */
new Chart(document.getElementById('barChart'), {
  type: 'bar',
  data: {
    labels: ['Athletes', 'Announcements', 'Events', 'Pending Approval'],
    datasets: [{
      data: [
        {{ $barData['athletes'] }},
        {{ $barData['announcement'] }},
        {{ $barData['events'] }},
        {{ $barData['pendings'] }}
      ],
      backgroundColor: ['#10B981','#F59E0B','#3B82F6','#EF4444'],
      borderRadius: 8
    }]
  },
  options: {
    plugins: { 
      legend: { display: false },
      tooltip: {
        callbacks: {
          label: function(context) {
            const label = context.label || '';
            const value = context.raw || 0;
            const total = context.dataset.data.reduce((a, b) => a + b, 0);
            
            if (total === 0) return `${label}: ${value}`;
            
            const percentage = ((value / total) * 100).toFixed(1);
            return `${label}: ${value} (${percentage}%)`;
          }
        }
      }
    },
    scales: {
      y: { 
        beginAtZero: true,
        ticks: {
          callback: function(value) {
            return value;
          }
        }
      }
    }
  },
  plugins: [insidePercentagePlugin]
});
</script>

@endsection