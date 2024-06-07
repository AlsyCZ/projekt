<?php
require_once 'xp_system.php';
require_once 'config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $comment = $_POST['comment'];
    $game = isset($_POST['game']) ? $_POST['game'] : null;
    $loggedInUserId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';

    $currentDate = date('Y-m-d H:i:s');

    if (empty($loggedInUserId)) {
        echo "Pro odeslání komentáře se musíte přihlásit.";
        exit();
    }

    try {
        $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $dbuser, $password_db);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $user_id = $_SESSION['user_id'];
        $role = $_SESSION['role'];

        if($role == "user"){
            add_xp_for_comment($pdo, $user_id, $XP_VALUES);
        }
        
        $stmtInsertComment = $pdo->prepare("
            INSERT INTO komentare_pod_strankou (hra_name, datum, obsah, uzivatel_id)
            VALUES (?, ?, ?, ?)
        ");
        $stmtInsertComment->execute([$game, $currentDate, $comment, $loggedInUserId]);

        header('Location: porovnavani.php');
        exit();
    } catch (PDOException $e) {
        echo "Chyba při připojování k databázi: " . $e->getMessage();
    }
}
?>