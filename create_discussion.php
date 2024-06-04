<?php
session_start();
$userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="Styles/createforum.css">
    <title>Index</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>

        function redirectToForum() {
            window.location.href = 'forum.php';
        }

        function redirectToIndex() {
            window.location.href = 'index.php';
        }

        window.onload = function () {
            LoadGameOptions();
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
    </script>
</head>
<body>
<div class="page-transition">
    <div class="center-container">  
    <button type="submit" class="backhomebtn2" onclick="redirectToForum()">Zpět</button>
        <h1 class="gametext">Vytvoř diskuzi!</h1>
        <form class="form" action="create_discussion_dtb.php" method="post">
            <div class="form-group">
                <label class="choosegametext" for="nazev">Název diskuze:</label>
                <input type="text" class="form-control" id="nazev" name="nazevinp" required>
            </div>
            <h2 class="choosegametext">Vyber hru:</h2>
            <select name="selectgame" id="selectgame">
                <!-- AJAX -->
            </select>
            <div class="form-group">
                <label class="choosegametext" for="obsah">Obsah diskuze:</label>
                <textarea class="form-control" name="obsahinp" rows="4" required></textarea>
            </div>
            <?php
if (empty($userId)) {
        echo "<p style='color: red;float:right;'>Pro vytvoření diskuze se musíte přihlásit.</p>";
    }
?>
            <button type="submit" class="backhomebtn" <?php echo empty($userId) ? 'disabled' : ''; ?> >Vytvoř diskuzi</button>
            
        </form>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
