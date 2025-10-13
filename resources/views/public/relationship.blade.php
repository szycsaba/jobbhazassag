<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="description" content="Kapcsolati törések és helyreállításuk a mindennapokban">
    <meta name="keywords" content="HTML, CSS, JavaScript">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kapcsolati törések és helyreállításuk a mindennapokban</title>
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
    <header class="header-bg text-white flex items-center">

        <i id="menuBurger" class="fa-solid fa-bars fixed ml-auto cursor-pointer text-[#326e6c] hover:text-gray-400 transition-colors duration-500 mt-[5rem]"></i>
        <nav class="navMenu items-center transition duration-700 ease-in-out translate-x-full">
            <ul class="grid grid-rows-6 justify-items-center gap-2">
                <li class="flex justify-between w-full items-center"> 
                    <i id="imgProfile" class="fa-regular fa-user cursor-pointer text-white-800 hover:text-gray-600 transition-colors duration-500"></i>
                    <i id="backArrow" class="fa-solid fa-arrow-right ml-auto cursor-pointer text-white-800 hover:text-gray-600 transition-colors duration-500"></i>
                </li>
                <li></li>

                <li class="flex items-center">
                    <a href="#lesson" class="hover:text-gray-600 transition-colors duration-500">Kapcsolati lecke</a>
                </li>
                <li class="flex items-center">
                    <a href="#quiz" class="hover:text-gray-600 transition-colors duration-500">Kvíz</a>
                </li>
                <li class="flex items-center">
                    <a href="#selfLearn" class="hover:text-gray-600 transition-colors duration-500">Önismereti gyakorlat</a>
                </li>
                <li class="flex items-center">
                    <a href="#whatWillITake" class="hover:text-gray-600 transition-colors duration-500">Megosztás</a>
                </li>
            </ul>

        </nav>

    </header>

    <main class="container-bg flex-1">
        <section class="flex flex-col justify-start items-center h-screen bg-[linear-gradient(rgba(0,0,0,0.1),rgba(0,0,0,0.3))] box-border">
            <h1 class="h-[25%] md:h-[30%] !text-white text-center !font-['Bona_Nova',serif] !font-[700] !text-[48px] md:!text-[83px] flex items-center justify-center w-full max-w-[901px] mt-0 mb-0">Védelem a válás ellen</h1>
            <div class="flex justify-center items-center h-[50%] md:h-[45%]">
                <div class="w-full h-full max-w-[901px] opacity-70">
                    <img src="/img/article/tores.png" alt="Background" class="w-full h-full object-contain object-center">
                </div>
            </div>
            <h5 class="h-[25%] md:h-[25%] !text-white text-center max-w-[901px] mx-auto mt-[40px] !text-[18px] !leading-[24px] md:!text-[32px] md:!leading-[38px] !font-['Noto_Sans',sans-serif] !font-bold flex items-center justify-center px-[10px] w-full">5 hetes program a házasságotok karbantartásáért</h5>
        </section>
        <section id="lesson" class="section">

            <h1 class="title font-extrabold fade-section opacity-0 translate-y-5 transition-all duration-700 ease-out">Kapcsolati törések</h1>
            <h5 class="subtitle font-bold fade-section opacity-0 translate-y-5 transition-all duration-700 ease-out mb-8">és helyreállításuk a mindennapokban</h5>
            <div class="whiteBox fade-section opacity-0 translate-y-5 transition-all duration-700 ease-out">
                <p>Zsófi reggel hatkor ébredt, de nem az ébresztőre, hanem a gyerek köhögésére.
                    Aztán láz, 38.6. Gyógyszer, teáskanál, borogatás. Egész nap ugyanaz a körforgás:
                    hőmérő, rajzfilm, hőmérő, tejbegríz. November volt, esett már reggeltől, Zsófiék egész
                    nap a lakásba szorultak.
                </p>

            </div>

            <div class="whiteBox fade-section opacity-0 translate-y-5 transition-all duration-700 ease-out">
                <p>Tizenegykor hívta először a férjét. Pétert. Nem volt nagy dolog, csak hallani akarta a
                    hangját, valami apróságot mondani, hogy „szegény Dani egész délelőtt nyűgös volt, de
                    most elaludt, és tudod, hiányzol.” Péter nem vette fel. Meetingen volt.
                </p>
                <p class="pt-2">
                    Zsófi írt is egy üzenetet. <span class="text-(color:--text-color-green) font-bold">Nem vádlót,
                            nem szemrehányót, csak egy kis jelzést:</span> „Képzeld, Dani 38,5. De most alszik.
                    Remélem, neked nem túl húzós a napod.” Elküldte, és bámulta a kijelzőt, de a három pötty
                    nem jelent meg.
                </p>
                <p class="pt-2">
                    Délután kettőkor újra próbálkozott. Újabb hívás, újabb üzenet.
                    „Csak jó lett volna hallani rólad.” Semmi. Péter közben prezentációt tartott,
                    bólogatott a főnöknek, jegyzetelt a laptopjába, pörgött a munkában, a telefonja nem
                    volt az asztalán.
                </p>
            </div>
            <div class="whiteBox fade-section opacity-0 translate-y-5 transition-all duration-700 ease-out">
                <p>Ebédnél, amikor látta Zsófi üzenetét, már épp gépelte volna a választ, de egy kollégája leült mellé beszélgetni,
                    ő pedig visszacsúsztatta a telefont a tálca mellé. Teljesen elfelejtette, hogy nem válaszolt.
                </p>
            </div>
            <div class="boxHolder justify-items-center fade-section opacity-0 translate-y-5 transition-all duration-700 ease-out">
                <div class="imgBox w-full max-w-[280px]">
                    <img class="image w-full h-auto" src="/img/article/ebed.png" alt="">
                </div>
            </div>
            <div class="whiteBox fade-section opacity-0 translate-y-5 transition-all duration-700 ease-out">
                <p>Hat óra lett, mire hazaért. A sötét lakásban meleg volt,a cipőjét még le se vette,
                    amikor Zsófi már ott állt előtte.
                </p>
            </div>
            <div class="transpBox fade-section opacity-0 translate-y-5 transition-all duration-700 ease-out">
                <p><i>“Egész nap kerestelek”. - mondta halkan. Nem volt benne kiabálás, csak az a hideg tónus, ami sokkal rosszabb.</p>
                <p class="pt-2">“Dolgoztam.” - felelte Péter, mintha ezzel minden el lenne intézve.</p>
                <p class="pt-2">“Azért ez nem működik, hogy vissza se hívsz. Itthon vagyok egész nap a beteg gyerekkel, és mi van, ha valami baj van, akkor kit hívjak?” - csattant Zsófi.</i></p>
            </div>
            <div class="whiteBox fade-section opacity-0 translate-y-5 transition-all duration-700 ease-out">
                <p>Péter bámult a cipőjére. Olyan fáradt volt, hogy az se tudta eldönteni,
                    Zsófinak igaza van-e, csak arra vágyott, hogy lerogyhasson a kanapéra, ahol Dani nézte a tévét.
                </p>
            </div>
            <div class="greenBox fade-section opacity-0 translate-y-5 transition-all duration-700 ease-out">
                <p>Zsófi egyetlen mozdulattal felkapta a táskáját.</p>
            </div>
            <div class="transpBox fade-section opacity-0 translate-y-5 transition-all duration-700 ease-out">
                <p><i>“Oldd meg, én elmegyek.”</i></p>
            </div>
            <div class="whiteBox fade-section opacity-0 translate-y-5 transition-all duration-700 ease-out">
                <p>Péter ott maradt. Levette a cipőjét, levette a kabátját és odalépett a gyerekhez, megpuszilta és visszahúzta rá a
                    takarót, Lerogyott a kanapére, egyszerre kavargott benne a bűntudat, a fáradtság meg a düh.
                </p>
            </div>
            <div class="greenBox fade-section opacity-0 translate-y-5 transition-all duration-700 ease-out">
                <p>A zsebében rezgett a telefon. Elővette. Az üzenet most érkezett Zsófitól:
                    Talán így majd megértesz.</p>
            </div>
            <h5 class="articleSubtitle font-extrabold fade-section opacity-0 translate-y-5 transition-all duration-700 ease-out pt-5">Mi az a kapcsolati törés? </h5>
            <div class="whiteBox fade-section opacity-0 translate-y-5 transition-all duration-700 ease-out">
                <p><span class="text-(color:--text-color-green) font-bold">Kapcsolati törésnek azt nevezzük, amikor egy pillanatra megszakad az érzelmi biztonság.</span>
                    A kapcsolati törés nem a látványos dolgokról szól. Nem kell ajtócsapkodás, nem kell hangos vita. Elég annyi, hogy az egyik fél hirtelen úgy érzi: <i>„most nem vagy velem, nem figyelsz rám, nem számítok neked.” </i>
                    Ez a pillanat szintén fájdalmas lehet, csak sokszor nehezebb felismerni.
                </p>
            </div>
            <div class="boxHolder justify-items-center fade-section opacity-0 translate-y-5 transition-all duration-700 ease-out">
                <div class="imgBox w-full max-w-[280px]">
                    <img class="image w-full h-auto" src="/img/article/tores.png" alt="">
                </div>
            </div>
            <div class="whiteBox fade-section opacity-0 translate-y-5 transition-all duration-700 ease-out">
                <p>
                    Sokan gondolják azt, hogy a jó kapcsolat titka, hogy nincsenek benne törések. Pedig ez tévedés.
                    <span class="text-(color:--text-color-green) font-bold">Törések minden kapcsolatban előfordulnak, még a legerősebbekben is.</span>
                    Az élet egyszerűen hozza magával a fáradtságot, a stresszt, a félreértéseket.
                    A különbséget nem az jelenti, hogy el tudjuk-e őket kerülni, hanem hogy van-e a felekben elég szeretet egymás felé, hogy tegyenek lépést a helyreállításért.
                    A hosszú távon stabil kapcsolatok abban különböznek, hogy képesek vagytok visszatalálni egymáshoz.
                </p>
            </div>
            <h5 class="articleSubtitle font-bold fade-section opacity-0 translate-y-5 transition-all duration-700 ease-out pt-5">Hétköznapi példák</h5>
            <div class="whiteBox fade-section opacity-0 translate-y-5 transition-all duration-700 ease-out">
                <p>
                    A kapcsolati törések legtöbbször nem abból születnek, hogy valaki valami óriási hibát követ el.
                    Sokkal inkább a mindennapokban, apróságokból pattannak ki.
                </p>
            </div>
            <div class="whiteBox fade-section opacity-0 translate-y-5 transition-all duration-700 ease-out">
                <p>
                    Ott van például, amikor elfelejted elhozni a csomagot a postáról. Reggel szólt a párod: „nagyon fontos, ma lejár, ne felejtsd el.” Te persze elfelejtetted. Mire hazaérsz, a csomagot már visszavitték, és a párod arcán látod a csalódottságot.
                    Nem kiabál, de látod, benne ott van:<i> „Megint nem számíthattam rád.” </i>Ez egy mini törés.
                </p>
            </div>
            <div class="whiteBox fade-section opacity-0 translate-y-5 transition-all duration-700 ease-out">
                <p>
                    Aztán ott vannak a veszekedések közben elszabadult mondatok. Például amikor dühből odaszúrsz:
                    „Most pont olyan vagy, mint az apád.” Lehet, hogy csak kicsúszott, lehet, hogy akkor és ott igaznak érezted.
                    De <span class="text-(color:--text-color-green) font-bold">a másikban megnyom egy gombot, és hirtelen nemcsak a jelenről van szó,
                         hanem egy egész gyerekkorról.</span> Ez már nem apró törés, hanem mély karcolás.
                </p>
            </div>
            <div class="whiteBox fade-section opacity-0 translate-y-5 transition-all duration-700 ease-out">
                <p>
                    És előfordul, hogy nem a pillanat szüli, hanem a rosszul hangolt időzítés. Elmondtad, hogy júniusban lesz a céges tréning, fontos, nem lehet kihagyni.
                    A párod mégis arra a hétre veszi meg a repülőjegyet a nyári nyaralásra. Ő lelkes, te viszont csak azt érzed:
                    <i> „mintha nem is figyelt volna rám.”</i> Egy repülőjegy miatt egyszerre omlik rád
                    a figyelmetlenség, a csalódás és az, hogy megint minden bonyolultabb lett a kelleténél.
                </p>
            </div>
            <div class="boxHolder justify-items-center fade-section opacity-0 translate-y-5 transition-all duration-700 ease-out">
                <div class="imgBox w-full max-w-[280px]">
                    <img class="image w-full h-auto" src="/img/article/repulojegy.png" alt="">
                </div>
            </div>
            <div class="greenBox fade-section opacity-0 translate-y-5 transition-all duration-700 ease-out">
                <p>Ilyenek a hétköznapi törések. Nem nagy drámák, hanem kicsi repedések a falon. De ha nem javítjuk őket,
                    idővel ugyanúgy szétfeszítik a szerkezetet, mint a nagyobb repedések.</p>
            </div>
            <h5 class="articleSubtitle font-extrabold fade-section opacity-0 translate-y-5 transition-all duration-700 ease-out pt-5">Hogyan néz ki a helyreállítás?</h5>
            <div class="whiteBox fade-section opacity-0 translate-y-5 transition-all duration-700 ease-out">
                <p>
                    <span class="text-(color:--text-color-green) font-bold">A valódi helyreállításhoz kell valami, amitől sokan ódzkodunk: az egó félretétele.</span>
                    Belátni, hogy
                    hibáztunk ugyanis nem egyszerű. Vannak, akik szinte „gyárilag” képtelenek rá. Mem megyünk most bele a
                    mélyebb pszichológiai okaiba ennek, de tény, hogy sokunknak küzdelem kimondani akár magunkban is:
                    <i> „Sajnálom, hibáztam.”</i> Pedig bizonyos helyzetekben nem elég egy ölelés vagy egy apró gesztus.
                    Nagyobb töréseknél igenis ki kell
                    mondani a szavakat, és mellé oda kell tenni a tetteket is, hogy helyrehozzuk, amit elrontottunk.
                </p>
            </div>
            <div class="whiteBox fade-section opacity-0 translate-y-5 transition-all duration-700 ease-out">
                <p>
                    Az első példánál – elfelejtetted elhozni a csomagot a postáról, és visszavitték – a helyreállítás nem annyi,
                    hogy kimondod, “Bocs, ezt elbénáztam”. Ki kell tudnod mondani:
                    <i> „Tudom, fontos volt, sajnálom, hogy elfelejtettem.”</i>
                    És mellé felajánlod: <i> „Én intézem el az újrarendelést, ne neked legyen vele dolgod.”</i><br>
                    <span class="text-(color:--text-color-green) font-bold"> Ez a kettő együtt számít: bocsánatkérés és helyreállítás.</span>
                </p>
                <p class="pt-2">

                    A második helyzetben, amikor vita közben odaszúrsz: <i>„Most pont olyan vagy, mint az apád.”</i> – az
                    igazi helyreállítás az, ha utána odalépsz, és azt mondod: <i> „Nagyon sajnálom, hogy ezt mondtam. Tudom,
                        mennyire bántó, és nem így akartam. Csak elvesztettem a türelmem.”</i> És itt sem biztos, hogy elég a szó: utána
                    ell egy gesztus, ami visszavezeti a másikat a biztonságba,
                    egy ölelés, egy odafordulás, valami, amivel megérzi, hogy komolyan gondolod.
                </p>
            </div>
            <div class="greenBox fade-section opacity-0 translate-y-5 transition-all duration-700 ease-out">
                <p>A harmadik helyzet, a repülőjegyes történet már nagyobb léptékű törés. Ott sem elég pusztán bocsánatot kérni, mert a másik csak akkor érzi, hogy fontos,
                    ha a hibát valahogy tényleg korrigálod.</p>
            </div>
            <h5 class="articleSubtitle font-extrabold fade-section opacity-0 translate-y-5 transition-all duration-700 ease-out pt-5">Három út van</h5>
            <div class="solutionBox flex flex-col md:flex-row gap-2 fade-section opacity-0 translate-y-5 transition-all duration-700 ease-out">
                <div class="path1 flex-1 pb-4">
                    <div class="flex justify-center">
                        <i id="bulbIcon" class="fa-solid fa-lightbulb" style="color: #1f5145;"></i>
                    </div>
                    <h5 class="font-bold pt-4">Praktikus megoldás</h5>
                    <p class="text-base pt-4 text-left">Módosítod a jegyet, még ha kerül is pénzbe és utánajárásba. Ez mutatja, hogy vállalod a felelősséget.</p>

                </div>
                <div class="path2 flex-1 bt-2 pb-4">
                    <div class="flex justify-center">
                        <i id="handshakeIcon" class="fa-solid fa-handshake" style="color: #1f5145;"></i>
                    </div>
                    <h5 class="font-bold pt-4">Kompromisszum</h5>
                    <p class="text-base pt-4 text-center">Ha nem lehet változtatni, a család elutazik, és te a tréning után utánuk mész. Nem tökéletes, de együtt keresitek a kiutat.</p>


                </div>
                <div class="path3 flex-1 pb-4">
                    <div class="flex justify-center">
                        <i id="clockIcon" class="fa-solid fa-clock" style="color: #112f36;"></i>
                    </div>
                    <h5 class="font-bold pt-4">Radikális gesztus</h5>
                    <p class="text-base pt-4 text-center">Beteget jelentesz a munkahelyen, és elutazol. Ez erős üzenet: a közös idő fontosabb, mint a munka. Nem hosszú távú stratégia, de egyszer-egyszer hatalmas erőt adhat a kapcsolatnak</p>
                </div>

            </div>
            <div class="greenBox fade-section opacity-0 translate-y-5 transition-all duration-700 ease-out">
                <p>A helyreállítás tehát két részből állhat: bocsánatkérés és cselekvés. A törés nagysága dönti el, hogy mindkettőre szükség van-e.</p>
            </div>
            <div class="boxHolder justify-items-center fade-section opacity-0 translate-y-5 transition-all duration-700 ease-out">
                <div class="imgBox w-full max-w-[280px]">
                    <img class="image w-full h-auto" src="/img/article/vaza.png" alt="">
                </div>
            </div>
            <div class="solutionBox flex flex-col md:flex-row gap-2 fade-section opacity-0 translate-y-5 transition-all duration-700 ease-out">
                <div class="path1 flex-1">

                    <h5 class="subtitle font-bold pt-4">Mini töréseknél</h5>
                    <p class="text-base pt-4 text-center">(pl. elfelejtett apróságok, közbevágás,
                        egy figyelmetlen mozdulat) sokszor <span class="text-(color:--text-color-green) font-bold">elég egy gesztus vagy egy ölelés</span>, mert a
                        másik gyorsan érzi belőle, hogy „itt vagy, fontos vagy nekem”. Ilyenkor nem
                        kell nagy szavakat tenni mellé.</p>

                </div>
                <div class="path2 flex-1">

                    <h5 class="subtitle font-bold pt-4">Közepes töréseknél</h5>
                    <p class="text-base pt-4 text-center">(pl. figyelmetlenség, elfelejtett csomag,
                        kisebb megszegése a másik kérésének) általában kell <span class="text-(color:--text-color-green) font-bold">szó és kell tett is</span>: kimondani,
                        hogy sajnálod, és felajánlani valami jóvátételt (újrarendeled, elintézed). Ez adja a
                        hitelességet.
                    </p>
                </div>
                <div class="path3 flex-1">

                    <h5 class="subtitle font-bold pt-4">Nagy töréseknél</h5>
                    <p class="text-base pt-4 text-center">(pl. bántó mondat, kiabálás, rossz időzítéssel hozott fontos
                        döntés, elfelejtett ígéret) <span class="text-(color:--text-color-green) font-bold">szinte biztos, hogy mindkettő kell</span>: a bocsánatkérés kimondva és valami
                        cselekvés, ami visszaépíti a bizalmat. Itt önmagában egy „bocs” vagy egy ölelés kevés lenne.</p>
                </div>

            </div>
            <h5 class="articleSubtitle font-extrabold fade-section opacity-0 translate-y-5 transition-all duration-700 ease-out pt-5 text-left">Egy párkapcsolat olyan, mint a wifi</h5>
            <h5 class="articleSubtitleMini font-bold fade-section opacity-0 translate-y-5 transition-all duration-700 ease-out">néha elmegy a jel, és újra kell kapcsolódni</h5>
            <div class="whiteBox fade-section opacity-0 translate-y-5 transition-all duration-700 ease-out">
                <p>
                    A törések és a helyreállítások messze túlmutatnak az adott pillanaton. Egy gyerek például pontosan ebből tanulja meg, hogy egy konfliktus nem a világ vége.
                </p>
            </div>
            <div class="greenBox fade-section opacity-0 translate-y-5 transition-all duration-700 ease-out">
                <p>Minden sikeres helyreállítás kicsit olyan, mint egy újabb öltés a kapcsolatotok szövetében. Ha mindig képesek vagytok összevarrni, a szövet nem gyengül, hanem erősebb lesz. Ha viszont a helyreállítás elmarad, a sérelmek lassan egymásra rakódnak. </p>
            </div>
            <div class="whiteBox fade-section opacity-0 translate-y-5 transition-all duration-700 ease-out">
                <p>
                    Aki gyerekként sosem kapott bocsánatkérést, annak gyógyító erejű, ha a párja ki tudja mondani: „Sajnálom.” De másik oldalról is: katartikus élmény, ha valaki életében először ki tudja mondani, hogy “Bocsánat, hibáztam” és látja, hogy nem dől össze a világ,
                    nem lesz ettől kevesebb a párja szemében. <span class="text-(color:--text-color-green) font-bold">Ezek a pillanatok írják át a régi mintáitokat.</span>
                </p>
            </div>
            <div class="whiteBox fade-section opacity-0 translate-y-5 transition-all duration-700 ease-out">
                <p>
                    Zsófi és Péter története tipikus: mindennapi helyzetekből: beteg gyerek, túlterhelt munka,
                    elfelejtett üzenetek, könnyen lesz kapcsolati törés. A döntő nem az, hányszor történik ilyen,
                    hanem hogy <span class="text-(color:--text-color-green) font-bold">megvan-e bennetek a képesség a helyreállításra:
                         kimondani a bocsánatkérést, keresni a megoldást, és újra egymás felé fordulni.</span>
                </p>
            </div>
            <h5 class="articleSubtitle font-extrabold fade-section opacity-0 translate-y-5 transition-all duration-700 ease-out pt-5 text-left">Teszt</h5>
            <h5 class="articleSubtitleMini font-bold fade-section opacity-0 translate-y-5 transition-all duration-700 ease-out">Kapcsolati törések és helyreállításuk a mindennapokban</h5>
            <div id="quiz" class="quizBox fade-section opacity-0 translate-y-5 transition-all duration-700 ease-out">
                <h5 class="question text-3xl text-(color:--text-color-green) font-bold mb-4">Mit jelent pontosan a „kapcsolati törés”?</h5>
                <div class="answerBox mb-4">
                    <p> A) Egy helyzet, amikor teljesen eltűnik a bizalom a kapcsolatból. </p>
                </div>
                <div class="answerBox mb-4">
                    <p> B) Amikor az egyik fél hűtlenné válik.</p>
                </div>
                <div class="answerBox mb-4">
                    <p> C) Egy pillanatot, amikor az egyik fél úgy érzi, hogy a másik nincs vele, nem figyel rá</p>
                </div>      
                <div class="w-full bg-[var(--yellowProgressBarText-bg)] rounded-full h-4">
                    <div class="bg-[var(--progressBar-bg)] h-4 rounded-full transition-all duration-500 ease-in-out" style="width: 14%;"></div>
                    <div class="flex justify-center mt-2">
                        <span class="text-sm font-medium text-gray-700">1 / 7</span>
                    </div>
                </div>
             </div> 
             <h5 class="articleSubtitle font-extrabold fade-section opacity-0 translate-y-5 transition-all duration-700 ease-out pt-5 text-left">Mini önismeret</h5>
             <div class="transpBox fade-section opacity-0 translate-y-5 transition-all duration-700 ease-out">
                <div class="dropdownMenu max-w-[850px] m-auto">
                    <div class="w-full mb-4">
                            <button class="dropdownButton w-full bg-[var(--butterYellow-bg)] border border-white rounded-full px-4 py-2 text-left flex justify-between items-center focus:ring-2 focus:ring-white-500">
                            1. Mi volt egy helyzet a közelmúltban, ahol én okoztam a kapcsolati törést?

                            <svg xmlns="http://www.w3.org/2000/svg" class="arrowIcon h-5 w-5 text-grey-500 transform transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" id="arrowIcon">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                            </button>
                            <div id="dropdownInputWrapper" class="mt-1 rounded-lg">
                            <input type="text" placeholder="Írj egy szöveget" class="inpArea hidden bg-[var(--butterYellow-bg)] w-full  rounded-full px-3 py-2 transition">
                            </div>
                    </div>
                    <div class="w-full mb-4">
                            <button class="dropdownButton w-full bg-[var(--butterYellow-bg)] border border-white rounded-full px-4 py-2 text-left flex justify-between items-center focus:ring-2 focus:ring-white-500">
                            2. Hogy reagáltam az adott helyzetben, amikor a párom felhozta vagy én megéreztem, hogy itt most egy törés történi? 

                            <svg xmlns="http://www.w3.org/2000/svg" class="arrowIcon h-5 w-5 text-grey-500 transform transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" id="arrowIcon">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                            </button>
                            <div class="mt-1 rounded-lg">
                            <input type="text" placeholder="Írj egy szöveget" class="inpArea hidden bg-[var(--butterYellow-bg)] w-full  rounded-full px-3 py-2 transition">
                            </div>
                    </div>
                    <div class="w-full mb-4">
                            <button class="dropdownButton w-full bg-[var(--butterYellow-bg)] border border-white rounded-full px-4 py-2 text-left flex justify-between items-center focus:ring-2 focus:ring-white-500">
                            3. Tudtam alkalmazni a két lépést: felelősségvállalás+helyreállítás? Ha nem, mi akadályozott meg benne?

                            <svg xmlns="http://www.w3.org/2000/svg" class="arrowIcon h-5 w-5 text-grey-500 transform transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" id="arrowIcon">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                            </button>
                            <div class="mt-1 rounded-lg">
                            <input type="text" placeholder="Írj egy szöveget" class="inpArea hidden bg-[var(--butterYellow-bg)] w-full  rounded-full px-3 py-2 transition">
                            </div>
                    </div>
                    <div class="w-full mb-4">
                            <button class="dropdownButton w-full bg-[var(--butterYellow-bg)] border border-white rounded-full px-4 py-2 text-left flex justify-between items-center focus:ring-2 focus:ring-white-500">
                            4. Hogyan szoktam reagálni a párom töréseire: azonnal jelzem, ha bánt, vagy inkább magamba zárom?

                           <!--  <svg xmlns="http://www.w3.org/2000/svg" class="arrowIcon h-5 w-5 text-grey-500 transform transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" id="arrowIcon">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg> -->
                            </button>
                            <div class="mt-1 rounded-lg">
                            <input type="text" placeholder="Írj egy szöveget" class="inpArea hidden bg-[var(--butterYellow-bg)] w-full  rounded-full px-3 py-2 transition">
                            </div>
                    </div>
                    <div class="w-full mb-4">
                            <button class="dropdownButton w-full bg-[var(--butterYellow-bg)] border border-white rounded-full px-4 py-2 text-left flex justify-between items-center focus:ring-2 focus:ring-white-500">
                            5. Van konkrét apróság (pl. ölelés, üzenet, közös vacsora), ami nálunk bevált a törések helyreállítására?
        
                            <svg xmlns="http://www.w3.org/2000/svg" class=" arrowIcon h-5 w-5 text-grey-500 transform transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" id="arrowIcon">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                            </button>
                            <div class="mt-1 rounded-lg">
                            <input type="text" placeholder="Írj egy szöveget" class="inpArea hidden bg-[var(--butterYellow-bg)] w-full  rounded-full px-3 py-2 transition">
                            </div>
                    </div>
                </div>
             </div>
             <div class="emailBox fade-section opacity-0 translate-y-5 transition-all duration-700 ease-out mt-4 mb-4">
                <h5 class="articleSubtitle font-extrabold text-center mb-6 mt-4">Mondd el a párodnak</h5>
                <p class="text-[var(--text-color-green)]! fontSans text-center font-semibold text-[1.2rem] mb-4 pl-2 pr-2 sm:pl-0 sm:pr-0">Ha szeretnéd tudatni a pároddal, hogy elvégezted a Jobb Házasság Akadémia Kapcsolati törések és helyreállításuk leckéjét, írd be a párod címét és mi küldünk neki egy emailt erről. </p>
                <div class="pl-2 pr-2 sm:pl-0 sm:pr-0 text-center">
                <span class="text-[var(--text-color-green)] fontSans mb-8 mt-4"><i class="">Természetesen nem tároljuk el partnered email címét, hacsak nem vesz ő is részt a programban.</i></span>
                </div>
                <div class="flex justify-center mt-8 mb-8">
                    <div class="flex w-full sm:max-w-lg rounded-[20px] shadow-[0_1px_3px_rgba(0,0,0,0.1),0_-1px_3px_rgba(0,0,0,0.1)] overflow-hidden">
                            <input
                                type="text"
                                placeholder="Partnered email címe..."
                                class="flex-grow py-2.5 px-8 text-base text-gray-700 focus:outline-none cursor-pointer border-t border-b border-gray-200 rounded-none"
                            />
                            <button
                                type="button"
                                class="flex justify-center items-center fontSans text-white bg-[#587a7b] hover:scale-105 transition transform shadow-[0_4px_12px_rgba(0,0,0,0.08)] hover:bg-[#328d8d] hover:shadow-[0_4px_12px_rgba(0,0,0,0.08)] font-semibold text-base py-2.5 px-6 sm:px-8 md:px-10 text-center transition-all cursor-pointer rounded-l-[20px] rounded-r-[20px]">
                                Küldés
                            </button>
                    </div>
                </div>
            </div>












        </section>

    </main>
    <footer class="h-16">

    </footer>

</div>
@vite(['resources/js/article.js'])
</body>
</html>
