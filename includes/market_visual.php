<?php

/*
|--------------------------------------------------------------------------
| AURUM - MARKET VISUAL ENGINE
|--------------------------------------------------------------------------
|
| File ini bertugas membuat visual market berdasarkan:
|
| - visual_type
| - asset_symbol
| - chart_timeframe
| - gold_effect
| - title
|
| Untuk tahap awal, chart menggunakan data simulasi/deterministik.
| Pada tahap berikutnya akan kita hubungkan ke market data API.
|
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| ESCAPE HTML
|--------------------------------------------------------------------------
*/

if (!function_exists('aurumVisualEscape')) {

    function aurumVisualEscape($value)
    {
        return htmlspecialchars(
            (string) ($value ?? ''),
            ENT_QUOTES,
            'UTF-8'
        );
    }
}


/*
|--------------------------------------------------------------------------
| NORMALIZE ASSET
|--------------------------------------------------------------------------
|
| Mengubah beberapa kemungkinan penulisan simbol
| menjadi simbol standar AURUM.
|
*/

if (!function_exists('aurumNormalizeAsset')) {

    function aurumNormalizeAsset($symbol)
    {
        $symbol = strtoupper(trim((string) $symbol));

        $map = [

            'GOLD'      => 'XAUUSD',
            'XAU'       => 'XAUUSD',
            'XAU/USD'   => 'XAUUSD',

            'OIL'       => 'WTI',
            'WTIUSD'    => 'WTI',
            'USOIL'     => 'WTI',

            'DOLLAR'    => 'DXY',
            'USD'       => 'DXY',

            'BITCOIN'   => 'BTCUSD',
            'BTC'       => 'BTCUSD',
            'BTC/USD'   => 'BTCUSD',

            'SILVER'    => 'XAGUSD',
            'XAG'       => 'XAGUSD',
            'XAG/USD'   => 'XAGUSD',
        ];

        return $map[$symbol] ?? ($symbol ?: 'XAUUSD');
    }
}


/*
|--------------------------------------------------------------------------
| ASSET INFORMATION
|--------------------------------------------------------------------------
|
| Informasi dasar untuk masing-masing aset.
|
*/

if (!function_exists('aurumGetAssetInfo')) {

    function aurumGetAssetInfo($symbol)
    {
        $symbol = aurumNormalizeAsset($symbol);

        $assets = [

            'XAUUSD' => [
                'name'  => 'GOLD / XAUUSD',
                'unit'  => 'USD / troy oz',
                'base'  => 4636.14,
            ],

            'WTI' => [
                'name'  => 'WTI CRUDE OIL',
                'unit'  => 'USD / barrel',
                'base'  => 82.60,
            ],

            'DXY' => [
                'name'  => 'US DOLLAR INDEX',
                'unit'  => 'INDEX',
                'base'  => 99.14,
            ],

            'BTCUSD' => [
                'name'  => 'BITCOIN / BTCUSD',
                'unit'  => 'USD',
                'base'  => 79752.40,
            ],

            'XAGUSD' => [
                'name'  => 'SILVER / XAGUSD',
                'unit'  => 'USD / troy oz',
                'base'  => 68.78,
            ],
        ];

        if (isset($assets[$symbol])) {
            return $assets[$symbol];
        }

        return [
            'name' => $symbol,
            'unit' => 'MARKET',
            'base' => 100,
        ];
    }
}


/*
|--------------------------------------------------------------------------
| DETEKSI VISUAL BERDASARKAN BERITA
|--------------------------------------------------------------------------
|
| Jika database sudah memiliki visual_type dan asset_symbol,
| kita gunakan data tersebut.
|
| Jika kosong, sistem mencoba mendeteksi dari judul,
| kategori, summary, dan content.
|
*/

