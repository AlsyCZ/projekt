<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Styles/login.css">
    <title>Login</title>
    <script>
        function redirectToRegPage() {
            window.location.href = 'register.php';
        }

        function redirectToHomePage() {
            window.location.href = 'index.php';
        }
    </script>
</head>
<body>
<div class="page-transition">
<div class="col-md-6 center-container">
    <div class="container mt-5">
        <div class="row justify-content-center">
        <h2 class="textik text-center mb-4">User login</h2>
            <div class="col-md-6"><br>
                <form action="login.php" method="post">
                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <?php
session_start();
require_once 'config.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    try {
        $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $dbuser, $password_db);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "SELECT * FROM uzivatele WHERE jmeno = ? LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['heslo'])) {
            $_SESSION['username'] = $user['jmeno'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['user_id'] = $user['id'];

            $hardwareSql = "SELECT * FROM hardware WHERE uzivatel_id = ?";
            $hardwareStmt = $pdo->prepare($hardwareSql);
            $hardwareStmt->execute([$user['id']]);
            $hardware = $hardwareStmt->fetch(PDO::FETCH_ASSOC);

            if ($hardware) {
                header("Location: index.php");
                exit();
            } else {
                header("Location: hardware.php");
                exit();
            }
            
            } else {
                echo '<span style="color:red;">Neplatné přihlašovací údaje</span>';
            }
    } catch (PDOException $e) {
        echo "Chyba při připojování k databázi: " . $e->getMessage();
    }
}
?>
                    <button type="submit" class="btn btn-primary btn-block submit">Login</button>
                </form>
            </div>
        </div>
        <div class="text-center button-group">
            <label for="account">Nemáte účet?</label>
            <button type="submit" class="btn btn-primary btn-sm register-btn" onclick="redirectToRegPage()">Registrace</button>
            <button type="submit" class="btn btn-primary btn-sm back-home-btn" onclick="redirectToHomePage()">Zpět na hlavní stránku</button>
        </div>
    </div>
</div>
</div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
