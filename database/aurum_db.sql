-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 13, 2026 at 06:18 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `aurum_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `demo_accounts`
--

CREATE TABLE `demo_accounts` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `demo_accounts`
--

INSERT INTO `demo_accounts` (`id`, `email`, `password`, `created_at`) VALUES
(1, 'shintyabelinauliaaulia@gmail.com', '$2y$10$O8jAQPIdhlVJE6W6hWdovumPzb3/9lROM8wg5vJtHdLNXiaAXgVae', '2026-09-07 04:24:29');

-- --------------------------------------------------------

--
-- Table structure for table `demo_leads`
--

CREATE TABLE `demo_leads` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(190) NOT NULL,
  `no_handphone` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `demo_leads`
--

INSERT INTO `demo_leads` (`id`, `nama`, `email`, `no_handphone`, `created_at`) VALUES
(1, 'Shinta Berlina A.S', 'shintyabelinauliaaulia@gmail.com', '085732434915', '2026-11-13 04:30:12'),
(2, 'Ridho Mizola', 'Ridhomizola@gmail.com', '085644736738', '2026-11-13 05:11:33');

-- --------------------------------------------------------

--
-- Table structure for table `demo_users`
--

CREATE TABLE `demo_users` (
  `id` int(10) UNSIGNED NOT NULL,
  `email` varchar(191) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ebooks`
--

CREATE TABLE `ebooks` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `level` enum('Beginner','Intermediate','Professional') NOT NULL,
  `category` varchar(100) NOT NULL,
  `cover_image` varchar(255) DEFAULT NULL,
  `file_url` varchar(500) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `glossary`
--

