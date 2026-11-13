<?php
require_once "../config/database.php";


/* =========================================================
   HELPER
========================================================= */

function e($value)
{
    return htmlspecialchars(
        $value ?? '',
        ENT_QUOTES,
        'UTF-8'
    );
}


function formatDateIndonesia($date)
{
    if (empty($date)) {
        return '-';
    }

    $timestamp = strtotime($date);

    if (!$timestamp) {
        return '-';
    }

    $bulan = [
        1  => 'Januari',
        2  => 'Februari',
        3  => 'Maret',
        4  => 'April',
        5  => 'Mei',
        6  => 'Juni',
        7  => 'Juli',
        8  => 'Agustus',
        9  => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember'
    ];

    return date('d', $timestamp)
        . ' '
        . $bulan[(int) date('m', $timestamp)]
        . ' '
        . date('Y', $timestamp);
}


/* =========================================================
   AMBIL SLUG / ID
========================================================= */

$slug = isset($_GET['slug'])
    ? trim($_GET['slug'])
    : '';

$id = isset($_GET['id'])
    ? (int) $_GET['id']
    : 0;


/* =========================================================
   VALIDASI
========================================================= */

if ($slug === '' && $id <= 0) {

    header(
        "Location: index.php"
    );

    exit;
}


/* =========================================================
   QUERY BERITA
========================================================= */

