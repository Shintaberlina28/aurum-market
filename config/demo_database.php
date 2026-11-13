<?php

/*
|--------------------------------------------------------------------------
| AURUM DEMO ACCOUNT DATABASE
|--------------------------------------------------------------------------
| Database khusus untuk sistem Demo Account.
*/

$host = "localhost";
$dbname = "aurum_db";
$username = "root";
$password = "";

try {

    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );

} catch (PDOException $e) {

    die(
        "Koneksi database gagal. "
        . "Pastikan Apache dan MySQL XAMPP aktif, "
        . "serta database aurum_db sudah dibuat."
    );
}