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
      <a href="{{ route('staff.dashboard.index') }}" class="w-12 h-12 mx-auto text-white hover:bg-[#3E1F0A] transition-colors flex items-center justify-center">
        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
          <rect x="3" y="3" width="8" height="8" rx="1"/>
          <rect x="13" y="3" width="8" height="8" rx="1"/>
          <rect x="3" y="13" width="8" height="8" rx="1"/>
          <rect x="13" y="13" width="8" height="8" rx="1"/>
        </svg>
        @if (request()->routeIs('staff.dashboard.index'))
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

    <!-- Control Panel -->
    <div class="relative group">
      <a href="{{ route('control.panel') }}" class="w-12 h-12 mx-auto text-white hover:bg-[#3E1F0A] transition-colors flex items-center justify-center">
        <!-- Cog Icon (outline style) -->
        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 3.5l.75 2.5a7.003 7.003 0 014.5 4.5l2.5.75-2.5.75a7.003 7.003 0 01-4.5 4.5l-.75 2.5-.75-2.5a7.003 7.003 0 01-4.5-4.5l-2.5-.75 2.5-.75a7.003 7.003 0 014.5-4.5l.75-2.5z" />
          <circle cx="12" cy="12" r="3" />
        </svg>

        @if (request()->routeIs('control.panel'))
          <div class="absolute right-[-8px] top-0 h-full w-1 bg-white rounded-l"></div>
        @endif
      </a>
      <span class="absolute left-14 top-1/2 -translate-y-1/2 whitespace-nowrap bg-black text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition">Control Panel</span>
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

    <!-- Registration approval -->
    <div class="relative group">
      <a href="{{ route('staff.approval.index') }}" class="w-12 h-12 mx-auto text-white hover:bg-[#3E1F0A] transition-colors flex items-center justify-center">
        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
          <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
        </svg>
        @if (request()->routeIs('staff.approval.index'))
          <div class="absolute right-[-8px] top-0 h-full w-1 bg-white rounded-l"></div>
        @endif
      </a>
      <span class="absolute left-14 top-1/2 -translate-y-1/2 whitespace-nowrap bg-black text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition">Registration Approval</span>
    </div>

    <!-- Deactivate Account -->
    <div class="relative group">
      <a href="{{ route('staff.athlete.deactivate') }}" 
        class="w-12 h-12 mx-auto text-white hover:bg-[#3E1F0A] transition-colors flex items-center justify-center">
        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
          <path d="M12 2L4 5v6c0 5 4 9 8 11 4-2 8-6 8-11V5l-8-3z"/>
        </svg>
        @if (request()->routeIs('staff.athlete.deactivate'))
          <div class="absolute right-[-8px] top-0 h-full w-1 bg-white rounded-l"></div>
        @endif
      </a>
      <span class="absolute left-14 top-1/2 -translate-y-1/2 whitespace-nowrap bg-black text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition">
        Deactivate Account
      </span>
    </div>

  <!--Athlete update -->
  <div class="relative group">
      <a href="{{ route('staff.athletes.index') }}" 
        class="w-12 h-12 mx-auto text-white hover:bg-[#3E1F0A] transition-colors flex items-center justify-center">
          <svg xmlns="http://www.w3.org/2000/svg" 
              class="w-6 h-6" 
              fill="none" 
              viewBox="0 0 24 24" 
              stroke="currentColor" 
              stroke-width="2">
              <!-- User -->
              <path stroke-linecap="round" stroke-linejoin="round" 
                    d="M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
              <path stroke-linecap="round" stroke-linejoin="round" 
                    d="M12 14c-4 0-6 2-6 4v2h6" />
              <!-- Cog -->
              <path stroke-linecap="round" stroke-linejoin="round" 
                    d="M19.4 15a1.65 1.65 0 010 2l1.1 1.7a.5.5 0 01-.2.7l-2 .6a5.4 5.4 0 01-1.2.7l-.4 2.1a.5.5 0 01-.5.4h-2.4a.5.5 0 01-.5-.4l-.4-2.1a5.4 5.4 0 01-1.2-.7l-2-.6a.5.5 0 01-.2-.7l1.1-1.7a1.65 1.65 0 010-2l-1.1-1.7a.5.5 0 01.2-.7l2-.6a5.4 5.4 0 011.2-.7l.4-2.1a.5.5 0 01.5-.4h2.4a.5.5 0 01.5.4l.4 2.1a5.4 5.4 0 011.2.7l2 .6a.5.5 0 01.2.7L19.4 15z" />
          </svg>

          @if (request()->routeIs(['staff.athletes.index', 'coach.athletes.show']))
              <div class="absolute right-[-8px] top-0 h-full w-1 bg-white rounded-l"></div>
          @endif
      </a>

      <span class="absolute left-14 top-1/2 -translate-y-1/2 whitespace-nowrap 
                  bg-black text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition">
          Manage Athletes
      </span>
