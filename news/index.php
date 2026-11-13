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
   SEARCH & CATEGORY
========================================================= */

$search = isset($_GET['search'])
    ? trim($_GET['search'])
    : '';

$category = isset($_GET['category'])
    ? trim($_GET['category'])
    : '';


/* =========================================================
   QUERY BERITA
========================================================= */

$sql = "
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
    WHERE 1=1
";

$params = [];
$types = "";


/* =========================================================
   SEARCH
========================================================= */

if ($search !== '') {

    $sql .= "
        AND (
            title LIKE ?
            OR summary LIKE ?
            OR content LIKE ?
        )
    ";

    $searchValue = '%' . $search . '%';

    $params[] = $searchValue;
    $params[] = $searchValue;
    $params[] = $searchValue;

    $types .= "sss";
}


/* =========================================================
   FILTER CATEGORY
========================================================= */

if ($category !== '') {

    $sql .= "
        AND category = ?
    ";

    $params[] = $category;

    $types .= "s";
}


/* =========================================================
   SORT BERITA TERBARU
========================================================= */

$sql .= "
    ORDER BY
        COALESCE(published_at, created_at) DESC,
        id DESC
";


/* =========================================================
   PREPARE
========================================================= */

$stmt = $conn->prepare($sql);

if (!$stmt) {

    die(
        "Query database gagal: "
        . e($conn->error)
    );
}


/* =========================================================
   BIND PARAMETER
========================================================= */

