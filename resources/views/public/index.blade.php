<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('images/favicon.png') }}" rel="shortcut icon" type="image/x-icon" />
    <link href="{{ asset('img/favicon.png') }}" rel="icon" type="image/png" />
    <link href="{{ asset('img/webclip.png') }}" rel="apple-touch-icon" />
    <title>Jobb Házasság Akadémia - 5 Hetes Program</title>
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
    <!-- Meta Pixel Code -->
    <script>
        !function (f, b, e, v, n, t, s) {
            if (f.fbq) return; n = f.fbq = function () {
                n.callMethod ?
                    n.callMethod.apply(n, arguments) : n.queue.push(arguments)
            };
            if (!f._fbq) f._fbq = n; n.push = n; n.loaded = !0; n.version = '2.0';
            n.queue = []; t = b.createElement(e); t.async = !0;
            t.src = v; s = b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t, s)
        }(window, document, 'script',
            'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '714888287981522');
        fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none"
                   src="https://www.facebook.com/tr?id=714888287981522&ev=PageView&noscript=1" /></noscript>
    <!-- End Meta Pixel Code -->

    <link
        href="https://fonts.googleapis.com/css2?family=Bona+Nova:ital,wght@0,400;0,700;1,400&family=Domine:wght@400..700&family=Noto+Sans:ital,wght@0,100..900;1,100..900&family=Ovo&display=swap"
        rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/style.css'])
</head>

<body>

<!-- POP-UP -->
<div class="modal fade" id="emailModal" tabindex="-1" aria-labelledby="emailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header">
                <h1 class="modal-title fs-4" id="programModalLabel">
                    <i class="fas fa-heart me-2"></i>Köszönjük, hogy érdeklődsz!
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="lead mb-4">
                    A programot jelenleg <strong>20 család</strong> próbálja ki, hogy minden a helyére kerüljön,
                    és a lehető legnagyobb segítséget tudja nyújtani nektek.
                </p>


                <div class="alert border-0 lead" style="background: linear-gradient(135deg, #cdfaff, #d5f5eb);">
                    <h4 class="alert-heading mb-3">Az első 100 jelentkezőnek azt ajánljuk, hogy:</h4>
                    <div class="benefit-item">
                        <span class="green-circle">🟢</span>
                        <span><strong>elsőként értesítünk</strong>, amikor elindul a program</span>
                    </div>
                    <div class="benefit-item">
                        <span class="green-circle">🟢</span>
                        <span>és a <strong>teljes, 4 hetes programot</strong>
                                <span class="price-highlight">4900 Ft-ért</span> kapod meg
                                <small class="text-muted">(teljes ár helyett)</small>
                            </span>
                    </div>
                </div>

                <form id="interestForm" class="mt-3">
                    <div class="row">
                        <h4 class="my-4">Ha érdekel a lehetőség kérjük add meg az email címed és hogy hogyan
                            szólíthatunk.</h4>
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label lead"><strong>Email cím *</strong></label>
                            <input type="email" class="form-control" id="emailInput" placeholder="pelda@email.com"
                                   required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label lead"><strong>Hogyan szólíthatunk?
                                    *</strong></label>
                            <input type="text" class="form-control" id="name" placeholder="Neved" required>
                        </div>
                    </div>
                    <div class="invalid-feedback">
                        Érvénytelen email cím.
                    </div>
                </form>
                <div id="alertContainer"></div>
            </div>
            <div class="modal-footer border-0 justify-content-center mb-2">
                <!--<button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Bezárás</button>-->
                <button type="button" class="btn btn-primary-custom2 m-auto w-50 test-center p-2 mb-3"
                        id="submitEmail" style="font-size: 1.3rem;">
                    <i class="fas fa-paper-plane me-2"></i>Jelentkezem!
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Progress Indicator
<div class="progress-indicator">
    <div class="progress-bar" id="progressBar"></div>
</div>
 -->

<!-- Hero Section -->
<!--    <div class="header-title">
       <h1 class="display-1 mb-4 fw-bold">Védelem a válás ellen</h1>

   </div>  -->

<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-12 col mx-auto text-center">
                <h1 class="header-title fw-bold">Védelem a válás ellen</h1>
                <div class="filler"></div>
                <p class="h4 mb-4 opacity-90 sub-hl fs-2">5 hetes program a házasságotok karbantartásáért</p>
            </div>
        </div>
    </div>
</section>

<!--MI ez?-->
<section id="program" class="pt-5 pb-4">
    <div class="container">
        <div class="section-card">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <h2 class="h1 mb-4 text-primary-teal display-4">A filozófiánk: a házasság egy maraton</h2>
                    <p class="lead">Egy rövid kapcsolat olyan, mint lefutni pár kilométert alkalomszerűen.</br>
                        Nem kell hozzá különösebb felkészülés, belefér, ha kifulladsz a végén.</p>
                    <p class="lead">De egy évtizedekre tervezett kapcsolat már <strong>maraton.</strong>
                        Nem lehet ugyanazzal a hozzáállással
                        végigcsinálni, mint egy rövid futást. Más tempó, más kitartás, másfajta erőbeosztás kell
                        hozzá.</p>
                    <p class="lead">És ahogy a maratonra sem indul el senki edzés nélkül, a kapcsolatot sem lehet hosszú
                        távon működtetni <strong>tanulás nélkül.</strong></p>
                    <p class="text-primary-teal mt-4 lead fw-semibold">Ezért hoztuk létre a <strong>Jobb Házasság Akadémiát</strong> és a <strong>Váláspajzs programot</strong>, hogy
                        megkapjátok azt a tudást és eszköztárat, amivel végig lehet futni a közös maratonotokat.
                    </p>
                    <div class="my-3">
                        <p class="lead"
                           A Váláspajzs10 ennek az első lépése: 10 lecke ami megóvhatja a kapcsolatotokat a
                           válástól.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- sikersztori -->
<section class="pb-4">
    <div class="container">
        <div class="section-card">
            <h4 class="siker-hl p-2">
                <img class="testimonial" src="img/testimonials/Eszter.jpg" alt="Eszter">
                Sikersztori -
                Eszter, 42
            </h4>
            <p class="fst-italic p-3 lead">
                „Mindig kiborított, amikor vitánál a férjem csak ült és hallgatott, miközben én választ vártam
                tőle. A <strong>Mi történik stressz alatt</strong> lecke után értettem meg, hogy ő ezt nem közönyből csinálja,
                hanem így reagál a stresszre: egyszerűn lefagy. Nem vagyunk egyformák, és nem mindig
                könnyűek a hétköznapok, de ezek a leckék segítettek abban, hogy jobban megértsem őt és
                újra el tudjunk kezdeni közeledni egymáshoz."
            </p>
        </div>
    </div>
</section>



<!--       CAROUSELS      -->

<section id="carouselPos">
    <div class="container text-center" id="carouselContainer">

        <!--    MINI TANANYAGOK     -->
        <div class="carousel-section py-4" id="carouselSection1">
            <h1 class="mb-4 w-75 m-auto text-center" style="color: var(--primary-teal)">A romboló kommunikációs
                spirál
                felismerése</h1>
            <span class="close-btn">X</span>
            <div id="imageCarousel1" class="carousel slide">

                <div class="carousel-inner" id="carouselInner">
                    <div class="carousel-item active">
                        <img src="img/carousel/tananyag/001.png" class="d-block w-100" alt="mini tananyagok kep">
                    </div>
                    <div class="carousel-item">
                        <img src="img/carousel/tananyag/002.png" class="d-block w-100" alt="mini tananyagok kep"
                             loading="lazy">
                    </div>
                    <div class="carousel-item">
                        <img src="img/carousel/tananyag/003.png" class="d-block w-100" alt="mini tananyagok kep"
                             loading="lazy">
                    </div>
                    <div class="carousel-item">
                        <img src="img/carousel/tananyag/004.png" class="d-block w-100" alt="mini tananyagok kep"
                             loading="lazy">
                    </div>
                    <div class="carousel-item">
                        <img src="img/carousel/tananyag/005.png" class="d-block w-100" alt="mini tananyagok kep"
                             loading="lazy">
                    </div>
                    <div class="carousel-item">
                        <img src="img/carousel/tananyag/006.png" class="d-block w-100" alt="mini tananyagok kep"
                             loading="lazy">
                    </div>
                    <div class="carousel-item">
                        <img src="img/carousel/tananyag/007.png" class="d-block w-100" alt="mini tananyagok kep"
                             loading="lazy">
                    </div>
                    <div class="carousel-item">
                        <img src="img/carousel/tananyag/008.png" class="d-block w-100" alt="mini tananyagok kep"
                             loading="lazy">
                    </div>
                    <div class="carousel-item">
                        <img src="img/carousel/tananyag/009.png" class="d-block w-100" alt="mini tananyagok kep"
                             loading="lazy">
                    </div>
                    <div class="carousel-item">
                        <img src="img/carousel/tananyag/010.png" class="d-block w-100" alt="mini tananyagok kep"
                             loading="lazy">
                    </div>
                    <div class="carousel-item">
                        <img src="img/carousel/tananyag/011.png" class="d-block w-100" alt="mini tananyagok kep"
                             loading="lazy">
                    </div>
                    <div class="carousel-item">
                        <img src="img/carousel/tananyag/012.png" class="d-block w-100" alt="mini tananyagok kep"
                             loading="lazy">
                    </div>
                    <div class="carousel-item">
                        <img src="img/carousel/tananyag/013.png" class="d-block w-100" alt="mini tananyagok kep"
                             loading="lazy">
                    </div>
                    <div class="carousel-item">
                        <img src="img/carousel/tananyag/014.png" class="d-block w-100" alt="mini tananyagok kep"
                             loading="lazy">
                    </div>
                    <div class="carousel-item">
                        <img src="img/carousel/tananyag/015.png" class="d-block w-100" alt="mini tananyagok kep"
                             loading="lazy">
                    </div>
                    <div class="carousel-item">
                        <img src="img/carousel/tananyag/016.png" class="d-block w-100" alt="mini tananyagok kep"
                             loading="lazy">
                    </div>
                    <div class="carousel-item">
                        <img src="img/carousel/tananyag/017.png" class="d-block w-100" alt="mini tananyagok kep"
                             loading="lazy">
                    </div>
                    <div class="carousel-item">
                        <img src="img/carousel/tananyag/018.png" class="d-block w-100" alt="mini tananyagok kep"
                             loading="lazy">
                    </div>
                    <div class="carousel-item">
                        <button class="btn carousel-select-btn" id="tananyagEnd">Kész</button>
                        <img src="img/carousel/tananyag/019.png" class="d-block w-100" alt="mini tananyagok kep"
                             loading="lazy">
                    </div>

                </div>

                <div class="navigation px-1">
                    <img src="img/carousel/arrow_l.png" class="nav-btn1 prev-btn" data-bs-target="#imageCarousel1"
                         data-bs-slide="prev" id="prevBtn0">
                    <img src="img/carousel/arrow_r.png" class="nav-btn1 next-btn" data-bs-target="#imageCarousel1"
                         data-bs-slide="next" id="nextBtn0">
                    <img src="img/carousel/close.png" class="nav-btn1 end-btn" style="display: none">
                </div>
            </div>
        </div>


        <script>
            //reset carousel
            document.getElementById("tananyagEnd").addEventListener("click", () => {
                hideAndScroll();
                const tanCarousel = document.getElementById('imageCarousel1');
                const tanItems = tanCarousel.querySelectorAll('.carousel-item');
                for (let i = 0; i < tanItems.length; i++) {
                    if (i === 0) {
                        tanItems[i].classList.add("active");
                    } else {
                        tanItems[i].classList.remove("active");
                    }
                }
            });
        </script>


        <!--     TEDDMEGMA     -->
        <div class="carousel-section py-4" id="carouselSection4">
            <h1 class="mb-4 w-75 m-auto text-center" style="color: var(--primary-teal)">TeddMegMa feladat</h1>
            <span class="close-btn">X</span>
            <div id="imageCarousel2" class="carousel slide">

                <div class="carousel-inner" id="carouselInner2">

                    <button class="btn carousel-select-btn" id="CourseSelect" style="display: none;">Ezt
                        választom</button>

                    <div class="carousel-item active">
                        <img src="img/carousel/teddmegma/001.png" class="d-block w-100" alt="teddmegma kep">
                    </div>
                    <div class="carousel-item course">
                        <img src="img/carousel/teddmegma/002.png" class="d-block w-100" alt="teddmegma kep"
                             loading="lazy">
                    </div>
                    <div class="carousel-item course">
                        <img src="img/carousel/teddmegma/003.png" class="d-block w-100" alt="teddmegma kep"
                             loading="lazy">
                    </div>
                    <div class="carousel-item course">
                        <img src="img/carousel/teddmegma/004.png" class="d-block w-100" alt="teddmegma kep"
                             loading="lazy">
                    </div>
                    <div class="carousel-item course">
                        <img src="img/carousel/teddmegma/005.png" class="d-block w-100" alt="teddmegma kep"
                             loading="lazy">
                    </div>
                    <div class="carousel-item course">
                        <img src="img/carousel/teddmegma/006.png" class="d-block w-100" alt="teddmegma kep"
                             loading="lazy">
                    </div>
                </div>


                <div class="navigation px-1">
                    <img src="img/carousel/arrow_l.png" class="nav-btn1 prev-btn" id="prevBtn01"
                         data-bs-target="#imageCarousel2" data-bs-slide="prev">
                    <img src="img/carousel/arrow_r.png" class="nav-btn1 next-btn" id="nextBtn01"
                         data-bs-target="#imageCarousel2" data-bs-slide="next">
                </div>
            </div>
            <!--gratulation slide-->
            <div class="mt-4 mb-4" id="GreetingSlide" style="display: none;">
                <img src="img/carousel/teddmegma/final.png" class="d-block w-100 mx-auto" alt="teddmegma kep"
                     style="margin-top: -20px;">
                <button class="btn carousel-select-btn" style="bottom: 120px; box-shadow: none;"
                        id="resetTeddMegMaCarousel">Kész</button>
            </div>
        </div>

        <script>

            function setFirstCarouselItemActive(carouselInnerId) {
                const carouselInner = document.getElementById(carouselInnerId);
                if (!carouselInner) return;
                const items = carouselInner.querySelectorAll('.carousel-item');
                console.log(items);
                if (items.length > 0) {
                    items[0].classList.add("active");
                }
                carouselInner.querySelectorAll('.carousel-item.course').forEach(item => item.classList.remove("active"));
            }

            // reset carousel
            document.getElementById('resetTeddMegMaCarousel').addEventListener('click', () => {
                hideAndScroll();
                setFirstCarouselItemActive('carouselInner2');
                document.getElementById("GreetingSlide").style.display = "none";
                document.getElementById("GreetingSlide").classList.remove("fade-in");
                document.getElementById('imageCarousel2').style.display = "block";
                document.getElementById("CourseSelect").style.display = "none";
            })

            function FadeBtn() {
                const courseSelectBtn = document.getElementById("CourseSelect");
                let activeCourseItems = [
                    ...document.querySelectorAll(".carousel-item.course.carousel-item-next.carousel-item-start"),
                    ...document.querySelectorAll(".carousel-item.course.carousel-item-prev.carousel-item-end")
                ];
                if (activeCourseItems.length > 0) {
                    if (courseSelectBtn) {
                        courseSelectBtn.style.display = "none";
                        setTimeout(() => {
                            courseSelectBtn.style.display = "block";
                            courseSelectBtn.classList.add("fade-in");
                        }, 300);
                        setTimeout(() => {
                            courseSelectBtn.classList.remove("fade-in");
                        }, 1000);
                    }
                }
                else {
                    courseSelectBtn.style.display = "none";
                }
            }

            // Fade in effect for CourseSelect button
            document.getElementById('prevBtn01').addEventListener("click", () => {
                FadeBtn();
            });
            document.getElementById('nextBtn01').addEventListener("click", () => {
                FadeBtn();
            });

            // Swipe gesture
            let touchStartX1 = 0;
            let touchEndX1 = 0;
            const actualSlider = document.getElementById('carouselSection4');

            actualSlider.addEventListener('touchstart', (e) => {
                touchStartX1 = e.changedTouches[0].screenX;
            });

            actualSlider.addEventListener('touchend', (e) => {
                touchEndX1 = e.changedTouches[0].screenX;
                if (touchEndX1 < touchStartX1 - 50) {
                    FadeBtn();
                }
                if (touchEndX1 > touchStartX1 + 50) {
                    FadeBtn();
                }
            });

            //reveal greeting slide
            document.getElementById('CourseSelect').addEventListener("click", () => {
                const greetingSlide = document.getElementById("GreetingSlide");
                greetingSlide.style.display = "block";
                greetingSlide.classList.add("fade-in");
                document.getElementById('imageCarousel2').style.display = "none";
            });
        </script>




        <!--    PÁRKAPCSOLATI TEST   -->
        <div class="carousel-section py-4" id="carouselSection3">
            <h1 class="mb-4 w-75 m-auto text-center" style="color: var(--primary-teal)">Kommunikációs mintáid</h1>
            <span class="close-btn">X</span>

            <!--    open slide    -->
            <div class="carousel-wrapper" id="testOpenSlide">
                <div class="p-3 mt-2">
                    <h1 class="card-title mt-1">Teszt</h1>
                    <p class="test-description">4 kommunikációs minta, ami ha jelen van nagy valószinűséggel a
                        kapcsolatotok felbomlásához vezet</p>
                    <p class="pb-2 pt-0 test-description"><b>Próbáld meg őszintén kiválasztani azt a választ, ami jellegében a legközelebb áll
                            hozzád.</b></p>
                    <button class="btn btn-center" id="startTestBtn">Elkezdem a
                        tesztet!</button>
                    <img src="img/carousel/test/hellboy.png" class="w-75" style="padding-top: 30px; "
                         alt="ördög">
                </div>
            </div>
            <!--    test slides   -->
            <div class="carousel-wrapper" id="testWrapper" style="display: none;">
                <div class="carousel">
                    <div class="slider" id="testSlider">
                        <!--    JS rendering cards  -->
                    </div>
                    <div class="navigation">
                        <img src="img/carousel/arrow_l.png" class="nav-btn1 prev-btn" id="prevBtn">
                        <img src="img/carousel/arrow_r.png" class="nav-btn1 next-btn" id="nextBtn">
                        <img src="img/carousel/close.png" class="nav-btn1 end-btn" style="display: none">
                    </div>
                </div>

            </div>
            <!--    next slide    -->
            <div id="imageCarousel4" class="carousel slide" style="display: none;">
                <div class="start-test-cover">
                    <p class="test-description" style="margin-top: 50px; text-align: left;">Valószinűleg időnként
                        kritikusan, védekezően, vagy elzárkózva reagálsz a feszültségekre - és talán már
                        észrevetted, hogy ezek a minták nem előrevisznek,hanem eltávolitanak. A jó hír? Az, hogy már
                        észrevetted ezt, azt jelzi: van benned önreflexió. És ez az első lépés a változáshoz.</p>
                    <button class="btn btn-bottom" id="nextslide1">Tovább</button>
                </div>
            </div>
            <!--    next slide    -->
            <div id="imageCarousel5" class="carousel slide" style="display: none;">
                <div class="start-test-cover">
                    <p class="test-description" style="font-size: 19px;">Ezek a minták nem sorsszerűek - hanem
                        tanultak, és mint ilyenek: át is írhatók. Ha tudatosítod, mikor bukkannak fel benned ezek a
                        reakciók, és az önismereti gyakorlatok és a TeddMegMa feladatok segítségével dolgoztok ezek
                        megáltoztatásán, a kapcsolatotok is visszatalálhat az intimitáshoz.</p>
                    <img src="img/carousel/test/visszatalal.png" class="test-img-bottom" loading="lazy">
                    <button class="btn btn-bottom" id="nextslide2">Tovább</button>
                </div>
            </div>
            <!--    final slide    -->
            <div id="imageCarousel6" class="carousel slide" style="display: none;">
                <div class="start-test-cover">
                    <h1 class="card-hl mt-5 mb-2" style="font-weight: bold; font-size: 45px !important;">Egy mondat
                        magamnak</h1>
                    <textarea class="card-answer mt-5"
                              placeholder="Ide be tudod magadnak írni, amit esetleg szeretnél megváltoztatni a kommunikációs mintáitokkal kapcsolatban..."
                              onfocus="this.placeholder = ''"></textarea>
                    <button class="btn btn-bottom" id="finalBtn" onclick="hideAndScroll()">Kész</button>
                </div>
            </div>
            <!--    progress bar    -->
            <div class="progress-container">
                <div class="container">
                    <div class="row">
                        <div class="col-2">
                            <span class="progress-label">Teszt</span>
                        </div>
                        <div class="col-8">
                            <div class="progress-bar-test" id="progressBarTest">
                            </div>
                        </div>
                        <div class="col-2">
                            <span class="progress-label">Egy mondat</span>
                        </div>
                    </div>
                    <div class="progress-header">
                    </div>
                </div>
            </div>
        </div>



        <!--    ÖNISMERETI GYAK - with class control  -->
        <div class="carousel-section py-4" id="carouselSection2">
            <h1 class="mb-4 w-75 m-auto text-center" style="color: var(--primary-teal)">Miért nehéz kimondanom:
                “Igazad
                van”</h1>
            <span class="close-btn">X</span>

            <!-- gyakorlat carousel -->
            <div id="gyakCarousel">
                <div class="carousel-wrapper" style="height: 650px !important;">
                    <div class="carousel">
                        <div class="slider">
                            <div class="carousel-card" style="background-color: var(--carousel-option-bg);">
                                <h2 class="card-hl mt-5 text-center" style="font-size: 36px !important">Miért nehéz
                                    kimondanom:
                                </h2>
                                <p class="answer-block">„Sajnálom,<br>igazad van"?</p>
                                <img class="card-image-bottom" src="./img/carousel/answer/woman.png"
                                     alt="Answer Image">
                            </div>
                            <div class="carousel-card">
                                <h2 class="card-hl"
                                    style="font-size: 42px !important; margin-top: 150px; font-weight: bold;">
                                    Belső ellenállás gyakorlat</h2>
                                <p class="test-description">Cél: Feltérképezni, miért vált egy egyszerű mondat belső
                                    tiltássá.
                                </p>
                            </div>
                            <div class="carousel-card">
                                <h2 class="card-hl">Mi volt az a pillanat, amikor először megfordult a fejedben,
                                    hogy
                                    talán igaza van - és mit éreztél ekkor?"
                                </h2>
                                <textarea class="card-answer" placeholder="Írd be a válaszod..."
                                          onfocus="this.placeholder = ''"></textarea>
                                <div class="card-footer">Kérdés 1 / 5</div>
                            </div>
                            <div class="carousel-card">
                                <h2 class="card-hl">Mi lett volna a veszteség, ha akkor ott kimondod: „igazad van"?
                                </h2>
                                <textarea class="card-answer" placeholder="Írd be a válaszod..."
                                          onfocus="this.placeholder = ''"></textarea>
                                <div class="card-footer">Kérdés 2 / 5</div>
                            </div>
                            <div class="carousel-card">
                                <h2 class="card-hl">Honnan ismerős ez az érzés? Volt-e olyan gyerekkori vagy korábbi
                                    élményed, ahol a hibázás megalázással, gyengeséggel vagy fájdalommal járt?</h2>
                                <textarea class="card-answer" placeholder="Írd be a válaszod..."
                                          onfocus="this.placeholder = ''"></textarea>
                                <div class="card-footer">Kérdés 3 / 5</div>
                            </div>
                            <div class="carousel-card">
                                <h2 class="card-hl" style="font-size: 20px !important;">Mit jelent számodra az, ha
                                    tudod, hogy a párodnak igaza van és ki is mondod neki „igazad van"? Azt, hogy
                                    lemondasz valamiről? Hogy alul maradsz? Hogy akkor te kevesebb vagy, mint ő?
                                </h2>
                                <textarea class="card-answer" placeholder="Írd be a válaszod..."
                                          onfocus="this.placeholder = ''"></textarea>
                                <div class="card-footer">Kérdés 4 / 5</div>
                            </div>
                            <div class="carousel-card">
                                <h2 class="card-hl">Mi változna kettőtök között, ha egyszer tényleg kimondanád:
                                    „Sajnálom, ebben neked volt igazad"?</h2>
                                <textarea class="card-answer" placeholder="Írd be a válaszod..."
                                          onfocus="this.placeholder = ''"></textarea>
                                <div class="card-footer">Kérdés 5 / 5</div>
                                <button class="btn btn-bottom" id="nextsCarousel">Tovább</button>
                            </div>
                        </div>
                        <div class="navigation">
                            <img src="img/carousel/arrow_l.png" class="nav-btn1 prev-btn">
                            <img src="img/carousel/arrow_r.png" class="nav-btn1 next-btn">
                            <img src="img/carousel/close.png" class="nav-btn1 end-btn" style="display: none">
                        </div>
                    </div>
                </div>
                <div class="progress-container">
                    <div class="container">
                        <div class="row">
                            <div class="col-2">
                                <span class="progress-label">Önismereti<br>gyakorlatok</span>
                            </div>
                            <div class="col-8">
                                <div class="progress-bar-test">
                                    <!-- Progress segments will be generated dynamically -->
                                    <img class="progress-arrow" src="./img/carousel/test/arrow_empty.png">
                                </div>
                            </div>
                            <div class="col-2">
                                <span class="progress-label">Séma<br>minták</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- image carousel -->
            <div id="imageCarouselGyak" class="carousel slide" style="display: none;">
                <div class="carousel-inner" id="carouselInner">
                    <div class="carousel-item active">
                        <img src="img/carousel/answer/001.png" class="d-block w-100" alt="önismereti gyakorlat">
                    </div>
                    <div class="carousel-item">
                        <img src="img/carousel/answer/002.png" class="d-block w-100" alt="önismereti gyakorlat"
                             loading="lazy">
                    </div>
                    <div class="carousel-item">
                        <img src="img/carousel/answer/003.png" class="d-block w-100" alt="önismereti gyakorlat"
                             loading="lazy">
                    </div>
                    <div class="carousel-item">
                        <img src="img/carousel/answer/004.png" class="d-block w-100" alt="önismereti gyakorlat"
                             loading="lazy">
                    </div>
                    <div class="carousel-item">
                        <img src="img/carousel/answer/005.png" class="d-block w-100" alt="önismereti gyakorlat"
                             loading="lazy">
                    </div>
                    <div class="carousel-item">
                        <img src="img/carousel/answer/006.png" class="d-block w-100" alt="önismereti gyakorlat"
                             loading="lazy">
                    </div>
                    <div class="carousel-item">
                        <img src="img/carousel/answer/007.png" class="d-block w-100" alt="önismereti gyakorlat"
                             loading="lazy">
                    </div>
                    <div class="carousel-item">
                        <img src="img/carousel/answer/008.png" class="d-block w-100" alt="önismereti gyakorlat"
                             loading="lazy">
                    </div>
                    <div class="carousel-item">
                        <div class="start-test-cover pt-4 mt-0" style="height: 720px;">
                            <h1 class="card-hl mb-2"
                                style="font-weight: bold; font-size: 45px !important; margin-top: 110px;">Egy mondat
                                magamnak</h1>
                            <textarea class="card-answer"
                                      placeholder="Itt feljegyezheted, ha a gyakorlat közben rájöttél valamire a működésed kapcsán."
                                      onfocus="this.placeholder = ''"></textarea>
                            <button class="btn btn-bottom" id="finalBtn" onclick="hideAndScroll()">Kész</button>
                        </div>
                    </div>
                </div>
                <div class="navigation px-1">
                    <img src="img/carousel/arrow_l.png" class="nav-btn1 prev-btn"
                         data-bs-target="#imageCarouselGyak" data-bs-slide="prev" id="prevBtnGyak">
                    <img src="img/carousel/arrow_r.png" class="nav-btn1 next-btn"
                         data-bs-target="#imageCarouselGyak" data-bs-slide="next" id="nextBtnGyak">
                </div>
                <script>
                    // Disable next-btn when last carousel-item is active
                    function updateGyakCarouselNav() {
                        const carousel = document.getElementById('imageCarouselGyak');
                        const items = carousel.querySelectorAll('.carousel-item');
                        const nextBtn = document.getElementById('nextBtnGyak');
                        const prevBtn = document.getElementById('prevBtnGyak');
                        const lastIdx = items.length - 1;
                        const activeIdx = Array.from(items).findIndex(item => item.classList.contains('active'));
                        // Disable next if last active
                        if (activeIdx === lastIdx) {
                            nextBtn.classList.add('disabled');
                            nextBtn.style.pointerEvents = 'none';
                            nextBtn.style.opacity = '0';
                        } else {
                            nextBtn.classList.remove('disabled');
                            nextBtn.style.pointerEvents = '';
                            nextBtn.style.opacity = '1';
                        }
                        // Optionally disable prev if first active
                        if (activeIdx === 0) {
                            prevBtn.classList.add('disabled');
                            prevBtn.style.pointerEvents = 'none';
                            prevBtn.style.opacity = '0';
                        } else {
                            prevBtn.classList.remove('disabled');
                            prevBtn.style.pointerEvents = '';
                            prevBtn.style.opacity = '1';
                        }
                    }
                    document.getElementById('imageCarouselGyak').addEventListener('slid.bs.carousel', updateGyakCarouselNav);
                    // Initial state
                    updateGyakCarouselNav();
                </script>
            </div>

        </div>

        <script>
            //go forward to the image carousel
            document.getElementById("nextsCarousel").addEventListener("click", () => {
                document.getElementById("gyakCarousel").style.display = "none";
                document.getElementById("imageCarouselGyak").classList.add("fade-in2");
                document.getElementById("imageCarouselGyak").style.display = "block";
            });
        </script>



    </div>
</section>


<!-- How it Works -->
<section id="how-it-works" class="pb-5">
    <div class="container">
        <div class="section-card">
            <h2 class="text-center mb-5 display-5">Hogyan működik a gyakorlatban?</h2>
            <p class="lead text-center mb-5">
                A Váláspajzs10 egy 10 leckéből álló érthető, gyakorlatias bevezetés a párkapcsolatok
                pszichológiájába. Segít megérteni, miért választottad a párod, hogyan működtök együtt, és
                mit tehettek azért, hogy hosszú távon is közel maradjatok egymáshoz.
            </p>

            <div class="row">
                <div class="col-lg-6 mb-4 mt-2">
                    <div class="text-center">
                        <div class="feature-icon">
                            <i class="fas fa-tasks"></i>
                        </div>
                        <h4>Kiszámítható ritmus</h4>
                        <p class="lead">Minden kedden és pénteken reggel érkezik egy új lecke e-mailben. Így van időd átgondolni,
                            mit olvastál, és beépíteni a mindennapokba, mielőtt jön a következő.</p>

                    </div>
                </div>
                <div class="col-lg-6 mb-5 mt-2">
                    <div class="text-center">
                        <div class="feature-icon">
                            <i class="fas fa-book-reader"></i>
                        </div>
                        <h4>Pszichológia, amit értesz</h4>
                        <p class="lead">Minden lecke egy hétköznapi, számotokra is ismerős történettel kezdődik, ezen keresztül
                            mutatjuk be a kapcsolati dinamikákat. A történet után elmagyarázzuk a működés mögött álló
                            pszichológiát, röviden és érthetően, a te nyelveden.</p>

                    </div>
                </div>
                <div class="col-lg-6 mb-4 mt-2">
                    <div class="text-center">
                        <div class="feature-icon">
                            <i class="fa-solid fa-location-crosshairs"></i>
                        </div>
                        <h4>Személyes felismerés</h4>
                        <p class="lead">Minden lecke végén egy rövid teszt vár, ami segít felismerni a saját mintáidat. Így nem csak
                            általánosságban tanulsz, hanem pontosan látod, hogyan jelenik meg mindez a ti
                            kapcsolatotokban.</p>


                    </div>
                </div>
                <div class="col-lg-6 mb-5">
                    <div class="text-center">
                        <div class="feature-icon">
                            <i class="fas fa-book-open"></i>
                        </div>
                        <h4>Teljes nyugalom</h4>
                        <p class="lead">Nem használunk szakzsargont, nincs mellébeszélés, csak az, ami tényleg működik. A
                            párodat is nyugodtan meghívhatod a programba ingyen. És hogy semmit se kockáztass: <strong>14
                                napos pénzvisszafizetési garanciát adunk</strong>.</p>

                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</section>


<!-- sikersztori -->
<section class="pb-4">
    <div class="container">
        <div class="section-card">
            <h4 class="siker-hl p-2">
                <img class="testimonial" src="img/testimonials/Katalin.jpg" alt="Katalin">
                Sikersztori -
                Katalin, 46
            </h4>
            <p class="fst-italic p-3 lead">
                „A Szeretetnyelv lecke után tudtam először kimondani a férjemnek azt az egyszerű mondatot:
                „Köszi, hogy elintézted, nagyon ügyes vagy.” (a kocsi biztosításait intézte el.) A „Köszi, hogy
                elintézted” eddig is könnyen ment, de azt, hogy „nagyon ügyes vagy” még soha nem
                mondtam. Most viszont kimondtam, és nagyon mélyre ment neki. Azt mondta, talán életében
                először dicsérték meg így, és akkor döbbent rá, mennyire hiányzott neki ez eddig.”
            </p>
        </div>
    </div>
</section>

<!--     4 MODUL       -->
<section id="modules" class="pt-5">
    <div class="container">
        <h2 class="text-center mb-5 display-5">Mit tanulsz a programban?</h2>

        <!-- Module 1 -->
        <div class="module-card">
            <div class="module-header" data-bs-toggle="collapse" data-bs-target="#module1" aria-expanded="true"
                 aria-controls="module1">
                <h3 class="d-inline">
                    <span style="color:var(--new-orange)">1. hét</span> - A kezdetek és a most
                </h3>
                <i class="fas fa-chevron-down collapse-icon"></i>
            </div>
            <div class="collapse show module-content" id="module1">
                <div class="module-body fade-in">
                    <div class="row">
                        <div class="col-lg-12">
                            <ol class="styled-list">
                                <li data-number="1.">lecke Miért pont vele? - A párválasztás pszichológiája</li>
                                <li data-number="2.">lecke Amiért még együtt vagytok - bevezetés a sémák világába</li>
                            </ol>

                        </div>

                        <div class="col-12">
                            <ul class="list-unstyled">
                                <li><i class="fa-solid fa-lightbulb icon-bulb" style="color: #2d6666;"></i>
                                    <em>
                                        A párválasztás mögött mindig ott vannak a gyerekkorban szerzett minták. Az első héten
                                        meglátod, miért pont őt választottad, és milyen belső hiedelmek hatnak rátok máig.
                                    </em>

                                <li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Module 2 -->
        <div class="module-card">
            <div class="module-header" data-bs-toggle="collapse" data-bs-target="#module2" aria-expanded="false"
                 aria-controls="module2">
                <h3 class="d-inline">
                    <span style="color:var(--new-orange)">2. hét</span> – Közel, távol, újra közel
                </h3>
                <i class="fas fa-chevron-down collapse-icon"></i>
            </div>
            <div class="collapse module-content" id="module2">
                <div class="module-body fade-in">
                    <div class="row">
                        <div class="col-lg-12">
                            <ol class="styled-list">
                                <li data-number="3.">lecke A rózsaszín ködtől a valóságig - miért ne ijedjetek meg, ha megjelenik az árnyék?</li>
                                <li data-number="4.">lecke Kapcsolati törések és helyreállításuk a mindennapjaitokban</li>
                            </ol>

                        </div>

                        <div class="col-12">
                            <ul class="list-unstyled">
                                <li><i class="fa-solid fa-lightbulb icon-bulb" style="color: #2d6666;"></i>
                                    <i>
                                        Az elején minden könnyű volt. Aztán jöttek a hibák, a viták, a hallgatások. Ez a hét arról
                                        szól, hogyan értsd meg, miért távolodtok el néha, és hogyan tudtok újra egymás mellett
                                        kikötni, ha a repedések megjelennek.
                                    </i>

                                <li>

                            </ul>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- Module 3 -->
        <div class="module-card">
            <div class="module-header" data-bs-toggle="collapse" data-bs-target="#module3" aria-expanded="false"
                 aria-controls="module3">
                <h3 class="d-inline">
                    <span style="color:var(--new-orange)">3. hét</span> - Amiben különbözünk és amikor kiborulunk
                </h3>
                <i class="fas fa-chevron-down collapse-icon"></i>
            </div>
            <div class="collapse module-content" id="module3">
                <div class="module-body fade-in">
                    <div class="row">
                        <div class="col-lg-12">
                            <ol class="styled-list">
                                <li data-number="5.">lecke Miért idegesít annyira a másik mássága? A Big Five személyiségmodell a kapcsolatban</li>
                                <li data-number="6.">lecke Hogyan viselkedsz, amikor nagy a nyomás rajtad? Kiborulsz, lefagysz? És a párod?
                                    Ebben a leckében megérted a kettőtök stresszreakcióit és, hogy hogyan tudjátok megállítani
                                    a veszekedések spirálját.
                                </li>
                            </ol>

                        </div>

                        <div class="col-12">
                            <ul class="list-unstyled">
                                <li><i class="fa-solid fa-lightbulb icon-bulb" style="color: #2d6666;"></i>
                                    <i>Miért van az, hogy a különbségekből vita lesz, a viták pedig nem hoznak megoldást?</i>
                                </li>

                            </ul>
                        </div>
                    </div>


                </div>
            </div>
        </div>

        <!-- Module 4 -->
        <div class="module-card">
            <div class="module-header" data-bs-toggle="collapse" data-bs-target="#module4" aria-expanded="false"
                 aria-controls="module4">
                <h3 class="d-inline">
                    <span style="color:var(--new-orange)">4. hét</span> - Intimitás és szeretet
                </h3>
                <i class="fas fa-chevron-down collapse-icon"></i>
            </div>
            <div class="collapse module-content" id="module4">
                <div class="module-body fade-in">
                    <div class="row">
                        <div class="col-lg-12">
                            <ol class="styled-list">
                                <li data-number="7.">lecke Vágytól az ágyig – Intimitás fenntartása a hosszú távú kapcsolatban</li>
                                <li data-number="8.">lecke Szeretetnyelvek máshogy. 5 lépés, amit tudtok tenni, ha különböző a szeretetnyelvetek.</li>
                            </ol>

                        </div>

                        <div class="col-12">
                            <ul class="list-unstyled">
                                <li><i class="fa-solid fa-lightbulb icon-bulb" style="color: #2d6666;"></i>
                                    <i>
                                        Lehet, hogy már rég nem emlékszel, mikor csókolt meg úgy utoljára, mint régen. És lehet
                                        hogy te is inkább a gyerekek körül vagy a munkádban forogsz, mint körülötte. A héten arról
                                        tanulsz, hogyan hozhatjátok vissza az intimitást a kapcsolatotokba.</i>
                                </li>

                            </ul>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!-- Module 5 -->
        <div class="module-card">
            <div class="module-header" data-bs-toggle="collapse" data-bs-target="#module4" aria-expanded="false"
                 aria-controls="module4">
                <h3 class="d-inline">
                    <span style="color:var(--new-orange)">5. hét</span> - Láthatatlan akadályok
                </h3>
                <i class="fas fa-chevron-down collapse-icon"></i>
            </div>
            <div class="collapse module-content" id="module4">
                <div class="module-body fade-in">
                    <div class="row">
                        <div class="col-lg-12">
                            <ol class="styled-list">
                                <li data-number="9.">lecke Miért nem változik a másik? - Meg lehet változtatni a párom?</li>
                                <li data-number="10.">lecke A pénz pszichológiája – mit jelent a pénz egy házasságban?</li>
                            </ol>

                        </div>

                        <div class="col-12">
                            <ul class="list-unstyled">
                                <li><i class="fa-solid fa-lightbulb icon-bulb" style="color: #2d6666;"></i>
                                    <i>
                                        Sokszor nem a szándék hiányzik, hanem valami láthatatlan erő tart vissza. Miért nem
                                        változik a párod akkor sem, ha szeretnéd, és hogy mit kezdhetsz ezzel, hogy ne csak
                                        falakba ütközz. Az utolsó lecke pedig a pénzről szól: azokról a láthatatlan hiedelmekről,
                                        amiket a:forintok mögé pakolunk. Biztonság, szabadság, hatalom - kinek mit jelent a pénz a
                                        kapcsolatban.</i>
                                </li>

                            </ul>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>

<!-- sikersztori -->
<section class="pb-4">
    <div class="container">
        <div class="section-card">
            <h4 class="siker-hl p-2">
                <img class="testimonial" src="img/testimonials/Norbert.jpg" alt="Norbert">
                Sikersztori -
                Norbert, 44
            </h4>
            <p class="fst-italic p-3 lead">
                „Őszintén, kicsit legyintettem, amikor azt olvastam, hogy kezdjük apró gesztusokkal újra. De
                amikor a konyhában átöleltem a feleségem miközben főzött, és csak úgy maradtunk egy
                percet, hirtelen leesett, mennyire hiányzott ez a közelség. Nem nagy dolgok kellenek, ezek
                az apró érintések hozták vissza köztünk a vágyat."
            </p>
        </div>
    </div>
</section>

<!-- Illustration 1 -->
<div class="container">
    <img class="illustration" src="img/illustration_1.png" alt="woman sad">
</div>

<!-- Gyakori kerdesek -->
<section class="pb-5 pt-2">
    <div class="container lead">
        <div class="section-card">
            <h2 class="text-center mb-5 display-5">Gyakori kérdések</h2>
            <div class="accordion" id="faqAccordion">
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                data-bs-target="#faq1">
                            Működik-e, ha csak az egyik fél csinálja?
                        </button>
                    </h2>
                    <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            igen, már az is sokat ad, ha te elkezded más szemmel látni a kapcsolatotokat. De ha
                            szeretnéd, <strong>a párodat a vásárlás után ingyen hozzáadhatod a programhoz</strong>, így ő is megkapja az
                            anyagokat. Tapasztalatból tudjuk: együtt sokkal könnyebb beszélgetni róla, kipróbálni a
                            gyakorlatokat, és érezni a változást.
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#faq2">
                            Mennyi idő alatt látsz eredményt?
                        </button>
                    </h2>
                    <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Sok résztvevő már az első hét után érez változást. A jelentős áttörések általában a 2-3. héten jönnek..
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#faq3">
                            Miért cicákkal?
                        </button>
                    </h2>
                    <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Mert lesznek keményebb, mélyebb témák is, és mi nem akarjuk, hogy ez túl száraz vagy
                            nyomasztó legyen. A cicák játékosan, humorral kísérnek végig a leckéken, segítenek oldani
                            a feszültséget, és mosolyt csalnak az arcotokra még akkor is, ha nehéz dolgokról van szó.
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#faq4">
                            Mi van, ha nem tetszik?
                        </button>
                    </h2>
                    <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Semmi gond. 14 napos pénzvisszafizetési garanciát adunk. Így kockázat nélkül
                            kipróbálhatod, ha úgy érzed, nem neked való, egyszerűen visszakapod a pénzed.
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#faq5">
                            Mi jön utána?
                        </button>
                    </h2>
                    <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Ha megszereted a folyamatot, beléphetsz a Váláspajzs előfizetéses programba. Ez egy 1
                            éves program, ami végigvezet a kapcsolatotok minden fontos területén. Olyan, mintha
                            kapnátok egy használati útmutatót egymáshoz.
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#faq6">
                            Mik a technikai követelmények?
                        </button>
                    </h2>
                    <div id="faq6" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            A program bármilyen modern böngészőből elérhető, mobilon és számítógépen is. 
                            A regisztrációhoz egy Google-fiókra lesz szükséged.
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

<!-- sikersztori -->
<section class="pb-4">
    <div class="container">
        <div class="section-card">
            <h4 class="siker-hl p-2">
                <img class="testimonial" src="img/testimonials/Judit.jpg" alt="Judit">
                Sikersztori -
                Judit, 48
            </h4>
            <p class="fst-italic p-3 lead">
                „A Váláspajzs segített kimondani dolgokat, amiket évek óta tologattunk. És azt is, hogy mit
                akarunk együtt 5 év múla. Most már nemcsak túléljük a napokat, hanem talán megyünk
                együtt valami felé és ez visszahozta nálunk az intimitást is."
            </p>
        </div>
    </div>
</section>

<!-- Illustration 2 -->
<div class="container" style="margin-bottom: -18px;">
    <img class="illustration" src="img/illustration_3.png" alt="happy sad" style="max-width: 300px;">
</div>

<!-- Expectations -->
<section id="expectations" class="pb-4">
    <div class="container">
        <div class="section-card">
            <h2 class="text-center mb-5 display-5">Mit várhatsz ettől az 5 héttől - őszintén?</h2>
            <div class="row">
                <div class="col-lg-6 row">
                    <div class="d-flex mb-3">
                        <i class="fas fa-check-circle text-success fs-4 me-3 mt-1"></i>
                        <div class="lead">
                            <strong>Nem fogod "megjavítani" a párodat.</strong> De megértheted, mi történik benned.
                        </div>
                    </div>
                    <div class="d-flex mb-3">
                        <i class="fas fa-check-circle text-success fs-4 me-3 mt-1"></i>
                        <div class="lead">
                            <strong>Nem garantálunk tökéletes kapcsolatot.</strong> De mutatunk utat egy működőbb
                            verzió felé.
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 row">
                    <div class="d-flex mb-3">
                        <i class="fas fa-check-circle text-success fs-4 me-3 mt-1"></i>
                        <div class="lead">
                            <strong>Nem kell egyedül kitalálnod, merre indulj.</strong> Minden kedden és pénteken kapsz egy új leckét,
                            ami érthetően végigvezet a legfontosabb helyzeteken.
                        </div>
                    </div>
                    <div class="d-flex mb-3">
                        <i class="fas fa-check-circle text-success fs-4 me-3 mt-1"></i>
                        <div class="lead">
                            És talán újra meglátod: <strong>a törések ellenére is van köztetek egy láthatatlan kapocs, amit
                                érdemes ápolni és megerősíteni.</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- Pricing -->
<section id="pricing" class="py-5" style="background: var(--warm-beige);">
    <div class="container">
        <h2 class="text-center mb-5 display-5">Csomag ára a teljes programért</h2>
        <div class="row justify-content-center">
            <div class="col-lg-5 mb-4">
                <div class="price-card">
                    <h3>Egyszeri díj</h3>
                    <div class="display-4 my-3">2.900 Ft</div>
                    <div class="d-flex mb-3">
                        <i class="fas fa-check-circle fs-4 me-3 mt-1"></i>
                        <div class="price-tag">
                            <p class="text-price">Teljes 5 hetes program.</p>
                        </div>
                    </div>
                    <div class="d-flex mb-3">
                        <i class="fas fa-check-circle fs-4 me-3 mt-1"></i>
                        <div class="price-tag">
                            <p class="text-price">Heti 2 ✖ 20 perc.</p>
                        </div>
                    </div>
                    <div class="d-flex mb-3">
                        <i class="fas fa-check-circle fs-4 me-3 mt-1"></i>
                        <div class="price-tag">
                            <p class="text-price">Ez az út nemcsak rólad, hanem kettőtökről szól. </br>Ezért ha szeretnéd, a párodat a
                                rendelés után teljesen ingyen hozzáadhatod a programhoz.
                            </p>
                        </div>
                    </div>
                    <div class="d-flex mb-3">
                        <i class="fas fa-check-circle fs-4 me-3 mt-1"></i>
                        <div class="price-tag">
                            <p class="text-price">14 napos pénzvisszafizetési garancia. </br>Ha nem hoz felismerést, visszakérheted a teljes
                                összeget.
                            </p>

                        </div>
                    </div>
                    <a href="https://buy.stripe.com/test_00w00k4lKcNh9cw6iieEo00" class="btn btn-light btn-lg w-100 mt-3">Elkezdem most!</a>

                </div>
            </div>

        </div>

    </div>
</section>

<!-- Illustration 3 -->
<div class="container pt-4">
    <img class="illustration" src="img/illustration_2.png" alt="happy coule"
         style="max-width: 300px; margin-bottom: -2px; z-index: -1;">
</div>


<!-- Final CTA -->
<section class="py-5" style="background: linear-gradient(135deg, var(--primary-teal) 0%, var(--light-teal) 100%);">
    <div class="container text-center text-white">
        <h2 class="display-4 mb-4">Készen állsz megvédeni a házasságod?</h2>
        <p class="lead mb-4" style="color: white">Kezdd el még ma az 5 hetes kapcsolatvédő programot!</p>
        <a href="https://buy.stripe.com/test_00w00k4lKcNh9cw6iieEo00" class="btn btn-light btn-lg px-5 py-3">
            <i class="fas fa-heart me-2"></i>
            Igen, szeretném elkezdeni!
        </a>
    </div>
</section>



<!-- FOOTER -->
<div class="py-3 text-center" style="background: white;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-3 mb-2">
                <i class="fas fa-shield-alt text-success me-2"></i>
                <span class="text-muted lead">Pénzvisszafizetési garancia</span>
            </div>
            <div class="col-md-3 mb-2">
                <i class="fas fa-user-md text-primary me-2"></i>
                <span class="text-muted lead">Gyakorlati tudás</span>
            </div>
            <div class="col-md-3 mb-2">
                <i class="fas fa-calendar-days text-info me-2"></i>
                <span class="test-muted lead">5 hét</span>
            </div>
            <div class="col-md-3 mb-2">
                <i class="fas fa-clock text-warning me-2"></i>
                <span class="text-muted lead">Heti 2X20 perc</span>
            </div>
        </div>
    </div>
</div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
@vite(['resources/js/script.js'])
<script>

    // Initialize carousel reveal buttons
    ["carousel-btn1", "carousel-btn2", "carousel-btn3", "carousel-btn4"].forEach((btnId, idx) => {
        const btn = document.getElementById(btnId);
        const sectionIds = ["carouselSection1", "carouselSection2", "carouselSection3", "carouselSection4"];
        if (btn) {
            btn.addEventListener("click", () => hideAllCarousel());
            btn.addEventListener("click", () => scrollToCarousel(sectionIds[idx]));
        }
    });

    function hideAllCarousel() {
        const carouselSections = document.getElementsByClassName("carousel-section");
        for (let i = 0; i < carouselSections.length; i++) {
            carouselSections[i].style.display = 'none';
            carouselSections[i].classList.remove('show');
        }
    }

    hideAllCarousel();

    function scrollToCarousel(sectionId) {
        const section = document.getElementById(sectionId);
        if (section) {
            section.style.display = 'block';
            section.classList.add('show');
            section.scrollIntoView({ behavior: 'smooth' });
        }
    }

    function scrollToSection(sectionId) {
        const section = document.getElementById(sectionId);
        if (section) {
            section.scrollIntoView({ behavior: 'smooth' });
        }
    }

    function hideAndScroll() {
        hideAllCarousel();
        scrollToSection("how-it-works");
    }

    //scroll back on close btn
    const closeButtons = document.getElementsByClassName("close-btn");
    for (let i = 0; i < closeButtons.length; i++) {
        closeButtons[i].addEventListener("click", () => {
            hideAndScroll();
        })
    }


</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
        crossorigin="anonymous"></script>
<script src="js/test_carousel.js"></script>
<script src="js/CarouselHandler.js"></script>
<script src="js/email_modal.js"></script>
<script>
    //carousel nav btns hide / show
    function setupCarouselControls(carouselId, prevBtnId, nextBtnId) {
        const carouselElement = document.querySelector(carouselId);
        const prevBtn = carouselElement.querySelector(prevBtnId);
        const nextBtn = carouselElement.querySelector(nextBtnId);

        const carousel = new bootstrap.Carousel(carouselElement, {
            wrap: false
        });

        function updateButtons() {
            const items = carouselElement.querySelectorAll('.carousel-item');
            const first = 0;
            const last = items.length - 1;
            const current = Array.from(items).findIndex(item => item.classList.contains('active'));

            prevBtn.style.opacity = current === first ? '0' : '1';
            nextBtn.style.display = current === last ? 'none' : 'block';
        }
        updateButtons();
        carouselElement.addEventListener('slid.bs.carousel', updateButtons);
    }

    setupCarouselControls('#imageCarousel1', '#prevBtn0', '#nextBtn0');
    setupCarouselControls('#imageCarousel2', '#prevBtn01', '#nextBtn01');
</script>
</body>

</html>
