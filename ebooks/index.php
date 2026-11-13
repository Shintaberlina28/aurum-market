<?php
// =====================================================
// VICTORY MARKET
// E-BOOK / LEARNING CENTER
// =====================================================

$pageTitle = "E-Book | Victory Market";
$currentPage = "ebooks";

// =====================================================
// LOGO
// =====================================================

$logoImage = "../assets/images/victory-logo.jpeg";

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title><?= $pageTitle; ?></title>


    <style>

        /* =====================================================
           RESET
        ===================================================== */

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }


        html {
            scroll-behavior: smooth;
        }


        body {
            font-family:
                Arial,
                Helvetica,
                sans-serif;

            background: #070809;

            color: #ffffff;

            line-height: 1.6;
        }


        a {
            text-decoration: none;
            color: inherit;
        }


        /* =====================================================
           NAVBAR
        ===================================================== */

        .navbar {

            width: 100%;

            min-height: 84px;

            background: rgba(
                7,
                8,
                9,
                0.97
            );

            border-bottom:
                1px solid #24272b;

            display: flex;

            align-items: center;

            justify-content: center;

            padding:
                0 5%;

            position: sticky;

            top: 0;

            z-index: 1000;
        }


        /* =====================================================
           NAV CONTAINER
        ===================================================== */

        .nav-container {

            width: min(
                1280px,
                100%
            );

            display: flex;

            align-items: center;

            justify-content: space-between;

            gap: 30px;
        }


        /* =====================================================
           BRAND
        ===================================================== */

        .brand {

            display: flex;

            align-items: center;

            gap: 12px;

            flex-shrink: 0;
        }


        /* =====================================================
           VICTORY LOGO
        ===================================================== */

        .brand-logo {

            width: 48px;

            height: 48px;

            border-radius: 9px;

            overflow: hidden;

            display: flex;

            align-items: center;

            justify-content: center;

            background: #000000;

            flex-shrink: 0;
        }


        .brand-logo img {

            width: 100%;

            height: 100%;

            object-fit: contain;

            display: block;
        }


        /* =====================================================
           BRAND TEXT
        ===================================================== */

        .brand-text {

            display: flex;

            flex-direction: column;

            justify-content: center;

            gap: 3px;
        }


        .brand-text h2 {

            color: #ffffff;

            font-size: 18px;

            font-weight: 800;

            letter-spacing: 2px;

            line-height: 1.1;
        }


        .brand-text span {

            display: block;

            color: #6f7782;

            font-size: 8px;

            font-weight: 500;

            letter-spacing: 1.7px;

            line-height: 1.2;

            white-space: nowrap;
        }


        /* =====================================================
           NAV MENU
        ===================================================== */

        .nav-menu {

            display: flex;

            align-items: center;

            justify-content: flex-end;

            gap: 30px;
        }


        .nav-menu a {

            color: #a7adb6;

            font-size: 15px;

            font-weight: 500;

            transition:
                color 0.3s ease;
        }


        .nav-menu a:hover {

            color: #e5b82e;
        }


        .nav-menu a.active {

            color: #e5b82e;

            font-weight: 700;
        }


        /* =====================================================
           START LEARNING BUTTON
        ===================================================== */

        .nav-button {

            background: #e5b82e !important;

            color: #08090a !important;

            padding:
                13px
                24px;

            border-radius: 12px;

            font-weight: 700 !important;

            white-space: nowrap;

            transition:
                0.3s ease;
        }


        .nav-button:hover {

            background: #f1c84d !important;

            color: #08090a !important;

            transform:
                translateY(-2px);
        }


        /* =====================================================
           HERO
        ===================================================== */

        .hero {

            padding:
                95px
                8%
                75px;

            text-align: center;

            background:
                radial-gradient(
                    circle at center,
                    rgba(
                        229,
                        184,
                        46,
                        0.12
                    ),
                    transparent 45%
                );
        }


        /* =====================================================
           HERO LABEL
        ===================================================== */

        .hero-label {

            display: inline-flex;

            align-items: center;

            gap: 9px;

            border:
                1px solid #5d4b18;

            color: #e5b82e;

            padding:
                8px
                17px;

            border-radius: 30px;

            font-size: 12px;

            font-weight: 700;

            letter-spacing: 1px;

            margin-bottom: 25px;
        }


        .hero-label::before {

            content: "";

            width: 7px;

            height: 7px;

            background: #e5b82e;

            border-radius: 50%;
        }


        /* =====================================================
           HERO TITLE
        ===================================================== */

        .hero h1 {

            font-size:
                clamp(
                    42px,
                    6vw,
                    70px
                );

            line-height: 1.05;

            max-width: 1000px;

            margin: auto;

            font-weight: 800;
        }


        .hero h1 span {

            color: #e5b82e;
        }


        /* =====================================================
           HERO DESCRIPTION
        ===================================================== */

        .hero p {

            max-width: 720px;

            margin:
                25px auto 0;

            color: #929aa5;

            font-size: 18px;

            line-height: 1.7;
        }


        /* =====================================================
           LEVEL SECTION
        ===================================================== */

        .section {

            padding:
                30px
                8%
                100px;
        }


        /* =====================================================
           SECTION TITLE
        ===================================================== */

        .section-title {

            text-align: center;

            margin-bottom: 45px;
        }


        .section-title h2 {

            font-size: 32px;

            margin-bottom: 10px;
        }


        .section-title p {

            color: #7e8792;
        }


        /* =====================================================
           LEVEL GRID
        ===================================================== */

        .level-grid {

            display: grid;

            grid-template-columns:
                repeat(3, 1fr);

            gap: 25px;

            max-width: 1250px;

            margin: auto;
        }


        /* =====================================================
           LEVEL CARD
        ===================================================== */

        .level-card {

            background: #101216;

            border:
                1px solid #272b31;

            border-radius: 20px;

            padding: 32px;

            display: flex;

            flex-direction: column;

            min-height: 500px;

            transition:
                0.3s ease;
        }


        .level-card:hover {

            transform:
                translateY(-7px);

            border-color:
                #b89122;

            box-shadow:
                0 20px 60px
                rgba(
                    0,
                    0,
                    0,
                    0.35
                );
        }


        /* =====================================================
           LEVEL NUMBER
        ===================================================== */

        .level-number {

            width: 45px;

            height: 45px;

            border-radius: 12px;

            background:
                rgba(
                    229,
                    184,
                    46,
                    0.12
                );

            color: #e5b82e;

            display: flex;

            align-items: center;

            justify-content: center;

            font-weight: 800;

            margin-bottom: 25px;
        }


        /* =====================================================
           CARD TITLE
        ===================================================== */

        .level-card h3 {

            font-size: 28px;

            margin-bottom: 8px;
        }


        .level-subtitle {

            color: #e5b82e;

            font-size: 13px;

            font-weight: 700;

            margin-bottom: 18px;
        }


        .level-description {

            color: #929aa5;

            font-size: 14px;

            margin-bottom: 25px;

            line-height: 1.7;
        }


        /* =====================================================
           MATERIAL LIST
        ===================================================== */

        .material-list {

            list-style: none;

            margin-bottom: 30px;
        }


        .material-list li {

            color: #c4c8ce;

            font-size: 14px;

            padding:
                9px 0;

            border-bottom:
                1px solid #202329;
        }


        .material-list li::before {

            content: "✓";

            color: #e5b82e;

            font-weight: bold;

            margin-right: 10px;
        }


        /* =====================================================
           CARD BUTTON
        ===================================================== */

        .card-button {

            margin-top: auto;

            display: block;

            text-align: center;

            padding: 14px;

            border-radius: 10px;

            background: #e5b82e;

            color: #08090a;

            font-weight: 700;

            transition:
                0.3s ease;
        }


        .card-button:hover {

            background: #f2ca4e;
        }


        .card-button.dark {

            background: transparent;

            color: #e5b82e;

            border:
                1px solid #5c4a19;
        }


        .card-button.dark:hover {

            background:
                rgba(
                    229,
                    184,
                    46,
                    0.08
                );
        }


        /* =====================================================
           LEARNING PATH
        ===================================================== */

        .learning-path {

            padding:
                80px
                8%;

            background: #0c0e11;

            border-top:
                1px solid #202329;

            border-bottom:
                1px solid #202329;
        }


        .path-container {

            max-width: 1100px;

            margin: auto;
        }


        /* =====================================================
           PATH TITLE
        ===================================================== */

        .path-title {

            text-align: center;

            margin-bottom: 50px;
        }


        .path-title h2 {

            font-size: 32px;
        }


        .path-title p {

            color: #828a95;

            margin-top: 8px;
        }


        /* =====================================================
           PATH
        ===================================================== */

        .path {

            display: flex;

            align-items: center;

            justify-content: center;

            gap: 20px;
        }


        .path-item {

            text-align: center;

            flex: 1;
        }


        .path-icon {

            width: 65px;

            height: 65px;

            margin:
                auto
                auto
                15px;

            border-radius: 50%;

            background: #15181d;

            border:
                1px solid #66521c;

            color: #e5b82e;

            display: flex;

            align-items: center;

            justify-content: center;

            font-size: 20px;

            font-weight: 800;
        }


        .path-item h4 {

            font-size: 17px;
        }


        .path-item p {

            color: #7f8792;

            font-size: 13px;

            margin-top: 5px;
        }


        .arrow {

            color: #5d626a;

            font-size: 25px;
        }


        /* =====================================================
           DISCLAIMER
        ===================================================== */

        .disclaimer {

            max-width: 1000px;

            margin:
                70px auto 0;

            padding: 25px;

            border:
                1px solid #292d33;

            border-radius: 15px;

            background: #0e1014;
        }


        .disclaimer h4 {

            color: #e5b82e;

            margin-bottom: 8px;
        }


        .disclaimer p {

            color: #777f8a;

            font-size: 13px;

            line-height: 1.7;
        }


        /* =====================================================
           FOOTER
        ===================================================== */

        .footer {

            padding:
                35px
                8%;

            border-top:
                1px solid #202329;

            text-align: center;

            color: #656d77;

            font-size: 13px;
        }


        .footer strong {

            color: #e5b82e;
        }


        /* =====================================================
           RESPONSIVE 1100PX
        ===================================================== */

        @media (max-width: 1100px) {

            .nav-container {

                width: 94%;
            }


            .nav-menu {

                gap: 20px;
            }


            .nav-menu a {

                font-size: 14px;
            }


            .level-grid {

                grid-template-columns: 1fr;

                max-width: 650px;
            }


            .path {

                flex-direction: column;
            }


            .arrow {

                transform:
                    rotate(90deg);
            }

        }


        /* =====================================================
           RESPONSIVE 900PX
        ===================================================== */

        @media (max-width: 900px) {

            .navbar {

                height: auto;

                padding:
                    18px
                    5%;
            }


            .nav-container {

                flex-wrap: wrap;

                justify-content: center;
            }


            .brand {

                width: 100%;

                justify-content: center;
            }


            .nav-menu {

                justify-content: center;

                flex-wrap: wrap;
            }

        }


        /* =====================================================
           RESPONSIVE 750PX
        ===================================================== */

        @media (max-width: 750px) {

            .navbar {

                padding:
                    18px
                    5%;
            }


            .nav-container {

                gap: 20px;
            }


            .brand {

                justify-content: flex-start;
            }


            .nav-menu {

                gap:
                    12px
                    18px;
            }


            .nav-button {

                display: none;
            }


            .hero {

                padding:
                    65px
                    5%;
            }


            .hero h1 {

                font-size: 42px;
            }


            .hero p {

                font-size: 16px;
            }


            .section {

                padding-left: 5%;

                padding-right: 5%;
            }


            .learning-path {

                padding-left: 5%;

                padding-right: 5%;
            }

        }


        /* =====================================================
           RESPONSIVE 500PX
        ===================================================== */

        @media (max-width: 500px) {

            .brand-logo {

                width: 42px;

                height: 42px;
            }


            .brand-text h2 {

                font-size: 16px;

                letter-spacing: 1.5px;
            }


            .brand-text span {

                font-size: 6px;

                letter-spacing: 1.2px;
            }


            .nav-menu {

                gap:
                    10px
                    13px;
            }


            .nav-menu a {

                font-size: 11px;
            }


            .hero {

                padding-top: 55px;
            }


            .hero h1 {

                font-size: 35px;
            }


            .hero p {

                font-size: 14px;
            }


            .level-card {

                padding: 25px;

                min-height: auto;
            }


            .level-card h3 {

                font-size: 25px;
            }


            .section-title h2 {

                font-size: 27px;
            }


            .path-title h2 {

                font-size: 27px;
            }

        }

    </style>

