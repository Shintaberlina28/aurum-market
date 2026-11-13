<?php

require_once "config/database.php";

/* =========================================================
   GET LATEST MARKET DATA
========================================================= */

$market = null;

$query = "
    SELECT *
    FROM market_updates
    ORDER BY updated_at DESC
    LIMIT 1
";

$result = $conn->query($query);

if ($result && $result->num_rows > 0) {
    $market = $result->fetch_assoc();
}


/* =========================================================
   DEFAULT MARKET DATA
========================================================= */

$symbol = $market['symbol'] ?? 'XAU/USD';
$price = isset($market['price']) ? (float) $market['price'] : 0;

$changePercent = isset($market['change_percent'])
    ? (float) $market['change_percent']
    : 0;

$status = $market['market_status'] ?? 'Neutral';
$updatedAt = $market['updated_at'] ?? null;


/* =========================================================
   FORMAT DATE
========================================================= */

if ($updatedAt) {

    $formattedDate = date(
        "d M Y, H:i",
        strtotime($updatedAt)
    );

} else {

    $formattedDate = "-";

}


/* =========================================================
   MARKET CHANGE
========================================================= */

if ($changePercent > 0) {

    $changeClass = "text-emerald-400";
    $changeSign = "+";

} elseif ($changePercent < 0) {

    $changeClass = "text-red-400";
    $changeSign = "";

} else {

    $changeClass = "text-gray-400";
    $changeSign = "";

}


/* =========================================================
   MARKET STATUS COLOR
========================================================= */

$statusLower = strtolower($status);

if (
    strpos($statusLower, 'bull') !== false ||
    strpos($statusLower, 'up') !== false ||
    strpos($statusLower, 'strong') !== false
) {

    $statusClass = "bg-emerald-500/10 text-emerald-400";

} elseif (
    strpos($statusLower, 'bear') !== false ||
    strpos($statusLower, 'down') !== false ||
    strpos($statusLower, 'weak') !== false
) {

    $statusClass = "bg-red-500/10 text-red-400";

} else {

    $statusClass = "bg-yellow-500/10 text-yellow-400";

}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Victory Market — Victory Internasional Futures
    </title>


    <!-- =====================================================
         GOOGLE FONT
    ====================================================== -->

    <link
        rel="preconnect"
        href="https://fonts.googleapis.com"
    >

    <link
        rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin
    >

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    >


    <!-- =====================================================
         TAILWIND CSS
    ====================================================== -->

    <script src="https://cdn.tailwindcss.com"></script>


    <script>

        tailwind.config = {

            theme: {

                extend: {

                    colors: {

                        aurum: {

                            gold: '#D4AF37',

                            dark: '#08090C',

                            surface: '#111318',

                            card: '#17191F'

                        }

                    },

                    fontFamily: {

                        sans: ['Inter', 'sans-serif']

                    }

                }

            }

        }

    </script>


    <!-- =====================================================
         CUSTOM CSS
    ====================================================== -->

    <link
        rel="stylesheet"
        href="/aurum/assets/css/style.css"
    >

</head>


<body class="bg-aurum-dark text-white font-sans">


<!-- =========================================================
     NAVBAR
========================================================= -->

