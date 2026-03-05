<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl" data-theme="luxury_light">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, viewport-fit=cover">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&display=swap" rel="stylesheet">
  <!-- AOS Animation Library -->
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
  <title>{{ isset($title) ? $title.' - '.config('app.name') : config('app.name') }}</title>
  @livewireStyles
  @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>
<body class="min-h-screen antialiased bg-background text-text" data-aos-easing="ease-out-quad" data-aos-duration="1500">
  {{-- MAIN CONTENT AREA --}}
  <main>
    {{ $slot }}
  </main>
  {{-- منطقة التنبيهات (Toasts) --}}
  <x-toast />
  @livewireScripts
  <!-- AOS Animation Library -->
  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  <script>
    document.addEventListener('livewire:init', () => {
      // Initialize AOS when Livewire is ready
      AOS.init({
        duration: 800,
        once: true,
        easing: 'ease-out-quad'
      });
      
      // Refresh AOS after each Livewire update
      Livewire.hook('element.updated', (el, component) => {
        setTimeout(() => {
          AOS.refresh();
        }, 100);
      });
      
      // Initialize AOS for any elements added via Alpine
      document.addEventListener('alpine:initialized', () => {
        setTimeout(() => {
          AOS.refresh();
        }, 300);
      });
    });
    
    // Handle page navigation
    document.addEventListener('alpine:navigated', () => {
      setTimeout(() => {
        AOS.refresh();
      }, 500);
    });
  </script>
  @stack('scripts')
<livewire:cart-wizard />
</body>
</html>