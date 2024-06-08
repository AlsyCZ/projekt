<?php
session_start();
require_once 'config.php';
$loggedInUsername = isset($_SESSION['username']) ? $_SESSION['username'] : '';
$loggedInRole = isset($_SESSION['role']) ? $_SESSION['role'] : '';
$userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';

if ($loggedInRole != 'admin') {
    echo "Nemáte oprávnění pro zobrazení této stránky.";
    exit;
}

try {
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $dbuser, $password_db);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT id, jmeno, role, xp FROM uzivatele WHERE jmeno != 'Admin'";
    $stmt = $pdo->query($sql);
    $allUsers = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
    <link rel="stylesheet" href="Styles/usermanagement.css">
    <title>HardwareHub</title>
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
<h1 style="margin-left: 5%;">Seznam uživatelů:</h1>
<div class="flexcontainer">
        <?php
        if (!empty($allUsers)) {
            foreach ($allUsers as $user) {
                echo '<div class="longrounddiv">';
                echo '<div class="contentleft">';
                echo '<h2 class="discussion-title">' . htmlspecialchars($user['jmeno']) . '</h2>';
                echo '<p class="game-description">XP uživatele: ' . htmlspecialchars($user['xp']) . '</p>';
                echo '</div>';
                echo '<div class="contentright">';
                
                // Create a form with a dropdown to update the user's role
                echo '<form method="POST" action="update_role.php">';
                echo '<input type="hidden" name="user_id" value="' . htmlspecialchars($user['id']) . '">';
                if ($loggedInUsername != $user['jmeno']) { // Check if the logged in user is different from the current user
                    echo '<select class="dropdwn" name="role">';
                    echo '<option value="user"' . ($user['role'] == 'user' ? ' selected' : '') . '>User</option>';
                    echo '<option value="moderátor"' . ($user['role'] == 'moderátor' ? ' selected' : '') . '>Moderátor</option>';
                    echo '<option value="admin"' . ($user['role'] == 'admin' ? ' selected' : '') . '>Admin</option>';
                    echo '</select>';
                    echo '<input type="submit" class="submitdwn" value="Update Role">';
                }
                echo '</form>';

                echo '</div>';
                echo '</div>';
            }
        } else {
            echo "Žádné příspěvky nebyly nalezeny.";
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
