<?php
require_once 'xp_system.php';
require_once 'config.php';
session_start();

$discussionId = $_POST['discussion_id'];
$comment = $_POST['comment'];
$userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
$username = isset($_SESSION['username']) ? $_SESSION['username'] : '';

$currentDate = date('Y-m-d H:i:s');

try {
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password_db);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $user_id = $_SESSION['user_id'];
    $role = $_SESSION['role'];

        if($role == "user"){
            add_xp_for_comment($pdo, $user_id, $XP_VALUES);
        }

    $sql = "INSERT INTO komentare_na_foru (diskuze_id, uzivatel_name, obsah, datum) VALUES (:discussionId, :username, :comment, :curdate)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':discussionId', $discussionId);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':comment', $comment);
    $stmt->bindParam(':curdate', $currentDate);
    $stmt->execute();

    header("Location: forumdiskuze.php?id=$discussionId");
    exit();
} catch (PDOException $e) {
    echo "Chyba při připojování k databázi: " . $e->getMessage();
}
?>
