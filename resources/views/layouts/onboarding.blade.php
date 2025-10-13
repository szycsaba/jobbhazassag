<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta
        content="Csatlakozz a Váláspajzs 5 hetes programhoz! Heti két lecke segít erősíteni a kapcsolatodat, és párodat is ingyen bevonhatod a tanfolyamba."
        name="description"
    />
    <meta content="Jobb Házasság Akadémia" property="og:title" />
    <meta
        content="Csatlakozz a Váláspajzs 5 hetes programhoz! Heti két lecke segít erősíteni a kapcsolatodat, és párodat is ingyen bevonhatod a tanfolyamba."
        property="og:description"
    />
    <meta content="Jobb Házasság Akadémia" property="twitter:title" />
    <meta
        content="Csatlakozz a Váláspajzs 5 hetes programhoz! Heti két lecke segít erősíteni a kapcsolatodat, és párodat is ingyen bevonhatod a tanfolyamba."
        property="twitter:description"
    />
    <meta property="og:type" content="website" />
    <meta content="summary_large_image" name="twitter:card" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @if(isset($isLoggedIn))
        <meta name="user-auth" content="{{ $isLoggedIn ? 'true' : 'false' }}">
    @endif
    <title>@yield('title', 'Jobb Házasság Akadémia')</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="{{ asset('img/favicon.png') }}" rel="icon" type="image/png" />
    <link href="{{ asset('img/webclip.png') }}" rel="apple-touch-icon" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link
    href="https://fonts.googleapis.com/css2?family=Bona+Nova:ital,wght@0,400;0,700;1,400&family=Domine:wght@400..700&family=Noto+Sans:ital,wght@0,100..900;1,100..900&family=Ovo&display=swap"
    rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/css/article.css', 'resources/css/variables.css'])
    <style>
        .onboarding-welcome-popup {
            border-radius: 20px !important;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3) !important;
        }
        .onboarding-welcome-title {
            color: #326e6c !important;
            font-weight: bold !important;
            font-size: 1.5rem !important;
        }
        .onboarding-welcome-content {
            color: #555 !important;
            font-size: 1.1rem !important;
            line-height: 1.6 !important;
        }
    </style>
</head>
<body>

<div class="min-h-screen flex flex-col container-bg ">

    @hasSection('header')
        @yield('header')
    @endif

    <main class="flex-1 mt-[1.5rem]">
        <section id="lesson" class="section">
            @yield('content')
        </section>
    </main>
    <footer class="h-16">
    </footer>
</div>
@vite(['resources/js/app.js', 'resources/js/article.js', 'resources/js/quiz-handler.js', 'resources/js/onboarding-reason.js', 'resources/js/onboarding-signin-disabler.js', 'resources/js/onboarding-welcome.js', 'resources/js/logout-handler.js'])
</body>
</html>
