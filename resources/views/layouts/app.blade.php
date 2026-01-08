<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', 'AthletiX')</title>
    <link rel="icon" href="https://c.animaapp.com/mevbdbzo2I14VB/img/logo.png" type="image/x-icon" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  </head>
  <body class="bg-white min-h-screen">
    <div class="flex">
        {{-- Role-based navbar --}}
        @php $roles = explode('|', auth()->user()->role ?? '') @endphp

        @if(in_array('SuperAdmin', $roles))
            @include('partials.navbar')
        @endif
        @if(in_array('Staff', $roles))
            @include('partials.staffNavbar')
        @endif
        @if(in_array('Coach', $roles))
            @include('partials.coachNavbar')
        @endif
        @if(in_array('Athlete', $roles))
            @include('partials.athleteNavbar')
        @endif
        <main class="flex-1 p-8 bg-white justify-items-between min-h-screen w-screen">
        <h2 class="text-2xl font-bold mb-6 shadow-lg p-6">
          Welcome, {{ auth()->user()->role === 'Staff' ? 'Admin' : auth()->user()->role }}
      </h2>

  
        <header class="flex justify-between items-start mb-1">
            <h1 class="text-4xl font-bold text-brown-primary font-inknut">
              @yield('title')
            </h1>
            @yield('header-actions')
          </header>
            @yield('content')
        </main>
    </div>

    <!-- ADD THIS LINE - Scripts section -->
    @yield('scripts')
  </body>
</html>