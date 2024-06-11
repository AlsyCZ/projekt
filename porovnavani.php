<?php
session_start();
require_once 'config.php';
$loggedInUsername = isset($_SESSION['username']) ? $_SESSION['username'] : '';
$loggedInRole = isset($_SESSION['role']) ? $_SESSION['role'] : '';
$userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';


try {
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $dbuser, $password_db);
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link rel="stylesheet" href="Styles/porovnani.css">
    <title>HardwareHub</title>
    <script>
        function redirectToForum() {
            window.location.href = 'forum.php';
        }

        function redirectToPorovnavani() {
            window.location.href = 'porovnavani.php';
        }
    </script>
</head>
<style>
    
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
    echo '<h3 class="currxp">' . $current_xp . '/100</h3>';
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
<h1 class="choosegametext">Vyber si hru:</h1>
<select class="selectgame" name="selectgame" id="selectgame">
        <!-- AJAX -->
    </select>
<div class="containerimgtext">
<div class="center-container3">
<h2 class="hw">O hře:</h2>
<table id="load_gamedata_table">
        <!-- AJAX -->
    </table>
</div>

<div class="center-container4">
<img id="game_image" src="images/rd2.jpg" alt="images" loading="lazy">
    <!-- AJAX -->
</div>
</div>

<div class="col-md-6 center-container">
<h2 class="hw">Tabulky s hardwarem:</h2>
<table id="load_data_table">
            <!-- AJAX -->
        </table>
</div>

<div class="col-md-6 center-container2">
        <h2>Komentáře</h2> 
        <div id="commentsContainer">
            <!-- AJAX -->
        </div>
    </div>

<script>
window.onload = function () {
    LoadGameOptions();
    var selectedGameId;
    function LoadGameDataAndComments() {
        $('#hiddenGameId').val(selectedGameId);
        LoadGameData();
        LoadComments();
    }
    $('#selectgame').change(function () {
        selectedGameId = $('#selectgame').val();
        localStorage.setItem('selectedGame', selectedGameId);
        LoadGameDataAndComments();
    });
    setTimeout(function () {
        selectedGameId = localStorage.getItem('selectedGame');
        if (!selectedGameId) {
            selectedGameId = $('#selectgame option:first').val();
        }
        $('#selectgame').val(selectedGameId);
        LoadGameDataAndComments();
    }, 300);
};


    function LoadGameOptions() {
        $.ajax({
            url: 'load_game_options.php',
            type: 'GET',
            success: function (data) {
                $('#selectgame').html(data);
            },
            error: function () {
                console.log('Chyba při načítání možností hry.');
            }
        });
    }

    function LoadGameData() {
    var selectedGame = $('#selectgame').val();
    $.ajax({
        url: 'load_data_table.php',
        type: 'POST',
        data: { game: selectedGame },
        success: function (data) {
            $('#load_data_table').html(data);
        },
        error: function () {
            console.log('Chyba při načítání dat o hře.');
        }
    });

    $.ajax({
        url: 'load_image.php',
        type: 'POST',
        data: { game: selectedGame },
        success: function (imageUrl) {
            $('#game_image').attr('src', imageUrl);
        },
        error: function () {
            console.log('Chyba při načítání URL obrázku.');
        }
    });

    $.ajax({
        url: 'load_gamedata_table.php',
        type: 'POST',
        data: { game: selectedGame },
        success: function (data) {
            $('#load_gamedata_table').html(data);
        },
        error: function () {
            console.log('Chyba při načítání dat o hře.');
        }
    });
}


    function LoadComments() {
        var selectedGame = $('#selectgame').val();
        $.ajax({
            url: 'load_comments.php',
            type: 'POST',
            data: { gameId: selectedGame },
            success: function (data) {
                $('#commentsContainer').html(data);
            },
            error: function () {
                console.log('Chyba při načítání komentářů.');
            }
        });
    }
    </script>
        
        <div class="col-md-6">
            <h2 style="margin-left: 5%;margin-top:3%;">Přidat komentář</h2>
            <form method="post" action="add_comment.php">
                <input type="hidden" name="game" id="hiddenGameId" value="">
                <div class="form-group">
                    <textarea class="formcontrol" name="comment" rows="4" required></textarea>
                </div>
                <button id="button1" type="submit" class="btn1 btn-primary" <?php echo empty($userId) ? 'disabled' : ''; ?>>Odeslat komentář</button >
                <?php
        if (empty($userId)) {
            echo "<p style='color: red;margin-left:5%;'>Pro odeslání komentáře se musíte přihlásit.</p>";
            
        }
        ?>
            </form>
        </div>
    </div>
</div>
</div>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>