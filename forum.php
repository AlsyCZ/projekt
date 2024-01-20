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
    <link rel="stylesheet" href="Styles/forum.css">
    <title>Index</title>
    <script>
        function redirectToForum() {
            window.location.href = 'forum.php';
        }

        function redirectToPorovnavani() {
            window.location.href = 'porovnavani.php';
        }
        
    </script>
</head>
<body>
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

<h1 class="mt-4 texthelp">TODO: vytvoření diskuze, komentáře...</h1>
</table>
</div>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