if (!function_exists('aurumDetectNewsVisual')) {

    function aurumDetectNewsVisual(array $news)
    {
        $visualType = strtolower(
            trim($news['visual_type'] ?? '')
        );

        $assetSymbol = strtoupper(
            trim($news['asset_symbol'] ?? '')
        );

        /*
        |--------------------------------------------------------------------------
        | Kalau database sudah menentukan visual
        |--------------------------------------------------------------------------
        */

        if ($visualType !== '') {

            if ($assetSymbol === '') {

                $assetSymbol =
                    aurumDetectAssetFromText($news);

            }

            return [
                'type'  => $visualType,
                'asset' => aurumNormalizeAsset($assetSymbol),
            ];
        }


        /*
        |--------------------------------------------------------------------------
        | Kalau database belum menentukan visual
        |--------------------------------------------------------------------------
        */

        $assetSymbol =
            aurumDetectAssetFromText($news);

        $text = strtolower(
            implode(
                ' ',
                [
                    $news['title'] ?? '',
                    $news['category'] ?? '',
                    $news['summary'] ?? '',
                    $news['content'] ?? '',
                ]
            )
        );


        /*
        |--------------------------------------------------------------------------
        | Technical analysis
        |--------------------------------------------------------------------------
        */

        if (
            strpos($text, 'analisis teknikal') !== false ||
            strpos($text, 'technical analysis') !== false ||
            strpos($text, 'support') !== false ||
            strpos($text, 'resistance') !== false ||
            strpos($text, 'stop loss') !== false
        ) {

            return [
                'type'  => 'candlestick',
                'asset' => $assetSymbol,
            ];
        }


        /*
        |--------------------------------------------------------------------------
        | Market-related assets
        |--------------------------------------------------------------------------
        */

        if ($assetSymbol !== '') {

            return [
                'type'  => 'candlestick',
                'asset' => $assetSymbol,
            ];
        }


        /*
        |--------------------------------------------------------------------------
        | Default
        |--------------------------------------------------------------------------
        */

        return [
            'type'  => 'editorial',
            'asset' => '',
        ];
    }
}


/*
|--------------------------------------------------------------------------
| DETEKSI ASSET DARI TEKS BERITA
|--------------------------------------------------------------------------
*/

if (!function_exists('aurumDetectAssetFromText')) {

    function aurumDetectAssetFromText(array $news)
    {
        $text = strtolower(
            implode(
                ' ',
                [
                    $news['title'] ?? '',
                    $news['category'] ?? '',
                    $news['summary'] ?? '',
                    $news['content'] ?? '',
                ]
            )
        );


        /*
        |--------------------------------------------------------------------------
        | GOLD
        |--------------------------------------------------------------------------
        */

        if (
            strpos($text, 'emas') !== false ||
            strpos($text, 'gold') !== false ||
            strpos($text, 'xau') !== false
        ) {

            return 'XAUUSD';
        }


        /*
        |--------------------------------------------------------------------------
        | OIL
        |--------------------------------------------------------------------------
        */

        if (
            strpos($text, 'minyak') !== false ||
            strpos($text, 'oil') !== false ||
            strpos($text, 'wti') !== false ||
            strpos($text, 'brent') !== false
        ) {

            return 'WTI';
        }


        /*
        |--------------------------------------------------------------------------
        | SILVER
        |--------------------------------------------------------------------------
        */

        if (
            strpos($text, 'perak') !== false ||
            strpos($text, 'silver') !== false
        ) {

            return 'XAGUSD';
        }


        /*
        |--------------------------------------------------------------------------
        | BITCOIN
        |--------------------------------------------------------------------------
        */

        if (
            strpos($text, 'bitcoin') !== false ||
            strpos($text, 'btc') !== false ||
            strpos($text, 'kripto') !== false ||
            strpos($text, 'crypto') !== false
        ) {

            return 'BTCUSD';
        }


        /*
        |--------------------------------------------------------------------------
        | DOLLAR
        |--------------------------------------------------------------------------
        */

        if (
            strpos($text, 'dolar as') !== false ||
            strpos($text, 'dollar') !== false ||
            strpos($text, 'dxy') !== false
        ) {

            return 'DXY';
        }


        return '';
    }
}


/*
|--------------------------------------------------------------------------
| SEED DETERMINISTIK
|--------------------------------------------------------------------------
|
| Supaya setiap berita menghasilkan chart yang berbeda,
| tetapi chart tidak berubah-ubah setiap kali halaman direfresh.
|
*/

if (!function_exists('aurumVisualSeed')) {

    function aurumVisualSeed(array $news)
    {
        $string =
            ($news['id'] ?? '') . '|' .
            ($news['title'] ?? '') . '|' .
            ($news['asset_symbol'] ?? '');

        return abs(
            crc32($string)
        );
    }
}


