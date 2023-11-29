<!doctype html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  @vite('resources/css/app.css')
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            clifford: '#da373d',
          }
        }
      }
    }
  </script>
  <style type="text/tailwindcss">
    @layer utilities {
      .content-auto {
        content-visibility: auto;
      }
    }
  </style>
</head>
<body>
<div class="bg-white">
<div class="bg-white">
  <header class="relative inset-x-0 top-0 z-50">
    <nav class="flex items-center justify-between p-6 lg:px-8" aria-label="Global">
      <div class="flex lg:flex-1">
        <a href="/" class="-m-1.5 p-1.5">
          <span class="sr-only">Super Cookie</span>
        </a>
      </div>
      <div class="lg:flex lg:gap-x-12">
        <a href="/" class="text-sm font-semibold leading-6 text-gray-900"> 🏠</a>
        <a href="/cookie" class="text-sm font-semibold leading-6 text-gray-900">🍪</a>
        <a href="/secret" class="text-sm font-semibold leading-6 text-gray-900">🔒</a>
      </div>
      <div class="hidden lg:flex lg:flex-1 lg:justify-end">
        <a href="/registeration" class="text-sm font-semibold leading-6 text-gray-900">Log in <span aria-hidden="true">&rarr;</span></a>
      </div>
    </nav>
  </header>

    <div class="relative isolate px-6 pt-14 lg:px-8">
        <!-- Your content for the home page can go here -->
        @yield('content')
    </div>

</body>
</html>
