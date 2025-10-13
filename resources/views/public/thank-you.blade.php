<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="utf-8" />
    <title>Jobb Házasság Akadémia - Köszönjük!</title>
    <meta
        name="description"
        content="Csatlakozz a Váláspajzs 5 hetes programhoz! Heti két lecke segít erősíteni a kapcsolatodat, és párodat is ingyen bevonhatod a tanfolyamba."
    />
    <meta property="og:title" content="Jobb Házasság Akadémia" />
    <meta property="og:description"
          content="Csatlakozz a Váláspajzs 5 hetes programhoz! Heti két lecke segít erősíteni a kapcsolatodat, és párodat is ingyen bevonhatod a tanfolyamba." />
    <meta property="twitter:title" content="Jobb Házasság Akadémia" />
    <meta property="twitter:description"
          content="Csatlakozz a Váláspajzs 5 hetes programhoz! Heti két lecke segít erősíteni a kapcsolatodat, és párodat is ingyen bevonhatod a tanfolyamba." />
    <meta property="og:type" content="website" />
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    {{-- CSRF token a JS fetch-hez --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- CSS-ek vite-tel --}}
    @vite([
        'resources/css/all.min.css',
        'resources/css/normalize.css',
        'resources/css/webflow.css',
        'resources/css/subscribe.css'
    ])
    
    <link href="{{ asset('img/favicon.png') }}" rel="icon" type="image/png" />
    <link href="{{ asset('img/webclip.png') }}" rel="apple-touch-icon" />
</head>
<body class="body">

{{-- Hibaüzenet --}}
@if(!empty($errorMsg))
    <p class="paragraph color-red">{{ $errorMsg }}</p>
@endif

{{-- Fizetés sikeres --}}
@if($paymentStatus === 'paid')
    <section class="section">
        <div class="welcome-wrapper">
            <div class="img-wrapper"></div>
            <h1 class="heading1">Köszönjük a vásárlást!</h1>

            <p class="paragraph">
                Örülünk, hogy csatlakoztál a Váláspajzs 5 hetes programhoz!
            </p>
        </div>
    </section>

    <section class="section">
        <div class="w-layout-blockcontainer container w-container">
            <div class="intro-wrapper">
                <div class="intro-content-wrapper">
                    <h1 class="heading2">
                        Add hozzá a programhoz a párodat is most teljesen ingyen!
                    </h1>
                    <p class="paragraph text-left">
                        Drukkolunk, hogy a párodat is sikerüljön becsábítani a programba,
                        hiszen ha ketten csináljátok, akkor az még hatékonyabb. Mi ezt
                        azzal próbáljuk neked megkönnyíteni, hogy most a párodat teljesen
                        ingyen hozzáadhatod a programhoz. Ha szeretnéd, hogy együtt
                        csináljátok a programot, akkor töltsd ki a lenti mezőket és mi
                        máris küldünk egy meghívót a párodnak.
                    </p>
                </div>

                <div class="intro-form-wrapper">
                    <div class="form-block w-form">
                        <form id="email_form" name="email_form" method="post" class="form" novalidate>
                            <input type="hidden" name="session_id" value="{{ $sessionId }}">

                            <input
                                class="form-text-field w-input"
                                maxlength="256"
                                name="name"
                                id="name"
                                placeholder="Párod keresztneve"
                                type="text"
                                required
                            />
                            <input
                                class="form-text-field w-input"
                                maxlength="256"
                                name="email"
                                id="email"
                                placeholder="Párod email címe"
                                type="email"
                                required
                            />
                            <input
                                class="form-text-field w-input"
                                maxlength="256"
                                name="your_name"
                                id="your_name"
                                placeholder="Rád milyen néven hivatkozzunk"
                                type="text"
                                required
                            />

                            <div id="message" class="form-message-field"></div>
                            <p id="response-message" class="system-message"></p>

                            <input
                                type="submit"
                                data-wait="Dolgozunk rajta..."
                                class="submit-button w-button"
                                value="Elküldöm a meghívót"
                            />
                        </form>

                        <div class="w-form-fail">
                            <p class="system-message">
                                Probléma lépett fel a formula elküldése közben. Kérlek próbáld meg újra!
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif

<footer class="footer">
    <div class="w-layout-blockcontainer container-2 w-container">
        <div class="footer-wrapper">
            <div class="copyright-text">
                © Copyright {{ date('Y') }} - jobbhazassag.hu
            </div>
        </div>
    </div>
</footer>

{{-- JS vite-tel --}}
@vite(['resources/js/welcome.js', 'resources/js/invite.js'])

</body>
</html>
