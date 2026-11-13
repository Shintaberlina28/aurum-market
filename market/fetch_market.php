<?php

require_once "../config/database.php";
require_once "../config/api.php";

/*
|--------------------------------------------------------------------------
| AURUM - FETCH MARKET DATA
|--------------------------------------------------------------------------
| Mengambil data XAU/USD dari Twelve Data
| lalu menyimpan:
|
| 1. market_updates  -> data market terbaru
| 2. market_history  -> riwayat perubahan harga
|
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| 1. API URL
|--------------------------------------------------------------------------
*/

$apiUrl =
    "https://api.twelvedata.com/quote"
    . "?symbol=" . urlencode(MARKET_SYMBOL)
    . "&apikey=" . urlencode(TWELVE_DATA_API_KEY);


/*
|--------------------------------------------------------------------------
| 2. REQUEST KE TWELVE DATA
|--------------------------------------------------------------------------
*/

$ch = curl_init();

curl_setopt_array($ch, [
    CURLOPT_URL => $apiUrl,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 15,
    CURLOPT_CONNECTTIMEOUT => 10,
    CURLOPT_SSL_VERIFYPEER => true
]);

$response = curl_exec($ch);

$curlError = curl_error($ch);

$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

curl_close($ch);


/*
|--------------------------------------------------------------------------
| 3. CEK CURL
|--------------------------------------------------------------------------
*/

if ($response === false || !empty($curlError)) {

    die(
        "Gagal menghubungi API Twelve Data.<br><br>"
        . "Error: "
        . htmlspecialchars($curlError)
    );

}


/*
|--------------------------------------------------------------------------
| 4. CEK HTTP RESPONSE
|--------------------------------------------------------------------------
*/

if ($httpCode < 200 || $httpCode >= 300) {

    die(
        "API Twelve Data mengembalikan HTTP Code: "
        . htmlspecialchars((string) $httpCode)
    );

}


/*
|--------------------------------------------------------------------------
| 5. DECODE JSON
|--------------------------------------------------------------------------
*/

$data = json_decode($response, true);


if (!is_array($data)) {

    die("Response API tidak dapat dibaca sebagai JSON.");

}


/*
|--------------------------------------------------------------------------
| 6. CEK API ERROR
|--------------------------------------------------------------------------
*/

if (
    isset($data['status']) &&
    strtolower($data['status']) === 'error'
) {

    die(
        "Twelve Data API Error: "
        . htmlspecialchars(
            $data['message'] ?? 'Unknown error'
        )
    );

}


/*
|--------------------------------------------------------------------------
| 7. AMBIL DATA MARKET
|--------------------------------------------------------------------------
*/

$symbol = $data['symbol'] ?? MARKET_SYMBOL;


/*
|--------------------------------------------------------------------------
| CURRENT PRICE
|--------------------------------------------------------------------------
*/

$price = isset($data['close'])
    ? (float) $data['close']
    : 0;


/*
|--------------------------------------------------------------------------
| CHANGE VALUE
|--------------------------------------------------------------------------
*/

$changeValue = isset($data['change'])
    ? (float) $data['change']
    : 0;


/*
|--------------------------------------------------------------------------
| CHANGE PERCENT
|--------------------------------------------------------------------------
*/

$percentChange = isset($data['percent_change'])
    ? (float) $data['percent_change']
    : 0;


/*
|--------------------------------------------------------------------------
| 8. VALIDASI HARGA
|--------------------------------------------------------------------------
*/

if ($price <= 0) {

    die(
        "Harga market tidak valid.<br><br>"
        . "Response API:<br>"
        . "<pre>"
        . htmlspecialchars($response)
        . "</pre>"
    );

}


/*
|--------------------------------------------------------------------------
| 9. TENTUKAN MARKET STATUS
|--------------------------------------------------------------------------
*/

if ($percentChange > 0) {

    $marketStatus = "Bullish";

} elseif ($percentChange < 0) {

    $marketStatus = "Bearish";

} else {

    $marketStatus = "Neutral";

}


/*
|--------------------------------------------------------------------------
| 10. MULAI DATABASE TRANSACTION
|--------------------------------------------------------------------------
*/

$conn->begin_transaction();


