<?php

// ======================================================
// VICTORY MARKET
// HALAMAN VIDEO PEMBELAJARAN
// ======================================================


// ======================================================
// DATA VIDEO YOUTUBE
// ======================================================


$videos = [

    [
        'id' => 'L292Q_MM3D8',
        'title' => 'Video Pembelajaran 1',
        'description' => 'Pelajari dasar-dasar market melalui video pembelajaran yang telah disediakan.'
    ],

    [
        'id' => 'XjjUIQcmiqA',
        'title' => 'Video Pembelajaran 2',
        'description' => 'Pelajari informasi dan konsep market melalui video pembelajaran berikut.'
    ],

    [
        'id' => 'FlzTrluAaE8',
        'title' => 'Video Pembelajaran 3',
        'description' => 'Tingkatkan pemahaman mengenai market melalui materi video yang tersedia.'
    ],

    [
        'id' => 'I-CAJdH_l9o',
        'title' => 'Video Pembelajaran 4',
        'description' => 'Simak video pembelajaran berikut untuk menambah wawasan mengenai market.'
    ],

    [
        'id' => 'nwK_RZVScLc',
        'title' => 'Video Pembelajaran 5',
        'description' => 'Materi pembelajaran tambahan dari Victory Market.'
    ],

    [
        'id' => 'o-gnIYDYZhE',
        'title' => 'Video Pembelajaran 6',
        'description' => 'Materi pembelajaran tambahan untuk meningkatkan wawasan market.'
    ]

];



