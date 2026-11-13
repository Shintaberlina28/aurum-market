
<?php

/*
|--------------------------------------------------------------------------
| VICTORY MARKET - NEWS CONFIGURATION
|--------------------------------------------------------------------------
*/

return [

    /*
     * Masukkan URL RSS Investing.com yang resmi
     * dan memang diizinkan untuk digunakan.
     *
     * Jangan mengisi URL halaman berita biasa.
     */
    'investing_rss_url' => '',

    /*
     * Maksimal artikel yang diperiksa setiap sinkronisasi.
     */
    'max_articles' => 30,

    /*
     * Timeout koneksi.
     */
    'http_timeout' => 25,

    /*
     * Zona waktu website.
     */
    'timezone' => 'Asia/Jakarta',

    /*
     * Kategori default.
     */
    'default_category' => 'Komoditas',

];
