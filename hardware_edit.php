<?php
session_start();
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cpu = $_POST["cpu"];
    $gpu = $_POST["gpu"];
    $mobo = $_POST["mobo"];
    $ram = $_POST["ram"];

    $userId = $_SESSION['user_id'];


    try {
        $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password_db);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "
        INSERT INTO hardware (uzivatel_id, procesor, ram, graficka_karta, operacni_system)
        VALUES (?, ?, ?, ?, ?)
        ON CONFLICT (uzivatel_id) DO UPDATE SET
        procesor = EXCLUDED.procesor,
        ram = EXCLUDED.ram,
        graficka_karta = EXCLUDED.graficka_karta,
        operacni_system = EXCLUDED.operacni_system
";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$userId, $cpu, $ram, $gpu, $mobo]);

        header("Location: index.php");
        exit();
    } catch (PDOException $e) {
        echo "Chyba při připojování k databázi: " . $e->getMessage();
    }
}
?>
