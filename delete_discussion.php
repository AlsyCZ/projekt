<?php
session_start();
require_once 'config.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['discussion_id'])) {

    try {
        $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $dbuser, $password_db);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $sql_comments = "DELETE FROM komentare_na_foru WHERE diskuze_id = :discussion_id";
        $stmt_comments = $pdo->prepare($sql_comments);
        $stmt_comments->bindParam(':discussion_id', $_POST['discussion_id']);
        $stmt_comments->execute();

        $sql_discussion = "DELETE FROM prispevky_na_foru WHERE id = :discussion_id";
        $stmt_discussion = $pdo->prepare($sql_discussion);
        $stmt_discussion->bindParam(':discussion_id', $_POST['discussion_id']);
        $stmt_discussion->execute();
        
        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Chyba při mazání diskuze: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'Neplatný požadavek']);
}
?>
