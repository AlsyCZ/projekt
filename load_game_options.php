<?php
$host = 'localhost';
$dbname = 'Project';
$user = 'postgres';
$password_db = 'XXXXXXXXX';
try {
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password_db);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $pdo->query("SELECT nazev FROM hry");
    $options = '';
    while ($row = $stmt->fetch()) {
        $options .= "<option value='" . $row['nazev'] . "'>" . $row['nazev'] . "</option>";
    }
    echo $options;
} catch (PDOException $e) {
    echo "Chyba při připojování k databázi: " . $e->getMessage();
}
?>
