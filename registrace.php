<?php
require_once 'config.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    try {
        $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $dbuser, $password_db);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $checkUserStmt = $pdo->prepare("SELECT COUNT(*) FROM uzivatele WHERE jmeno = ? OR email = ?");
        $checkUserStmt->execute([$username, $email]);
        $rowCount = $checkUserStmt->fetchColumn();
        if ($rowCount > 0) {
            $_SESSION['error'] = "Jméno nebo e-mail již existuje v databázi.";
            header('Location: register.php');
            exit();
        }

        $sql = "INSERT INTO uzivatele (jmeno, email, heslo, role) VALUES (?, ?, ?, 'user')";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$username, $email, password_hash($password, PASSWORD_DEFAULT)]);

        $userId = $pdo->lastInsertId();
        $userSql = "SELECT * FROM uzivatele WHERE id = ?";
        $userStmt = $pdo->prepare($userSql);
        $userStmt->execute([$userId]);
        $user = $userStmt->fetch(PDO::FETCH_ASSOC);

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