try {

    /*
    |--------------------------------------------------------------------------
    | 11. HAPUS DATA MARKET TERBARU LAMA
    |--------------------------------------------------------------------------
    |
    | market_updates digunakan untuk menyimpan kondisi market TERBARU.
    |
    | Jadi setiap fetch dilakukan:
    |
    | data lama XAU/USD
    |        ↓
    |      dihapus
    |        ↓
    | data terbaru
    |        ↓
    |   dimasukkan kembali
    |
    */

    $deleteUpdate = $conn->prepare("
        DELETE FROM market_updates
        WHERE symbol = ?
    ");


    if ($deleteUpdate === false) {

        throw new Exception(
            "Gagal menyiapkan query penghapusan market_updates: "
            . $conn->error
        );

    }


    $deleteUpdate->bind_param(
        "s",
        $symbol
    );


    if (!$deleteUpdate->execute()) {

        throw new Exception(
            "Gagal menghapus market update lama: "
            . $deleteUpdate->error
        );

    }


    $deleteUpdate->close();


    /*
    |--------------------------------------------------------------------------
    | 12. SIMPAN MARKET UPDATE TERBARU
    |--------------------------------------------------------------------------
    |
    | Sesuai struktur tabel:
    |
    | id
    | symbol
    | price
    | change_value
    | change_percent
    | market_status
    | support
    | resistance
    | market_summary
    | market_factors
    | updated_at
    |
    | Kita baru memiliki data:
    |
    | symbol
    | price
    | change_value
    | change_percent
    | market_status
    |
    | Support, resistance, summary, factors
    | dibiarkan NULL karena belum berasal dari API.
    |
    */

    $stmt = $conn->prepare("
        INSERT INTO market_updates
        (
            symbol,
            price,
            change_value,
            change_percent,
            market_status,
            updated_at
        )
        VALUES
        (
            ?,
            ?,
            ?,
            ?,
            ?,
            NOW()
        )
    ");


    if ($stmt === false) {

        throw new Exception(
            "Gagal menyiapkan query market_updates: "
            . $conn->error
        );

    }


    /*
    |--------------------------------------------------------------------------
    | 13. BIND PARAMETER
    |--------------------------------------------------------------------------
    |
    | s = string
    | d = decimal / double
    |
    | symbol          -> s
    | price           -> d
    | change_value    -> d
    | change_percent  -> d
    | market_status   -> s
    |
    */

    $stmt->bind_param(
        "sddds",
        $symbol,
        $price,
        $changeValue,
        $percentChange,
        $marketStatus
    );


    if (!$stmt->execute()) {

        throw new Exception(
            "Gagal menyimpan market update: "
            . $stmt->error
        );

    }


    $stmt->close();


    /*
    |--------------------------------------------------------------------------
    | 14. SIMPAN KE MARKET HISTORY
    |--------------------------------------------------------------------------
    |
    | Sesuai struktur:
    |
    | id
    | symbol
    | price
    | change_percent
    | market_status
    | recorded_at
    |
    */

    $stmtHistory = $conn->prepare("
        INSERT INTO market_history
        (
            symbol,
            price,
            change_percent,
            market_status,
            recorded_at
        )
        VALUES
        (
            ?,
            ?,
            ?,
            ?,
            NOW()
        )
    ");


    if ($stmtHistory === false) {

        throw new Exception(
            "Gagal menyiapkan query market_history: "
            . $conn->error
        );

    }


    /*
    |--------------------------------------------------------------------------
    | 15. BIND HISTORY
    |--------------------------------------------------------------------------
    |
    | symbol          -> s
    | price           -> d
    | change_percent  -> d
    | market_status   -> s
    |
    */

    $stmtHistory->bind_param(
        "sdds",
        $symbol,
        $price,
        $percentChange,
        $marketStatus
    );


    if (!$stmtHistory->execute()) {

        throw new Exception(
            "Gagal menyimpan market history: "
            . $stmtHistory->error
        );

    }


    $stmtHistory->close();


    /*
    |--------------------------------------------------------------------------
    | 16. COMMIT
    |--------------------------------------------------------------------------
    */

    $conn->commit();


} catch (Exception $e) {

    /*
    |--------------------------------------------------------------------------
    | 17. ROLLBACK JIKA ERROR
    |--------------------------------------------------------------------------
    */

    $conn->rollback();

    die(
        "<h2>Gagal memperbarui market</h2>"
        . "<p>"
        . htmlspecialchars($e->getMessage())
        . "</p>"
    );

}


/*
|--------------------------------------------------------------------------
| 18. FORMAT DATA UNTUK TAMPILAN
|--------------------------------------------------------------------------
*/

$priceFormatted = number_format(
    $price,
    2,
    '.',
    ','
);


$changeFormatted =
    ($changeValue >= 0 ? "+" : "")
    . number_format(
        $changeValue,
        2,
        '.',
        ','
    );


$percentFormatted =
    ($percentChange >= 0 ? "+" : "")
    . number_format(
        $percentChange,
        2,
        '.',
        ','
    );


$updateTime = date(
    "d M Y, H:i:s"
);


/*
|--------------------------------------------------------------------------
| 19. WARNA STATUS
|--------------------------------------------------------------------------
*/

if ($marketStatus === "Bullish") {

    $statusClass = "bullish";

} elseif ($marketStatus === "Bearish") {

    $statusClass = "bearish";

} else {

    $statusClass = "neutral";

}


/*
|--------------------------------------------------------------------------
| 20. SUCCESS PAGE
|--------------------------------------------------------------------------
*/

?>

<!DOCTYPE html>

<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>AURUM — Market Updated</title>


    <style>

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }


        body {

            min-height: 100vh;

            display: flex;

            justify-content: center;

            align-items: center;

            padding: 30px;

            font-family:
                Arial,
                Helvetica,
                sans-serif;

            background:
                radial-gradient(
                    circle at top right,
                    rgba(212, 175, 55, 0.10),
                    transparent 35%
                ),
                #08090C;

            color: #ffffff;

        }


        .card {

            width: 100%;

            max-width: 620px;

            background: #15171C;

            border: 1px solid #292C33;

            border-radius: 24px;

            padding: 40px;

            box-shadow:
                0 25px 80px
                rgba(0, 0, 0, 0.45);

        }


        .logo {

            display: flex;

            align-items: center;

            gap: 12px;

            margin-bottom: 30px;

        }


        .logo-icon {

            width: 44px;

            height: 44px;

            display: flex;

            align-items: center;

            justify-content: center;

            border-radius: 12px;

            background: #D4AF37;

            color: #08090C;

            font-size: 22px;

            font-weight: bold;

        }


        .logo-text {

            font-size: 18px;

            font-weight: bold;

            letter-spacing: 2px;

        }


        .logo-subtitle {

            margin-top: 4px;

            font-size: 9px;

            letter-spacing: 3px;

            color: #777C87;

        }


        .success {

            display: inline-flex;

            align-items: center;

            gap: 8px;

            padding: 8px 14px;

            border-radius: 999px;

            background: rgba(52, 211, 153, 0.10);

            color: #34D399;

            font-size: 13px;

            font-weight: 600;

            margin-bottom: 20px;

        }


        h1 {

            font-size: 32px;

            margin-bottom: 10px;

        }


        .description {

            color: #8F96A3;

            line-height: 1.6;

            margin-bottom: 30px;

        }


        .market-box {

            padding: 25px;

            background: #101217;

            border: 1px solid #292C33;

            border-radius: 18px;

        }


        .symbol {

            color: #8F96A3;

            font-size: 14px;

            margin-bottom: 8px;

        }


        .symbol strong {

            color: #ffffff;

        }


        .price {

            font-size: 48px;

            font-weight: 700;

            margin: 10px 0;

            letter-spacing: -1px;

        }


        .changes {

            display: flex;

            gap: 20px;

            margin-top: 10px;

            color: #8F96A3;

            font-size: 14px;

        }


        .change-value {

            color: #ffffff;

        }


        .status {

            display: inline-block;

            margin-top: 22px;

            padding: 8px 14px;

            border-radius: 999px;

            font-size: 13px;

            font-weight: 600;

        }


        .bullish {

            color: #34D399;

            background: rgba(
                52,
                211,
                153,
                0.10
            );

        }


        .bearish {

            color: #F87171;

            background: rgba(
                248,
                113,
                113,
                0.10
            );

        }


        .neutral {

            color: #FBBF24;

            background: rgba(
                251,
                191,
                36,
                0.10
            );

        }


        .info {

            margin-top: 25px;

            padding-top: 20px;

            border-top: 1px solid #292C33;

            color: #777C87;

            font-size: 13px;

            line-height: 1.8;

        }


        .info strong {

            color: #B8BDC7;

        }


        .back {

            display: inline-block;

            margin-top: 25px;

            padding: 12px 20px;

            border-radius: 10px;

            background: #D4AF37;

            color: #08090C;

            text-decoration: none;

            font-weight: 700;

        }


        .back:hover {

            opacity: 0.9;

        }

    </style>

</head>


<body>


<div class="card">


    <div class="logo">

        <div class="logo-icon">
            A
        </div>

        <div>

            <div class="logo-text">
                AURUM
            </div>

            <div class="logo-subtitle">
                GOLD MARKET EDUCATION
            </div>

        </div>

    </div>


    <div class="success">

        ✓

        Data berhasil diperbarui

    </div>


    <h1>
        Market Update
    </h1>


    <p class="description">

        Data market emas berhasil diambil
        dari Twelve Data dan disimpan
        ke database AURUM.

    </p>


    <div class="market-box">


        <div class="symbol">

            Gold / US Dollar

            <strong>
                <?= htmlspecialchars($symbol) ?>
            </strong>

        </div>


        <div class="price">

            <?= $priceFormatted ?>

        </div>


        <div class="changes">

            <div>

                Change:

                <span class="change-value">

                    <?= $changeFormatted ?>

                </span>

            </div>


            <div>

                Percentage:

                <span class="change-value">

                    <?= $percentFormatted ?>%

                </span>

            </div>

        </div>


        <div class="status <?= $statusClass ?>">

            <?= htmlspecialchars($marketStatus) ?>

        </div>


    </div>


    <div class="info">

        <div>

            <strong>Symbol:</strong>

            <?= htmlspecialchars($symbol) ?>

        </div>


        <div>

            <strong>Source:</strong>

            Twelve Data

        </div>


        <div>

            <strong>Updated:</strong>

            <?= $updateTime ?>

        </div>


        <div>

            <strong>Database:</strong>

            market_updates + market_history

        </div>

    </div>


    <a
        href="../"
        class="back"
    >

        ← Kembali ke AURUM

    </a>


</div>


</body>

</html>