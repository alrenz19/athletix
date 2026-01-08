<!-- Sidebar -->
<aside id="sidebar" class="w-28 bg-[#8C2C08] min-h-screen flex flex-col items-center py-6 fixed md:static md:translate-x-0 transform -translate-x-full transition-transform duration-300 z-50">
  <!-- Logo -->
  <div class="mb-8">
    <img 
      src="https://c.animaapp.com/meod3nrskPlg16/img/logo.png" 
      alt="Organization logo" 
      class="w-16 h-16 object-contain"
    />
  </div>
  
  <!-- Navigation Icons -->
  <nav class="flex flex-col space-y-2 w-full px-2" role="navigation" aria-label="Main navigation">

    <!-- Dashboard -->
    <div class="relative group">
      <a href="{{ route('coach.dashboard.index') }}" class="w-12 h-12 mx-auto text-white hover:bg-[#3E1F0A] transition-colors flex items-center justify-center">
        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
          <rect x="3" y="3" width="8" height="8" rx="1"/>
          <rect x="13" y="3" width="8" height="8" rx="1"/>
          <rect x="3" y="13" width="8" height="8" rx="1"/>
          <rect x="13" y="13" width="8" height="8" rx="1"/>
        </svg>
        @if (request()->routeIs('coach.dashboard.index'))
          <div class="absolute right-[-8px] top-0 h-full w-1 bg-white rounded-l"></div>
        @endif
        </a>
      <span class="absolute left-14 top-1/2 -translate-y-1/2 whitespace-nowrap bg-black text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition">Dashboard</span>
    </div>

    <!-- Announcements -->
    <div class="relative group">
      <a href="{{ route('announcements.index') }}" class="w-12 h-12 mx-auto text-white hover:bg-[#3E1F0A] transition-colors flex items-center justify-center">
        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
          <path d="M4 10v4c0 1.1.9 2 2 2h2l4 5V5L8 9H6c-1.1 0-2 .9-2 2zm10-5v14l7-5V10l-7-5z"/>
        </svg>
        @if (request()->routeIs('announcements.index'))
          <div class="absolute right-[-8px] top-0 h-full w-1 bg-white rounded-l"></div>
        @endif
      </a>
      <span class="absolute left-14 top-1/2 -translate-y-1/2 whitespace-nowrap bg-black text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition">Announcements</span>
    </div>

    <!-- Manage Athlete -->
    <div class="relative group">
      <a href="{{ route('coach.athletes.index') }}" class="w-12 h-12 mx-auto text-white hover:bg-[#3E1F0A] transition-colors flex items-center justify-center">
        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
          <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
        </svg>
        @if (request()->routeIs('coach.athletes.index'))
          <div class="absolute right-[-8px] top-0 h-full w-1 bg-white rounded-l"></div>
        @endif
      </a>
      <span class="absolute left-14 top-1/2 -translate-y-1/2 whitespace-nowrap bg-black text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition">Manage Athletes</span>
    </div>

    <!-- Events -->
    <div class="relative group">
      <a href="{{ route('events') }}" class="w-12 h-12 mx-auto text-white hover:bg-[#3E1F0A] transition-colors flex items-center justify-center">
        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
          <path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11zM7 10h5v5H7z"/>
        </svg>
        @if (request()->routeIs('events'))
          <div class="absolute right-[-8px] top-0 h-full w-1 bg-white rounded-l"></div>
        @endif
      </a>
      <span class="absolute left-14 top-1/2 -translate-y-1/2 whitespace-nowrap bg-black text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition">Events</span>
    </div>

    <!-- Attendance Monitor -->
    <div class="relative group">
      <a href="{{ route('coach.attendance.index') }}" class="w-12 h-12 mx-auto text-white hover:bg-[#3E1F0A] transition-colors flex items-center justify-center">
        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
            <path d="M16 6l2.29 2.29-4.88 4.88-4-4L2 16.59 3.41 18l6-6 4 4 6.3-6.29L22 12V6z"/>
        </svg>
        @if (request()->routeIs('coach.attendance.index'))
          <div class="absolute right-[-8px] top-0 h-full w-1 bg-white rounded-l"></div>
        @endif
      </a>
      <span class="absolute left-14 top-1/2 -translate-y-1/2 whitespace-nowrap bg-black text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition">Manage Attendance</span>
    </div>

    <!-- Registration Approval -->
    <div class="relative group">
        <a href="{{ route('coach.events.registrations', ['sport' => 'all']) }}"
          class="w-12 h-12 mx-auto text-white hover:bg-[#4A230F] transition-colors flex items-center justify-center rounded">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 12l8-5H4l8 5zm0 2l-8-5v10h16v-10l-8 5z"/>
            </svg>
            @if(request()->routeIs('coach.events.registrations'))
                <div class="absolute right-[-8px] top-0 h-full w-1 bg-white rounded-l"></div>
            @endif
        </a>
        <span class="absolute left-14 top-1/2 -translate-y-1/2 whitespace-nowrap bg-black text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition">Registrations</span>
    </div>
  
    <!-- Awards -->
    <div class="relative group">
      <a href="{{ route('coach.awards.index') }}" 
        class="w-12 h-12 mx-auto text-white hover:bg-[#3E1F0A] transition-colors flex items-center justify-center">
        <!-- Trophy icon -->
        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
          <path d="M17 3H7v2H4v2c0 2.76 2.24 5 5 5 .35 0 .69-.04 1.02-.1A7.002 7.002 0 0012 21a7.002 7.002 0 001.98-9.1c.33.06.67.1 1.02.1 2.76 0 5-2.24 5-5V5h-3V3zm-5 16c-1.66 0-3-1.34-3-3h6c0 1.66-1.34 3-3 3z"/>
        </svg>
        @if (request()->routeIs('coach.awards.*'))
          <div class="absolute right-[-8px] top-0 h-full w-1 bg-white rounded-l"></div>
        @endif
      </a>
      <span class="absolute left-14 top-1/2 -translate-y-1/2 whitespace-nowrap bg-black text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition">
        Awards
      </span>
    </div>