</div>

  <!-- Reports -->
  <div class="relative group">
    <a href="{{ route('reports') }}" class="w-12 h-12 mx-auto text-white hover:bg-[#3E1F0A] transition-colors flex items-center justify-center">
      <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
        <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z"/>
      </svg>
      @if (request()->routeIs('reports'))
        <div class="absolute right-[-8px] top-0 h-full w-1 bg-white rounded-l"></div>
      @endif
    </a>
    <span class="absolute left-14 top-1/2 -translate-y-1/2 whitespace-nowrap bg-black text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition">Reports</span>
  </div>

  <!-- Notifications -->
  <div class="relative group">
    <a href="{{ route('staff.notifications.index') }}" class="w-12 h-12 mx-auto text-white hover:bg-[#3E1F0A] transition-colors flex items-center justify-center">
      <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
        <path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.9 2 2 2zm6-6v-5c0-3.07-1.64-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.63 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z"/>
      </svg>
      @if (request()->routeIs('staff.notifications.index'))
        <div class="absolute right-[-8px] top-0 h-full w-1 bg-white rounded-l"></div>
      @endif
    </a>
    <span class="absolute left-14 top-1/2 -translate-y-1/2 whitespace-nowrap bg-black text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition">Notifications</span>
  </div>

    <!-- Settings -->

    <div class="relative group">
      <a href="{{ route('staff.profile.edit') }}" 
        class="w-12 h-12 mx-auto text-white hover:bg-[#3E1F0A] transition-colors flex items-center justify-center">
        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
          <path d="M19.14,12.94c0.04-0.3,0.06-0.61,0.06-0.94c0-0.32-0.02-0.64-0.07-0.94l2.03-1.58c0.18-0.14,0.23-0.41,0.12-0.61 l-1.92-3.32c-0.12-0.22-0.37-0.29-0.59-0.22l-2.39,0.96c-0.5-0.38-1.03-0.7-1.62-0.94L14.4,2.81c-0.04-0.24-0.24-0.41-0.48-0.41 h-3.84c-0.24,0-0.43,0.17-0.47,0.41L9.25,5.35C8.66,5.59,8.12,5.92,7.63,6.29L5.24,5.33c-0.22-0.08-0.47,0-0.59,0.22L2.74,8.87 C2.62,9.08,2.66,9.34,2.86,9.48l2.03,1.58C4.84,11.36,4.8,11.69,4.8,12s0.02,0.64,0.07,0.94l-2.03,1.58 c-0.18,0.14-0.23,0.41-0.12,0.61l1.92,3.32c0.12,0.22,0.37,0.29,0.59,0.22l2.39-0.96c0.5,0.38,1.03,0.7,1.62,0.94l0.36,2.54 c0.05,0.24,0.24,0.41,0.48,0.41h3.84c0.24,0,0.44-0.17,0.47-0.41l0.36-2.54c0.59-0.24,1.13-0.56,1.62-0.94l2.39,0.96 c0.22,0.08,0.47,0,0.59-0.22l1.92-3.32c0.12-0.22,0.07-0.47-0.12-0.61L19.14,12.94z M12,15.6c-1.98,0-3.6-1.62-3.6-3.6 s1.62-3.6,3.6-3.6s3.6,1.62,3.6,3.6S13.98,15.6,12,15.6z"/>
        </svg>
        @if (request()->routeIs('staff.profile.edit'))
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
