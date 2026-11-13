
<?php

/**
 * VICTORY MARKET - INVESTING NEWS SYNC
 *
 * Jalankan:
 * C:\xampp\php\php.exe C:\xampp\htdocs\aurum\news\fetch_news.php
 */

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit("Akses hanya melalui PHP CLI.\n");
}

require_once __DIR__ . '/../config/database.php';

$config = require __DIR__ . '/../config/news_config.php';

date_default_timezone_set(
    $config['timezone'] ?? 'Asia/Jakarta'
);

$logFile = __DIR__ . '/sync_log.txt';


/* =====================================================
   LOG
===================================================== */

function logMessage($message)
{
    global $logFile;

    $line = '[' . date('Y-m-d H:i:s') . '] '
          . $message . PHP_EOL;

    file_put_contents(
        $logFile,
        $line,
        FILE_APPEND | LOCK_EX
    );

    echo $line;
}


/* =====================================================
   HTTP REQUEST
===================================================== */

function httpGet($url, $timeout = 25)
{
    if (!filter_var($url, FILTER_VALIDATE_URL)) {
        throw new RuntimeException('URL feed tidak valid.');
    }

    $scheme = strtolower(
        parse_url($url, PHP_URL_SCHEME) ?? ''
    );

    if ($scheme !== 'https') {
        throw new RuntimeException(
            'Feed harus menggunakan HTTPS.'
        );
    }

    $ch = curl_init($url);

    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_MAXREDIRS => 3,
        CURLOPT_CONNECTTIMEOUT => 10,
        CURLOPT_TIMEOUT => $timeout,
        CURLOPT_USERAGENT => 'VictoryMarketNews/1.0',
        CURLOPT_HTTPHEADER => [
            'Accept: application/rss+xml, application/xml, text/xml'
        ],
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_SSL_VERIFYHOST => 2
    ]);

    $response = curl_exec($ch);

    $error = curl_error($ch);

    $status = curl_getinfo(
        $ch,
        CURLINFO_HTTP_CODE
    );

    curl_close($ch);

    if ($response === false) {
        throw new RuntimeException(