</head>


<body>


<!-- =====================================================
     NAVBAR
===================================================== -->

<header class="navbar">

    <div class="nav-container">


        <!-- =================================================
             BRAND + LOGO
        ================================================== -->

        <a
            href="../index.php"
            class="brand"
        >


            <div class="brand-logo">

                <img
                    src="<?= htmlspecialchars($logoImage); ?>"
                    alt="Victory International Futures"
                >

            </div>


            <div class="brand-text">

                <h2>
                    VICTORY MARKET
                </h2>

                <span>
                    TRADING &amp; MARKET EDUCATION
                </span>

            </div>


        </a>


        <!-- =================================================
             NAVIGATION
        ================================================== -->

        <nav class="nav-menu">


            <a href="../index.php">
                Home
            </a>


            <a href="../market/index.php">
                Daily Market
            </a>


            <a href="../news/index.php">
                Market News
            </a>


            <a href="../demo_account/index.php">
                Platform
            </a>


            <a href="../videos/index.php">
                Videos
            </a>


            <a
                href="index.php"
                class="active"
            >
                E-Book
            </a>


            <a
                href="../demo_account/index.php"
                class="nav-button"
            >
                Start Learning
            </a>


        </nav>


    </div>

</header>



<!-- =====================================================
     HERO
===================================================== -->

