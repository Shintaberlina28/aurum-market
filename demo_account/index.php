
<?php
session_start();

require_once "../config/database.php";

// Alamat tujuan setelah data berhasil disimpan
$redirectUrl = "https://cdd.vifx.co.id/r-907-778899/e6ee";

// Lokasi gambar
$logoImage = "../assets/images/victory-logo.jpeg";
$bannerImage = "../assets/images/platform-banner.png";

// Pesan dan nilai form
$message = "";
$messageType = "";

$fullName = "";
$email = "";
$phone = "";

// Membuat token keamanan form
if (empty($_SESSION["csrf_token"])) {
    $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
}

// Memproses form ketika tombol LOGIN ditekan
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $fullName = trim($_POST["full_name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $phone = trim($_POST["phone"] ?? "");
    $consent = isset($_POST["consent"]);
    $csrfToken = $_POST["csrf_token"] ?? "";

    // Memeriksa token keamanan
    if (
        empty($_SESSION["csrf_token"]) ||
        !hash_equals($_SESSION["csrf_token"], $csrfToken)
    ) {
        $message = "Permintaan tidak valid. Silakan muat ulang halaman.";
        $messageType = "error";

    } elseif ($fullName === "" || $email === "" || $phone === "") {
        $message = "Semua kolom wajib diisi.";
        $messageType = "error";

    } elseif (strlen($fullName) > 100) {
        $message = "Nama maksimal 100 karakter.";
        $messageType = "error";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Format email tidak valid.";
        $messageType = "error";

    } elseif (strlen($email) > 190) {
        $message = "Email terlalu panjang.";
        $messageType = "error";

    } elseif (
        !preg_match('/^\+?[0-9\s().-]{8,20}$/', $phone) ||
        strlen(preg_replace('/\D/', '', $phone)) < 8
    ) {
        $message = "Nomor handphone tidak valid.";
        $messageType = "error";

    } elseif (!$consent) {
        $message = "Silakan centang persetujuan penyimpanan data.";
        $messageType = "error";

    } else {

        // Menyimpan data pengunjung ke database
       
            $stmt = $conn->prepare(
                "INSERT INTO demo_leads
                (nama, email, no_handphone)
                VALUES (?, ?, ?)"
            );


        if ($stmt) {

            $stmt->bind_param(
                "sss",
                $fullName,
                $email,
                $phone
            );

            if ($stmt->execute()) {

                $stmt->close();

                // Mengarahkan pengunjung ke halaman VIFX
                header("Location: " . $redirectUrl, true, 303);
                exit;

            } else {

                $message = "Data belum berhasil disimpan. Silakan coba kembali.";
                $messageType = "error";
                $stmt->close();
            }

        } else {

            $message = "Terjadi kesalahan pada sistem. Silakan coba kembali.";
            $messageType = "error";
        }
    }
}

// Fungsi untuk mengamankan tampilan teks
function e($value) {
    return htmlspecialchars($value, ENT_QUOTES, "UTF-8");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Platform - Victory International Futures - VIFX</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #080808;
            color: #ffffff;
            min-height: 100vh;
        }

        /* NAVBAR */
        .navbar {
            width: 100%;
            min-height: 85px;
            background: #080808;
            border-bottom: 1px solid #242424;
            display: flex;
            align-items: center;
            position: relative;
            z-index: 10;
        }

        .nav-container {
            width: 90%;
            max-width: 1400px;
            margin: auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 30px;
        }

        .logo img {
            width: 145px;
            max-height: 65px;
            object-fit: contain;
        }

        .nav-menu {
            display: flex;
            align-items: center;
            gap: 30px;
            list-style: none;
        }

        .nav-menu a {
            color: #ffffff;
            text-decoration: none;
            font-size: 14px;
            transition: 0.3s;
        }

        .nav-menu a:hover,
        .nav-menu a.active {
            color: #d5ad55;
        }

        /* MAIN CONTENT */
        .main-container {
            min-height: calc(100vh - 85px);
            display: grid;
            grid-template-columns: 43% 57%;
        }

        /* FORM SECTION */
        .register-section {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 55px 45px;
            background: #0b0b0b;
        }

        .register-content {
            width: 100%;
            max-width: 440px;
        }

        .register-label {
            color: #d5ad55;
            font-size: 13px;
            font-weight: bold;
            letter-spacing: 3px;
            margin-bottom: 15px;
        }

        .register-title {
            font-size: 42px;
            font-weight: 700;
            letter-spacing: 2px;
            margin-bottom: 12px;
        }

        .login-text {
            color: #b7b7b7;
            font-size: 14px;
            line-height: 1.7;
            margin-bottom: 35px;
        }

        .form-group {
            margin-bottom: 22px;
        }

        .form-group label {
            display: block;
            color: #eeeeee;
            font-size: 13px;
            margin-bottom: 10px;
        }

        .form-group input {
            width: 100%;
            height: 52px;
            background: #151515;
            border: 1px solid #393939;
            border-radius: 5px;
            padding: 0 16px;
            color: #ffffff;
            font-size: 14px;
            outline: none;
            transition: 0.3s;
        }

        .form-group input:focus {
            border-color: #d5ad55;
        }

        .form-group input::placeholder {
            color: #777777;
        }

        /* PERSETUJUAN DATA */
        .consent-box {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin: 25px 0;
            color: #bcbcbc;
            font-size: 12px;
            line-height: 1.7;
        }

        .consent-box input {
            margin-top: 3px;
            accent-color: #d5ad55;
            width: 16px;
            height: 16px;
            flex-shrink: 0;
            cursor: pointer;
        }

        .consent-box label {
            cursor: pointer;
        }

        /* TOMBOL LOGIN */
        .register-button {
            width: 100%;
            height: 54px;
            border: none;
            border-radius: 5px;
            background: #d5ad55;
            color: #080808;
            font-size: 14px;
            font-weight: bold;
            letter-spacing: 2px;
            cursor: pointer;
            transition: 0.3s;
        }

        .register-button:hover {
            background: #f0c96f;
            transform: translateY(-2px);
        }

        /* PESAN */
        .message {
            padding: 13px 15px;
            margin-bottom: 22px;
            border-radius: 5px;
            font-size: 13px;
            line-height: 1.5;
        }

        .message.error {
            background: rgba(220, 53, 69, 0.12);
            border: 1px solid #dc3545;
            color: #ff8b95;
        }

        /* BANNER */
        .banner-section {
            min-height: 650px;
            position: relative;
            overflow: hidden;
            background: #171717;
        }

        .banner-section img {
            width: 100%;
            height: 100%;
            min-height: 650px;
            object-fit: cover;
            position: absolute;
            inset: 0;
        }

        /* RESPONSIVE */
        @media (max-width: 900px) {
            .main-container {
                grid-template-columns: 1fr;
            }

            .register-section {
                padding: 60px 25px;
            }

            .banner-section {
                min-height: 350px;
            }

            .banner-section img {
                min-height: 350px;
            }
        }

        @media (max-width: 600px) {
            .navbar {
                padding: 15px 0;
            }

            .nav-container {
                flex-direction: column;
                gap: 15px;
            }

            .nav-menu {
                flex-wrap: wrap;
                justify-content: center;
                gap: 15px;
            }

            .nav-menu a {
                font-size: 12px;
            }

            .register-title {
                font-size: 34px;
            }

            .register-section {
                padding: 45px 22px;
            }
        }
    </style>
</head>

<body>

    <!-- NAVBAR -->
    <nav class="navbar">
        <div class="nav-container">

            <a href="../index.php" class="logo">
                <img src="<?php echo e($logoImage); ?>"
                     alt="Victory International Futures">
            </a>

            <ul class="nav-menu">
                <li><a href="../index.php">Beranda</a></li>
                <li><a href="../market/">Daily Market</a></li>
                <li><a href="../news/">Berita</a></li>
                <li><a href="./" class="active">Platform</a></li>
                <li><a href="../videos/">Video</a></li>
                <li><a href="../ebooks/">E-Book</a></li>
                <li><a href="../learning/">Pembelajaran</a></li>
            </ul>

        </div>
    </nav>

    <!-- MAIN -->
    <main class="main-container">

        <!-- FORM LOGIN -->
        <section class="register-section">
            <div class="register-content">

                <p class="register-label">VICTORY MARKET</p>

                <h1 class="register-title">LOGIN</h1>

                <p class="login-text">
                    Silakan lengkapi data diri Anda sebelum melanjutkan
                    ke platform Victory International Futures.
                </p>

                <!-- PESAN ERROR -->
                <?php if ($message !== ""): ?>
                    <div class="message <?php echo e($messageType); ?>">
                        <?php echo e($message); ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="" autocomplete="on">

                    <input
                        type="hidden"
                        name="csrf_token"
                        value="<?php echo e($_SESSION["csrf_token"]); ?>"
                    >

                    <!-- NAMA -->
                    <div class="form-group">
                        <label for="full_name">Nama Lengkap</label>

                        <input
                            type="text"
                            id="full_name"
                            name="full_name"
                            placeholder="Masukkan nama lengkap"
                            maxlength="100"
                            value="<?php echo e($fullName); ?>"
                            autocomplete="name"
                            required
                        >
                    </div>

                    <!-- EMAIL -->
                    <div class="form-group">
                        <label for="email">Email</label>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            placeholder="Masukkan alamat email"
                            maxlength="190"
                            value="<?php echo e($email); ?>"
                            autocomplete="email"
                            required
                        >
                    </div>

                    <!-- NOMOR HANDPHONE -->
                    <div class="form-group">
                        <label for="phone">Nomor Handphone</label>

                        <input
                            type="tel"
                            id="phone"
                            name="phone"
                            placeholder="Contoh: 081234567890"
                            maxlength="20"
                            value="<?php echo e($phone); ?>"
                            autocomplete="tel"
                            required
                        >
                    </div>

                    <!-- PERSETUJUAN -->
                    <div class="consent-box">
                        <input
                            type="checkbox"
                            id="consent"
                            name="consent"
                            value="1"
                            required
                        >

                        <label for="consent">
                            Saya menyetujui pengumpulan dan penyimpanan
                            data nama, email, dan nomor handphone yang
                            saya berikan untuk keperluan tindak lanjut
                            informasi layanan Victory International Futures.
                        </label>
                    </div>

                    <!-- TOMBOL -->
                    <button type="submit" class="register-button">
                        LOGIN
                    </button>

                </form>

            </div>
        </section>

        <!-- BANNER -->
        <section class="banner-section">
            <img
                src="<?php echo e($bannerImage); ?>"
                alt="Victory International Futures Platform"
            >
        </section>

    </main>

</body>
</html>