if ($slug !== '') {


    $stmt = $conn->prepare("
        SELECT
            id,
            title,
            slug,
            category,
            image,
            summary,
            content,
            impact_level,
            gold_effect,
            source,
            source_url,
            published_at,
            created_at
        FROM news
        WHERE slug = ?
        LIMIT 1
    ");


    if (!$stmt) {

        die(
            "Query database gagal: "
            . e($conn->error)
        );

    }


    $stmt->bind_param(
        "s",
        $slug
    );


} else {


    $stmt = $conn->prepare("
        SELECT
            id,
            title,
            slug,
            category,
            image,
            summary,
            content,
            impact_level,
            gold_effect,
            source,
            source_url,
            published_at,
            created_at
        FROM news
        WHERE id = ?
        LIMIT 1
    ");


    if (!$stmt) {

        die(
            "Query database gagal: "
            . e($conn->error)
        );

    }


    $stmt->bind_param(
        "i",
        $id
    );

}


/* =========================================================
   EXECUTE
========================================================= */

if (!$stmt->execute()) {

    die(
        "Gagal mengambil berita: "
        . e($stmt->error)
    );

}


/* =========================================================
   AMBIL DATA
========================================================= */

$result = $stmt->get_result();

$news = $result->fetch_assoc();

$stmt->close();


/* =========================================================
   BERITA TIDAK DITEMUKAN
========================================================= */

if (!$news) {

    http_response_code(404);

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
            Berita Tidak Ditemukan | AURUM
        </title>

        <style>

            * {
                box-sizing: border-box;
            }

            body {

                margin: 0;

                min-height: 100vh;

                display: flex;

                align-items: center;

                justify-content: center;

                background: #08090c;

                color: #ffffff;

                font-family:
                    Arial,
                    Helvetica,
                    sans-serif;

            }

            .error-card {

                width: min(
                    550px,
                    90%
                );

                padding: 45px;

                text-align: center;

                background: #15171c;

                border:
                    1px solid #292c33;

                border-radius: 18px;

            }

            .error-card h1 {

                color: #d4af37;

                margin-bottom: 12px;

            }

            .error-card p {

                color: #8f96a5;

                line-height: 1.6;

            }

            .error-card a {

                display: inline-block;

                margin-top: 20px;

                padding:
                    13px
                    22px;

                border-radius: 10px;

                background: #d4af37;

                color: #08090c;

                text-decoration: none;

                font-weight: bold;

            }

        </style>

    </head>


    <body>

        <div class="error-card">

            <h1>
                Berita Tidak Ditemukan
            </h1>

            <p>

                Berita yang kamu cari
                tidak tersedia atau
                sudah dihapus dari database.

            </p>

            <a href="index.php">

                ← Kembali ke Market News

            </a>

        </div>

    </body>

    </html>

    <?php

    exit;
}


/* =========================================================
   DATA
========================================================= */

$image =
    trim(
        $news['image'] ?? ''
    );


$category =
    trim(
        $news['category'] ?? ''
    );

if ($category === '') {
    $category = 'Market News';
}


$source =
    trim(
        $news['source'] ?? ''
    );

if ($source === '') {
    $source = 'Investing.com';
}


$goldEffect =
    trim(
        $news['gold_effect']
        ?? 'Neutral'
    );


$goldEffectClass =
    strtolower(
        $goldEffect
    );


$impact =
    trim(
        $news['impact_level']
        ?? 'Medium'
    );


$publishedDate =
    $news['published_at']
    ?: $news['created_at'];


$sourceUrl =
    trim(
        $news['source_url']
        ?? ''
    );


$pageTitle =
    ($news['title'] ?? 'Market News')
    . ' | AURUM';

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
        <?= e($pageTitle); ?>
    </title>

    <meta
        name="description"
        content="<?= e(
            $news['summary']
            ?: 'Berita pasar terbaru dari AURUM.'
        ); ?>"
    >


    <style>

        * {

            box-sizing: border-box;

            margin: 0;

            padding: 0;

        }


        body {

            font-family:
                Arial,
                Helvetica,
                sans-serif;

            background: #08090c;

            color: #f5f5f5;

            min-height: 100vh;

        }


        a {

            color: inherit;

            text-decoration: none;

        }


        /* =====================================================
           NAVBAR
        ===================================================== */

        .navbar {

            height: 82px;

            background: #08090c;

            border-bottom:
                1px solid #202228;

            display: flex;

            align-items: center;

        }


        .nav-container {

            width:
                min(
                    1200px,
                    92%
                );

            margin: auto;

            display: flex;

            align-items: center;

            justify-content: space-between;

        }


        .logo {

            display: flex;

            align-items: center;

            gap: 12px;

        }


        .logo-box {

            width: 42px;

            height: 42px;

            border-radius: 11px;

            background: #d4af37;

            color: #08090c;

            display: flex;

            align-items: center;

            justify-content: center;

            font-size: 22px;

            font-weight: 800;

        }


        .logo-text {

            display: flex;

            flex-direction: column;

            gap: 3px;

        }


        .logo-title {

            font-size: 17px;

            font-weight: 800;

            letter-spacing: 2px;

        }


        .logo-subtitle {

            font-size: 7px;

            letter-spacing: 2px;

            color: #687083;

        }


        .nav-menu {

            display: flex;

            align-items: center;

            gap: 30px;

        }


        .nav-menu a {

            color: #8f96a5;

            font-size: 14px;

            transition: 0.2s;

        }


        .nav-menu a:hover {

            color: #ffffff;

        }


        .nav-menu a.active {

            color: #d4af37;

            font-weight: 600;

        }


        .start-learning {

            background: #d4af37;

            color: #08090c !important;

            padding:
                13px
                22px;

            border-radius: 12px;

            font-weight: 700;

        }


        .start-learning:hover {

            background: #e4c34a !important;

        }


        /* =====================================================
           CONTAINER
        ===================================================== */

        .container {

            width:
                min(
                    1050px,
                    92%
                );

            margin: auto;

        }


        /* =====================================================
           BREADCRUMB
        ===================================================== */

        .breadcrumb {

            padding-top: 38px;

            margin-bottom: 28px;

            font-size: 13px;

            color: #687083;

        }


        .breadcrumb a {

            color: #a3aab8;

        }


        .breadcrumb a:hover {

            color: #d4af37;

        }


        .breadcrumb span {

            margin:
                0
                8px;

            color: #41454e;

        }


        /* =====================================================
           ARTICLE
        ===================================================== */

        .article {

            background: #15171c;

            border:
                1px solid #292c33;

            border-radius: 20px;

            overflow: hidden;

            margin-bottom: 60px;

        }


        /* =====================================================
           IMAGE
        ===================================================== */

        .article-image {

            width: 100%;

            height: 500px;

            object-fit: cover;

            display: block;

        }


        .article-image-placeholder {

            width: 100%;

            height: 500px;

            display: flex;

            align-items: center;

            justify-content: center;

            background:
                linear-gradient(
                    135deg,
                    #15171c,
                    #0d0f13
                );

            color: #697386;

            font-size: 13px;

            letter-spacing: 1px;

            text-transform: uppercase;

            text-align: center;

        }


        /* =====================================================
           BODY
        ===================================================== */

        .article-body {

            padding:
                42px
                50px
                55px;

        }


        .article-category {

            display: inline-block;

            background: #d4af37;

            color: #08090c;

            padding:
                8px
                14px;

            border-radius: 20px;

            font-size: 10px;

            font-weight: 800;

            letter-spacing: 1px;

            text-transform: uppercase;

            margin-bottom: 20px;

        }


        .article-title {

            font-size: 43px;

            line-height: 1.18;

            letter-spacing: -1px;

            font-weight: 800;

            color: #ffffff;

            margin-bottom: 22px;

        }


        .article-summary {

            font-size: 17px;

            line-height: 1.75;

            color: #9ba5b7;

            border-left:
                3px solid #d4af37;

            padding-left: 18px;

            margin-bottom: 28px;

        }


        /* =====================================================
           META
        ===================================================== */

        .article-meta {

            display: flex;

            flex-wrap: wrap;

            align-items: center;

            gap: 20px;

            padding-bottom: 25px;

            border-bottom:
                1px solid #292c33;

        }


        .meta-item {

            color: #7d8697;

            font-size: 12px;

        }


        .meta-item strong {

            color: #d0d5df;

        }


        /* =====================================================
           MARKET INFO
        ===================================================== */

        .analysis-info {

            display: grid;

            grid-template-columns:
                repeat(2, 1fr);

            gap: 14px;

            margin-top: 25px;

        }


        .info-box {

            background: #101217;

            border:
                1px solid #292c33;

            border-radius: 12px;

            padding: 17px;

        }


        .info-label {

            display: block;

            font-size: 10px;

            color: #697386;

            letter-spacing: 1px;

            text-transform: uppercase;

            margin-bottom: 8px;

        }


        .info-value {

            font-size: 14px;

            font-weight: 700;

            color: #ffffff;

        }


        .bullish {

            color:
                #34d399 !important;

        }


        .bearish {

            color:
                #f87171 !important;

        }


        .neutral {

            color:
                #d4af37 !important;

        }


        /* =====================================================
           CONTENT
        ===================================================== */

        .article-content {

            margin-top: 38px;

            color: #c1c7d2;

            font-size: 16px;

            line-height: 1.9;

        }


        .article-content p {

            margin-bottom: 22px;

        }


        .article-content h2 {

            color: #ffffff;

            font-size: 25px;

            margin:
                32px
                0
                15px;

        }


        .article-content h3 {

            color: #ffffff;

            font-size: 20px;

            margin:
                28px
                0
                12px;

        }


        .article-content ul,
        .article-content ol {

            margin:
                15px
                0
                25px
                25px;

        }


        .article-content li {

            margin-bottom: 9px;

        }


        .article-content a {

            color: #d4af37;

            text-decoration: underline;

        }


        .article-content img {

            max-width: 100%;

            height: auto;

            border-radius: 12px;

            margin:
                20px
                0;

        }


        /* =====================================================
           SOURCE
        ===================================================== */

        .source-box {

            margin-top: 38px;

            padding: 23px;

            background: #101217;

            border:
                1px solid #292c33;

            border-radius: 13px;

        }


        .source-label {

            color: #687083;

            font-size: 10px;

            letter-spacing: 1.5px;

            text-transform: uppercase;

            margin-bottom: 8px;

        }


        .source-name {

            color: #ffffff;

            font-weight: 700;

            margin-bottom: 15px;

        }


        .source-button {

            display: inline-block;

            background: #d4af37;

            color: #08090c;

            padding:
                12px
                18px;

            border-radius: 9px;

            font-size: 13px;

            font-weight: 700;

        }


        .source-button:hover {

            background: #e4c34a;

        }


        /* =====================================================
           BACK
        ===================================================== */

        .back-button {

            display: inline-block;

            margin-top: 28px;

            color: #9ba3b2;

            font-size: 13px;

        }


        .back-button:hover {

            color: #d4af37;

        }


        /* =====================================================
           FOOTER
        ===================================================== */

        footer {

            border-top:
                1px solid #202228;

            padding:
                25px
                0;

            color: #687083;

            font-size: 12px;

            text-align: center;

        }


        /* =====================================================
           RESPONSIVE
        ===================================================== */

        @media (max-width: 900px) {

            .nav-menu {
                gap: 15px;
            }

            .article-title {
                font-size: 36px;
            }

            .article-image,
            .article-image-placeholder {
                height: 380px;
            }

        }


        @media (max-width: 700px) {

            .navbar {

                height: auto;

                padding:
                    18px
                    0;

            }

            .nav-container {

                flex-direction: column;

                gap: 18px;

            }

            .nav-menu {

                flex-wrap: wrap;

                justify-content: center;

            }

            .start-learning {

                display: none;

            }

            .article-body {

                padding:
                    30px
                    24px
                    35px;

            }

            .article-image,
            .article-image-placeholder {

                height: 270px;

            }

            .article-title {

                font-size: 30px;

            }

            .article-summary {

                font-size: 15px;

            }

            .analysis-info {

                grid-template-columns: 1fr;

            }

        }

    </style>

</head>


<body>


<!-- =========================================================
     NAVBAR
========================================================= -->

<header class="navbar">

    <div class="nav-container">


        <a href="../" class="logo">

            <div class="logo-box">
                A
            </div>

            <div class="logo-text">

                <div class="logo-title">
                    AURUM
                </div>

                <div class="logo-subtitle">
                    GOLD MARKET EDUCATION
                </div>

            </div>

        </a>


        <nav class="nav-menu">

            <a href="../">
                Home
            </a>

            <a href="../market/">
                Daily Market
            </a>

            <a
                href="index.php"
                class="active"
            >
                Market News
            </a>

            <a href="../learning/">
                Learning
            </a>

            <a href="../videos/">
                Videos
            </a>

            <a href="../ebook/">
                E-Book
            </a>

            <a
                href="../learning/"
                class="start-learning"
            >
                Start Learning
            </a>

        </nav>


    </div>

</header>


<!-- =========================================================
     MAIN
========================================================= -->

<main class="container">


    <!-- BREADCRUMB -->

    <div class="breadcrumb">

        <a href="../">
            Home
        </a>

        <span>›</span>

        <a href="index.php">
            Market News
        </a>

        <span>›</span>

        <?= e($category); ?>

    </div>


    <!-- ARTICLE -->

    <article class="article">


        <!-- =================================================
             IMAGE
        ================================================= -->

        <?php if ($image !== ''): ?>


            <img
                src="<?= e($image); ?>"
                alt="<?= e($news['title']); ?>"
                class="article-image"
                onerror="
                    this.style.display='none';
                    this.nextElementSibling.style.display='flex';
                "
            >


            <div
                class="article-image-placeholder"
                style="display:none;"
            >

                Gambar berita tidak tersedia

            </div>


        <?php else: ?>


            <div class="article-image-placeholder">

                Gambar berita belum tersedia

            </div>


        <?php endif; ?>


        <!-- =================================================
             BODY
        ================================================= -->

        <div class="article-body">


            <!-- CATEGORY -->

            <div class="article-category">

                <?= e($category); ?>

            </div>


            <!-- TITLE -->

            <h1 class="article-title">

                <?= e(
                    $news['title']
                ); ?>

            </h1>


            <!-- SUMMARY -->

            <?php if (!empty($news['summary'])): ?>

                <div class="article-summary">

                    <?= e(
                        $news['summary']
                    ); ?>

                </div>

            <?php endif; ?>


            <!-- META -->

            <div class="article-meta">


                <div class="meta-item">

                    Dipublikasikan:

                    <strong>

                        <?= formatDateIndonesia(
                            $publishedDate
                        ); ?>

                    </strong>

                </div>


                <div class="meta-item">

                    Sumber:

                    <strong>

                        <?= e(
                            $source
                        ); ?>

                    </strong>

                </div>


            </div>


            <!-- =================================================
                 MARKET INFORMATION
            ================================================= -->

            <div class="analysis-info">


                <div class="info-box">

                    <span class="info-label">
                        Gold Effect
                    </span>


                    <span
                        class="
                            info-value
                            <?= e(
                                $goldEffectClass
                            ); ?>
                        "
                    >

                        <?= e(
                            $goldEffect
                        ); ?>

                    </span>

                </div>


                <div class="info-box">

                    <span class="info-label">
                        Impact Level
                    </span>


                    <span class="info-value">

                        <?= e(
                            $impact
                        ); ?>

                    </span>

                </div>


            </div>


            <!-- =================================================
                 ARTICLE CONTENT
            ================================================= -->

            <div class="article-content">

                <?php

                $content =
                    trim(
                        $news['content']
                        ?? ''
                    );


                if ($content !== '') {

                    echo nl2br(
                        e($content)
                    );

                } else {

                    echo
                        '<p>Isi berita belum tersedia.</p>';

                }

                ?>

            </div>


            <!-- =================================================
                 SOURCE
            ================================================= -->

            <div class="source-box">


                <div class="source-label">

                    Sumber Berita

                </div>


                <div class="source-name">

                    <?= e($source); ?>

                </div>


                <?php if ($sourceUrl !== ''): ?>


                    <a
                        href="<?= e($sourceUrl); ?>"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="source-button"
                    >

                        Baca Sumber Asli →

                    </a>


                <?php endif; ?>


            </div>


            <!-- BACK -->

            <a
                href="index.php"
                class="back-button"
            >

                ← Kembali ke Market News

            </a>


        </div>

    </article>

</main>


<!-- =========================================================
     FOOTER
========================================================= -->

<footer>

    <div class="container">

        AURUM — Gold Market Education
        &copy; <?= date('Y'); ?>

    </div>

</footer>


</body>

</html>