<section class="hero">


    <div class="hero-label">

        GOLD MARKET EDUCATION

    </div>


    <h1>

        Learn the Market.<br>

        <span>
            Build Your Knowledge.
        </span>

    </h1>


    <p>

        Pelajari gold market dan dasar trading secara bertahap
        melalui materi yang disusun dari level pemula hingga
        profesional.

    </p>


</section>



<!-- =====================================================
     LEVEL SECTION
===================================================== -->

<section class="section">


    <div class="section-title">


        <h2>
            Learning Levels
        </h2>


        <p>
            Pilih level pembelajaran sesuai dengan pemahamanmu.
        </p>


    </div>



    <div class="level-grid">


        <!-- =================================================
             BEGINNER
        ================================================== -->

        <div class="level-card">


            <div class="level-number">
                01
            </div>


            <h3>
                Beginner
            </h3>


            <div class="level-subtitle">
                MEMBANGUN DASAR
            </div>


            <p class="level-description">

                Cocok untuk kamu yang baru mengenal gold market
                dan ingin memahami konsep dasar trading.

            </p>


            <ul class="material-list">

                <li>
                    Pengenalan Gold Market
                </li>

                <li>
                    Dasar Trading
                </li>

                <li>
                    Istilah Trading
                </li>

                <li>
                    Candlestick Dasar
                </li>

                <li>
                    Buy, Sell &amp; Spread
                </li>

            </ul>


            <a
                href="beginner.php"
                class="card-button"
            >

                Mulai Beginner →

            </a>


        </div>



        <!-- =================================================
             INTERMEDIATE
        ================================================== -->

        <div class="level-card">


            <div class="level-number">
                02
            </div>


            <h3>
                Intermediate
            </h3>


            <div class="level-subtitle">
                MEMAHAMI ANALISIS
            </div>


            <p class="level-description">

                Memperdalam kemampuan membaca market melalui
                analisis teknikal, fundamental dan pengelolaan
                modal.

            </p>


            <ul class="material-list">

                <li>
                    Analisis Teknikal
                </li>

                <li>
                    Analisis Fundamental
                </li>

                <li>
                    Money Management
                </li>

                <li>
                    Support &amp; Resistance
                </li>

                <li>
                    Indikator Teknikal
                </li>

            </ul>


            <a
                href="intermediate.php"
                class="card-button"
            >

                Mulai Intermediate →

            </a>


        </div>



        <!-- =================================================
             PROFESSIONAL
        ================================================== -->

        <div class="level-card">


            <div class="level-number">
                03
            </div>


            <h3>
                Professional
            </h3>


            <div class="level-subtitle">
                STRATEGI &amp; PERENCANAAN
            </div>


            <p class="level-description">

                Materi lanjutan untuk memahami strategi trading,
                analisis market dan penyusunan trading plan.

            </p>


            <ul class="material-list">

                <li>
                    Strategi Trading
                </li>

                <li>
                    Market Analysis
                </li>

                <li>
                    Risk Management
                </li>

                <li>
                    Risk Reward Ratio
                </li>

                <li>
                    Trading Plan
                </li>

            </ul>


            <a
                href="professional.php"
                class="card-button"
            >

                Mulai Professional →

            </a>


        </div>


    </div>

