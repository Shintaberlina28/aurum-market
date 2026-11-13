<?php

require_once "../config/database.php";

/*
|--------------------------------------------------------------------------
| GET LATEST MARKET DATA
|--------------------------------------------------------------------------
| Mengambil data terbaru dari tabel market_updates.
|--------------------------------------------------------------------------
*/

$queryLatest = "
    SELECT
        id,
        symbol,
        price,
        change_value,
        change_percent,
        market_status,
        support,
        resistance,
        market_summary,
        market_factors,
        updated_at
    FROM market_updates
    WHERE symbol = 'XAU/USD'
    ORDER BY updated_at DESC
    LIMIT 1
";

$resultLatest = $conn->query($queryLatest);

$market = null;

if ($resultLatest && $resultLatest->num_rows > 0) {

    $market = $resultLatest->fetch_assoc();

}


/*
|--------------------------------------------------------------------------
| DEFAULT MARKET DATA
|--------------------------------------------------------------------------
*/

$symbol = $market['symbol'] ?? 'XAU/USD';

$price = isset($market['price'])
    ? (float) $market['price']
    : 0;

$changeValue = isset($market['change_value'])
    ? (float) $market['change_value']
    : 0;

$changePercent = isset($market['change_percent'])
    ? (float) $market['change_percent']
    : 0;

$status = $market['market_status'] ?? 'Neutral';

$support = isset($market['support'])
    ? (float) $market['support']
    : 0;

$resistance = isset($market['resistance'])
    ? (float) $market['resistance']
    : 0;

$marketSummary = $market['market_summary'] ?? '';

$marketFactors = $market['market_factors'] ?? '';

$updatedAt = $market['updated_at'] ?? null;


/*
|--------------------------------------------------------------------------
| FORMAT UPDATE DATE
|--------------------------------------------------------------------------
*/

if ($updatedAt) {

    $formattedDate = date(
        "d M Y, H:i",
        strtotime($updatedAt)
    );

} else {

    $formattedDate = "-";

}


/*
|--------------------------------------------------------------------------
| MARKET STATUS CLASS
|--------------------------------------------------------------------------
*/

$statusLower = strtolower($status);

if (str_contains($statusLower, 'bull')) {

    $statusClass = 'positive';

} elseif (str_contains($statusLower, 'bear')) {

    $statusClass = 'negative';

} else {

    $statusClass = 'neutral';

}


/*
|--------------------------------------------------------------------------
| CHANGE CLASS
|--------------------------------------------------------------------------
*/

$changeClass = $changePercent >= 0
    ? 'positive'
    : 'negative';


/*
|--------------------------------------------------------------------------
| MOMENTUM INTERPRETATION
|--------------------------------------------------------------------------
*/

if ($changePercent >= 1) {

    $trendText = "Strong positive momentum";

} elseif ($changePercent > 0) {

    $trendText = "Moderate upward movement";

} elseif ($changePercent <= -1) {

    $trendText = "Strong negative momentum";

} elseif ($changePercent < 0) {

    $trendText = "Moderate downward movement";

} else {

    $trendText = "Market relatively stable";

}


/*
|--------------------------------------------------------------------------
| TREND BAR
|--------------------------------------------------------------------------
*/

if ($changePercent >= 1) {

    $trendWidth = 90;

} elseif ($changePercent > 0) {

    $trendWidth = 65;

} elseif ($changePercent == 0) {

    $trendWidth = 50;

} elseif ($changePercent > -1) {

    $trendWidth = 35;

} else {

    $trendWidth = 15;

}


/*
|--------------------------------------------------------------------------
| TREND BAR COLOR
|--------------------------------------------------------------------------
*/

if ($statusClass === 'positive') {

    $trendBarClass = 'bg-emerald-400';

} elseif ($statusClass === 'negative') {

    $trendBarClass = 'bg-rose-400';

} else {

    $trendBarClass = 'bg-yellow-400';

}


/*
|--------------------------------------------------------------------------
| MARKET HISTORY
|--------------------------------------------------------------------------
| Mengambil maksimal 24 data terakhir untuk grafik.
|--------------------------------------------------------------------------
*/