// ======================================================
// LOGO
// ======================================================

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

    <title>
        Videos — Victory Market
    </title>

    <meta
        name="description"
        content="Kumpulan video pembelajaran Victory Market mengenai gold market dan trading."
    >


    <style>

        /* ==================================================
           RESET
        ================================================== */

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }


        /* ==================================================
           BODY
        ================================================== */

        body {

            font-family:
                Arial,
                Helvetica,
                sans-serif;

            background: #05080b;

            color: #ffffff;

            min-height: 100vh;

        }


        a {
            text-decoration: none;
        }


        /* ==================================================
           NAVBAR
        ================================================== */

        .navbar {

            width: 100%;

            height: 82px;

            display: flex;

            align-items: center;

            justify-content: center;

            padding: 0;

            background: #05080b;

            border-bottom:
                1px solid #20262d;

            position: sticky;

            top: 0;

            z-index: 1000;

        }


        /* ==================================================
           NAV CONTAINER
        ================================================== */

        .nav-container {

            width: min(1200px, 92%);

            margin: 0 auto;

            display: flex;

            align-items: center;

            justify-content: space-between;

            gap: 30px;

        }


        /* ==================================================
           BRAND
        ================================================== */

        .brand {

            display: flex;

            align-items: center;

            gap: 12px;

            text-decoration: none;

            color: #ffffff;

            flex-shrink: 0;

        }


        /* ==================================================
           LOGO BOX
        ================================================== */

        .logo-box {

            width: 48px;

            height: 48px;

            border-radius: 10px;

            overflow: hidden;

            background: #05080b;

            display: flex;

            align-items: center;

            justify-content: center;

            flex-shrink: 0;

        }


        .logo-box img {

            width: 100%;

            height: 100%;

            object-fit: contain;

            display: block;

        }


        /* ==================================================
           LOGO TEXT
        ================================================== */

        .logo-text {

            display: flex;

            flex-direction: column;

            justify-content: center;

            gap: 3px;

        }


        .logo-title {

            font-size: 17px;

            font-weight: 800;

            letter-spacing: 2px;

            line-height: 1.1;

            color: #ffffff;

        }


        .logo-subtitle {

            font-size: 7px;

            font-weight: 500;

            letter-spacing: 1.5px;

            line-height: 1.2;

            color: #687083;

            white-space: nowrap;

        }


        /* ==================================================
           NAVIGATION
        ================================================== */

        .nav-menu {

            display: flex;

            align-items: center;

            justify-content: flex-end;

            gap: 30px;

        }


        .nav-menu a {

            color: #f1f1f1;

            text-decoration: none;

            font-size: 14px;

            font-weight: 500;

            transition:
                color 0.25s ease;

            white-space: nowrap;

        }


        .nav-menu a:hover {

            color: #f4b400;

        }


        .nav-menu a.active {

            color: #f4b400;

            font-weight: 700;

        }


        /* ==================================================
           START LEARNING
        ================================================== */

        .start-learning {

            color: #05080b !important;

            background: #f4b400;

            padding:
                13px
                22px;

            border-radius: 12px;

            font-size: 14px !important;

            font-weight: 700 !important;

            transition:
                0.25s ease;

            white-space: nowrap;

        }


        .start-learning:hover {

            background: #ffc928;

            color: #05080b !important;

            transform:
                translateY(-2px);

        }


        /* ==================================================
           MAIN
        ================================================== */

        .main {

            width: 100%;

            max-width: 1250px;

            margin: 0 auto;

            padding:
                80px
                40px
                100px;

        }


        /* ==================================================
           PAGE HEADER
        ================================================== */

        .page-header {

            text-align: center;

            max-width: 800px;

            margin:
                0
                auto
                60px;

        }


        /* ==================================================
           PAGE LABEL
        ================================================== */

        .page-label {

            display: inline-flex;

            align-items: center;

            gap: 10px;

            border:
                1px solid #705800;

            border-radius: 30px;

            padding:
                9px
                18px;

            color: #f4b400;

            font-size: 13px;

            font-weight: 600;

            letter-spacing: 0.5px;

            margin-bottom: 24px;

            background:
                rgba(
                    244,
                    180,
                    0,
                    0.04
                );

        }


        .page-label::before {

            content: "";

            width: 7px;

            height: 7px;

            border-radius: 50%;

            background: #f4b400;

        }


        /* ==================================================
           PAGE TITLE
        ================================================== */

        .page-header h1 {

            font-size: 54px;

            line-height: 1.1;

            font-weight: 800;

            margin-bottom: 20px;

        }


        .page-header h1 span {

            color: #f4b400;

        }


        /* ==================================================
           PAGE DESCRIPTION
        ================================================== */

        .page-header p {

            color: #8e9bad;

            font-size: 17px;

            line-height: 1.7;

            max-width: 700px;

            margin: 0 auto;

        }


        /* ==================================================
           VIDEO GRID
        ================================================== */

        .video-grid {

            display: grid;

            grid-template-columns:
                repeat(2, 1fr);

            gap: 30px;

        }


        /* ==================================================
           VIDEO CARD
        ================================================== */

        .video-card {

            background: #101419;

            border:
                1px solid #252c34;

            border-radius: 18px;

            overflow: hidden;

            transition:
                transform 0.3s ease,
                border-color 0.3s ease,
                box-shadow 0.3s ease;

        }


        .video-card:hover {

            transform:
                translateY(-5px);

            border-color:
                #8b6c00;

            box-shadow:
                0 15px 40px
                rgba(
                    0,
                    0,
                    0,
                    0.35
                );

        }


        /* ==================================================
           VIDEO FRAME
        ================================================== */

        .video-wrapper {

            width: 100%;

            aspect-ratio: 16 / 9;

            background: #000000;

            position: relative;

            overflow: hidden;

        }


        .video-wrapper iframe {

            width: 100%;

            height: 100%;

            border: none;

            display: block;

        }


        /* ==================================================
           VIDEO CONTENT
        ================================================== */

        .video-content {

            padding: 25px;

        }


        .video-number {

            color: #f4b400;

            font-size: 13px;

            font-weight: 700;

            letter-spacing: 1px;

            margin-bottom: 10px;

            text-transform: uppercase;

        }


        .video-content h2 {

            font-size: 22px;

            font-weight: 700;

            margin-bottom: 10px;

            color: #ffffff;

        }


        .video-content p {

            color: #8995a5;

            font-size: 14px;

            line-height: 1.7;

            margin-bottom: 20px;

        }


        /* ==================================================
           YOUTUBE BUTTON
        ================================================== */

        .youtube-button {

            display: inline-flex;

            align-items: center;

            gap: 8px;

            text-decoration: none;

            color: #f4b400;

            border:
                1px solid #705800;

            border-radius: 8px;

            padding:
                10px
                16px;

            font-size: 13px;

            font-weight: 600;

            transition:
                0.25s ease;

        }


        .youtube-button:hover {

            background: #f4b400;

            color: #05080b;

            border-color: #f4b400;

        }


        /* ==================================================
           BOTTOM CTA
        ================================================== */

        .bottom-section {

            text-align: center;

            margin-top: 75px;

            padding:
                50px
                30px;

            border:
                1px solid #252c34;

            border-radius: 18px;

            background:
                radial-gradient(
                    circle at center,
                    rgba(
                        244,
                        180,
                        0,
                        0.08
                    ),
                    transparent 65%
                );

        }


        .bottom-section h2 {

            font-size: 30px;

            margin-bottom: 12px;

        }


        .bottom-section p {

            color: #8995a5;

            font-size: 15px;

            line-height: 1.6;

            margin-bottom: 25px;

        }


        .bottom-button {

            display: inline-block;

            text-decoration: none;

            color: #05080b;

            background: #f4b400;

            padding:
                13px
                25px;

            border-radius: 9px;

            font-size: 14px;

            font-weight: 700;

            transition:
                0.25s ease;

        }


        .bottom-button:hover {

            background: #ffc928;

            transform:
                translateY(-2px);

        }


        /* ==================================================
           FOOTER
        ================================================== */

        .footer {

            border-top:
                1px solid #20262d;

            text-align: center;

            padding:
                30px
                20px;

            color: #697586;

            font-size: 13px;

        }


        .footer strong {

            color: #f4b400;

        }


        /* ==================================================
           RESPONSIVE TABLET
        ================================================== */

        @media (max-width: 1100px) {

            .nav-container {

                width: 92%;

            }


            .nav-menu {

                gap: 20px;

            }


            .nav-menu a {

                font-size: 13px;

            }


            .start-learning {

                padding:
                    12px
                    18px;

            }


            .logo-title {

                font-size: 16px;

            }

        }


        /* ==================================================
           RESPONSIVE SMALL TABLET
        ================================================== */

        @media (max-width: 900px) {

            .navbar {

                height: auto;

                padding:
                    18px
                    0;

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


            .main {

                padding:
                    60px
                    25px
                    80px;

            }


            .video-grid {

                grid-template-columns: 1fr;

            }

        }


        /* ==================================================
           RESPONSIVE MOBILE
        ================================================== */

        @media (max-width: 700px) {

            .navbar {

                padding:
                    18px
                    15px;

            }


            .nav-container {

                width: 100%;

                gap: 18px;

            }


            .brand {

                justify-content: flex-start;

            }


            .logo-box {

                width: 44px;

                height: 44px;

            }


            .logo-title {

                font-size: 16px;

            }


            .logo-subtitle {

                font-size: 6px;

            }


            .nav-menu {

                gap:
                    12px
                    18px;

            }


            .nav-menu a {

                font-size: 12px;

            }


            .start-learning {

                display: none;

            }


            .main {

                padding:
                    55px
                    20px
                    70px;

            }


            .page-header {

                margin-bottom: 40px;

            }


            .page-header h1 {

                font-size: 40px;

            }


            .page-header p {

                font-size: 15px;

            }


            .video-grid {

                grid-template-columns: 1fr;

                gap: 25px;

            }

        }


        /* ==================================================
           SMALL MOBILE
        ================================================== */

        @media (max-width: 480px) {

            .logo-box {

                width: 40px;

                height: 40px;

                border-radius: 8px;

            }


            .logo-title {

                font-size: 14px;

                letter-spacing: 1.5px;

            }


            .logo-subtitle {

                font-size: 5px;

                letter-spacing: 1px;

            }


            .nav-menu {

                gap:
                    10px
                    13px;

            }


            .nav-menu a {

                font-size: 11px;

            }


            .page-header h1 {

                font-size: 34px;

            }


            .page-label {

                font-size: 11px;

            }


            .video-content {

                padding: 20px;

            }


            .video-content h2 {

                font-size: 19px;

            }


            .bottom-section {

                padding:
                    35px
                    20px;

            }


            .bottom-section h2 {

                font-size: 24px;

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
             BRAND
        ================================================== -->

        <a
            href="../index.php"
            class="brand"
        >


            <div class="logo-box">

                <img
                    src="<?= htmlspecialchars($logoImage) ?>"
                    alt="Victory International Futures"
                >

            </div>


            <div class="logo-text">

                <div class="logo-title">
                    VICTORY MARKET
                </div>


                <div class="logo-subtitle">
                    TRADING &amp; MARKET EDUCATION
                </div>

            </div>


        </a>



        <!-- =================================================
             MENU
        ================================================== -->

        <nav class="nav-menu">


            <a href="../index.php">
                Home
            </a>


            <a href="../market/">
                Daily Market
            </a>


            <a href="../news/">
                Market News
            </a>


            <a href="../demo_account/">
                Platform
            </a>


            <a
                href="index.php"
                class="active"
            >
                Videos
            </a>


            <a href="../ebooks/">
                E-Book
            </a>


            <a
                href="../ebooks/"
                class="start-learning"
            >
                Start Learning
            </a>


        </nav>


    </div>

</header>



<!-- =====================================================
     MAIN CONTENT
===================================================== -->

<main class="main">


    <!-- =================================================
         PAGE HEADER
    ================================================== -->

    <section class="page-header">


        <div class="page-label">

            GOLD MARKET EDUCATION

        </div>


        <h1>

            Learn Through

            <span>
                Video.
            </span>

        </h1>


        <p>

            Pelajari berbagai materi mengenai market melalui
            kumpulan video pembelajaran yang telah disediakan.
            Akses video secara langsung untuk menambah wawasan
            dan pemahaman mengenai gold market.

        </p>


    </section>



    <!-- =================================================
         VIDEO LIST
    ================================================== -->

    <section class="video-grid">


        <?php foreach ($videos as $index => $video): ?>


            <article class="video-card">


                <!-- =========================================
                     YOUTUBE VIDEO
                ========================================== -->

                <div class="video-wrapper">


                    <iframe

                        src="https://www.youtube.com/embed/<?php
                            echo htmlspecialchars($video['id']);
                        ?>"

                        title="<?php
                            echo htmlspecialchars($video['title']);
                        ?>"

                        loading="lazy"

                        allow="
                            accelerometer;
                            autoplay;
                            clipboard-write;
                            encrypted-media;
                            gyroscope;
                            picture-in-picture;
                            web-share
                        "

                        allowfullscreen>

                    </iframe>


                </div>



                <!-- =========================================
                     VIDEO INFORMATION
                ========================================== -->

                <div class="video-content">


                    <div class="video-number">

                        Video
                        <?php echo $index + 1; ?>

                    </div>


                    <h2>

                        <?php
                            echo htmlspecialchars(
                                $video['title']
                            );
                        ?>

                    </h2>


                    <p>

                        <?php
                            echo htmlspecialchars(
                                $video['description']
                            );
                        ?>

                    </p>



                    <!-- =====================================
                         YOUTUBE LINK
                    ====================================== -->

                    <a

                        href="https://youtu.be/<?php
                            echo htmlspecialchars($video['id']);
                        ?>"

                        target="_blank"

                        rel="noopener noreferrer"

                        class="youtube-button"

                    >

                        Tonton di YouTube

                        <span>
                            ↗
                        </span>

                    </a>


                </div>


            </article>


        <?php endforeach; ?>


    </section>



    <!-- =================================================
         BOTTOM CTA
    ================================================== -->

    <section class="bottom-section">


        <h2>

            Ingin Memperdalam Pembelajaran?

        </h2>


        <p>

            Jelajahi materi pembelajaran yang tersedia
            melalui E-Book dan pelajari market secara
            bertahap sesuai kebutuhanmu.

        </p>


        <a
            href="../ebooks/"
            class="bottom-button"
        >

            Jelajahi E-Book →

        </a>


    </section>


</main>



<!-- =====================================================
     FOOTER
===================================================== -->

<footer class="footer">


    <p>

        ©
        <?php echo date('Y'); ?>

        <strong>
            Victory Market
        </strong>.

        Gold Market Education Platform.

    </p>


</footer>


</body>

</html>