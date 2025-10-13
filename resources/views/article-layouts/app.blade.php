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
    <title>@yield('title', 'Jobb Házasság Akadémia')</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="{{ asset('img/favicon.png') }}" rel="icon" type="image/png" />
    <link href="{{ asset('img/webclip.png') }}" rel="apple-touch-icon" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link
    href="https://fonts.googleapis.com/css2?family=Bona+Nova:ital,wght@0,400;0,700;1,400&family=Domine:wght@400..700&family=Noto+Sans:ital,wght@0,100..900;1,100..900&family=Ovo&display=swap"
    rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/css/article.css', 'resources/css/variables.css'])
</head>
<body>

<div class="min-h-screen flex flex-col container-bg ">
    <header class="header-bg text-white flex items-center">
        <i id="menuBurger" class="fa-solid fa-bars fixed ml-auto cursor-pointer text-[#285956] hover:text-gray-400 transition-colors duration-500 mt-[5rem]"></i>
        <nav class="navMenu items-center transition duration-700 ease-in-out translate-x-full">
            <ul class="grid grid-rows-6 justify-items-center gap-2">
                <li class="flex justify-between w-full items-center">
                    @auth('google')
                        <img id="imgProfile" src="{{ Auth::guard('google')->user()->avatar_url }}" alt="Profile" class="w-8 h-8 rounded-full cursor-pointer hover:opacity-80 transition-opacity duration-500" />
                    @else
                        <i id="imgProfile" class="fa-brands fa-google cursor-pointer text-white-800 hover:text-gray-600 transition-colors duration-500" onclick="window.location.href='{{ route('redirect.google') }}'"></i>
                    @endauth
                    <i id="backArrow" class="fa-solid fa-arrow-right ml-auto cursor-pointer text-white-800 hover:text-gray-600 transition-colors duration-500"></i>
                </li>
                @auth('google')
                <li class="flex items-center">
                    <a href="#logout" class="hover:text-gray-600 transition-colors duration-500 text-[1.3rem] sm:text-[1.5rem]">Kijelentkezés</a>
                </li>
                @else
                    <li></li>
                @endauth

                <li class="flex items-center">
                    <a href="#lesson" class="hover:text-gray-600 transition-colors duration-500 text-[1.3rem] sm:text-[1.5rem]">I. Kapcsolati lecke</a>
                </li>
                <li class="flex items-center">
                    <a href="#quiz" class="hover:text-gray-600 transition-colors duration-500 text-[1.3rem] sm:text-[1.5rem]">II. Kvíz</a>
                </li>
                <li class="flex items-center">
                    <a href="#selfLearn" class="hover:text-gray-600 transition-colors duration-500 text-[1.3rem] sm:text-[1.5rem]">III. Önismereti gyakorlat</a>
                </li>
                <!--<li class="flex items-center">
                    <a href="#whatWillITake" class="hover:text-gray-600 transition-colors duration-500 text-[1.3rem] sm:text-[1.5rem]">IV. Megosztás</a>
                </li>-->
                <li class="flex items-center">
                    <a href="#previousArticles" class="hover:text-gray-600 transition-colors duration-500 text-[1.3rem] sm:text-[1.5rem]">IV. Korábbi cikkek</a>
                </li>
            </ul>
        </nav>
    </header>

    @yield('header')

    <main class="flex-1 mt-[1.5rem]">
        <section id="lesson" class="section">
            @yield('content')
        </section>
    </main>
    <footer class="h-16">
    </footer>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@vite(['resources/js/app.js', 'resources/js/article.js', 'resources/js/quiz-handler.js', 'resources/js/article-reflection-notes.js', 'resources/js/logout-handler.js'])
</body>
</html>
