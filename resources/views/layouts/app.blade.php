<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'SYSMYC') }} - @yield('title', 'Dashboard')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Additional Styles -->
    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-200 m-0 p-0">
    
    <!-- Top Navigation -->
    @include('layouts.partials.top-nav')
    
    <!-- Left Sidebar -->
    @include('layouts.partials.left-sidebar')
    
    <!-- Main Content Area -->
    <div class="main-content">
        <!-- Page Header (Optional) -->
        @if(isset($header) || View::hasSection('header'))
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="max-w-full mx-auto py-4 px-6">
                    @isset($header)
                        {{ $header }}
                    @else
                        @yield('header')
                    @endisset
                </div>
            </header>
        @endif

        <!-- Page Content -->
        {{-- Pages can @section('full_width', true) to remove padding and fill the content area --}}
        <main class="{{ View::hasSection('full_width') ? 'p-0 h-[calc(100vh-var(--top-nav-height))] overflow-hidden' : 'p-6' }}">
            @unless(View::hasSection('full_width'))
                <!-- Success/Error Messages -->
                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg">
                        {{ session('error') }}
                    </div>
                @endif
            @endunless
            
            <!-- Main Content Slot -->
            @isset($slot)
                {{ $slot }}
            @else
                @yield('content')
            @endisset
        </main>
    </div>
    
    <!-- Right Slideout Panels -->
    @include('layouts.partials.right-slideouts')
    
    <!-- Auto-refresh CSRF token -->
    <script>
        // Refresh CSRF token every 10 minutes
        setInterval(function() {
            fetch('/refresh-csrf', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                document.querySelectorAll('input[name="_token"]').forEach(input => {
                    input.value = data.token;
                });
                const metaTag = document.querySelector('meta[name="csrf-token"]');
                if (metaTag) {
                    metaTag.setAttribute('content', data.token);
                }
            })
            .catch(error => console.error('CSRF refresh error:', error));
        }, 600000);
    </script>
    
    <!-- Additional Scripts -->
    @stack('scripts')
</body>
</html>
