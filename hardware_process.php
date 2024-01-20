<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cpu = $_POST["cpu"];
    $gpu = $_POST["gpu"];
    $mobo = $_POST["mobo"];
    $ram = $_POST["ram"];
    $userId = $_SESSION['user_id'];

    $host = 'localhost';
    $dbname = 'Project';
    $user = 'postgres';
    $password_db = '4wnsdXJ1';

    try {
        $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password_db);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "INSERT INTO hardware (uzivatel_id, procesor, ram, graficka_karta, zakladni_deska) VALUES (?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$userId, $cpu, $ram, $gpu, $mobo]);

        $userId = $_SESSION['user_id'];

        header("Location: index.php");
        exit();
    } catch (PDOException $e) {
        echo "Chyba při připojování k databázi: " . $e->getMessage();
    }
}
?>
