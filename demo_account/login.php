<?php

session_start();

require_once "../config/demo_database.php";


/*
|--------------------------------------------------------------------------
| CSRF TOKEN
|--------------------------------------------------------------------------
*/

if (empty($_SESSION['csrf_token'])) {

    $_SESSION['csrf_token'] =
        bin2hex(random_bytes(32));
}


$error = "";


/*
|--------------------------------------------------------------------------
| LOGIN
|--------------------------------------------------------------------------
*/

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $csrfToken =
        $_POST["csrf_token"] ?? "";


    if (
        !hash_equals(
            $_SESSION['csrf_token'],
            $csrfToken
        )
    ) {

        $error =
            "Permintaan tidak valid. Silakan coba lagi.";

    } else {

        $email =
            trim(
                strtolower(
                    $_POST["email"] ?? ""
                )
            );

        $password =
            $_POST["password"] ?? "";


        if ($email === "") {

            $error =
                "Email wajib diisi.";

        } elseif (
            !filter_var(
                $email,
                FILTER_VALIDATE_EMAIL
            )
        ) {

            $error =
                "Format email tidak valid.";

        } elseif ($password === "") {

            $error =
                "Password wajib diisi.";

        } else {

            /*
            |--------------------------------------------------------------------------
            | FIND USER
            |--------------------------------------------------------------------------
            */

            $stmt = $pdo->prepare(
                "
                SELECT
                    id,
                    email,
                    password
                FROM demo_users
                WHERE email = :email
                LIMIT 1
                "
            );

            $stmt->execute([
                ":email" => $email
            ]);

            $user =
                $stmt->fetch();


            /*
            |--------------------------------------------------------------------------
            | VERIFY PASSWORD
            |--------------------------------------------------------------------------
            */

            if (
                $user &&
                password_verify(
                    $password,
                    $user["password"]
                )
            ) {

                /*
                |--------------------------------------------------------------------------
                | PREVENT SESSION FIXATION
                |--------------------------------------------------------------------------
                */

                session_regenerate_id(true);


                $_SESSION["demo_user_id"] =
                    $user["id"];

                $_SESSION["demo_user_email"] =
                    $user["email"];


                /*
                |--------------------------------------------------------------------------
                | REDIRECT
                |--------------------------------------------------------------------------
                */

                header(
                    "Location: dashboard.php"
                );

                exit;

            } else {

                $error =
                    "Email atau password salah.";
            }
        }
    }
}

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
        Masuk — Victory Market
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

            background:
                radial-gradient(
                    circle at 70% 30%,
                    rgba(234,179,8,0.12),
                    transparent 40%
                ),
                #05080b;

            color: white;

            display: flex;

            align-items: center;

            justify-content: center;

            padding: 25px;
        }


        .container {

            width: 100%;

            max-width: 460px;

            background: #10151a;

            border: 1px solid rgba(255,255,255,0.1);

            border-radius: 20px;

            padding: 42px;
        }


        .logo {

            text-align: center;

            color: #eab308;

            font-size: 14px;

            font-weight: 700;

            letter-spacing: 2px;
        }


        h1 {

            text-align: center;

            margin: 15px 0 10px;

            font-size: 36px;
        }


        .subtitle {

            text-align: center;

            color: #9ca3af;

            font-size: 14px;

            line-height: 1.6;

            margin-bottom: 30px;
        }


        .alert {

            background: rgba(220,38,38,0.10);

            border: 1px solid rgba(248,113,113,0.3);

            color: #fca5a5;

            padding: 13px;

            border-radius: 8px;

            font-size: 14px;

            margin-bottom: 20px;
        }


        .group {

            margin-bottom: 18px;
        }


        label {

            display: block;

            margin-bottom: 8px;

            font-size: 14px;

            font-weight: 600;
        }


        input {

            width: 100%;

            height: 54px;

            padding: 0 16px;

            border-radius: 8px;

            border: 1px solid #303943;

            background: #0a0f13;

            color: white;

            outline: none;

            font-size: 15px;
        }


        input:focus {

            border-color: #eab308;

            box-shadow:
                0 0 0 2px rgba(234,179,8,0.1);
        }


        button {

            width: 100%;

            height: 56px;

            border: none;

            border-radius: 8px;

            background: #eab308;

            color: #000;

            font-weight: 700;

            font-size: 16px;

            cursor: pointer;

            margin-top: 8px;
        }


        button:hover {

            filter: brightness(1.08);
        }


        .register {

            text-align: center;

            margin-top: 25px;

            color: #9ca3af;

            font-size: 14px;
        }


        .register a {

            color: #eab308;

            text-decoration: none;

            font-weight: 600;
        }


        .home {

            display: block;

            text-align: center;

            margin-top: 20px;

            color: #6b7280;

            text-decoration: none;

            font-size: 13px;
        }

    </style>

</head>


<body>


<div class="container">


    <div class="logo">
        VICTORY MARKET
    </div>


    <h1>
        MASUK
    </h1>


    <p class="subtitle">

        Masuk ke akun demo kamu
        untuk melanjutkan pembelajaran.

    </p>



    <?php if ($error !== ""): ?>

        <div class="alert">

            <?= htmlspecialchars($error) ?>

        </div>

    <?php endif; ?>



    <form
        method="POST"
        action=""
    >

        <input
            type="hidden"
            name="csrf_token"
            value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>"
        >


        <div class="group">

            <label for="email">
                Email
            </label>

            <input
                type="email"
                id="email"
                name="email"
                placeholder="nama@email.com"
                required
                autocomplete="email"
            >

        </div>



        <div class="group">

            <label for="password">
                Password
            </label>

            <input
                type="password"
                id="password"
                name="password"
                placeholder="Masukkan password"
                required
                autocomplete="current-password"
            >

        </div>



        <button type="submit">
            Masuk ke Demo Account →
        </button>

    </form>



    <div class="register">

        Belum memiliki akun?

        <a href="index.php">
            Daftar sekarang
        </a>

    </div>



    <a
        href="/aurum/"
        class="home"
    >
        ← Kembali ke Home
    </a>


</div>


</body>

</html>