if (!empty($params)) {

    $bind = [];
    $bind[] = $types;

    foreach ($params as $key => $value) {
        $bind[] = &$params[$key];
    }

    call_user_func_array(
        [$stmt, 'bind_param'],
        $bind
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
   RESULT
========================================================= */

$result = $stmt->get_result();


/* =========================================================
   AMBIL KATEGORI
========================================================= */

$categoryQuery = "
    SELECT DISTINCT category
    FROM news
    WHERE category IS NOT NULL
      AND category != ''
    ORDER BY category ASC
";

$categoryResult = $conn->query($categoryQuery);

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Market News | Victory Market</title>

    <meta
        name="description"
        content="Berita pasar emas, komoditas, ekonomi, dan perkembangan global terkini dari Victory Market."
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
            width: min(1200px, 92%);

            margin: auto;

            display: flex;
            align-items: center;
            justify-content: space-between;
        }


        /* =====================================================
           LOGO
        ===================================================== */

        .logo {
            display: flex;
            align-items: center;

            gap: 12px;
        }

        .logo-box {
            width: 48px;
            height: 48px;

            border-radius: 10px;

            background: #08090c;

            display: flex;
            align-items: center;
            justify-content: center;

            overflow: hidden;

            flex-shrink: 0;
        }

        .logo-box img {
            width: 100%;
            height: 100%;

            object-fit: contain;

            display: block;
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

            letter-spacing: 1.5px;

            color: #687083;
        }


        /* =====================================================
           NAV MENU
        ===================================================== */

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

            padding: 13px 22px;

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
            width: min(1200px, 92%);

            margin: auto;
        }


        /* =====================================================
           HERO
        ===================================================== */

        .hero {
            padding:
                55px
                0
                25px;
        }

        .eyebrow {
            display: inline-flex;

            align-items: center;

            gap: 8px;

            border:
                1px solid #4d411d;

            color: #d4af37;

            padding:
                8px
                14px;

            border-radius: 30px;

            font-size: 10px;

            letter-spacing: 1.5px;

            font-weight: 700;
        }

        .eyebrow-dot {
            width: 7px;
            height: 7px;

            background: #34d399;

            border-radius: 50%;
        }

        .hero h1 {
            font-size: 54px;

            line-height: 1.1;

            margin-top: 22px;

            font-weight: 800;

            letter-spacing: -2px;
        }

        .hero h1 span {
            color: #d4af37;
        }

        .hero-description {
            max-width: 680px;

            margin-top: 16px;

            color: #8fa0ba;

            font-size: 15px;

            line-height: 1.7;
        }


        /* =====================================================
           SEARCH
        ===================================================== */

        .toolbar {
            margin-top: 25px;

            display: flex;

            gap: 12px;

            flex-wrap: wrap;
        }

        .search-form {
            flex: 1;

            min-width: 260px;

            display: flex;

            gap: 10px;
        }

        .search-input {
            width: 100%;

            background: #121419;

            border:
                1px solid #292d35;

            color: #ffffff;

            padding:
                15px
                18px;

            border-radius: 12px;

            outline: none;

            font-size: 14px;
        }

        .search-input:focus {
            border-color: #d4af37;
        }

        .search-button {
            border: none;

            background: #d4af37;

            color: #08090c;

            padding:
                0
                23px;

            border-radius: 12px;

            font-weight: 700;

            cursor: pointer;
        }

        .search-button:hover {
            background: #e4c34a;
        }

        .category-filter {
            background: #121419;

            border:
                1px solid #292d35;

            color: #d9dce3;

            padding:
                15px
                16px;

            border-radius: 12px;

            outline: none;

            min-width: 180px;
        }


        /* =====================================================
           SECTION HEADER
        ===================================================== */

        .section-header {
            display: flex;

            justify-content: space-between;

            align-items: end;

            margin:
                45px
                0
                20px;
        }

        .section-label {
            color: #d4af37;

            font-size: 11px;

            letter-spacing: 2px;

            font-weight: 700;

            margin-bottom: 8px;
        }

        .section-header h2 {
            font-size: 28px;

            font-weight: 800;
        }

        .result-info {
            color: #6e7687;

            font-size: 13px;
        }


        /* =====================================================
           NEWS GRID
        ===================================================== */

        .news-grid {
            display: grid;

            grid-template-columns:
                repeat(3, 1fr);

            gap: 22px;

            padding-bottom: 70px;
        }


        /* =====================================================
           CARD
        ===================================================== */

        .news-card {
            background: #15171c;

            border:
                1px solid #292c33;

            border-radius: 17px;

            overflow: hidden;

            transition:
                transform 0.25s ease,
                border-color 0.25s ease,
                box-shadow 0.25s ease;
        }

        .news-card:hover {
            transform: translateY(-5px);

            border-color: #5c4d1e;

            box-shadow:
                0 15px 40px
                rgba(0, 0, 0, 0.25);
        }


        /* =====================================================
           IMAGE
        ===================================================== */

        .news-image-wrapper {
            position: relative;

            height: 220px;

            background: #101217;

            overflow: hidden;
        }

        .news-image {
            width: 100%;
            height: 100%;

            object-fit: cover;

            display: block;

            transition:
                transform 0.35s ease;
        }

        .news-card:hover .news-image {
            transform: scale(1.04);
        }

        .image-placeholder {
            width: 100%;
            height: 100%;

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

            font-size: 12px;

            letter-spacing: 1px;

            text-transform: uppercase;

            text-align: center;

            padding: 20px;
        }

        .category-badge {
            position: absolute;

            left: 15px;
            bottom: 14px;

            background: #d4af37;

            color: #08090c;

            padding:
                8px
                13px;

            border-radius: 20px;

            font-size: 10px;

            font-weight: 800;

            letter-spacing: 0.6px;

            text-transform: uppercase;
        }


        /* =====================================================
           CONTENT
        ===================================================== */

        .news-content {
            padding: 21px;
        }

        .news-title {
            font-size: 21px;

            line-height: 1.3;

            font-weight: 750;

            margin-bottom: 12px;

            color: #ffffff;
        }

        .news-summary {
            color: #8e97a8;

            font-size: 13px;

            line-height: 1.65;

            display: -webkit-box;

            -webkit-line-clamp: 3;

            -webkit-box-orient: vertical;

            overflow: hidden;

            min-height: 64px;
        }


        /* =====================================================
           TAG
        ===================================================== */

        .news-tags {
            display: flex;

            flex-wrap: wrap;

            gap: 7px;

            margin-top: 14px;
        }

        .tag {
            font-size: 10px;

            padding:
                6px
                9px;

            border-radius: 7px;

            background: #202329;

            color: #aeb5c1;
        }

        .tag.bullish {
            background:
                rgba(52, 211, 153, 0.12);

            color: #34d399;
        }

        .tag.bearish {
            background:
                rgba(248, 113, 113, 0.12);

            color: #f87171;
        }

        .tag.neutral {
            background:
                rgba(212, 175, 55, 0.10);

            color: #d4af37;
        }


        /* =====================================================
           META
        ===================================================== */

        .news-meta {
            display: flex;

            justify-content: space-between;

            align-items: center;

            gap: 10px;

            border-top:
                1px solid #292c33;

            margin-top: 18px;

            padding-top: 16px;
        }

        .date {
            color: #778092;

            font-size: 11px;
        }

        .source {
            color: #9ea6b5;

            font-size: 11px;

            font-weight: 600;
        }


        /* =====================================================
           EMPTY
        ===================================================== */

        .empty {
            grid-column: 1 / -1;

            background: #15171c;

            border:
                1px solid #292c33;

            border-radius: 17px;

            padding:
                60px
                25px;

            text-align: center;
        }

        .empty h3 {
            font-size: 22px;

            margin-bottom: 10px;
        }

        .empty p {
            color: #7e8798;

            font-size: 14px;

            line-height: 1.6;
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

        @media (max-width: 950px) {

            .nav-menu {
                gap: 16px;
            }

            .news-grid {
                grid-template-columns:
                    repeat(2, 1fr);
            }

            .hero h1 {
                font-size: 44px;
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

            .hero {
                padding-top: 35px;
            }

            .hero h1 {
                font-size: 37px;
            }

            .news-grid {
                grid-template-columns: 1fr;
            }

            .section-header {
                align-items: flex-start;

                flex-direction: column;

                gap: 8px;
            }

            .toolbar {
                flex-direction: column;
            }

            .search-form {
                width: 100%;
            }

            .category-filter {
                width: 100%;
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

            <!-- LOGO VICTORY -->
            <div class="logo-box">

                <img
                    src="../assets/images/victory-logo.jpeg"
                    alt="Victory International Futures"
                >

            </div>


            <div class="logo-text">

                <div class="logo-title">
                    VICTORY MARKET
                </div>

                <div class="logo-subtitle">
                    VICTORY INTERNASIONAL FUTURES
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

            <a href="../demo_account/">
                Platform
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


    <!-- =====================================================
         HERO
    ===================================================== -->

    <section class="hero">

        <div class="eyebrow">

            <span class="eyebrow-dot"></span>

            MARKET NEWS

        </div>


        <h1>

            Market
            <span>News.</span>

        </h1>


        <p class="hero-description">

            Ikuti perkembangan berita ekonomi,
            komoditas, dan faktor global yang dapat
            memengaruhi pergerakan harga emas.

        </p>


        <!-- =================================================
             SEARCH
        ================================================= -->

        <div class="toolbar">


            <form
                method="GET"
                action="index.php"
                class="search-form"
            >

                <input
                    type="text"
                    name="search"
                    class="search-input"
                    placeholder="Cari berita..."
                    value="<?= e($search); ?>"
                >


                <?php if ($category !== ''): ?>

                    <input
                        type="hidden"
                        name="category"
                        value="<?= e($category); ?>"
                    >

                <?php endif; ?>


                <button
                    type="submit"
                    class="search-button"
                >
                    Cari
                </button>

            </form>


            <form
                method="GET"
                action="index.php"
            >

                <?php if ($search !== ''): ?>

                    <input
                        type="hidden"
                        name="search"
                        value="<?= e($search); ?>"
                    >

                <?php endif; ?>


                <select
                    name="category"
                    class="category-filter"
                    onchange="this.form.submit()"
                >

                    <option value="">
                        Semua Kategori
                    </option>


                    <?php if ($categoryResult): ?>

                        <?php while ($cat = $categoryResult->fetch_assoc()): ?>

                            <option
                                value="<?= e($cat['category']); ?>"
                                <?= $category === $cat['category']
                                    ? 'selected'
                                    : ''; ?>
                            >

                                <?= e($cat['category']); ?>

                            </option>

                        <?php endwhile; ?>

                    <?php endif; ?>

                </select>

            </form>

        </div>

    </section>


    <!-- =====================================================
         SECTION HEADER
    ===================================================== -->

    <div class="section-header">

        <div>

            <div class="section-label">
                LATEST UPDATES
            </div>

            <h2>
                Berita Pasar Terkini
            </h2>

        </div>


        <div class="result-info">

            <?php if ($search !== ''): ?>

                Hasil pencarian:

                <strong>
                    <?= e($search); ?>
                </strong>

            <?php else: ?>

                Berita terbaru dari Victory Market

            <?php endif; ?>

        </div>

    </div>


    <!-- =====================================================
         NEWS GRID
    ===================================================== -->

    <section class="news-grid">


        <?php if ($result && $result->num_rows > 0): ?>


            <?php while ($news = $result->fetch_assoc()): ?>


                <?php

                $image = trim(
                    $news['image'] ?? ''
                );

                $slug = trim(
                    $news['slug'] ?? ''
                );


                if ($slug !== '') {

                    $detailLink =
                        'detail.php?slug='
                        . urlencode($slug);

                } else {

                    $detailLink =
                        'detail.php?id='
                        . (int) $news['id'];
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


                $categoryName =
                    trim(
                        $news['category']
                        ?? ''
                    );


                if ($categoryName === '') {
                    $categoryName = 'Market News';
                }


                $source =
                    trim(
                        $news['source']
                        ?? ''
                    );


                if ($source === '') {
                    $source = 'Victory Market';
                }

                ?>


                <article class="news-card">

                    <a href="<?= e($detailLink); ?>">


                        <!-- =================================================
                             IMAGE
                        ================================================= -->

                        <div class="news-image-wrapper">


                            <?php if ($image !== ''): ?>

                                <img
                                    src="<?= e($image); ?>"
                                    alt="<?= e($news['title']); ?>"
                                    class="news-image"
                                    loading="lazy"
                                    onerror="
                                        this.style.display='none';
                                        this.nextElementSibling.style.display='flex';
                                    "
                                >


                                <div
                                    class="image-placeholder"
                                    style="display:none;"
                                >
                                    Gambar berita tidak tersedia
                                </div>


                            <?php else: ?>


                                <div class="image-placeholder">
                                    Gambar berita belum tersedia
                                </div>


                            <?php endif; ?>


                            <div class="category-badge">

                                <?= e($categoryName); ?>

                            </div>


                        </div>


                        <!-- =================================================
                             CONTENT
                        ================================================= -->

                        <div class="news-content">


                            <h3 class="news-title">

                                <?= e(
                                    $news['title']
                                ); ?>

                            </h3>


                            <p class="news-summary">

                                <?= e(
                                    $news['summary']
                                    ?: 'Berita pasar terbaru mengenai perkembangan ekonomi dan komoditas.'
                                ); ?>

                            </p>


                            <!-- =================================================
                                 TAG
                            ================================================= -->

                            <div class="news-tags">


                                <?php if (!empty($news['gold_effect'])): ?>

                                    <span
                                        class="tag <?= e($goldEffectClass); ?>"
                                    >

                                        Gold:
                                        <?= e($goldEffect); ?>

                                    </span>

                                <?php endif; ?>


                                <?php if (!empty($news['impact_level'])): ?>

                                    <span class="tag">

                                        Impact:

                                        <?= e(
                                            $news['impact_level']
                                        ); ?>

                                    </span>

                                <?php endif; ?>


                            </div>


                            <!-- =================================================
                                 META
                            ================================================= -->

                            <div class="news-meta">

                                <span class="date">

                                    <?= formatDateIndonesia(
                                        $news['published_at']
                                        ?: $news['created_at']
                                    ); ?>

                                </span>


                                <span class="source">

                                    <?= e($source); ?>

                                </span>

                            </div>


                        </div>

                    </a>

                </article>


            <?php endwhile; ?>


        <?php else: ?>


            <div class="empty">

                <h3>
                    Belum Ada Berita
                </h3>


                <p>

                    <?php if ($search !== ''): ?>

                        Berita dengan kata
                        "<strong><?= e($search); ?></strong>"
                        tidak ditemukan.

                    <?php elseif ($category !== ''): ?>

                        Belum ada berita pada kategori
                        "<strong><?= e($category); ?></strong>".

                    <?php else: ?>

                        Data berita belum tersedia
                        di database Victory Market.

                    <?php endif; ?>

                </p>

            </div>


        <?php endif; ?>


    </section>


</main>


<!-- =========================================================
     FOOTER
========================================================= -->

<footer>

    <div class="container">

        VICTORY MARKET —
        Trading &amp; Market Education

        &copy; <?= date('Y'); ?>

    </div>

</footer>


</body>

</html>

<?php

$stmt->close();

?>