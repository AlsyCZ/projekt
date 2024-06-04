<?php
session_start();
require_once "function.html";
require_once 'config.php';
$userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
$loggedInRole = isset($_SESSION['role']) ? $_SESSION['role'] : '';

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
            echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" crossorigin="anonymous" />';
            if (!empty($loggedInRole) && ($loggedInRole === 'admin' || $loggedInRole === 'moderátor')) {
                echo '<i class="fas fa-trash delete-comment-icon" style="font-size: 20px; cursor: pointer;" onclick="deleteComment(' . $comment['id'] . ')"></i>';
            }
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
