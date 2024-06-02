<?php
session_start();

$loggedInUsername = isset($_SESSION['username']) ? $_SESSION['username'] : '';
$loggedInRole = isset($_SESSION['role']) ? $_SESSION['role'] : '';
$userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';

$host = 'localhost';
$dbname = 'Project';
$user = 'postgres';
$password_db = '4wnsdXJ1';

try {
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password_db);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $current_xp = 0;
    if ($loggedInRole == 'user') {
        $sql = "SELECT xp FROM uzivatele WHERE id = :userId";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result !== false) {
            $current_xp = $result['xp'];
        }
    }
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}

$div_count = isset($_SESSION['div_count']) ? $_SESSION['div_count'] : 0;

if (isset($_GET['add_div'])) {
    $div_count = $_GET['add_div'];
    $_SESSION['div_count'] = $div_count;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="Styles/forumedit.css">
    
    <title>Index</title>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        function redirectToForum() {
            var count = <?php echo isset($_SESSION['div_count']) ? $_SESSION['div_count'] : 0; ?>;
            count++;
            window.location.href = 'forum.php?add_div=' + count;
        }

        function redirectToPorovnavani() {
            window.location.href = 'porovnavani.php';
        }

        function redirectToForumDiscussion(discussionId) {
           window.location.href = 'forumdiskuze.php?id=' + discussionId;
        }
        function deleteDiscussion(discussionId) {
    if (confirm('Opravdu chcete smazat tuto diskuzi?')) {
        $.ajax({
            type: 'POST',
            url: 'delete_discussion.php',
            data: { discussion_id: discussionId },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert('Chyba: ' + response.error);
                }
            },
            error: function(xhr, status, error) {
                alert('Chyba při provádění požadavku: ' + error);
            }
        });
    }
}
function searchDiscussion() {
    var searchText = document.querySelector('.search').value.toLowerCase();
    
    var discussions = document.querySelectorAll('.longrounddiv');

    var deletebuttons = document.querySelectorAll('.deletebutton');

    discussions.forEach(function(discussion, index) {
        var discussionTitle = discussion.querySelector('.discussion-title').textContent.toLowerCase();
        var discussionGame = discussion.querySelector('.game-description').textContent.toLowerCase();

        var deleteButton = deletebuttons[index];

        if (!discussionTitle.includes(searchText) && !discussionGame.includes(searchText)) {
            discussion.style.display = 'none';
            if (deleteButton) {
                deleteButton.style.display = 'none';
            }
        } else {
            discussion.style.display = 'block';
            if (deleteButton) {
                deleteButton.style.display = 'block';
            }
        }
    });
}
    </script>
</head>
<style>
<?php
$loggedInRole = isset($_SESSION['role']) ? $_SESSION['role'] : '';

$longRoundDivWidth = $loggedInRole === 'admin' ? '72vw' : '79vw';

$flexContainerColumns = $loggedInRole === 'admin' ? 'auto auto' : 'auto';
?>

.longrounddiv {
    width: <?php echo $longRoundDivWidth; ?> !important;
}

.flexcontainer {
    display: grid;
    grid-template-columns: <?php echo $flexContainerColumns; ?>;
    gap: 20px;
}

</style>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand hardwarehub" href="index.php">HardwareHub</a>
    <?php
if ($loggedInRole == 'user') {
    echo '<div class="xp-bar-container">';
    echo '<label for="xp" class="urxp">Tvoje XP:</label>';
    echo '<div class="progress">';
    echo '<div class="progress-bar progress-bar-striped progress-bar-animated xp-bar" role="progressbar" aria-valuenow="' . $current_xp . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . $current_xp . '%;"></div></div>';
    echo '</div>';
}
?>
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
    <div class="center-container">
        <div class="search-container">
            <form action="create_discussion.php">
                <input type="text" placeholder="Hledat..." name="search" class="search">
                <button type="button" class="searchbutton" onclick="searchDiscussion()"><i class="fa fa-search"></i></button>
                <button type="submit" class="backhomebtn" >Vytvořit diskuzi</button>
            </form>
        </div>
        <div class="flexcontainer">
        <?php
$host = 'localhost';
$dbname = 'Project';
$user = 'postgres';
$password_db = '4wnsdXJ1';

try {
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password_db);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $sql = "SELECT id, nazev, obsah, datum, hra_name, uzivatel_name FROM prispevky_na_foru";
    $stmt = $pdo->query($sql);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($result) {
        foreach ($result as $row) {
            $discussionId = $row['id'];
            echo '<button type="button" class="longrounddiv" onclick="redirectToForumDiscussion(' . $discussionId . ')">';
            echo '<div class="contentleft">';
            echo '<h2 class="discussion-title">' . $row['nazev'] . '</h2>';
            echo '<p class="game-description">Ke hře: ' . $row['hra_name'] . '</p>';
            echo '</div>';
            echo '<div class="contentright">';
            echo '<p>Vytvořeno: ' . $row['datum'] . '</p>';
            echo '<p>Vytvořeno uživatelem: ' . $row['uzivatel_name'] . '</p>';
            echo '</div>';
            echo '</button>';

            if (!empty($loggedInRole) && $loggedInRole === 'admin') {
                echo '<button type="button" class="deletebutton" onclick="deleteDiscussion(' . $discussionId . ')">';
                echo '<i class="fa fa-trash" style="font-size: 20px;"></i>';
                echo '</button>';
            }
        }
    } else {
        echo "Žádné příspěvky nebyly nalezeny.";
    }
    
} catch (PDOException $e) {
    echo "Chyba při připojování k databázi: " . $e->getMessage();
}
?>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
