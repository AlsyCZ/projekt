<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment_id'])) {
    $host = 'localhost';
    $dbname = 'Project';
    $user = 'postgres';
    $password_db = '4wnsdXJ1';

    try {
        $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password_db);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $sql = "DELETE FROM komentare_na_foru WHERE id = :comment_id";
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
