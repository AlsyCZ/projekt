<?php
require_once 'config.php';

try {
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $dbuser, $password_db);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT name FROM graficke_karty";
    $stmt = $pdo->query($sql);
    $graphicsCards = $stmt->fetchAll(PDO::FETCH_COLUMN);

    echo json_encode($graphicsCards);
} catch (PDOException $e) {
    echo "Chyba při připojování k databázi: " . $e->getMessage();
}
?>
