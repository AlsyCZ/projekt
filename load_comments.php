<?php
session_start();
$userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
$host = 'localhost';
$dbname = 'Project';
$user = 'postgres';
$password_db = '4wnsdXJ1';

try {
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password_db);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $gameId = isset($_POST['gameId']) ? $_POST['gameId'] : '';
    $sql = "SELECT komentare_pod_strankou.*, uzivatele.jmeno
            FROM komentare_pod_strankou
            INNER JOIN uzivatele ON komentare_pod_strankou.uzivatel_id = uzivatele.id
            WHERE komentare_pod_strankou.hra_name = ?
            ORDER BY komentare_pod_strankou.datum DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$gameId]);
    $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($comments) {
        foreach ($comments as $comment) {
            echo '<div class="comment">';
            echo '<img src="user_icon.png" alt="User Image" width="50px" height="50px">';
            echo '<p><strong>' . $comment['jmeno'] . '</strong> napsal(a) ' . $comment['datum'] . ': ' . $comment['obsah'] . '</p>';
            echo '</div>';
        }
    } else {
        echo '<p>Zatím nejsou žádné komentáře.</p>';
    }
} catch (PDOException $e) {
    echo "Chyba při připojování k databázi: " . $e->getMessage();
}
?>