CREATE TABLE `glossary` (
  `id` int(11) NOT NULL,
  `term` varchar(100) NOT NULL,
  `definition` text NOT NULL,
  `category` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `learning_materials`
--

CREATE TABLE `learning_materials` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  `level` enum('Beginner','Intermediate','Professional') NOT NULL,
  `category` varchar(100) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `market_history`
--

CREATE TABLE `market_history` (
  `id` int(11) NOT NULL,
  `symbol` varchar(20) NOT NULL,
  `price` decimal(12,2) NOT NULL,
  `change_percent` decimal(8,2) DEFAULT 0.00,
  `market_status` varchar(30) DEFAULT 'Neutral',
  `recorded_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `market_history`
--

INSERT INTO `market_history` (`id`, `symbol`, `price`, `change_percent`, `market_status`, `recorded_at`) VALUES
(1, 'XAU/USD', 3398.20, 0.12, 'Neutral', '2026-08-28 07:00:00'),
(2, 'XAU/USD', 3402.50, 0.25, 'Bullish', '2026-08-28 08:00:00'),
(3, 'XAU/USD', 3410.80, 0.47, 'Bullish', '2026-08-28 09:00:00'),
(4, 'XAU/USD', 3407.30, 0.38, 'Bullish', '2026-08-28 10:00:00'),
(5, 'XAU/USD', 3421.50, 0.85, 'Bullish', '2026-08-28 11:00:00'),
(6, 'XAU/USD', 4585.18, -0.35, 'Bearish', '2026-08-28 13:43:05'),
(7, 'XAU/USD', 4585.29, -0.35, 'Bearish', '2026-08-28 13:44:06');

-- --------------------------------------------------------

--
-- Table structure for table `market_updates`
--

CREATE TABLE `market_updates` (
  `id` int(11) NOT NULL,
  `symbol` varchar(20) NOT NULL DEFAULT 'XAU/USD',
  `price` decimal(12,2) NOT NULL,
  `change_value` decimal(12,2) DEFAULT 0.00,
  `change_percent` decimal(8,2) DEFAULT 0.00,
  `market_status` varchar(30) DEFAULT 'Neutral',
  `support` decimal(12,2) DEFAULT NULL,
  `resistance` decimal(12,2) DEFAULT NULL,
  `market_summary` text DEFAULT NULL,
  `market_factors` text DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `market_updates`
--

INSERT INTO `market_updates` (`id`, `symbol`, `price`, `change_value`, `change_percent`, `market_status`, `support`, `resistance`, `market_summary`, `market_factors`, `updated_at`) VALUES
(3, 'XAU/USD', 4585.29, -16.23, -0.35, 'Bearish', NULL, NULL, NULL, NULL, '2026-08-28 13:44:06');

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE `news` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `category` varchar(100) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `visual_type` varchar(50) DEFAULT NULL,
  `asset_symbol` varchar(30) DEFAULT NULL,
  `chart_timeframe` varchar(10) DEFAULT NULL,
  `summary` text DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  `impact_level` enum('Low','Medium','High') DEFAULT 'Medium',
  `gold_effect` enum('Bullish','Bearish','Neutral') DEFAULT 'Neutral',
  `source` varchar(255) DEFAULT NULL,
  `source_url` varchar(500) DEFAULT NULL,
  `published_at` datetime DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`id`, `title`, `slug`, `category`, `image`, `visual_type`, `asset_symbol`, `chart_timeframe`, `summary`, `content`, `impact_level`, `gold_effect`, `source`, `source_url`, `published_at`, `created_at`) VALUES
(1, 'Harga Minyak Sedikit Melemah, Pasar Pantau Venezuela dan Selat Hormuz', 'harga-minyak-sedikit-melemah-pasar-pantau-venezuela-dan-selat-hormuz', 'Komoditas', 'https://content-media.investing.com/news/moved_LYNXMPEM2N017_1200_L.jpg', 'candlestick', 'WTI', '1H', 'Harga minyak melemah tipis di tengah perhatian pasar terhadap perkembangan pasokan Venezuela dan negosiasi terkait Selat Hormuz.', 'Harga minyak bergerak sedikit melemah pada Jumat, 28 Agustus 2026. Pasar mencermati laporan mengenai kemungkinan akses Amerika Serikat terhadap cadangan minyak Venezuela serta perkembangan hubungan AS dan Iran terkait Selat Hormuz.\r\n\r\nBrent turun sekitar 0,3% menjadi US$89,45 per barel, sedangkan West Texas Intermediate (WTI) turun sekitar 0,2% menjadi US$83,39 per barel pada pagi hari. Kedua kontrak minyak tersebut juga diperkirakan mengalami penurunan dalam sepekan.\r\n\r\nPerhatian pasar semakin tertuju pada Venezuela setelah muncul laporan bahwa negara tersebut mempertimbangkan kemungkinan keluar dari OPEC. Di sisi lain, Amerika Serikat disebut sedang berupaya mendapatkan akses jangka panjang terhadap sebagian cadangan minyak Venezuela.\r\n\r\nPerkembangan tersebut menjadi perhatian karena perubahan hubungan antara Amerika Serikat dan Venezuela berpotensi memengaruhi prospek pasokan minyak global.\r\n\r\nSelain Venezuela, kondisi di Selat Hormuz juga menjadi faktor penting bagi pasar energi. Ketegangan antara Amerika Serikat dan Iran membuat investor terus memperhatikan potensi gangguan terhadap jalur perdagangan minyak.\r\n\r\nBagi pembelajar pasar, perkembangan geopolitik seperti kebijakan negara penghasil minyak, hubungan antarnegara, dan kondisi jalur perdagangan internasional merupakan beberapa faktor yang dapat memengaruhi pergerakan harga komoditas.', 'Medium', 'Neutral', 'Investing.com', 'https://id.investing.com/news/commodities-news/harga-minyak-sedikit-melemah-di-tengah-laporan-pasokan-venezuela-dan-fokus-pada-kesepakatan-hormuz-3058226', '2026-08-28 08:20:00', '2026-08-28 07:54:09'),
(2, 'Futures Emas Lebih Rendah pada Masa Dagang Asia', 'futures-emas-lebih-rendah-pada-masa-dagang-asia', 'Komoditas', 'https://content-media.investing.com/news/Gold_800x533_L_1729782846.jpg', 'candlestick', 'XAUUSD', '1H', 'Futures emas bergerak lebih rendah pada perdagangan Asia Jumat, dengan kontrak emas untuk penyerahan Desember turun sekitar 0,60% di tengah pergerakan dolar AS dan pasar komoditas lainnya.', 'Futures emas bergerak lebih rendah pada masa dagang Asia pada Jumat, 28 Agustus 2026. Pergerakan tersebut terjadi ketika pasar mencermati perkembangan harga emas, indeks dolar AS, serta pergerakan komoditas logam lainnya.\r\n\r\nPada perdagangan di Divisi Comex New York Mercantile Exchange, futures emas untuk penyerahan Desember diperdagangkan pada sekitar USD4.636,14 per troy ons pada waktu penulisan. Kontrak tersebut tercatat mengalami penurunan sekitar 0,60%.\r\n\r\nPergerakan harga emas menunjukkan bahwa tekanan masih terjadi pada pasar futures dalam perdagangan Asia. Berdasarkan data yang dikutip dalam artikel, level sekitar USD4.617,76 menjadi area support, sementara USD4.755,00 menjadi area resistance yang perlu diperhatikan oleh pelaku pasar.\r\n\r\nPergerakan dolar AS juga menjadi salah satu faktor yang diperhatikan. Indeks Dolar AS Berjangka yang mengukur kinerja dolar AS terhadap enam mata uang utama tercatat naik sekitar 0,05% dan diperdagangkan pada USD99,14.\r\n\r\nKenaikan dolar AS dapat menjadi faktor yang perlu diperhatikan oleh pasar emas karena perubahan nilai dolar dapat memengaruhi daya tarik komoditas yang dihargai dalam mata uang tersebut.\r\n\r\nSelain emas, sejumlah logam lainnya juga mengalami pergerakan berbeda. Perak untuk penyerahan September turun sekitar 0,94% menjadi USD68,78 per troy ons. Sementara itu, tembaga untuk penyerahan Oktober bergerak naik sekitar 0,09% menjadi USD6,60 per pon.\r\n\r\nBagi pembelajar pasar, pergerakan futures emas tidak hanya dipengaruhi oleh harga emas itu sendiri. Dolar AS, level support dan resistance, serta pergerakan komoditas logam lainnya juga dapat menjadi bagian dari konteks yang perlu diperhatikan ketika menganalisis pasar.\r\n\r\nPergerakan harga dalam satu sesi juga tidak selalu menunjukkan arah jangka panjang. Karena itu, informasi mengenai harga futures sebaiknya dipahami bersama dengan faktor ekonomi dan pasar lainnya sebelum digunakan sebagai bahan analisis.', 'Medium', 'Bearish', 'Investing.com', 'https://id.investing.com/news/commodities-news/futures-emas-lebih-rendah-pada-masa-dagang-asia-3058294', '2026-08-28 12:25:00', '2026-08-28 08:42:39'),
(3, 'Oil Prices Set for Second Weekly Decline as Trump Stays with Iran Blockade', 'oil-prices-second-weekly-decline-iran-blockade', 'Energi', 'https://content-media.investing.com/trkd-images/LYNXNPEMAC07J_L.jpg', 'candlestick', 'WTI', '1H', 'Harga minyak diperkirakan mengalami penurunan mingguan kedua di tengah kebijakan blokade Iran oleh Presiden Trump.', 'Pasar minyak mencermati kebijakan Amerika Serikat terhadap Iran. Ketidakpastian geopolitik dan pasokan energi menjadi faktor yang memengaruhi pergerakan harga minyak.', 'Medium', 'Neutral', 'Investing.com', 'https://www.investing.com/news/commodities-news/oil-prices-set-for-second-weekly-decline-as-trump-to-stay-with-iran-blockade-5024008', '2026-11-13 12:06:40', '2026-11-13 05:06:40'),
(4, 'Gold Languishes Near 13-Month Low as $96 Oil Fuels Bets on Bigger Fed Hikes', 'gold-near-13-month-low-oil-fed-hikes', 'Emas', 'https://content-media.investing.com/news/moved_LYNXNPEI3O02P_L.jpg', 'candlestick', 'XAUUSD', '1H', 'Harga emas tertekan di dekat level terendah 13 bulan seiring kenaikan harga minyak dan ekspektasi kenaikan suku bunga Federal Reserve.', 'Kenaikan harga minyak memicu kekhawatiran inflasi dan memperkuat ekspektasi kebijakan moneter yang lebih ketat. Kondisi tersebut memberikan tekanan terhadap harga emas.', 'High', 'Bearish', 'Investing.com', 'https://www.investing.com/news/commodities-news/gold-languishes-near-13month-low-as-96-oil-fuels-bets-on-bigger-fed-hikes-5023860', '2026-11-13 12:06:40', '2026-11-13 05:06:40'),
(5, 'Gold Clings Below $4,000 as Inflation Fears Fuel Bets on Bigger Fed Hikes', 'gold-below-4000-inflation-fed-hikes', 'Emas', 'https://content-media.investing.com/news/ThaiGold_800x533_L_1626864791.jpg', 'candlestick', 'XAUUSD', '1H', 'Harga emas bertahan di bawah 4.000 dolar AS ketika kekhawatiran inflasi meningkatkan ekspektasi kenaikan suku bunga The Fed.', 'Kekhawatiran inflasi mendorong pasar memperkirakan kebijakan suku bunga yang lebih ketat. Ekspektasi tersebut dapat meningkatkan tekanan terhadap harga emas.', 'High', 'Bearish', 'Investing.com', 'https://www.investing.com/news/commodities-news/gold-clings-below-4000-as-inflation-fears-fuel-bets-on-bigger-fed-hikes-5020139', '2026-11-13 12:06:40', '2026-11-13 05:06:40'),
(6, 'Gold Steadies Near $4,035 After Sharp Selloff as December Fed Hike Bets Firm', 'gold-steadies-near-4035-december-fed-hike', 'Emas', 'https://content-media.investing.com/news/gold3_800x533_L_1412152987.jpg', 'candlestick', 'XAUUSD', '1H', 'Harga emas bergerak stabil di sekitar 4.035 dolar AS setelah penurunan tajam, sementara ekspektasi kenaikan suku bunga The Fed menguat.', 'Pasar emas mencoba stabil setelah mengalami tekanan jual. Perhatian investor tertuju pada ekspektasi kebijakan suku bunga Federal Reserve pada bulan Desember.', 'Medium', 'Bearish', 'Investing.com', 'https://www.investing.com/news/commodities-news/gold-steadies-near-4035-after-sharp-selloff-as-december-fed-hike-bets-firm-5017433', '2026-11-13 12:06:40', '2026-11-13 05:06:40'),
(7, 'Oil Prices Drop More Than 1% as Iran-Ukraine Peace Talk Hopes Pressure Markets', 'oil-prices-drop-iran-ukraine-peace-talks', 'Energi', 'https://content-media.investing.com/trkd-images/LYNXNPEMAB056_L.jpg', 'candlestick', 'WTI', '1H', 'Harga minyak turun lebih dari 1% di tengah harapan perkembangan pembicaraan perdamaian Iran dan Ukraina.', 'Harapan tercapainya kesepakatan perdamaian memengaruhi sentimen pasar energi. Investor mencermati potensi perubahan risiko geopolitik dan pasokan minyak global.', 'Medium', 'Neutral', 'Investing.com', 'https://www.investing.com/news/commodities-news/oil-prices-drop-more-than-1-as-hopes-for-iran-ukraine-peace-talks-pressure-markets-5020171', '2026-11-13 12:06:40', '2026-11-13 05:06:40');

-- --------------------------------------------------------

--
-- Table structure for table `videos`
--

CREATE TABLE `videos` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `youtube_url` varchar(500) NOT NULL,
  `description` text DEFAULT NULL,
  `category` varchar(100) NOT NULL,
  `level` enum('Beginner','Intermediate','Professional') DEFAULT 'Beginner',
  `duration` varchar(20) DEFAULT NULL,
  `thumbnail` varchar(500) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `demo_accounts`
--
ALTER TABLE `demo_accounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `demo_leads`
--
ALTER TABLE `demo_leads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `demo_users`
--
ALTER TABLE `demo_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `ebooks`
--
ALTER TABLE `ebooks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `glossary`
--
ALTER TABLE `glossary`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `learning_materials`
--
ALTER TABLE `learning_materials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `market_history`
--
ALTER TABLE `market_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `market_updates`
--
ALTER TABLE `market_updates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `videos`
--
ALTER TABLE `videos`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `demo_accounts`
--
ALTER TABLE `demo_accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `demo_leads`
--
ALTER TABLE `demo_leads`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `demo_users`
--
ALTER TABLE `demo_users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ebooks`
--
ALTER TABLE `ebooks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `glossary`
--
ALTER TABLE `glossary`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `learning_materials`
--
ALTER TABLE `learning_materials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `market_history`
--
ALTER TABLE `market_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `market_updates`
--
ALTER TABLE `market_updates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `videos`
--
ALTER TABLE `videos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