/*
|--------------------------------------------------------------------------
| GENERATE CANDLE DATA SIMULASI
|--------------------------------------------------------------------------
*/

if (!function_exists('aurumGenerateCandles')) {

    function aurumGenerateCandles(
        array $news,
        $count = 34
    ) {

        $symbol =
            aurumNormalizeAsset(
                $news['asset_symbol'] ?? 'XAUUSD'
            );

        $asset =
            aurumGetAssetInfo($symbol);

        $base =
            (float) $asset['base'];

        /*
        |--------------------------------------------------------------------------
        | Seed berdasarkan berita
        |--------------------------------------------------------------------------
        */

        $seed =
            aurumVisualSeed($news);

        /*
        |--------------------------------------------------------------------------
        | Random generator lokal
        |--------------------------------------------------------------------------
        */

        $random = function () use (&$seed) {

            $seed =
                (1664525 * $seed + 1013904223)
                % 4294967296;

            return $seed / 4294967296;
        };


        /*
        |--------------------------------------------------------------------------
        | Volatilitas berbeda untuk setiap aset
        |--------------------------------------------------------------------------
        */

        $volatility = [

            'XAUUSD' => 0.0045,
            'WTI'    => 0.0080,
            'DXY'    => 0.0020,
            'BTCUSD' => 0.0150,
            'XAGUSD' => 0.0090,

        ];

        $vol =
            $volatility[$symbol]
            ?? 0.006;


        /*
        |--------------------------------------------------------------------------
        | Trend berdasarkan gold_effect
        |--------------------------------------------------------------------------
        */

        $effect =
            strtolower(
                trim(
                    $news['gold_effect'] ?? ''
                )
            );

        if ($effect === 'bullish') {

            $trend = 0.0015;

        } elseif ($effect === 'bearish') {

            $trend = -0.0015;

        } else {

            $trend = 0;
        }


        /*
        |--------------------------------------------------------------------------
        | Starting price
        |--------------------------------------------------------------------------
        */

        $price =
            $base *
            (
                0.985 +
                ($random() * 0.03)
            );


        $candles = [];


        /*
        |--------------------------------------------------------------------------
        | Generate OHLC
        |--------------------------------------------------------------------------
        */

        for ($i = 0; $i < $count; $i++) {

            $open =
                $price;


            $movement =
                (
                    ($random() - 0.5)
                    * 2
                    * $vol
                )
                + $trend;


            $close =
                $open *
                (1 + $movement);


            $range =
                $open *
                (
                    0.0015 +
                    ($random() * $vol * 0.8)
                );


            $high =
                max($open, $close)
                + ($range * $random());


            $low =
                min($open, $close)
                - ($range * $random());


            $candles[] = [

                'open'  => $open,
                'high'  => $high,
                'low'   => $low,
                'close' => $close,

            ];


            $price =
                $close;
        }


        return $candles;
    }
}


/*
|--------------------------------------------------------------------------
| FORMAT PRICE
|--------------------------------------------------------------------------
*/

if (!function_exists('aurumFormatPrice')) {

    function aurumFormatPrice($price, $symbol)
    {
        $symbol =
            aurumNormalizeAsset($symbol);


        if ($symbol === 'DXY') {

            return number_format(
                $price,
                2,
                '.',
                ','
            );
        }


        if ($symbol === 'WTI') {

            return number_format(
                $price,
                2,
                '.',
                ','
            );
        }


        if ($symbol === 'XAGUSD') {

            return number_format(
                $price,
                2,
                '.',
                ','
            );
        }


        if ($symbol === 'BTCUSD') {

            return number_format(
                $price,
                0,
                '.',
                ','
            );
        }


        return number_format(
            $price,
            2,
            '.',
            ','
        );
    }
}


/*
|--------------------------------------------------------------------------
| RENDER CANDLESTICK SVG
|--------------------------------------------------------------------------
*/

