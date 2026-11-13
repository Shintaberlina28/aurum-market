<?php

$pageTitle = "Beginner | AURUM";

$materials = [

    [
        "title" => "Pengenalan Gold Market",
        "description" => "Mengenal gold market, XAU/USD, serta faktor yang dapat memengaruhi pergerakan harga emas."
    ],

    [
        "title" => "Dasar Trading",
        "description" => "Memahami konsep dasar trading, posisi Buy dan Sell, serta bagaimana transaksi pada market bekerja."
    ],

    [
        "title" => "Istilah Trading",
        "description" => "Mengenal istilah dasar seperti lot, spread, leverage, margin, profit dan loss."
    ],

    [
        "title" => "Candlestick Dasar",
        "description" => "Mengenal struktur candlestick dan informasi dasar yang dapat dibaca dari sebuah candle."
    ],

    [
        "title" => "Buy, Sell & Spread",
        "description" => "Memahami konsep Buy, Sell dan spread sebagai bagian dari dasar aktivitas trading."
    ]

];

?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title><?= $pageTitle; ?></title>

<style>

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: Arial, Helvetica, sans-serif;
    background: #070809;
    color: white;
}

.container {
    width: 85%;
    max-width: 1100px;
    margin: auto;
}

.top {
    padding: 70px 0 45px;
}

.back {
    color: #e5b82e;
    text-decoration: none;
    font-size: 14px;
}

.badge {
    display: inline-block;
    margin-top: 35px;

    padding: 7px 15px;

    border: 1px solid #5b4918;
    border-radius: 20px;

    color: #e5b82e;
    font-size: 12px;
    font-weight: bold;
}

h1 {
    font-size: 52px;
    margin-top: 20px;
}

.subtitle {
    color: #929aa5;
    max-width: 700px;
    margin-top: 15px;
    font-size: 17px;
}

.materials {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;

    padding-bottom: 80px;
}

.material {
    background: #101216;
    border: 1px solid #272b31;

    border-radius: 16px;

    padding: 28px;

    transition: 0.3s;
}

.material:hover {
    border-color: #b89122;
    transform: translateY(-4px);
}

.material-number {
    color: #e5b82e;
    font-weight: bold;
    font-size: 13px;
}

.material h2 {
    margin-top: 12px;
    font-size: 21px;
}

.material p {
    color: #8e96a1;
    font-size: 14px;
    margin-top: 10px;
}

.read {
    display: inline-block;

    margin-top: 20px;

    color: #e5b82e;

    font-size: 14px;
    font-weight: bold;
}

@media(max-width: 700px) {

    .container {
        width: 90%;
    }

    h1 {
        font-size: 40px;
    }

    .materials {
        grid-template-columns: 1fr;
    }

}

</style>

</head>

<body>

<div class="container">

    <section class="top">

        <a href="index.php" class="back">
            ← Kembali ke E-Book
        </a>

        <br>

        <div class="badge">
            LEVEL 01 • BEGINNER
        </div>

        <h1>
            Beginner
        </h1>

        <p class="subtitle">
            Bangun pemahaman dasar mengenai gold market
            sebelum mempelajari analisis dan strategi yang
            lebih kompleks.
        </p>

    </section>


    <section class="materials">

        <?php foreach ($materials as $index => $material): ?>

            <article class="material">

                <div class="material-number">
                    MATERI <?= str_pad($index + 1, 2, "0", STR_PAD_LEFT); ?>
                </div>

                <h2>
                    <?= htmlspecialchars($material["title"]); ?>
                </h2>

                <p>
                    <?= htmlspecialchars($material["description"]); ?>
                </p>

                <a href="#" class="read">
                    Pelajari Materi →
                </a>

            </article>

        <?php endforeach; ?>

    </section>

</div>

</body>

</html>