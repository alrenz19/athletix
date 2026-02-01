<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5, minimum-scale=0.5" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'AthletiX')</title>
    <link rel="icon" href="https://c.animaapp.com/mevbdbzo2I14VB/img/logo.png" type="image/x-icon" />
    
    <!-- Font Import -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
      :root {
        --base-font-size: 16px;
        --scale-factor: 1;
        --container-width: 100%;
      }
      
      body {
        font-family: 'Inter', sans-serif;
        font-size: var(--base-font-size);
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        font-weight: 400;
        line-height: 1.6;
        color: #333;
        width: 100%;
        overflow-x: hidden;
        margin: 0;
        padding: 0;
      }
      
      /* Improved responsive typography for large screens */
      h1 { 
        font-size: clamp(1.75rem, 5vw, 3.5rem) !important;
        font-weight: 700 !important;
        line-height: 1.2 !important;
      }
      
      h2 { 
        font-size: clamp(1.5rem, 4vw, 2.5rem) !important;
        font-weight: 600 !important;
        line-height: 1.3 !important;
      }
      
      h3 { 
        font-size: clamp(1.25rem, 3vw, 2rem) !important;
        font-weight: 600 !important;
        line-height: 1.4 !important;
      }
      
      h4 { 
        font-size: clamp(1.125rem, 2.5vw, 1.5rem) !important;
        font-weight: 600 !important;
        line-height: 1.4 !important;
      }
      
      /* Body text that scales better on large screens */
      p, li, span, a, button, div:not(.text-sm):not(.text-xs):not(.text-lg):not(.text-xl):not(.text-2xl):not(.text-3xl) { 
        font-size: clamp(1rem, 1.5vw, 1.125rem) !important;
        line-height: 1.6 !important;
      }
      
      /* Specific classes for different text sizes */
      .text-large {
        font-size: clamp(1.125rem, 2vw, 1.5rem) !important;
      }
      
      .text-small {
        font-size: clamp(0.875rem, 1.2vw, 1rem) !important;
      }
      
      .responsive-heading {
        font-size: clamp(1.75rem, 5vw, 3rem) !important;
        font-weight: 700 !important;
      }
      
      .responsive-text {
        font-size: clamp(1rem, 2vw, 1.25rem) !important;
      }
      
      .responsive-subheading {
        font-size: clamp(1.25rem, 3vw, 2rem) !important;
      }
      
      /* Container scaling */
      .adaptive-container {
        width: 100%;
        margin: 0 auto;
      }
      
      /* Laptop screens (below 1920px) - FULL WIDTH */
      @media (max-width: 1919px) {
        .adaptive-container {
          max-width: 100% !important; /* Force full width */
          padding-left: 1rem !important; /* Fixed padding on laptops */
          padding-right: 1rem !important;
        }
        
        /* Make sure no max-width constraints exist */
        body .adaptive-container,
        .adaptive-container .adaptive-container {
          max-width: 100% !important;
          width: 100% !important;
        }
      }
      
      /* Desktop screens (1200px - 1919px) */
      @media (min-width: 1200px) and (max-width: 1919px) {
        .adaptive-container {
          padding-left: 2rem !important;
          padding-right: 2rem !important;
        }
      }
      
      /* Monitor screens (1920px and above) - CONSTRAINED WIDTH */
      @media (min-width: 1920px) {
        .adaptive-container {
          max-width: 1800px;
          padding-left: 4rem;
          padding-right: 4rem;
        }
        
        :root {
          --base-font-size: 18px;
        }
        
        body {
          font-size: 1.125rem;
        }
      }
      
      /* Larger monitor adjustments */
      @media (min-width: 2560px) {
        .adaptive-container {
          max-width: 2200px;
          padding-left: 6rem;
          padding-right: 6rem;
        }
        
        :root {
          --base-font-size: 20px;
        }
        
        body {
          font-size: 1.25rem;
        }
        
        h1 { font-size: 4rem !important; }
        h2 { font-size: 3rem !important; }
        h3 { font-size: 2.25rem !important; }
        p, span, div, a, button { 
          font-size: 1.375rem !important; 
        }
      }
      
      @media (min-width: 3840px) { /* 4K screens */
        .adaptive-container {
          max-width: 2800px;
          padding-left: 8rem;
          padding-right: 8rem;
        }
        
        :root {
          --base-font-size: 24px;
        }
        
        body {
          font-size: 1.5rem;
        }
        
        h1 { font-size: 5rem !important; }
        h2 { font-size: 3.5rem !important; }
        h3 { font-size: 2.75rem !important; }
        p, span, div, a, button { 
          font-size: 1.625rem !important; 
        }
      }
      
      /* Mobile adjustments */
      @media (max-width: 768px) {
        .adaptive-container {
          padding-left: 1rem !important;
          padding-right: 1rem !important;
        }
        
        h1 { font-size: 1.75rem !important; }
        h2 { font-size: 1.5rem !important; }
        h3 { font-size: 1.25rem !important; }
      }
      
      @media (max-width: 480px) {
        .adaptive-container {
          padding-left: 0.75rem !important;
          padding-right: 0.75rem !important;
        }
      }
      
      /* Prevent text size adjustment on zoom */
      html {
        -webkit-text-size-adjust: 100%;
        -moz-text-size-adjust: 100%;
        -ms-text-size-adjust: 100%;
        text-size-adjust: 100%;
        overflow-x: hidden;
        width: 100%;
      }
      
      /* Ensure buttons and interactive elements are large enough */
      button, 
      a.btn, 
      input, 
      select, 
      textarea {
        font-size: inherit !important;
        min-height: 2.75em;
      }
      
      /* Table text scaling */
      table {
        font-size: clamp(0.875rem, 1.2vw, 1rem) !important;
      }
      
      @media (min-width: 1920px) {
        table {
          font-size: 1.125rem !important;
        }
      }
      
      /* Form elements scaling */
      .form-input,
      .form-select,
      .form-textarea {
        font-size: clamp(1rem, 1.5vw, 1.125rem) !important;
        padding: 0.75em 1em !important;
      }
      
      /* Card text scaling */
      .card, .bg-gray-50, .shadow-lg {
        font-size: clamp(1rem, 1.5vw, 1.125rem) !important;
      }
      
      /* Navigation specific scaling */
      nav a, nav button, nav span {
        font-size: clamp(0.875rem, 1.2vw, 1rem) !important;
      }
      
      @media (min-width: 1920px) {
        nav a, nav button, nav span {
          font-size: 1.125rem !important;
        }
      }
    </style>
    
    <script>
      // Tailwind config for font scaling
      tailwind.config = {
        theme: {
          extend: {
            fontSize: {
              'dynamic-sm': 'clamp(0.875rem, 1.5vw, 1rem)',
              'dynamic-base': 'clamp(1rem, 2vw, 1.125rem)',
              'dynamic-lg': 'clamp(1.125rem, 2.5vw, 1.5rem)',
              'dynamic-xl': 'clamp(1.25rem, 3vw, 1.75rem)',
              'dynamic-2xl': 'clamp(1.5rem, 4vw, 2.5rem)',
              'dynamic-3xl': 'clamp(1.75rem, 5vw, 3rem)',
              'dynamic-4xl': 'clamp(2rem, 6vw, 3.5rem)',
              'dynamic-5xl': 'clamp(2.5rem, 7vw, 4rem)',
            },
            screens: {
              '3xl': '1920px',
              '4xl': '2560px',
              '5xl': '3840px',
            }
          }
        }
      }
    </script>