if (!function_exists('aurumRenderCandlestick')) {

    function aurumRenderCandlestick(
        array $news,
        $width = 900,
        $height = 420
    ) {

        $symbol =
            aurumNormalizeAsset(
                $news['asset_symbol'] ?? 'XAUUSD'
            );

        $asset =
            aurumGetAssetInfo($symbol);

        $candles =
            aurumGenerateCandles(
                $news,
                34
            );


        /*
        |--------------------------------------------------------------------------
        | Chart dimensions
        |--------------------------------------------------------------------------
        */

        $paddingLeft   = 55;
        $paddingRight  = 25;
        $paddingTop    = 55;
        $paddingBottom = 45;


        $chartWidth =
            $width -
            $paddingLeft -
            $paddingRight;


        $chartHeight =
            $height -
            $paddingTop -
            $paddingBottom;


        /*
        |--------------------------------------------------------------------------
        | Find min/max
        |--------------------------------------------------------------------------
        */

        $min =
            min(
                array_column(
                    $candles,
                    'low'
                )
            );


        $max =
            max(
                array_column(
                    $candles,
                    'high'
                )
            );


        $range =
            $max - $min;


        if ($range <= 0) {

            $range = 1;
        }


        /*
        |--------------------------------------------------------------------------
        | SVG helper
        |--------------------------------------------------------------------------
        */

        $y = function ($price) use (
            $min,
            $range,
            $chartHeight,
            $paddingTop
        ) {

            return
                $paddingTop
                +
                (
                    ($maxSafe = $range)
                    ? (
                        (
                            max(
                                0,
                                min(
                                    1,
                                    (
                                        ($GLOBALS['__aurum_max_price'] ?? 0)
                                        - $price
                                    )
                                    /
                                    $maxSafe
                                )
                            )
                        )
                        * $chartHeight
                    )
                    : 0
                );
        };


        /*
        |--------------------------------------------------------------------------
        | Karena closure di atas perlu max,
        | kita gunakan closure yang lebih sederhana.
        |--------------------------------------------------------------------------
        */

        $maxPrice = $max;

        $y = function ($price) use (
            $min,
            $maxPrice,
            $chartHeight,
            $paddingTop
        ) {

            return
                $paddingTop
                +
                (
                    (
                        $maxPrice - $price
                    )
                    /
                    max(
                        0.0000001,
                        $maxPrice - $min
                    )
                )
                *
                $chartHeight;
        };


        /*
        |--------------------------------------------------------------------------
        | Background
        |--------------------------------------------------------------------------
        */

        $svg = '';

        $svg .=
            '<svg '
            . 'xmlns="http://www.w3.org/2000/svg" '
            . 'viewBox="0 0 '
            . $width
            . ' '
            . $height
            . '" '
            . 'width="100%" '
            . 'height="100%" '
            . 'preserveAspectRatio="none">'
            ;


        /*
        |--------------------------------------------------------------------------
        | Chart background
        |--------------------------------------------------------------------------
        */

        $svg .=
            '<rect '
            . 'width="100%" '
            . 'height="100%" '
            . 'rx="18" '
            . 'fill="#101217" '
            . 'stroke="#292c33" '
            . 'stroke-width="1"/>'
            ;


        /*
        |--------------------------------------------------------------------------
        | Header
        |--------------------------------------------------------------------------
        */

        $svg .=
            '<text '
            . 'x="25" '
            . 'y="29" '
            . 'fill="#ffffff" '
            . 'font-family="Arial, Helvetica, sans-serif" '
            . 'font-size="15" '
            . 'font-weight="700">'
            . aurumVisualEscape(
                $asset['name']
            )
            . '</text>'
            ;


        $svg .=
            '<text '
            . 'x="25" '
            . 'y="47" '
            . 'fill="#7f899b" '
            . 'font-family="Arial, Helvetica, sans-serif" '
            . 'font-size="10">'
            . aurumVisualEscape(
                strtoupper(
                    $news['chart_timeframe']
                    ?? '1H'
                )
            )
            . ' • MARKET VISUAL PREVIEW'
            . '</text>'
            ;


        /*
        |--------------------------------------------------------------------------
        | Grid
        |--------------------------------------------------------------------------
        */

        $gridCount = 5;

        for ($i = 0; $i <= $gridCount; $i++) {

            $gridY =
                $paddingTop
                +
                (
                    $chartHeight
                    *
                    ($i / $gridCount)
                );


            $svg .=
                '<line '
                . 'x1="'
                . $paddingLeft
                . '" '
                . 'y1="'
                . round($gridY, 2)
                . '" '
                . 'x2="'
                . ($width - $paddingRight)
                . '" '
                . 'y2="'
                . round($gridY, 2)
                . '" '
                . 'stroke="#252a32" '
                . 'stroke-width="1"/>'
                ;


            $priceAtGrid =
                $max -
                (
                    (
                        $max - $min
                    )
                    *
                    ($i / $gridCount)
                );


            $svg .=
                '<text '
                . 'x="'
                . ($width - $paddingRight - 2)
                . '" '
                . 'y="'
                . ($gridY - 4)
                . '" '
                . 'text-anchor="end" '
                . 'fill="#697386" '
                . 'font-family="Arial, Helvetica, sans-serif" '
                . 'font-size="9">'
                . aurumVisualEscape(
                    aurumFormatPrice(
                        $priceAtGrid,
                        $symbol
                    )
                )
                . '</text>'
                ;
        }


        /*
        |--------------------------------------------------------------------------
        | Candle width
        |--------------------------------------------------------------------------
        */

        $count =
            count($candles);

        $slotWidth =
            $chartWidth / $count;

        $bodyWidth =
            max(
                4,
                min(
                    11,
                    $slotWidth * 0.55
                )
            );


        /*
        |--------------------------------------------------------------------------
        | Candles
        |--------------------------------------------------------------------------
        */

        foreach (
            $candles
            as $index => $candle
        ) {

            $centerX =
                $paddingLeft
                +
                (
                    $slotWidth
                    *
                    $index
                )
                +
                (
                    $slotWidth / 2
                );


            $openY =
                $y(
                    $candle['open']
                );


            $closeY =
                $y(
                    $candle['close']
                );


            $highY =
                $y(
                    $candle['high']
                );


            $lowY =
                $y(
                    $candle['low']
                );


            $isBullish =
                $candle['close']
                >=
                $candle['open'];


            /*
            |--------------------------------------------------------------------------
            | Wick
            |--------------------------------------------------------------------------
            */

            $svg .=
                '<line '
                . 'x1="'
                . round($centerX, 2)
                . '" '
                . 'y1="'
                . round($highY, 2)
                . '" '
                . 'x2="'
                . round($centerX, 2)
                . '" '
                . 'y2="'
                . round($lowY, 2)
                . '" '
                . 'stroke="'
                . (
                    $isBullish
                    ? '#34d399'
                    : '#f87171'
                )
                . '" '
                . 'stroke-width="1.2"/>'
                ;


            /*
            |--------------------------------------------------------------------------
            | Body
            |--------------------------------------------------------------------------
            */

            $bodyTop =
                min(
                    $openY,
                    $closeY
                );


            $bodyHeight =
                max(
                    2,
                    abs(
                        $closeY -
                        $openY
                    )
                );


            $svg .=
                '<rect '
                . 'x="'
                . round(
                    $centerX -
                    ($bodyWidth / 2),
                    2
                )
                . '" '
                . 'y="'
                . round($bodyTop, 2)
                . '" '
                . 'width="'
                . round(
                    $bodyWidth,
                    2
                )
                . '" '
                . 'height="'
                . round(
                    $bodyHeight,
                    2
                )
                . '" '
                . 'rx="1" '
                . 'fill="'
                . (
                    $isBullish
                    ? '#34d399'
                    : '#f87171'
                )
                . '"/>'
                ;
        }


        /*
        |--------------------------------------------------------------------------
        | Bottom label
        |--------------------------------------------------------------------------
        */

        $svg .=
            '<text '
            . 'x="'
            . $paddingLeft
            . '" '
            . 'y="'
            . ($height - 14)
            . '" '
            . 'fill="#687083" '
            . 'font-family="Arial, Helvetica, sans-serif" '
            . 'font-size="9">'
            . 'AURUM MARKET VISUAL'
            . '</text>'
            ;


        /*
        |--------------------------------------------------------------------------
        | Asset symbol
        |--------------------------------------------------------------------------
        */

        $svg .=
            '<text '
            . 'x="'
            . ($width - $paddingRight)
            . '" '
            . 'y="'
            . ($height - 14)
            . '" '
            . 'text-anchor="end" '
            . 'fill="#d4af37" '
            . 'font-family="Arial, Helvetica, sans-serif" '
            . 'font-size="9" '
            . 'font-weight="700">'
            . aurumVisualEscape(
                $symbol
            )
            . '</text>'
            ;


        /*
        |--------------------------------------------------------------------------
        | Close SVG
        |--------------------------------------------------------------------------
        */

        $svg .=
            '</svg>';


        return $svg;
    }
}