$historyLabels = [];
$historyPrices = [];

$queryHistory = "
    SELECT
        price,
        change_percent,
        market_status,
        recorded_at
    FROM market_history
    WHERE symbol = ?
    ORDER BY recorded_at DESC
    LIMIT 24
";

$stmtHistory = $conn->prepare($queryHistory);

if ($stmtHistory) {

    $stmtHistory->bind_param(
        "s",
        $symbol
    );

    $stmtHistory->execute();

    $resultHistory = $stmtHistory->get_result();

    while ($row = $resultHistory->fetch_assoc()) {

        $historyLabels[] = date(
            "H:i",
            strtotime($row['recorded_at'])
        );

        $historyPrices[] = (float) $row['price'];

    }

    $stmtHistory->close();

}


/*
|--------------------------------------------------------------------------
| BALIK DATA HISTORY
|--------------------------------------------------------------------------
| Karena query mengambil DESC, data dibalik agar grafik:
| data lama → data terbaru.
|--------------------------------------------------------------------------
*/

$historyLabels = array_reverse($historyLabels);

$historyPrices = array_reverse($historyPrices);


/*
|--------------------------------------------------------------------------
| DATA STATUS
|--------------------------------------------------------------------------
*/

$hasMarketData = ($market !== null);

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
        Daily Market | AURUM
    </title>


    <!-- GOOGLE FONT -->

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


    <!-- TAILWIND -->

    <script src="https://cdn.tailwindcss.com"></script>


    <script>

        tailwind.config = {

            theme: {

                extend: {

                    colors: {

                        aurum: {

                            gold: '#D4AF37',

                            dark: '#08090C',

                            card: '#15171C',

                            border: '#272A31'

                        }

                    }

                }

            }

        };

    </script>


    <!-- CHART JS -->

    <script
        src="https://cdn.jsdelivr.net/npm/chart.js"
    ></script>


    <style>

        * {
            box-sizing: border-box;
        }


        html {
            scroll-behavior: smooth;
        }


        body {

            font-family: 'Inter', sans-serif;

            background: #08090C;

            color: white;

        }


        .glass {

            background:
                rgba(21, 23, 28, 0.82);

            backdrop-filter:
                blur(18px);

        }


        .gold-glow {

            box-shadow:
                0 0 80px
                rgba(212, 175, 55, 0.07);

        }


        .card-hover {

            transition:
                transform 0.25s ease,
                border-color 0.25s ease,
                background 0.25s ease;

        }


        .card-hover:hover {

            transform:
                translateY(-4px);

            border-color:
                rgba(212, 175, 55, 0.25);

        }


        .positive {

            color: #34D399;

        }


        .negative {

            color: #FB7185;

        }


        .neutral {

            color: #FACC15;

        }


        .status-dot {

            width: 7px;

            height: 7px;

            border-radius: 50%;

            display: inline-block;

        }


        .scrollbar-hide::-webkit-scrollbar {

            display: none;

        }


        .scrollbar-hide {

            -ms-overflow-style: none;

            scrollbar-width: none;

        }

    </style>

</head>


<body class="text-white">


<!-- =====================================================
     NAVBAR
===================================================== -->