<header
    class="
        fixed
        top-0
        left-0
        right-0
        z-50
        border-b
        border-white/10
        bg-[#08090c]/80
        backdrop-blur-xl
    "
>

    <div
        class="
            max-w-7xl
            mx-auto
            px-6
            lg:px-8
        "
    >

        <nav
            class="
                h-20
                flex
                items-center
                justify-between
            "
        >


            <!-- =================================================
                 LOGO
            ================================================== -->

            <a
                href="/aurum/"
                class="flex items-center gap-3"
            >

                <!-- VICTORY LOGO -->

                <div
                    class="
                        flex
                        items-center
                        justify-center
                        w-12
                        h-12
                        overflow-hidden
                    "
                >

                    <img
                        src="/aurum/assets/images/victory-logo.jpeg"
                        alt="Victory Internasional Futures"
                        class="w-full h-full object-contain"
                    >

                </div>


                <!-- BRAND NAME -->

                <div>

                    <div
                        class="
                            font-bold
                            tracking-[0.15em]
                        "
                    >

                        VICTORY MARKET

                    </div>

                    <div
                        class="
                            text-[9px]
                            text-gray-500
                            tracking-[0.25em]
                            uppercase
                        "
                    >

                        Victory Internasional Futures

                    </div>

                </div>

            </a>


            <!-- =================================================
                 DESKTOP NAVIGATION
            ================================================== -->

            <div
                class="
                    hidden
                    lg:flex
                    items-center
                    gap-7
                    text-sm
                "
            >

                <a
                    href="/aurum/"
                    class="
                        text-white
                        hover:text-aurum-gold
                        transition
                    "
                >
                    Home
                </a>


                <a
                    href="/aurum/market/"
                    class="
                        text-gray-400
                        hover:text-aurum-gold
                        transition
                    "
                >
                    Daily Market
                </a>


                <a
                    href="/aurum/news/"
                    class="
                        text-gray-400
                        hover:text-aurum-gold
                        transition
                    "
                >
                    Market News
                </a>


                <a
                    href="/aurum/demo_account/"
                    class="
                        text-gray-400
                        hover:text-aurum-gold
                        transition
                    "
                >
                    Platform
                </a>


                <a
                    href="/aurum/videos/"
                    class="
                        text-gray-400
                        hover:text-aurum-gold
                        transition
                    "
                >
                    Videos
                </a>


                <a
                    href="/aurum/ebooks/"
                    class="
                        text-gray-400
                        hover:text-aurum-gold
                        transition
                    "
                >
                    E-Book
                </a>

            </div>


            <!-- =================================================
                 CTA
            ================================================== -->

            <div
                class="
                    hidden
                    md:flex
                    items-center
                    gap-3
                "
            >

                <a
                    href="/aurum/ebooks/"
                    class="
                        px-5
                        py-2.5
                        rounded-xl
                        bg-aurum-gold
                        text-black
                        text-sm
                        font-semibold
                        hover:scale-105
                        transition
                    "
                >

                    Start Learning

                </a>

            </div>


            <!-- =================================================
                 MOBILE BUTTON
            ================================================== -->

            <button
                id="menuButton"
                type="button"
                class="
                    lg:hidden
                    w-10
                    h-10
                    rounded-lg
                    border
                    border-white/10
                    flex
                    items-center
                    justify-center
                "
                aria-label="Open menu"
            >

                ☰

            </button>

        </nav>


        <!-- =================================================
             MOBILE MENU
        ================================================== -->

        <div
            id="mobileMenu"
            class="
                mobile-menu
                lg:hidden
                pb-5
            "
        >

            <div
                class="
                    flex
                    flex-col
                    gap-4
                    text-sm
                "
            >

                <a
                    href="/aurum/"
                    class="hover:text-aurum-gold transition"
                >
                    Home
                </a>


                <a
                    href="/aurum/market/"
                    class="hover:text-aurum-gold transition"
                >
                    Daily Market
                </a>


                <a
                    href="/aurum/news/"
                    class="hover:text-aurum-gold transition"
                >
                    Market News
                </a>


                <a
                    href="/aurum/demo_account/"
                    class="hover:text-aurum-gold transition"
                >
                    Platform
                </a>


                <a
                    href="/aurum/videos/"
                    class="hover:text-aurum-gold transition"
                >
                    Videos
                </a>


                <a
                    href="/aurum/ebooks/"
                    class="hover:text-aurum-gold transition"
                >
                    E-Book
                </a>


                <a
                    href="/aurum/ebooks/"
                    class="
                        text-aurum-gold
                        font-semibold
                    "
                >

                    Start Learning →

                </a>

            </div>

        </div>

    </div>

</header>



<!-- =========================================================
     MAIN
========================================================= -->

<main>


<!-- =========================================================
     HERO SECTION
========================================================= -->

<section
    class="
        relative
        min-h-screen
        pt-32
        pb-20
        flex
        items-center
        overflow-hidden
        hero-grid
    "
>


    <!-- GOLD GLOW -->

    <div
        class="
            absolute
            top-1/4
            right-1/4
            w-96
            h-96
            rounded-full
            bg-aurum-gold/10
            blur-[130px]
        "
    ></div>


    <div
        class="
            max-w-7xl
            mx-auto
            px-6
            lg:px-8
            w-full
        "
    >

        <div
            class="
                grid
                lg:grid-cols-2
                gap-16
                items-center
            "
        >


            <!-- =================================================
                 HERO TEXT
            ================================================== -->

            <div class="reveal">


                <div
                    class="
                        inline-flex
                        items-center
                        gap-2
                        px-4
                        py-2
                        rounded-full
                        border
                        border-aurum-gold/30
                        bg-aurum-gold/5
                        text-aurum-gold
                        text-xs
                        font-medium
                    "
                >

                    <span
                        class="
                            w-2
                            h-2
                            rounded-full
                            bg-aurum-gold
                            animate-pulse
                        "
                    ></span>

                    GOLD MARKET EDUCATION PLATFORM

                </div>


                <h1
                    class="
                        mt-7
                        text-5xl
                        md:text-6xl
                        lg:text-7xl
                        font-bold
                        leading-[1.05]
                        tracking-tight
                    "
                >

                    Understand the

                    <span class="text-aurum-gold">

                        Gold Market.

                    </span>

                </h1>


                <p
                    class="
                        mt-7
                        text-lg
                        text-gray-400
                        leading-relaxed
                        max-w-xl
                    "
                >

                    Learn how market data, economic events,
                    and global news influence gold through
                    a structured and accessible learning platform.

                </p>


                <!-- =================================================
                     HERO BUTTONS
                ================================================== -->

                <div
                    class="
                        mt-9
                        flex
                        flex-wrap
                        gap-4
                    "
                >

                    <a
                        href="/aurum/market/"
                        class="
                            px-6
                            py-3.5
                            rounded-xl
                            bg-aurum-gold
                            text-black
                            font-semibold
                            hover:scale-105
                            transition
                        "
                    >

                        Explore Market →

                    </a>


                    <a
                        href="/aurum/ebooks/"
                        class="
                            px-6
                            py-3.5
                            rounded-xl
                            border
                            border-white/10
                            text-white
                            hover:bg-white/5
                            transition
                        "
                    >

                        Start Learning

                    </a>

                </div>


                <!-- =================================================
                     STATS
                ================================================== -->

                <div
                    class="
                        mt-10
                        flex
                        flex-wrap
                        gap-8
                        text-sm
                    "
                >

                    <div>

                        <div class="text-white font-semibold">
                            3
                        </div>

                        <div class="text-gray-500">
                            Learning Levels
                        </div>

                    </div>


                    <div>

                        <div class="text-white font-semibold">
                            XAU/USD
                        </div>

                        <div class="text-gray-500">
                            Gold Market
                        </div>

                    </div>


                    <div>

                        <div class="text-white font-semibold">
                            Free
                        </div>

                        <div class="text-gray-500">
                            Educational Access
                        </div>

                    </div>

                </div>

            </div>



            <!-- =================================================
                 MARKET CARD
            ================================================== -->

            <div class="relative reveal">


                <!-- GLOW -->

                <div
                    class="
                        absolute
                        -inset-5
                        bg-aurum-gold/10
                        blur-3xl
                    "
                ></div>


                <div
                    class="
                        relative
                        bg-aurum-card
                        border
                        border-white/10
                        rounded-3xl
                        p-6
                        gold-glow
                    "
                >


                    <!-- MARKET HEADER -->

                    <div
                        class="
                            flex
                            items-center
                            justify-between
                        "
                    >

                        <div>

                            <p
                                class="
                                    text-sm
                                    text-gray-500
                                "
                            >

                                Gold / US Dollar

                            </p>


                            <h2
                                class="
                                    text-xl
                                    font-semibold
                                    mt-1
                                "
                            >

                                <?= htmlspecialchars($symbol) ?>

                            </h2>

                        </div>


                        <span
                            class="
                                px-3
                                py-1
                                rounded-full
                                text-xs
                                <?= $statusClass ?>
                            "
                        >

                            <?= htmlspecialchars($status) ?>

                        </span>

                    </div>


                    <!-- =================================================
                         PRICE
                    ================================================== -->

                    <div class="mt-8">

                        <span
                            class="
                                text-4xl
                                md:text-5xl
                                font-bold
                            "
                        >

                            <?= number_format($price, 2) ?>

                        </span>


                        <span
                            class="
                                ml-3
                                text-sm
                                <?= $changeClass ?>
                            "
                        >

                            <?= $changeSign ?>
                            <?= number_format($changePercent, 2) ?>%

                        </span>

                    </div>


                    <!-- =================================================
                         MARKET VISUAL
                    ================================================== -->

                    <div
                        class="
                            relative
                            h-48
                            mt-8
                            flex
                            items-end
                            gap-2
                            overflow-hidden
                        "
                    >

                        <div
                            class="
                                w-full
                                h-[32%]
                                bg-aurum-gold/10
                                rounded-t
                            "
                        ></div>

                        <div
                            class="
                                w-full
                                h-[45%]
                                bg-aurum-gold/20
                                rounded-t
                            "
                        ></div>

                        <div
                            class="
                                w-full
                                h-[38%]
                                bg-aurum-gold/10
                                rounded-t
                            "
                        ></div>

                        <div
                            class="
                                w-full
                                h-[55%]
                                bg-aurum-gold/20
                                rounded-t
                            "
                        ></div>

                        <div
                            class="
                                w-full
                                h-[48%]
                                bg-aurum-gold/20
                                rounded-t
                            "
                        ></div>

                        <div
                            class="
                                w-full
                                h-[67%]
                                bg-aurum-gold/30
                                rounded-t
                            "
                        ></div>

                        <div
                            class="
                                w-full
                                h-[61%]
                                bg-aurum-gold/30
                                rounded-t
                            "
                        ></div>

                        <div
                            class="
                                w-full
                                h-[83%]
                                bg-aurum-gold/50
                                rounded-t
                            "
                        ></div>

                        <div
                            class="
                                w-full
                                h-[72%]
                                bg-aurum-gold/40
                                rounded-t
                            "
                        ></div>

                        <div
                            class="
                                w-full
                                h-[92%]
                                bg-aurum-gold/70
                                rounded-t
                            "
                        ></div>

                    </div>


                    <!-- =================================================
                         DATABASE UPDATE
                    ================================================== -->

                    <div
                        class="
                            mt-6
                            pt-5
                            border-t
                            border-white/10
                            flex
                            justify-between
                            text-xs
                            text-gray-500
                        "
                    >

                        <span>
                            Last database update
                        </span>


                        <span>

                            <?= htmlspecialchars($formattedDate) ?>

                        </span>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>



<!-- =========================================================
     EXPLORE SECTION
========================================================= -->

<section
    class="
        py-24
        border-t
        border-white/5
    "
>

    <div
        class="
            max-w-7xl
            mx-auto
            px-6
            lg:px-8
        "
    >


        <div class="max-w-2xl reveal">

            <p
                class="
                    text-aurum-gold
                    text-sm
                    font-semibold
                    uppercase
                    tracking-widest
                "
            >

                Explore Victory Market

            </p>


            <h2
                class="
                    text-3xl
                    md:text-4xl
                    font-bold
                    mt-3
                "
            >

                What do you want to explore?

            </h2>


            <p class="text-gray-500 mt-4">

                Choose the experience that matches
                what you need today.

            </p>

        </div>



        <!-- =================================================
             EXPLORE CARDS
        ================================================== -->

        <div
            class="
                grid
                md:grid-cols-2
                lg:grid-cols-4
                gap-5
                mt-12
            "
        >


            <!-- DAILY MARKET -->

            <a
                href="/aurum/market/"
                class="
                    explore-card
                    p-6
                    rounded-2xl
                    bg-aurum-card
                    border
                    border-white/10
                    reveal
                "
            >

                <div
                    class="
                        w-12
                        h-12
                        rounded-xl
                        bg-aurum-gold/10
                        flex
                        items-center
                        justify-center
                        text-xl
                    "
                >

                    📊

                </div>


                <h3
                    class="
                        text-lg
                        font-semibold
                        mt-6
                    "
                >

                    Daily Market

                </h3>


                <p
                    class="
                        text-sm
                        text-gray-500
                        mt-3
                        leading-relaxed
                    "
                >

                    Explore today's gold market condition,
                    price movement and key market factors.

                </p>


                <div
                    class="
                        mt-6
                        text-sm
                        text-aurum-gold
                    "
                >

                    Explore Market →

                </div>

            </a>



            <!-- MARKET NEWS -->

            <a
                href="/aurum/news/"
                class="
                    explore-card
                    p-6
                    rounded-2xl
                    bg-aurum-card
                    border
                    border-white/10
                    reveal
                "
            >

                <div
                    class="
                        w-12
                        h-12
                        rounded-xl
                        bg-aurum-gold/10
                        flex
                        items-center
                        justify-center
                        text-xl
                    "
                >

                    📰

                </div>


                <h3
                    class="
                        text-lg
                        font-semibold
                        mt-6
                    "
                >

                    Market News

                </h3>


                <p
                    class="
                        text-sm
                        text-gray-500
                        mt-3
                        leading-relaxed
                    "
                >

                    Discover important economic news
                    and its potential relationship with gold.

                </p>


                <div
                    class="
                        mt-6
                        text-sm
                        text-aurum-gold
                    "
                >

                    Read News →

                </div>

            </a>



            <!-- E-BOOK -->

            <a
                href="/aurum/ebooks/"
                class="
                    explore-card
                    p-6
                    rounded-2xl
                    bg-aurum-card
                    border
                    border-white/10
                    reveal
                "
            >

                <div
                    class="
                        w-12
                        h-12
                        rounded-xl
                        bg-aurum-gold/10
                        flex
                        items-center
                        justify-center
                        text-xl
                    "
                >

                    📚

                </div>


                <h3
                    class="
                        text-lg
                        font-semibold
                        mt-6
                    "
                >

                    E-Book Learning

                </h3>


                <p
                    class="
                        text-sm
                        text-gray-500
                        mt-3
                        leading-relaxed
                    "
                >

                    Learn progressively from
                    Beginner to Professional.

                </p>


                <div
                    class="
                        mt-6
                        text-sm
                        text-aurum-gold
                    "
                >

                    Start Learning →

                </div>

            </a>



            <!-- VIDEOS -->

            <a
                href="/aurum/videos/"
                class="
                    explore-card
                    p-6
                    rounded-2xl
                    bg-aurum-card
                    border
                    border-white/10
                    reveal
                "
            >

                <div
                    class="
                        w-12
                        h-12
                        rounded-xl
                        bg-aurum-gold/10
                        flex
                        items-center
                        justify-center
                        text-xl
                    "
                >

                    🎥

                </div>


                <h3
                    class="
                        text-lg
                        font-semibold
                        mt-6
                    "
                >

                    Tutorial Videos

                </h3>


                <p
                    class="
                        text-sm
                        text-gray-500
                        mt-3
                        leading-relaxed
                    "
                >

                    Learn market concepts through
                    curated YouTube tutorials.

                </p>


                <div
                    class="
                        mt-6
                        text-sm
                        text-aurum-gold
                    "
                >

                    Watch Videos →

                </div>

            </a>

        </div>

    </div>

</section>



<!-- =========================================================
     LEARNING PATH
========================================================= -->

<section
    class="
        py-24
        bg-[#0b0c10]
    "
>

    <div
        class="
            max-w-7xl
            mx-auto
            px-6
            lg:px-8
        "
    >


        <div
            class="
                text-center
                max-w-2xl
                mx-auto
                reveal
            "
        >

            <p
                class="
                    text-aurum-gold
                    text-sm
                    font-semibold
                    uppercase
                    tracking-widest
                "
            >

                Learning Path

            </p>


            <h2
                class="
                    text-3xl
                    md:text-4xl
                    font-bold
                    mt-3
                "
            >

                From Beginner to Professional

            </h2>


            <p class="text-gray-500 mt-4">

                Build your market knowledge step by step.

            </p>

        </div>



        <!-- =================================================
             LEVEL CARDS
        ================================================== -->

        <div
            class="
                grid
                md:grid-cols-3
                gap-6
                mt-14
            "
        >


            <!-- BEGINNER -->

            <div
                class="
                    p-8
                    rounded-3xl
                    bg-aurum-card
                    border
                    border-white/10
                    reveal
                "
            >

                <span
                    class="
                        text-xs
                        text-emerald-400
                        font-semibold
                        uppercase
                        tracking-wider
                    "
                >

                    Level 01

                </span>


                <h3
                    class="
                        text-2xl
                        font-bold
                        mt-3
                    "
                >

                    Beginner

                </h3>


                <p
                    class="
                        text-gray-500
                        mt-4
                        leading-relaxed
                    "
                >

                    Understand the fundamentals of
                    market, gold, charts and basic
                    trading concepts.

                </p>


                <a
                    href="/aurum/ebooks/beginner.php"
                    class="
                        inline-block
                        mt-7
                        text-aurum-gold
                        text-sm
                    "
                >

                    Explore Beginner →

                </a>

            </div>



            <!-- INTERMEDIATE -->

            <div
                class="
                    p-8
                    rounded-3xl
                    bg-aurum-card
                    border
                    border-white/10
                    reveal
                "
            >

                <span
                    class="
                        text-xs
                        text-yellow-400
                        font-semibold
                        uppercase
                        tracking-wider
                    "
                >

                    Level 02

                </span>


                <h3
                    class="
                        text-2xl
                        font-bold
                        mt-3
                    "
                >

                    Intermediate

                </h3>


                <p
                    class="
                        text-gray-500
                        mt-4
                        leading-relaxed
                    "
                >

                    Develop technical and fundamental
                    analysis skills to understand
                    market movements.

                </p>


                <a
                    href="/aurum/ebooks/intermediate.php"
                    class="
                        inline-block
                        mt-7
                        text-aurum-gold
                        text-sm
                    "
                >

                    Explore Intermediate →

                </a>

            </div>



            <!-- PROFESSIONAL -->

            <div
                class="
                    p-8
                    rounded-3xl
                    bg-aurum-card
                    border
                    border-white/10
                    reveal
                "
            >

                <span
                    class="
                        text-xs
                        text-red-400
                        font-semibold
                        uppercase
                        tracking-wider
                    "
                >

                    Level 03

                </span>


                <h3
                    class="
                        text-2xl
                        font-bold
                        mt-3
                    "
                >

                    Professional

                </h3>


                <p
                    class="
                        text-gray-500
                        mt-4
                        leading-relaxed
                    "
                >

                    Explore advanced market analysis,
                    risk management and strategic
                    market thinking.

                </p>


                <a
                    href="/aurum/ebooks/profesional.php"
                    class="
                        inline-block
                        mt-7
                        text-aurum-gold
                        text-sm
                    "
                >

                    Explore Professional →

                </a>

            </div>

        </div>

    </div>

</section>



<!-- =========================================================
     CTA
========================================================= -->

<section class="py-24">

    <div
        class="
            max-w-5xl
            mx-auto
            px-6
            text-center
        "
    >

        <div
            class="
                relative
                overflow-hidden
                rounded-3xl
                border
                border-aurum-gold/20
                bg-aurum-gold/5
                p-10
                md:p-16
                reveal
            "
        >


            <!-- GLOW -->

            <div
                class="
                    absolute
                    w-64
                    h-64
                    bg-aurum-gold/10
                    rounded-full
                    blur-3xl
                    -top-20
                    -right-20
                "
            ></div>


            <p
                class="
                    text-aurum-gold
                    text-sm
                    uppercase
                    tracking-widest
                    font-semibold
                "
            >

                Your Market Journey Starts Here

            </p>


            <h2
                class="
                    text-3xl
                    md:text-5xl
                    font-bold
                    mt-4
                "
            >

                Learn the market.
                <br>

                Understand the move.

            </h2>


            <p
                class="
                    text-gray-400
                    mt-5
                    max-w-xl
                    mx-auto
                "
            >

                Explore market insights and build
                your understanding of gold step by step.

            </p>


            <a
                href="/aurum/ebooks/"
                class="
                    inline-flex
                    mt-8
                    px-7
                    py-3.5
                    rounded-xl
                    bg-aurum-gold
                    text-black
                    font-semibold
                    hover:scale-105
                    transition
                "
            >

                Start Learning →

            </a>

        </div>

    </div>

</section>

</main>



<!-- =========================================================
     FOOTER
========================================================= -->

<footer
    class="
        border-t
        border-white/10
        py-10
    "
>

    <div
        class="
            max-w-7xl
            mx-auto
            px-6
            lg:px-8
        "
    >

        <div
            class="
                flex
                flex-col
                md:flex-row
                justify-between
                gap-8
            "
        >


            <!-- FOOTER BRAND -->

            <div>

                <div
                    class="
                        font-bold
                        tracking-[0.15em]
                    "
                >

                    VICTORY MARKET

                </div>


                <p
                    class="
                        text-sm
                        text-gray-500
                        mt-2
                    "
                >

                    Gold Market Education & Insight

                </p>

            </div>


            <!-- DISCLAIMER -->

            <div
                class="
                    text-xs
                    text-gray-600
                    max-w-lg
                    leading-relaxed
                "
            >

                Victory Market is an educational and informational
                platform. Market information is provided
                for educational purposes and should not be
                considered financial advice or a recommendation
                to buy or sell any financial instrument.

            </div>

        </div>


        <!-- COPYRIGHT -->

        <div
            class="
                border-t
                border-white/5
                mt-8
                pt-6
                text-xs
                text-gray-600
            "
        >

            © <?= date("Y") ?> Victory Market.
            Gold Market Education & Insight.

        </div>

    </div>

</footer>



<!-- =========================================================
     JAVASCRIPT
========================================================= -->

<script src="/aurum/assets/js/main.js"></script>


</body>

</html>