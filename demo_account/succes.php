<?php

session_start();

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
        Registrasi Berhasil — Victory Market
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

            padding: 20px;

            font-family: Arial, sans-serif;

            background: #05080b;

            color: white;
        }


        .card {

            width: 100%;

            max-width: 520px;

            text-align: center;

            background: #10151a;

            border: 1px solid rgba(255,255,255,0.1);

            border-radius: 20px;

            padding: 50px 35px;
        }


        .icon {

            width: 75px;

            height: 75px;

            margin: auto;

            border-radius: 50%;

            display: flex;

            align-items: center;

            justify-content: center;

            background: rgba(234,179,8,0.15);

            color: #eab308;

            font-size: 38px;
        }


        h1 {

            margin-top: 25px;

            font-size: 30px;
        }


        p {

            color: #9ca3af;

            line-height: 1.7;

            margin-top: 15px;
        }


        .buttons {

            margin-top: 30px;

            display: flex;

            gap: 12px;

            justify-content: center;

            flex-wrap: wrap;
        }


        a {

            text-decoration: none;

            padding: 13px 22px;

            border-radius: 9px;

            font-weight: 600;
        }


        .login {

            background: #eab308;

            color: #000;
        }


        .home {

            border: 1px solid #30353b;

            color: white;
        }

    </style>

</head>


<body>

    <div class="card">

        <div class="icon">
            ✓
        </div>


        <h1>
            Registrasi Berhasil
        </h1>


        <p>

            Akun demo kamu berhasil dibuat.
            Silakan masuk menggunakan email
            dan password yang telah didaftarkan.

        </p>


        <div class="buttons">

            <a
                href="login.php"
                class="login"
            >
                Masuk ke Akun
            </a>


            <a
                href="/aurum/"
                class="home"
            >
                Kembali ke Home
            </a>

        </div>

    </div>

</body>

</html>