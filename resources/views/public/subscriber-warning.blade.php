<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="description" content="Hozzáférés szükséges - Jobb Házasság Akadémia">
    <meta name="keywords" content="HTML, CSS, JavaScript">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hozzáférés szükséges - Jobb Házasság Akadémia</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    @vite(['resources/css/article.css', 'resources/css/variables.css'])
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link
    href="https://fonts.googleapis.com/css2?family=Bona+Nova:ital,wght@0,400;0,700;1,400&family=Domine:wght@400..700&family=Noto+Sans:ital,wght@0,100..900;1,100..900&family=Ovo&display=swap"
    rel="stylesheet">
    <link href="{{ asset('img/favicon.png') }}" rel="icon" type="image/png" />
    <link href="{{ asset('img/webclip.png') }}" rel="apple-touch-icon" />
</head>
<body>

<div class="min-h-screen flex flex-col">

    <main class="container-bg flex-1">
        <section id="lesson" class="section">            
            <!-- Warning Message -->
            <h1 class="articleSubtitle text-center font-extrabold fade-section opacity-0 translate-y-5 transition-all duration-700 ease-out mb-8">Hozzáférés szükséges!</h1>
            <h5 class="subtitle text-center font-bold fade-section opacity-0 translate-y-5 transition-all duration-700 ease-out mb-8">A tartalom csak regisztrált és előfizetéssel rendelkező tagok számára elérhető.</h5>
            
            <!-- Action Buttons -->
            <div class="whiteBox fade-section opacity-0 translate-y-5 transition-all duration-700 ease-out">
                <div class="space-y-4">
                    <a href="{{ route('redirect.google', ['intended' => session('url.intended', request()->headers->get('referer'))]) }}" 
                       class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                        <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24">
                            <path fill="currentColor" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path fill="currentColor" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="currentColor" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                            <path fill="currentColor" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                        </svg>
                        Belépés Google fiókkal
                    </a>
                    
                    <a href="{{ route('home') }}" 
                       class="w-full flex justify-center py-3 px-4 border border-gray-300 rounded-md shadow-sm text-base font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Vissza a kezdőoldalra
                    </a>
                </div>
            </div>
            
            <!-- Debug Info (remove in production) -->
            @if(config('app.debug'))
            <div class="bg-yellow-100 p-4 rounded mb-4">
                <h6 class="font-bold">Debug Info:</h6>
                <p>Session intended URL: {{ session('url.intended') }}</p>
                <p>Referer: {{ request()->headers->get('referer') }}</p>
                <p>Google login URL: {{ route('redirect.google', ['intended' => session('url.intended', request()->headers->get('referer'))]) }}</p>
            </div>
            @endif
            
            <!-- Additional Info -->
            <div class="greenBox fade-section opacity-0 translate-y-5 transition-all duration-700 ease-out">
                <h5 class="font-bold mb-4">Miért kell regisztráció és előfizetés?</h5>
                <p>A tartalmak kizárólag fizetős előfizetők számára érhetők el. Ez biztosítja a minőségi tartalom fenntartását és a személyre szabott élményt.</p>
            </div>
        </section>
    </main>
    <footer class="h-16">
    </footer>
</div>
@vite(['resources/js/article.js'])
</body>
</html>
