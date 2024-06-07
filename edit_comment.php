<?php
require_once 'config.php';

try {
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $dbuser, $password_db);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $commentId = $_POST['comment_id'];
        $newContent = $_POST['new_content'];

        $sql = "UPDATE komentare_na_foru SET obsah = :new_content, upraveno = TRUE WHERE id = :comment_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':new_content', $newContent);
        $stmt->bindParam(':comment_id', $commentId);
        $stmt->execute();

        echo json_encode(array("success" => true));
    }
} catch (PDOException $e) {
    echo json_encode(array("success" => false, "error" => "Chyba při připojování k databázi: " . $e->getMessage()));
}
?>
