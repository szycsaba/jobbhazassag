<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <meta property="og:title" content="Jobb Házasság Akadémia" />
        <meta property="og:description"
              content="Csatlakozz a Váláspajzs 5 hetes programhoz! Heti két lecke segít erősíteni a kapcsolatodat, és párodat is ingyen bevonhatod a tanfolyamba." />
        <meta property="twitter:title" content="Jobb Házasság Akadémia" />
        <meta property="twitter:description"
              content="Csatlakozz a Váláspajzs 5 hetes programhoz! Heti két lecke segít erősíteni a kapcsolatodat, és párodat is ingyen bevonhatod a tanfolyamba." />
        <meta name="twitter:card" content="summary_large_image" />

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <link href="{{ asset('img/favicon.png') }}" rel="icon" type="image/png" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- SweetAlert2 -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        
        @stack('styles')
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-red shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>

            @yield('content')
        </div>
        
        @stack('scripts')
    </body>
</html>
