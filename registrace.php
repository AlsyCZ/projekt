<?php
require_once 'config.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    try {
        $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password_db);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "INSERT INTO uzivatele (jmeno, email, heslo, role) VALUES (?, ?, ?, 'user')";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$username, $email, password_hash($password, PASSWORD_DEFAULT)]);

        $userId = $pdo->lastInsertId();
        $userSql = "SELECT * FROM uzivatele WHERE id = ?";
        $userStmt = $pdo->prepare($userSql);
        $userStmt->execute([$userId]);
        $user = $userStmt->fetch(PDO::FETCH_ASSOC);

        session_start();
        $_SESSION['user_id'] = $userId;
        $_SESSION['username'] = $user['jmeno'];
        $_SESSION['role'] = $user['role'];

        header("Location: hardware.php");
        exit();
    } catch (PDOException $e) {
        echo "Chyba při připojování k databázi: " . $e->getMessage();
    }
}
?>
