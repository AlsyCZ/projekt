<?php
session_start();
$loggedInUsername = isset($_SESSION['username']) ? $_SESSION['username'] : '';
$loggedInRole = isset($_SESSION['role']) ? $_SESSION['role'] : '';
$userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="Styles/index.css">
    <title>Index</title>
    <script>
        function redirectToForum() {
            window.location.href = 'forum.php';
        }

        function redirectToPorovnavani() {
            window.location.href = 'porovnavani.php';
        }
        function redirectToRegPage() {
            window.location.href = 'register.php';
        }
    </script>
</head>
<style>
    

</style>
<body>
<div class="faded-background"></div>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand hardwarehub" href="index.php">HardwareHub</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <?php
            if (!empty($loggedInUsername)) {
                echo '<li class="nav-item">';
                echo '<span class="navbar-light navbar-brand urrole">Vaše role: ' . $loggedInRole . '</span>';
                echo '</li>';
                echo '<li class="nav-item dropdown">';
                echo '<a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                echo $loggedInUsername;
                echo '</a>';
                echo '<div class="dropdown-menu dropdown-menu-right custom-dropdown" aria-labelledby="userDropdown">';
                echo '<a class="dropdown-item" href="logout.php">Odhlásit se</a>';
                echo '<a class="dropdown-item" href="hardwareedit.php">Můj hardware</a>';
                echo '</div>';
                echo '</li>';
            } else {
                echo '<li class="nav-item">';
                echo '<a class="navbar-light navbar-brand" href="login.php">Přihlásit se</a>';
                echo '</li>';
            }
            ?>
        </ul>
    </div>
</nav>
<div class="page-transition">
<div class="container mt-4">
    <?php
    if (!empty($loggedInUsername)) {
        echo '<h1 class="text-center">Vítejte na našich stránkách, uživateli ' . $loggedInUsername . '!</h1>';
    }else{
        echo '<h1 class="text-center">Vítejte na našich stránkách, můžete přihlásit!</h1>';
    }
    ?>
</div>
<div class="col-md-6 center-container2">
<p>Zjistíte zde, zda váš hardware splňuje požadavky pro libovolnou hru, kterou si vyberete. Naleznete zde také komunitní fórum, kde můžete pokládat otázky a diskutovat o čemkoli, co vás napadne. 
    Přejeme vám napínavé prozkoumávání a doufáme, že se vám zde bude líbit! </p>
</div>
<div class="col-md-6 center-container">
<?php
    if ($userId) {
        echo '<h2 class="urhw">Tvůj Hardware:</h2>';
    } else{
echo '<div class="container mt-5">';
echo '    <div class="row justify-content-center">';
echo '        <h2 class="textik text-center mb-4">User login</h2>';
echo '        <div class="col-md-6"><br>';
echo '            <form action="index.php" method="post">';
echo '                <div class="form-group">';
echo '                    <label for="username">Username:</label>';
echo '                    <input type="text" class="form-control" id="username" name="username" required>';
echo '                </div>';
echo '                <div class="form-group">';
echo '                    <label for="password">Password:</label>';
echo '                    <input type="password" class="form-control" id="password" name="password" required>';
echo '                </div>';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $host = 'localhost';
    $dbname = 'Project';
    $user = 'postgres';
    $password_db = '4wnsdXJ1';

    try {
        $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password_db);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "SELECT * FROM uzivatele WHERE jmeno = ? LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['heslo'])) {
            $loggedInUsername = $_SESSION['username'] = $user['jmeno'];
            $loggedInRole = $_SESSION['role'] = $user['role'];
            $userId = $_SESSION['user_id'] = $user['id'];

            $hardwareSql = "SELECT * FROM hardware WHERE uzivatel_id = ?";
            $hardwareStmt = $pdo->prepare($hardwareSql);
            $hardwareStmt->execute([$user['id']]);
            $hardware = $hardwareStmt->fetch(PDO::FETCH_ASSOC);

            if ($hardware) {
                echo("<meta http-equiv='refresh' content='0'>");
                exit();
            } else {
                echo "<script>window.location.href='hardware.php';</script>";
                exit();
            }
        } else {
            echo '<p style="color:red;">Neplatné přihlašovací údaje</p>';
        }
    } catch (PDOException $e) {
        error_log("Chyba při připojování k databázi: " . $e->getMessage());
        echo '<p style="color:red;">Chyba při přihlašování</p>';
    }
}
echo '                <button type="submit" class="btn btn-primary btn-block submit">Login</button>';
echo '            </form>';
echo '        </div>';
echo '    </div>';
echo '    <div class="text-center button-group">';
echo '        <label for="account">Nemáte účet?</label>';
echo '        <button type="submit" class="btn btn-primary btn-sm register-btn" onclick="redirectToRegPage()">Registrace</button>';
echo '    </div>';
echo '</div>';
}
    ?>
<table>
<?php

$host = 'localhost';
$dbname = 'Project';
$user = 'postgres';
$password_db = '4wnsdXJ1';

try {
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password_db);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT * FROM hardware WHERE uzivatel_id = ?";
    $stmt = $pdo->prepare($sql);
    if (isset($_SESSION['user_id'])) {
        $stmt->execute([$userId]);
    } 
    $hardwareData = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if($hardwareData){
        echo '<tr>
            <th>Procesor</th>
            <td>' . $hardwareData[0]['procesor']. '</td>
        </tr><tr>
            <th>RAM</th>
            <td>' . $hardwareData[0]['ram'] . '</td>
        </tr><tr>
            <th>Grafická karta</th>
            <td>' . $hardwareData[0]['graficka_karta'] . '</td>
        </tr><tr>
            <th>Základní deska</th>
            <td>' . $hardwareData[0]['zakladni_deska'] . '</td>
        </tr>';
    }
    if ($userId && !$hardwareData){
        echo "<h2 style='margin-left:2%;'>Pro vypsání hardwaru si nejdříve musíte zaregistrovat svůj hardware do databáze!
        Učiníte tak v menu, když rozkliknete uživatele a dáte můj hardware. </h2>";
    }
} catch (PDOException $e) {
    echo "Chyba při připojování k databázi: " . $e->getMessage();
}
?>
</table>
<?php
    if (isset($_SESSION['user_id'])) {
        echo '<h2 class="udaje">Tvoje údaje:</h2>';
    } 
    ?>
<table>
<?php

$host = 'localhost';
$dbname = 'Project';
$user = 'postgres';
$password_db = '4wnsdXJ1';

try {
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password_db);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT * FROM uzivatele WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    if (isset($_SESSION['user_id'])) {
        $stmt->execute([$userId]);
    } 
    $hardwareData = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if($hardwareData){
        echo '<tr>
            <th>Username</th>
            <td>' . $loggedInUsername . '</td>
        </tr><tr>
            <th>Email</th>
            <td>' . $hardwareData[0]['email'] . '</td>
        </tr><tr>
            <th>Role</th>
            <td>' . $hardwareData[0]['role'] . '</td>
        </tr>';
    } 
} catch (PDOException $e) {
    echo "Chyba při připojování k databázi: " . $e->getMessage();
}
?>
</table>
</div>

    <button class="btn btn-primary btn1" onclick="redirectToPorovnavani()">Začni porovnávat</button>
    <div class="containeros">
        <button class="btn btn-info btn2" onclick="redirectToForum()">Naše fórum</button>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