<!-- Reports -->
<div id="reportsDropdown" class="relative">
  <button onclick="toggleReportsDropdown()" 
          class="w-12 h-12 mx-auto text-white hover:bg-[#3E1F0A] transition-colors flex items-center justify-center focus:outline-none relative">
    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
      <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z"/>
    </svg>
    @if (request()->routeIs('coach.reports.performance') || request()->routeIs('coach.reports.attendance'))
      <div class="absolute right-[-8px] top-0 h-full w-1 bg-white rounded-l"></div>
    @endif
  </button>

  <!-- Dropdown -->
  <div id="reportsMenu" 
       class="hidden absolute left-14 top-1/2 -translate-y-1/2 bg-[#1E1E1E] text-white text-sm rounded-lg shadow-lg w-40 transition-all duration-200 ease-in-out">
    <ul class="py-2">
      <li>
        <a href="{{ route('coach.reports.performance') }}" 
           class="block px-4 py-2 hover:bg-[#3E1F0A] rounded-md transition {{ request()->routeIs('coach.reports.performance') ? 'bg-[#3E1F0A]' : '' }}">
          📊 Performance
        </a>
      </li>
      <li>
        <a href="{{ route('coach.reports.attendance') }}" 
           class="block px-4 py-2 hover:bg-[#3E1F0A] rounded-md transition {{ request()->routeIs('coach.reports.attendance') ? 'bg-[#3E1F0A]' : '' }}">
          📝 Attendance
        </a>
      </li>
    </ul>
  </div>
</div>

<!-- Notifications -->
<div class="relative group">
  <a href="{{ route('coach.notifications.index') }}" class="w-12 h-12 mx-auto text-white hover:bg-[#3E1F0A] transition-colors flex items-center justify-center">
    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
      <path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.9 2 2 2zm6-6v-5c0-3.07-1.64-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.63 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z"/>
    </svg>
    @if (request()->routeIs('coach.notifications.index'))
      <div class="absolute right-[-8px] top-0 h-full w-1 bg-white rounded-l"></div>
    @endif
  </a>
  <span class="absolute left-14 top-1/2 -translate-y-1/2 whitespace-nowrap bg-black text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition">Notifications</span>