</head>
<body class="bg-white min-h-screen" x-data="{ mobileMenuOpen: false }">
    <div class="flex flex-col lg:flex-row w-full adaptive-container">
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
        
        <main class="flex-1 p-4 md:p-6 lg:p-8 bg-white w-full adaptive-container">
          <div class="mb-6 md:mb-8">
            <h2 class="responsive-heading font-bold mb-2 md:mb-4 shadow-lg p-4 md:p-6 rounded-lg bg-gray-50">
              Welcome, {{ auth()->user()->role === 'Staff' ? 'Admin' : auth()->user()->role }}
            </h2>
          </div>
          
          <div class="mb-4 md:mb-6">
            <header class="flex flex-col md:flex-row md:justify-between md:items-start gap-4">
              <h1 class="responsive-heading font-bold text-brown-primary">
                @yield('title')
              </h1>
              <div class="flex-shrink-0">
                @yield('header-actions')
              </div>
            </header>
          </div>
          
          <div class="responsive-container">
            @yield('content')
          </div>
        </main>
    </div>

    <!-- Scripts section -->
    @yield('scripts')
    
    <script>
      // Enhanced font scaling for large screens
      function updateFontScaling() {
        const screenWidth = window.innerWidth;
        const isLargeScreen = screenWidth >= 1920;
        const isExtraLargeScreen = screenWidth >= 2560;
        const is4kScreen = screenWidth >= 3840;
        
        // Get root element
        const root = document.documentElement;
        
        // Calculate dynamic base font size
        let baseFontSize = 16; // Default
        
        if (is4kScreen) {
          baseFontSize = 24;
        } else if (isExtraLargeScreen) {
          baseFontSize = 20;
        } else if (isLargeScreen) {
          baseFontSize = 18;
        } else if (screenWidth >= 1200) {
          // For desktop screens (1200px - 1919px) - laptop range
          baseFontSize = Math.min(18, 16 + (screenWidth - 1200) * 0.002);
        } else if (screenWidth <= 768) {
          // For mobile screens
          baseFontSize = Math.max(14, 16 - (768 - screenWidth) * 0.005);
        }
        
        // Apply base font size
        root.style.setProperty('--base-font-size', baseFontSize + 'px');
        document.body.style.fontSize = baseFontSize + 'px';
        
        // Log for debugging (remove in production)
        console.log('Screen:', screenWidth + 'px', 
                   'Device:', isLargeScreen ? 'Monitor' : 'Laptop/Smaller',
                   'Base font:', baseFontSize + 'px');
      }
      
      // Initialize on load
      document.addEventListener('DOMContentLoaded', function() {
        updateFontScaling();
        
        // Also check if user has increased default font size in browser
        const computedFontSize = parseFloat(getComputedStyle(document.body).fontSize);
        if (computedFontSize > 20) {
          // User has increased font size in browser settings
          document.documentElement.style.setProperty('--base-font-size', computedFontSize + 'px');
          document.body.classList.add('user-increased-font');
        }
      });
      
      // Handle resize
      let resizeTimeout;
      window.addEventListener('resize', function() {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(updateFontScaling, 250);
      });
      
      // Handle orientation change
      window.addEventListener('orientationchange', function() {
        setTimeout(updateFontScaling, 100);
      });
      
      // Add class for large screens (monitors)
      function checkScreenSize() {
        if (window.innerWidth >= 1920) {
          document.documentElement.classList.add('monitor-screen');
          document.documentElement.classList.remove('laptop-screen');
        } else {
          document.documentElement.classList.remove('monitor-screen');
          document.documentElement.classList.add('laptop-screen');
        }
      }
      
      // Initial check
      checkScreenSize();
      window.addEventListener('resize', checkScreenSize);
    </script>
</body>
</html>