</section>



<!-- =====================================================
     LEARNING PATH
===================================================== -->

<section class="learning-path">


    <div class="path-container">


        <div class="path-title">


            <h2>
                Learning Path
            </h2>


            <p>
                Belajar secara bertahap agar pemahaman lebih terstruktur.
            </p>


        </div>



        <div class="path">


            <!-- BEGINNER -->

            <div class="path-item">


                <div class="path-icon">
                    01
                </div>


                <h4>
                    Beginner
                </h4>


                <p>
                    Pahami dasar market
                </p>


            </div>



            <div class="arrow">
                →
            </div>



            <!-- INTERMEDIATE -->

            <div class="path-item">


                <div class="path-icon">
                    02
                </div>


                <h4>
                    Intermediate
                </h4>


                <p>
                    Pelajari analisis
                </p>


            </div>



            <div class="arrow">
                →
            </div>



            <!-- PROFESSIONAL -->

            <div class="path-item">


                <div class="path-icon">
                    03
                </div>


                <h4>
                    Professional
                </h4>


                <p>
                    Susun strategi
                </p>


            </div>


        </div>



        <!-- =================================================
             DISCLAIMER
        ================================================== -->

        <div class="disclaimer">


            <h4>
                Catatan Pembelajaran
            </h4>


            <p>

                Materi pada platform ini ditujukan untuk tujuan
                edukasi dan membantu memahami konsep dasar market
                dan trading. Informasi yang tersedia bukan
                merupakan rekomendasi atau ajakan untuk melakukan
                transaksi tertentu. Selalu lakukan pembelajaran
                dan pertimbangan secara mandiri.

            </p>


        </div>


    </div>

</section>



<!-- =====================================================
     FOOTER
===================================================== -->

<footer class="footer">


    <p>

        © <?= date("Y"); ?>

        <strong>
            Victory Market
        </strong>.

        Gold Market Education Platform.

    </p>


</footer>


</body>

</html>