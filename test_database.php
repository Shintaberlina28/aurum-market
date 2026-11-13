<?php

require_once "config/database.php";

$query = "SELECT * FROM market_updates ORDER BY updated_at DESC LIMIT 1";

$result = $conn->query($query);

if ($result && $result->num_rows > 0) {

    $market = $result->fetch_assoc();

    echo "<h1>Database AURUM Berhasil!</h1>";

    echo "<p>Symbol: " . htmlspecialchars($market['symbol']) . "</p>";

    echo "<p>Price: " . htmlspecialchars($market['price']) . "</p>";

    echo "<p>Change: " . htmlspecialchars($market['change_percent']) . "%</p>";

    echo "<p>Status: " . htmlspecialchars($market['market_status']) . "</p>";

} else {

    echo "Data market belum tersedia.";

}

?>