/*
|--------------------------------------------------------------------------
| RENDER GENERIC EDITORIAL VISUAL
|--------------------------------------------------------------------------
|
| Untuk berita yang bukan market chart.
|
*/

if (!function_exists('aurumRenderEditorialVisual')) {

    function aurumRenderEditorialVisual(
        array $news,
        $width = 900,
        $height = 420
    ) {

        $category =
            trim(
                $news['category']
                ?? 'MARKET NEWS'
            );


        $title =
            trim(
                $news['title']
                ?? 'Market News'
            );


        $svg = '';

        $svg .=
            '<svg '
            . 'xmlns="http://www.w3.org/2000/svg" '
            . 'viewBox="0 0 '
            . $width
            . ' '
            . $height
            . '" '
            . 'width="100%" '
            . 'height="100%">'
            ;


        $svg .=
            '<defs>'
            . '<linearGradient '
            . 'id="aurumEditorialGradient" '
            . 'x1="0%" y1="0%" '
            . 'x2="100%" y2="100%">'
            . '<stop offset="0%" stop-color="#17191f"/>'
            . '<stop offset="100%" stop-color="#08090c"/>'
            . '</linearGradient>'
            . '</defs>'
            ;


        $svg .=
            '<rect '
            . 'width="100%" '
            . 'height="100%" '
            . 'rx="18" '
            . 'fill="url(#aurumEditorialGradient)" '
            . 'stroke="#292c33"/>'
            ;


        $svg .=
            '<circle '
            . 'cx="'
            . ($width * 0.72)
            . '" '
            . 'cy="'
            . ($height * 0.42)
            . '" '
            . 'r="120" '
            . 'fill="#d4af37" '
            . 'fill-opacity="0.08"/>'
            ;


        $svg .=
            '<circle '
            . 'cx="'
            . ($width * 0.72)
            . '" '
            . 'cy="'
            . ($height * 0.42)
            . '" '
            . 'r="75" '
            . 'fill="#d4af37" '
            . 'fill-opacity="0.06"/>'
            ;


        $svg .=
            '<text '
            . 'x="35" '
            . 'y="55" '
            . 'fill="#d4af37" '
            . 'font-family="Arial, Helvetica, sans-serif" '
            . 'font-size="12" '
            . 'font-weight="700">'
            . aurumVisualEscape(
                strtoupper($category)
            )
            . '</text>'
            ;


        $svg .=
            '<text '
            . 'x="35" '
            . 'y="100" '
            . 'fill="#ffffff" '
            . 'font-family="Arial, Helvetica, sans-serif" '
            . 'font-size="22" '
            . 'font-weight="700">'
            . aurumVisualEscape(
                mb_substr(
                    $title,
                    0,
                    55
                )
            )
            . '</text>'
            ;


        $svg .=
            '<text '
            . 'x="35" '
            . 'y="'
            . ($height - 30)
            . '" '
            . 'fill="#687083" '
            . 'font-family="Arial, Helvetica, sans-serif" '
            . 'font-size="10">'
            . 'AURUM MARKET NEWS'
            . '</text>'
            ;


        $svg .=
            '</svg>';


        return $svg;
    }
}


/*
|--------------------------------------------------------------------------
| MAIN VISUAL RENDERER
|--------------------------------------------------------------------------
|
| Fungsi utama yang nanti dipanggil oleh index.php
| dan detail.php.
|
*/

if (!function_exists('aurumRenderNewsVisual')) {

    function aurumRenderNewsVisual(
        array $news,
        $width = 900,
        $height = 420
    ) {

        $visual =
            aurumDetectNewsVisual(
                $news
            );


        /*
        |--------------------------------------------------------------------------
        | Candlestick
        |--------------------------------------------------------------------------
        */

        if (
            $visual['type'] === 'candlestick' &&
            $visual['asset'] !== ''
        ) {

            $news['asset_symbol'] =
                $visual['asset'];


            return
                aurumRenderCandlestick(
                    $news,
                    $width,
                    $height
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Editorial
        |--------------------------------------------------------------------------
        */

        return
            aurumRenderEditorialVisual(
                $news,
                $width,
                $height
            );
    }
}