<header
    class="
        fixed
        top-0
        left-0
        right-0
        z-50
        border-b
        border-white/10
        bg-[#08090c]/85
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


            <!-- LOGO -->

            <a
                href="../index.php"
                class="
                    flex
                    items-center
                    gap-3
                "
            >

                <div
    class="
        w-10
        h-10
        rounded-xl
        flex
        items-center
        justify-center
        overflow-hidden
    "
>

    <img
        src="/aurum/assets/images/victory-logo.jpeg"
        alt="Victory Internasional Futures"
        class="w-full h-full object-contain"
    >

</div>


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


            <!-- MENU -->

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
                    href="../index.php"
                    class="
                        text-gray-400
                        hover:text-white
                        transition
                    "
                >
                    Home
                </a>


                <a
                    href="index.php"
                    class="text-aurum-gold"
                >
                    Daily Market
                </a>


                <a
                    href="../news/"
                    class="
                        text-gray-400
                        hover:text-white
                        transition
                    "
                >
                    Market News
                </a>


                <a
                    href="../demo_account/"
                    class="
                        text-gray-400
                        hover:text-white
                        transition
                    "
                >
                    Platform
                </a>


                <a
                    href="../videos/"
                    class="
                        text-gray-400
                        hover:text-white
                        transition
                    "
                >
                    Videos
                </a>


                <a
                    href="../ebooks/"
                    class="
                        text-gray-400
                        hover:text-white
                        transition
                    "
                >
                    E-Book
                </a>

            </div>


            <!-- BUTTON -->

            <a
                href="../learning/"
                class="
                    hidden
                    sm:block
                    bg-aurum-gold
                    text-black
                    font-semibold
                    px-5
                    py-2.5
                    rounded-xl
                    text-sm
                    hover:opacity-90
                    transition
                "
            >
                Start Learning
            </a>

        </nav>

    </div>

</header>


<!-- =====================================================
     MAIN
===================================================== -->

<main
    class="
        pt-32
        pb-24
    "
>


<!-- =====================================================
     INTRO
===================================================== -->

<section>

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
                lg:flex-row
                lg:items-end
                lg:justify-between
                gap-8
            "
        >


            <div>


                <!-- BADGE -->

                <div
                    class="
                        inline-flex
                        items-center
                        gap-2
                        px-4
                        py-2
                        rounded-full
                        border
                        border-aurum-gold/25
                        bg-aurum-gold/5
                        text-aurum-gold
                        text-xs
                        uppercase
                        tracking-[0.15em]
                    "
                >

                    <span
                        class="
                            status-dot
                            <?= $hasMarketData
                                ? 'bg-emerald-400 animate-pulse'
                                : 'bg-yellow-400'
                            ?>
                        "
                    ></span>

                    Market Overview

                </div>


                <!-- TITLE -->

                <h1
                    class="
                        text-4xl
                        md:text-6xl
                        font-bold
                        tracking-tight
                        mt-6
                    "
                >

                    Gold Market

                    <span
                        class="text-aurum-gold"
                    >
                        Today.
                    </span>

                </h1>


                <p
                    class="
                        text-gray-400
                        max-w-2xl
                        mt-5
                        text-base
                        md:text-lg
                        leading-relaxed
                    "
                >

                    A concise view of XAU/USD market
                    conditions, price movement, and the
                    factors influencing gold.

                </p>

            </div>


            <!-- UPDATE INFO -->

            <div
                class="lg:text-right"
            >

                <p
                    class="
                        text-xs
                        uppercase
                        tracking-widest
                        text-gray-600
                    "
                >
                    Last database update
                </p>


                <p
                    class="
                        text-sm
                        text-gray-300
                        mt-2
                    "
                >

                    <?= htmlspecialchars(
                        $formattedDate
                    ) ?>

                </p>

            </div>

        </div>

    </div>

</section>



<!-- =====================================================
     MAIN MARKET CARD
===================================================== -->

<section class="mt-12">

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
                grid
                lg:grid-cols-[1.65fr_0.75fr]
                gap-6
            "
        >


            <!-- =========================================
                 CHART
            ========================================== -->

            <div
                class="
                    glass
                    gold-glow
                    rounded-3xl
                    border
                    border-white/10
                    p-6
                    md:p-8
                "
            >


                <!-- CHART HEADER -->

                <div
                    class="
                        flex
                        flex-col
                        sm:flex-row
                        sm:items-start
                        sm:justify-between
                        gap-5
                    "
                >


                    <div>

                        <div
                            class="
                                flex
                                items-center
                                gap-3
                            "
                        >

                            <span
                                class="
                                    text-gray-400
                                    text-sm
                                "
                            >
                                Gold / US Dollar
                            </span>


                            <span
                                class="
                                    text-xs
                                    px-2.5
                                    py-1
                                    rounded-full
                                    <?=
                                        $statusClass === 'positive'
                                            ? 'bg-emerald-500/10 text-emerald-400'
                                            : (
                                                $statusClass === 'negative'
                                                    ? 'bg-rose-500/10 text-rose-400'
                                                    : 'bg-yellow-500/10 text-yellow-400'
                                            )
                                    ?>
                                "
                            >

                                <?= htmlspecialchars(
                                    $status
                                ) ?>

                            </span>

                        </div>


                        <!-- PRICE -->

                        <div
                            class="
                                flex
                                items-end
                                gap-4
                                mt-4
                            "
                        >

                            <h2
                                class="
                                    text-4xl
                                    md:text-5xl
                                    font-bold
                                "
                            >

                                <?= number_format(
                                    $price,
                                    2
                                ) ?>

                            </h2>


                            <span
                                class="
                                    <?= $changeClass ?>
                                    text-sm
                                    font-semibold
                                    pb-2
                                "
                            >

                                <?= $changePercent >= 0
                                    ? '+'
                                    : ''
                                ?>

                                <?= number_format(
                                    $changePercent,
                                    2
                                ) ?>%

                            </span>

                        </div>


                        <!-- CHANGE VALUE -->

                        <p
                            class="
                                text-xs
                                text-gray-500
                                mt-2
                            "
                        >

                            Change:

                            <span
                                class="<?= $changeClass ?>"
                            >

                                <?= $changeValue >= 0
                                    ? '+'
                                    : ''
                                ?>

                                <?= number_format(
                                    $changeValue,
                                    2
                                ) ?>

                            </span>

                        </p>

                    </div>


                    <!-- TIME FILTER -->

                    <div
                        class="
                            flex
                            rounded-xl
                            border
                            border-white/10
                            p-1
                            bg-black/20
                        "
                    >

                        <button
                            type="button"
                            class="
                                chart-range
                                active
                                px-3
                                py-2
                                rounded-lg
                                text-xs
                                bg-white/10
                                text-white
                            "
                            data-range="1D"
                        >
                            1D
                        </button>


                        <button
                            type="button"
                            class="
                                chart-range
                                px-3
                                py-2
                                rounded-lg
                                text-xs
                                text-gray-400
                                hover:text-white
                            "
                            data-range="1W"
                        >
                            1W
                        </button>


                        <button
                            type="button"
                            class="
                                chart-range
                                px-3
                                py-2
                                rounded-lg
                                text-xs
                                text-gray-400
                                hover:text-white
                            "
                            data-range="1M"
                        >
                            1M
                        </button>

                    </div>

                </div>


                <!-- CHART -->

                <div
                    class="
                        h-[320px]
                        mt-8
                    "
                >

                    <canvas
                        id="goldChart"
                    ></canvas>

                </div>


                <!-- CHART FOOTER -->

                <div
                    class="
                        mt-5
                        flex
                        items-center
                        justify-between
                        text-xs
                        text-gray-600
                    "
                >

                    <span>
                        XAU/USD
                    </span>

                    <span>
                        Historical market data
                    </span>

                </div>

            </div>



            <!-- =========================================
                 SNAPSHOT
            ========================================== -->

            <div
                class="
                    rounded-3xl
                    border
                    border-white/10
                    bg-aurum-card
                    p-7
                "
            >

                <p
                    class="
                        text-xs
                        uppercase
                        tracking-widest
                        text-gray-600
                    "
                >
                    Market Snapshot
                </p>


                <h2
                    class="
                        text-2xl
                        font-bold
                        mt-2
                    "
                >
                    Current View
                </h2>


                <div
                    class="
                        mt-8
                        space-y-6
                    "
                >


                    <!-- TREND -->

                    <div>

                        <div
                            class="
                                flex
                                justify-between
                                text-sm
                            "
                        >

                            <span
                                class="text-gray-500"
                            >
                                Trend
                            </span>


                            <span
                                class="<?= $statusClass ?>"
                            >
                                <?= htmlspecialchars(
                                    $status
                                ) ?>
                            </span>

                        </div>


                        <div
                            class="
                                h-1.5
                                bg-white/5
                                rounded-full
                                mt-3
                                overflow-hidden
                            "
                        >

                            <div
                                class="
                                    h-full
                                    rounded-full
                                    <?= $trendBarClass ?>
                                "
                                style="
                                    width:
                                    <?= $trendWidth ?>%;
                                "
                            ></div>

                        </div>

                    </div>


                    <!-- MOMENTUM -->

                    <div>

                        <div
                            class="
                                flex
                                justify-between
                                gap-4
                                text-sm
                            "
                        >

                            <span
                                class="text-gray-500"
                            >
                                Momentum
                            </span>


                            <span
                                class="
                                    <?= $changeClass ?>
                                    text-right
                                "
                            >

                                <?= htmlspecialchars(
                                    $trendText
                                ) ?>

                            </span>

                        </div>

                    </div>


                    <!-- SUPPORT -->

                    <div>

                        <div
                            class="
                                flex
                                justify-between
                                text-sm
                            "
                        >

                            <span
                                class="text-gray-500"
                            >
                                Support
                            </span>


                            <span
                                class="text-gray-300"
                            >

                                <?php if ($support > 0): ?>

                                    <?= number_format(
                                        $support,
                                        2
                                    ) ?>

                                <?php else: ?>

                                    -

                                <?php endif; ?>

                            </span>

                        </div>

                    </div>


                    <!-- RESISTANCE -->

                    <div>

                        <div
                            class="
                                flex
                                justify-between
                                text-sm
                            "
                        >

                            <span
                                class="text-gray-500"
                            >
                                Resistance
                            </span>


                            <span
                                class="text-gray-300"
                            >

                                <?php if ($resistance > 0): ?>

                                    <?= number_format(
                                        $resistance,
                                        2
                                    ) ?>

                                <?php else: ?>

                                    -

                                <?php endif; ?>

                            </span>

                        </div>

                    </div>


                    <!-- VOLATILITY -->

                    <div>

                        <div
                            class="
                                flex
                                justify-between
                                text-sm
                            "
                        >

                            <span
                                class="text-gray-500"
                            >
                                Volatility
                            </span>


                            <span
                                class="neutral"
                            >
                                Monitor
                            </span>

                        </div>

                    </div>


                    <!-- DATA -->

                    <div
                        class="
                            pt-5
                            border-t
                            border-white/10
                        "
                    >

                        <div
                            class="
                                flex
                                justify-between
                            "
                        >

                            <span
                                class="text-gray-500"
                            >
                                Data status
                            </span>


                            <span
                                class="
                                    text-emerald-400
                                    text-sm
                                "
                            >
                                ● Available
                            </span>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>



<!-- =====================================================
     MARKET SUMMARY
===================================================== -->

<section class="mt-12">

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
                grid
                md:grid-cols-2
                gap-6
            "
        >


            <!-- SUMMARY -->

            <div
                class="
                    rounded-3xl
                    border
                    border-white/10
                    bg-aurum-card
                    p-7
                "
            >

                <p
                    class="
                        text-aurum-gold
                        text-xs
                        uppercase
                        tracking-[0.2em]
                        font-semibold
                    "
                >
                    Market Summary
                </p>


                <h2
                    class="
                        text-2xl
                        font-bold
                        mt-3
                    "
                >
                    Today's Market View
                </h2>


                <p
                    class="
                        text-gray-400
                        leading-relaxed
                        mt-5
                    "
                >

                    <?php if (!empty($marketSummary)): ?>

                        <?= nl2br(
                            htmlspecialchars(
                                $marketSummary
                            )
                        ) ?>

                    <?php else: ?>

                        Market summary belum tersedia.
                        Data akan ditampilkan setelah
                        pembaruan market tersimpan
                        ke database.

                    <?php endif; ?>

                </p>

            </div>


            <!-- MARKET FACTORS -->

            <div
                class="
                    rounded-3xl
                    border
                    border-white/10
                    bg-aurum-card
                    p-7
                "
            >

                <p
                    class="
                        text-aurum-gold
                        text-xs
                        uppercase
                        tracking-[0.2em]
                        font-semibold
                    "
                >
                    Market Factors
                </p>


                <h2
                    class="
                        text-2xl
                        font-bold
                        mt-3
                    "
                >
                    What to Watch
                </h2>


                <p
                    class="
                        text-gray-400
                        leading-relaxed
                        mt-5
                    "
                >

                    <?php if (!empty($marketFactors)): ?>

                        <?= nl2br(
                            htmlspecialchars(
                                $marketFactors
                            )
                        ) ?>

                    <?php else: ?>

                        Market factors belum tersedia.
                        Perhatikan perkembangan US Dollar,
                        suku bunga, inflasi, dan sentimen
                        global ketika mempelajari pergerakan
                        harga emas.

                    <?php endif; ?>

                </p>

            </div>

        </div>

    </div>

</section>



<!-- =====================================================
     MARKET DRIVERS
===================================================== -->

<section class="mt-20">

    <div
        class="
            max-w-7xl
            mx-auto
            px-6
            lg:px-8
        "
    >

        <div>

            <p
                class="
                    text-aurum-gold
                    text-xs
                    uppercase
                    tracking-[0.2em]
                    font-semibold
                "
            >
                Market Drivers
            </p>


            <h2
                class="
                    text-3xl
                    md:text-4xl
                    font-bold
                    mt-3
                "
            >
                What moves gold?
            </h2>


            <p
                class="
                    text-gray-500
                    mt-4
                    max-w-2xl
                "
            >
                Gold does not move because of a single
                factor. Understanding the broader market
                context can help users interpret price
                movements more carefully.
            </p>

        </div>


        <div
            class="
                grid
                sm:grid-cols-2
                lg:grid-cols-4
                gap-5
                mt-10
            "
        >


            <!-- USD -->

            <div
                class="
                    card-hover
                    rounded-2xl
                    border
                    border-white/10
                    bg-aurum-card
                    p-6
                "
            >

                <div
                    class="
                        w-11
                        h-11
                        rounded-xl
                        bg-white/5
                        flex
                        items-center
                        justify-center
                        text-lg
                    "
                >
                    $
                </div>


                <h3
                    class="
                        font-semibold
                        text-lg
                        mt-5
                    "
                >
                    US Dollar
                </h3>


                <p
                    class="
                        text-sm
                        text-gray-500
                        mt-3
                        leading-relaxed
                    "
                >
                    Movements in the US dollar can
                    influence the relative attractiveness
                    of gold.
                </p>

            </div>


            <!-- RATES -->

            <div
                class="
                    card-hover
                    rounded-2xl
                    border
                    border-white/10
                    bg-aurum-card
                    p-6
                "
            >

                <div
                    class="
                        w-11
                        h-11
                        rounded-xl
                        bg-white/5
                        flex
                        items-center
                        justify-center
                        text-lg
                    "
                >
                    %
                </div>


                <h3
                    class="
                        font-semibold
                        text-lg
                        mt-5
                    "
                >
                    Interest Rates
                </h3>


                <p
                    class="
                        text-sm
                        text-gray-500
                        mt-3
                        leading-relaxed
                    "
                >
                    Rate expectations can affect how
                    investors evaluate gold relative
                    to yield-bearing assets.
                </p>

            </div>


            <!-- INFLATION -->

            <div
                class="
                    card-hover
                    rounded-2xl
                    border
                    border-white/10
                    bg-aurum-card
                    p-6
                "
            >

                <div
                    class="
                        w-11
                        h-11
                        rounded-xl
                        bg-white/5
                        flex
                        items-center
                        justify-center
                        text-lg
                    "
                >
                    ↗
                </div>


                <h3
                    class="
                        font-semibold
                        text-lg
                        mt-5
                    "
                >
                    Inflation
                </h3>


                <p
                    class="
                        text-sm
                        text-gray-500
                        mt-3
                        leading-relaxed
                    "
                >
                    Inflation expectations are one
                    factor considered when interpreting
                    gold demand.
                </p>

            </div>


            <!-- GLOBAL RISK -->

            <div
                class="
                    card-hover
                    rounded-2xl
                    border
                    border-white/10
                    bg-aurum-card
                    p-6
                "
            >

                <div
                    class="
                        w-11
                        h-11
                        rounded-xl
                        bg-white/5
                        flex
                        items-center
                        justify-center
                        text-lg
                    "
                >
                    ◉
                </div>


                <h3
                    class="
                        font-semibold
                        text-lg
                        mt-5
                    "
                >
                    Global Risk
                </h3>


                <p
                    class="
                        text-sm
                        text-gray-500
                        mt-3
                        leading-relaxed
                    "
                >
                    Economic and geopolitical uncertainty
                    can change market sentiment toward gold.
                </p>

            </div>

        </div>

    </div>

</section>



<!-- =====================================================
     AURUM INSIGHT
===================================================== -->

<section class="mt-20">

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
                relative
                overflow-hidden
                rounded-3xl
                border
                border-aurum-gold/20
                bg-gradient-to-br
                from-aurum-gold/10
                to-transparent
                p-8
                md:p-10
            "
        >

            <div
                class="
                    absolute
                    -right-20
                    -top-20
                    w-60
                    h-60
                    rounded-full
                    bg-aurum-gold/10
                    blur-3xl
                "
            ></div>


            <div
                class="
                    relative
                    grid
                    lg:grid-cols-[0.7fr_1.3fr]
                    gap-10
                "
            >

                <div>

                    <p
                        class="
                            text-aurum-gold
                            text-xs
                            uppercase
                            tracking-[0.2em]
                            font-semibold
                        "
                    >
                        AURUM Insight
                    </p>


                    <h2
                        class="
                            text-3xl
                            md:text-4xl
                            font-bold
                            mt-4
                        "
                    >
                        Read the market,
                        not just the price.
                    </h2>

                </div>


                <div>

                    <p
                        class="
                            text-gray-300
                            leading-relaxed
                        "
                    >
                        A price movement is only one part of
                        the market story. Changes in currency,
                        interest-rate expectations, inflation,
                        and global risk can interact and affect
                        gold market sentiment.
                    </p>


                    <p
                        class="
                            text-gray-500
                            leading-relaxed
                            mt-5
                        "
                    >
                        AURUM is designed to help users
                        connect market data with the concepts
                        they learn through the platform.
                    </p>


                    <a
                        href="../learning/"
                        class="
                            inline-flex
                            items-center
                            gap-2
                            mt-7
                            text-aurum-gold
                            text-sm
                            font-semibold
                            hover:gap-3
                            transition-all
                        "
                    >

                        Explore Learning Center

                        <span>
                            →
                        </span>

                    </a>

                </div>

            </div>

        </div>

    </div>

</section>



<!-- =====================================================
     DISCLAIMER
===================================================== -->

<section class="mt-14">

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
                border-t
                border-white/10
                pt-7
            "
        >

            <p
                class="
                    text-xs
                    text-gray-600
                    leading-relaxed
                    max-w-4xl
                "
            >

                <strong>
                    Educational Disclaimer:
                </strong>

                AURUM provides market information and
                educational content for informational and
                learning purposes only. Market data may be
                delayed or subject to data-source limitations.
                Nothing on this platform should be interpreted
                as personalized financial or investment advice.

            </p>

        </div>

    </div>

</section>


</main>



<!-- =====================================================
     CHART JAVASCRIPT
===================================================== -->

<script>

/*
|--------------------------------------------------------------------------
| PHP DATA → JAVASCRIPT
|--------------------------------------------------------------------------
*/

const historyLabels =
    <?= json_encode(
        $historyLabels,
        JSON_UNESCAPED_SLASHES
    ) ?>;


const historyPrices =
    <?= json_encode(
        $historyPrices,
        JSON_UNESCAPED_SLASHES
    ) ?>;


/*
|--------------------------------------------------------------------------
| CHART ELEMENT
|--------------------------------------------------------------------------
*/

const chartCanvas =
    document.getElementById('goldChart');


/*
|--------------------------------------------------------------------------
| CHECK CHART
|--------------------------------------------------------------------------
*/

if (chartCanvas) {

    const ctx =
        chartCanvas.getContext('2d');


    /*
    |--------------------------------------------------------------------------
    | GRADIENT
    |--------------------------------------------------------------------------
    */

    const gradient =
        ctx.createLinearGradient(
            0,
            0,
            0,
            320
        );


    gradient.addColorStop(
        0,
        'rgba(212,175,55,0.20)'
    );


    gradient.addColorStop(
        1,
        'rgba(212,175,55,0)'
    );


    /*
    |--------------------------------------------------------------------------
    | CREATE CHART
    |--------------------------------------------------------------------------
    */

    const goldChart =
        new Chart(
            ctx,
            {

                type: 'line',

                data: {

                    labels:
                        historyLabels,

                    datasets: [

                        {

                            label:
                                'XAU/USD',

                            data:
                                historyPrices,

                            borderColor:
                                '#D4AF37',

                            backgroundColor:
                                gradient,

                            borderWidth:
                                2,

                            tension:
                                0.35,

                            fill:
                                true,

                            pointRadius:
                                0,

                            pointHoverRadius:
                                5,

                            pointHoverBackgroundColor:
                                '#D4AF37'

                        }

                    ]

                },


                options: {

                    responsive:
                        true,

                    maintainAspectRatio:
                        false,


                    interaction: {

                        intersect:
                            false,

                        mode:
                            'index'

                    },


                    plugins: {

                        legend: {

                            display:
                                false

                        },


                        tooltip: {

                            backgroundColor:
                                '#15171C',

                            borderColor:
                                'rgba(255,255,255,0.1)',

                            borderWidth:
                                1,

                            padding:
                                12,

                            titleColor:
                                '#9CA3AF',

                            bodyColor:
                                '#FFFFFF',

                            displayColors:
                                false,


                            callbacks: {

                                label:
                                    function(context) {

                                        return ' $' +
                                            Number(
                                                context.parsed.y
                                            ).toLocaleString(
                                                'en-US',
                                                {
                                                    minimumFractionDigits:
                                                        2,
                                                    maximumFractionDigits:
                                                        2
                                                }
                                            );

                                    }

                            }

                        }

                    },


                    scales: {

                        x: {

                            grid: {

                                display:
                                    false

                            },


                            border: {

                                display:
                                    false

                            },


                            ticks: {

                                color:
                                    '#525866',

                                maxTicksLimit:
                                    8

                            }

                        },


                        y: {

                            position:
                                'right',


                            grid: {

                                color:
                                    'rgba(255,255,255,0.05)'

                            },


                            border: {

                                display:
                                    false

                            },


                            ticks: {

                                color:
                                    '#525866',


                                callback:
                                    function(value) {

                                        return Number(
                                            value
                                        ).toLocaleString(
                                            'en-US'
                                        );

                                    }

                            }

                        }

                    }

                }

            }
        );


    /*
    |--------------------------------------------------------------------------
    | RANGE BUTTON
    |--------------------------------------------------------------------------
    */

    const rangeButtons =
        document.querySelectorAll(
            '.chart-range'
        );


    rangeButtons.forEach(
        function(button) {

            button.addEventListener(
                'click',
                function() {


                    rangeButtons.forEach(
                        function(btn) {

                            btn.classList.remove(
                                'bg-white/10'
                            );

                            btn.classList.remove(
                                'text-white'
                            );

                            btn.classList.add(
                                'text-gray-400'
                            );

                        }
                    );


                    this.classList.remove(
                        'text-gray-400'
                    );

                    this.classList.add(
                        'bg-white/10'
                    );

                    this.classList.add(
                        'text-white'
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | RANGE NOTE
                    |--------------------------------------------------------------------------
                    | Data yang tersedia saat ini berasal dari
                    | market_history. Tombol akan memilih jumlah
                    | data yang tersedia di browser.
                    |--------------------------------------------------------------------------
                    */

                    const range =
                        this.dataset.range;


                    let limit = 24;


                    if (range === '1D') {

                        limit = 24;

                    } else if (range === '1W') {

                        limit = 168;

                    } else if (range === '1M') {

                        limit = 720;

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | FILTER DATA
                    |--------------------------------------------------------------------------
                    */

                    const total =
                        historyPrices.length;


                    const start =
                        Math.max(
                            0,
                            total - limit
                        );


                    const filteredLabels =
                        historyLabels.slice(
                            start
                        );


                    const filteredPrices =
                        historyPrices.slice(
                            start
                        );


                    goldChart.data.labels =
                        filteredLabels;


                    goldChart.data.datasets[0].data =
                        filteredPrices;


                    goldChart.update();

                }
            );

        }
    );

}

</script>


</body>

</html>