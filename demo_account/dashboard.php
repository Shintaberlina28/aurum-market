<?php

session_start();

require_once "../config/demo_database.php";


/*
|--------------------------------------------------------------------------
| PROTECT PAGE
|--------------------------------------------------------------------------
*/

if (
    !isset($_SESSION["demo_user_id"])
) {

    header(
        "Location: login.php"
    );

    exit;
}


/*
|--------------------------------------------------------------------------
| GET USER
|--------------------------------------------------------------------------
*/

$stmt = $pdo->prepare(
    "
    SELECT
        id,
        email,
        created_at
    FROM demo_users
    WHERE id = :id
    LIMIT 1
    "
);

$stmt->execute([
    ":id" => $_SESSION["demo_user_id"]
]);

$user = $stmt->fetch();


if (!$user) {

    session_unset();

    session_destroy();

    header(
        "Location: login.php"
    );

    exit;
}


$email = $user["email"];

$registeredDate =
    date(
        "d M Y",
        strtotime($user["created_at"])
    );

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
        Demo Account — Victory Market
    </title>


    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    >


    <style>

        * {
            box-sizing: border-box;
        }


        body {

            margin: 0;

            min-height: 100vh;

            font-family: "Inter", sans-serif;

            background: #05080b;

            color: white;
        }


        /* =====================================================
           NAV
        ====================================================== */

        .navbar {

            height: 78px;

            border-bottom: 1px solid rgba(255,255,255,0.1);

            display: flex;

            align-items: center;

            justify-content: space-between;

            padding: 0 5%;
        }


        .logo {

            font-size: 20px;

            font-weight: 800;

            letter-spacing: 1px;
        }


        .logo span {

            color: #eab308;
        }


        .nav-right {

            display: flex;

            align-items: center;

            gap: 20px;
        }


        .email {

            color: #9ca3af;

            font-size: 14px;
        }


        .logout {

            padding: 10px 18px;

            border: 1px solid rgba(255,255,255,0.15);

            border-radius: 8px;

            color: white;

            text-decoration: none;

            font-size: 14px;
        }


        .logout:hover {

            border-color: #eab308;

            color: #eab308;
        }


        /* =====================================================
           CONTENT
        ====================================================== */

        .content {

            width: 90%;

            max-width: 1200px;

            margin: 0 auto;

            padding: 60px 0;
        }


        .welcome {

            margin-bottom: 40px;
        }


        .welcome .label {

            color: #eab308;

            text-transform: uppercase;

            font-size: 13px;

            font-weight: 700;

            letter-spacing: 2px;
        }


        .welcome h1 {

            font-size: 42px;

            margin: 12px 0;
        }


        .welcome p {

            color: #9ca3af;

            line-height: 1.7;

            max-width: 700px;
        }


        /* =====================================================
           NOTICE
        ====================================================== */

        .notice {

            padding: 18px 20px;

            background: rgba(234,179,8,0.07);

            border: 1px solid rgba(234,179,8,0.2);

            border-radius: 12px;

            color: #d1d5db;

            line-height: 1.6;

            margin-bottom: 30px;
        }


        /* =====================================================
           CARDS
        ====================================================== */

        .grid {

            display: grid;

            grid-template-columns:
                repeat(3, 1fr);

            gap: 20px;
        }


        .card {

            background: #10151a;

            border: 1px solid rgba(255,255,255,0.08);

            border-radius: 16px;

            padding: 26px;
        }


        .card-icon {

            font-size: 28px;

            margin-bottom: 20px;
        }


        .card h2 {

            font-size: 20px;

            margin-bottom: 10px;
        }


        .card p {

            color: #8f98a2;

            font-size: 14px;

            line-height: 1.6;
        }


        .card a {

            display: inline-block;

            margin-top: 20px;

            color: #eab308;

            text-decoration: none;

            font-size: 14px;

            font-weight: 600;
        }


        /* =====================================================
           ACCOUNT
        ====================================================== */

        .account {

            margin-top: 30px;

            background: #10151a;

            border: 1px solid rgba(255,255,255,0.08);

            border-radius: 16px;

            padding: 25px;
        }


        .account h2 {

            margin-top: 0;
        }


        .account-row {

            display: flex;

            justify-content: space-between;

            gap: 20px;

            padding: 15px 0;

            border-bottom: 1px solid rgba(255,255,255,0.07);

            color: #9ca3af;
        }


        .account-row:last-child {

            border-bottom: none;
        }


        .account-row strong {

            color: white;
        }


        @media (max-width: 850px) {

            .grid {

                grid-template-columns: 1fr;
            }


            .email {

                display: none;
            }


            .welcome h1 {

                font-size: 32px;
            }

        }

    </style>

</head>


<body>


<!-- =========================================================
     NAVBAR
========================================================= -->

<header class="navbar">


    <div class="logo">

        VICTORY
        <span>MARKET</span>

    </div>


    <div class="nav-right">

        <span class="email">

            <?= htmlspecialchars($email) ?>

        </span>


        <a
            href="logout.php"
            class="logout"
        >
            Logout
        </a>

    </div>

</header>



<!-- =========================================================
     CONTENT
========================================================= -->

<main class="content">


    <section class="welcome">

        <div class="label">
            Demo Account
        </div>


        <h1>
            Selamat Datang di Platform Demo
        </h1>


        <p>

            Kamu berhasil masuk ke akun demo.
            Halaman ini digunakan untuk pembelajaran
            dan pengenalan fitur platform.

        </p>

    </section>



    <div class="notice">

        <strong>
            Mode Demo
        </strong>
        <br>

        Halaman ini merupakan lingkungan pembelajaran.
        Data dan aktivitas di dalamnya tidak menggunakan
        dana nyata.

    </div>



    <!-- =====================================================
         MENU
    ====================================================== -->

    <section class="grid">


        <!-- MARKET -->

        <div class="card">

            <div class="card-icon">
                📊
            </div>


            <h2>
                Daily Market
            </h2>


            <p>

                Pelajari kondisi dan pergerakan
                market melalui data yang tersedia
                di website.

            </p>


            <a href="/aurum/market/">

                Buka Daily Market →

            </a>

        </div>



        <!-- NEWS -->

        <div class="card">

            <div class="card-icon">
                📰
            </div>


            <h2>
                Market News
            </h2>


            <p>

                Baca informasi dan berita ekonomi
                yang berkaitan dengan pergerakan
                market.

            </p>


            <a href="/aurum/news/">

                Buka Market News →

            </a>

        </div>



        <!-- LEARNING -->

        <div class="card">

            <div class="card-icon">
                📚
            </div>


            <h2>
                E-Book
            </h2>


            <p>

                Tingkatkan pemahaman market
                melalui materi pembelajaran
                bertahap.

            </p>


            <a href="/aurum/ebooks/">

                Buka E-Book →

            </a>

        </div>

    </section>



    <!-- =====================================================
         ACCOUNT INFORMATION
    ====================================================== -->

    <section class="account">

        <h2>
            Informasi Akun
        </h2>


        <div class="account-row">

            <span>
                Email
            </span>

            <strong>
                <?= htmlspecialchars($email) ?>
            </strong>

        </div>


        <div class="account-row">

            <span>
                Status
            </span>

            <strong>
                Demo Account
            </strong>

        </div>


        <div class="account-row">

            <span>
                Terdaftar
            </span>

            <strong>
                <?= htmlspecialchars($registeredDate) ?>
            </strong>

        </div>

    </section>

</main>


</body>

</html>