</div>

  <!-- Settings -->

  <div class="relative group">
    <a href="{{ route('coach.profile.edit') }}" 
      class="w-12 h-12 mx-auto text-white hover:bg-[#3E1F0A] transition-colors flex items-center justify-center">
      <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
        <path d="M19.14,12.94c0.04-0.3,0.06-0.61,0.06-0.94c0-0.32-0.02-0.64-0.07-0.94l2.03-1.58c0.18-0.14,0.23-0.41,0.12-0.61 l-1.92-3.32c-0.12-0.22-0.37-0.29-0.59-0.22l-2.39,0.96c-0.5-0.38-1.03-0.7-1.62-0.94L14.4,2.81c-0.04-0.24-0.24-0.41-0.48-0.41 h-3.84c-0.24,0-0.43,0.17-0.47,0.41L9.25,5.35C8.66,5.59,8.12,5.92,7.63,6.29L5.24,5.33c-0.22-0.08-0.47,0-0.59,0.22L2.74,8.87 C2.62,9.08,2.66,9.34,2.86,9.48l2.03,1.58C4.84,11.36,4.8,11.69,4.8,12s0.02,0.64,0.07,0.94l-2.03,1.58 c-0.18,0.14-0.23,0.41-0.12,0.61l1.92,3.32c0.12,0.22,0.37,0.29,0.59,0.22l2.39-0.96c0.5,0.38,1.03,0.7,1.62,0.94l0.36,2.54 c0.05,0.24,0.24,0.41,0.48,0.41h3.84c0.24,0,0.44-0.17,0.47-0.41l0.36-2.54c0.59-0.24,1.13-0.56,1.62-0.94l2.39,0.96 c0.22,0.08,0.47,0,0.59-0.22l1.92-3.32c0.12-0.22,0.07-0.47-0.12-0.61L19.14,12.94z M12,15.6c-1.98,0-3.6-1.62-3.6-3.6 s1.62-3.6,3.6-3.6s3.6,1.62,3.6,3.6S13.98,15.6,12,15.6z"/>
      </svg>
      @if (request()->routeIs('coach.profile.edit'))
          <div class="absolute right-[-8px] top-0 h-full w-1 bg-white rounded-l"></div>
      @endif
    </a>
    <span class="absolute left-14 top-1/2 -translate-y-1/2 whitespace-nowrap bg-black text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition">Settings</span>
  </div>
  

  <!-- Logout -->
  <div class="relative group">
      <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="w-12 h-12 mx-auto text-white hover:bg-[#3E1F0A] transition-colors flex items-center justify-center">
          <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
              <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
              <polyline points="16,17 21,12 16,7"/>
              <line x1="21" y1="12" x2="9" y2="12"/>
          </svg>
      </a>
      <span class="absolute left-14 top-1/2 -translate-y-1/2 whitespace-nowrap bg-black text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition">Logout</span>

      <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
          @csrf
      </form>
  </div>

  </nav>
</aside>

<!-- Mobile Toggle Button -->
<button id="menu-toggle" class="absolute top-4 left-4 z-50 md:hidden bg-[#5C2E0E] text-white p-2 rounded">
  ☰
</button>

<script>
  // Mobile sidebar toggle
  document.addEventListener("DOMContentLoaded", () => {
    const sidebar = document.getElementById("sidebar");
    const toggle = document.getElementById("menu-toggle");

    toggle.addEventListener("click", () => {
      sidebar.classList.toggle("-translate-x-full");
    });
  });
</script>


<script>
function toggleReportsDropdown() {
  const menu = document.getElementById('reportsMenu');
  menu.classList.toggle('hidden');
}
document.addEventListener('click', function(event) {
  const dropdown = document.getElementById('reportsDropdown');
  if (!dropdown.contains(event.target)) {
    document.getElementById('reportsMenu').classList.add('hidden');
  }
});
</script>

<script>
function toggleReportsDropdown() {
  let menu = document.getElementById('reportsMenu');
  menu.classList.toggle('hidden');
}
</script>
