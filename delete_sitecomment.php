<?php
session_start();
require_once 'config.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment_id'])) {

    try {
        $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $dbuser, $password_db);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $sql = "DELETE FROM komentare_pod_strankou WHERE id = :comment_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':comment_id', $_POST['comment_id']);
        $stmt->execute();
        
        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Chyba při mazání komentáře: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'Neplatný požadavek']);
}
?>
