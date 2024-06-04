<?php
require_once 'config.php';

try {
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password_db);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['game'])) {
        $selectedGame = $_POST['game'];

        $query = "SELECT obrazek FROM hry WHERE nazev = :game";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':game', $selectedGame, PDO::PARAM_STR);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result !== false && isset($result['obrazek'])) {
            echo $result['obrazek'];
        } 
    }
} catch (PDOException $e) {
    echo "Chyba při připojování k databázi: " . $e->getMessage();
}
?>
