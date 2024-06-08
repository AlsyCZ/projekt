<?php
session_start();
require_once 'config.php';

$loggedInUsername = isset($_SESSION['username']) ? $_SESSION['username'] : '';
$loggedInRole = isset($_SESSION['role']) ? $_SESSION['role'] : '';
$userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';

if ($loggedInRole != 'admin' && $loggedInRole != 'moderátor') {
    echo "Nemáte oprávnění pro zobrazení této stránky.";
    exit;
}

$errorMessages = [];

try {
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $dbuser, $password_db);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $user_id = $_POST['user_id'];
        $action = $_POST['action'];
        $reason = $_POST['reason'];

        if ($action == 'reject' && empty($reason)) {
            $errorMessages[$user_id] = "Při zamítnutí je nutné uvést důvod.";
        } else {
            if ($action == 'approve') {
                $sql = "UPDATE uzivatele SET role = 'moderátor', moderator_request = TRUE WHERE id = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$user_id]);

                $message = "Gratulujeme! Byli jste povýšeni na moderátora.";
            } else {
                $sql = "UPDATE uzivatele SET moderator_request = TRUE WHERE id = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$user_id]);

                $message = "Vaše žádost o moderátora byla zamítnuta. Důvod: " . htmlspecialchars($reason);
            }

            $sql = "INSERT INTO user_messages (user_id, message) VALUES (?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$user_id, $message]);
        }
    }

    $sql = "SELECT id, jmeno, role, xp FROM uzivatele WHERE xp >= 100 AND role != 'moderátor' AND jmeno != 'Admin' AND moderator_request = FALSE";
    $stmt = $pdo->query($sql);
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Chyba při připojování k databázi: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="Styles/zadostimoderatori.css">
    <title>HardwareHub</title>
</head>
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
                echo '<a class="dropdown-item" href="user_messages.php">Moje zprávy</a>';
                if($loggedInRole == "admin"){
                    echo '<a class="dropdown-item" href="user_management.php">User management</a>';
                    echo '<a class="dropdown-item" href="zadosti_moderator.php">Žádosti o moderátora</a>';
                }
                if($loggedInRole == "moderátor"){
                    echo '<a class="dropdown-item" href="zadosti_moderator.php">Žádosti o moderátora</a>';
                }
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
<div class="center-container">
<div class="flexcontainer1">
    <h1>Žádosti o moderátora:</h1>
    <?php
    if (!empty($users)) {
        foreach ($users as $user) {
            echo '<div class="notification">';
            echo '<p>Uživatel ' . htmlspecialchars($user['jmeno']) . ' má ' . htmlspecialchars($user['xp']) . ' XP a žádá o roli moderátora.</p>';
            echo '<form method="POST">';
            echo '<input type="hidden" name="user_id" value="' . htmlspecialchars($user['id']) . '">';
            echo '<textarea name="reason" class="formcontrol" placeholder="Důvod zamítnutí (povinné při zamítnutí)"></textarea>';
            echo '<button type="submit" name="action" value="approve" class="submitdwn">Approve</button>';
            echo '<button type="submit" name="action" value="reject" class="submitdwn1">Reject</button>';
            if (isset($errorMessages[$user['id']])) {
                echo '<p style="color: red;">' . $errorMessages[$user['id']] . '</p>';
            }
            echo '</form>';
            echo '</div>';
        }
    } else {
        echo '<p>Žádné nové žádosti.</p>';
    }
    ?>
</div>
</div>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
