<?php
session_start();
require_once 'config.php';
$loggedInUsername = isset($_SESSION['username']) ? $_SESSION['username'] : '';
$loggedInRole = isset($_SESSION['role']) ? $_SESSION['role'] : '';
$userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';

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
    <link rel="stylesheet" href="Styles/forumdiskuze.css">
    
    <title>Index</title>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        function redirectToForum() {
            window.location.href = 'forum.php';
        }

        function redirectToPorovnavani() {
            window.location.href = 'porovnavani.php';
        }

        function redirectToForumDiscussion(discussionId) {
           window.location.href = 'forumdiskuze.php?id=' + discussionId;
        }
        function showBottomText() {
            var commentsDiv = document.getElementById('comments');
            commentsDiv.scrollTop = commentsDiv.scrollHeight;
        }

        window.onload = function() {
            showBottomText();
        };

        function deleteComment(Id) {
            if (confirm('Opravdu chcete smazat tento komentář?')) {
                $.ajax({
                    type: 'POST',
                    url: 'delete_comment.php',
                    data: { comment_id: Id },
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

        function editComment(id, currentContent) {
            var newContent = prompt('Upravit komentář:', currentContent);
            if (newContent !== null) {
                $.ajax({
                    type: 'POST',
                    url: 'edit_comment.php',
                    data: { comment_id: id, new_content: newContent },
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
    <div class="center-container">
    <div class="uidiv">
        <button type="submit" class="backhomebtn2" onclick="redirectToForum()">Zpět</button>
        <?php

            try {
                $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password_db);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $sql = "SELECT nazev, hra_name FROM prispevky_na_foru WHERE id = :discussionId";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':discussionId', $_GET['id']);
                $stmt->execute();
                $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if ($comments) {
                    foreach ($comments as $comment) {
                        echo '<h2 style="margin-top:0.5%">'. $comment['nazev'] .'</h2>';
                        echo '<h3>ke hře: '. $comment['hra_name'] .'</h3>';
                    }
                }
            } catch (PDOException $e) {
                echo "Chyba při připojování k databázi: " . $e->getMessage();
            }
            ?>
    </div>
        <div class="comments" id="comments">
            <?php

            try {
                $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password_db);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $sql = "SELECT id, obsah, uzivatel_name, datum FROM prispevky_na_foru WHERE id = :discussionId";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':discussionId', $_GET['id']);
                $stmt->execute();
                $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if ($comments) {
                    foreach ($comments as $comment) {
                        echo '<div class="comment">';
                        echo '<img src="user_icon.png" alt="User Image" width="50px" height="50px">';
                        echo '<p><strong>' . $comment['uzivatel_name'] . '</strong> napsal(a) ' . $comment['datum'] . ': ' . $comment['obsah'] . '</p>';
                        echo '</div>';
                    }
                } else {
                    echo "<p style='color: red;'>Tato diskuze neobsahuje žádné komentáře.</p>";
                }
                $sql = "SELECT id, obsah, uzivatel_name, datum, upraveno FROM komentare_na_foru WHERE diskuze_id = :discussionId ORDER BY datum ASC";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':discussionId', $_GET['id']);
                $stmt->execute();
                $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if ($comments) {
                    foreach ($comments as $comment) {
                        echo '<div class="comment">';
                        if (!empty($loggedInRole) && ($loggedInRole === 'admin' || $loggedInRole === 'moderátor')) {
                            echo '<i class="fa fa-pencil edit-comment-icon" style="font-size: 20px; cursor: pointer;margin-right:10px;" onclick="editComment(' . $comment['id'] . ', \'' . addslashes($comment['obsah']) . '\')"></i>';
                            echo '<i class="fa fa-trash delete-comment-icon" style="font-size: 20px; cursor: pointer;" onclick="deleteComment(' . $comment['id'] . ')"></i>';
                        }
                        echo '<img src="user_icon.png" alt="User Image" width="50px" height="50px">';
                        echo '<p><strong>' . htmlspecialchars($comment['uzivatel_name']) . '</strong> napsal(a) ' . htmlspecialchars($comment['datum']) . ': ' . htmlspecialchars($comment['obsah']) . '</p>';
                        if ($comment['upraveno']) {
                            echo '<p style="margin-left:10px;"><em>(Upraveno moderátorem)</em></p>';
                        }
                        echo '</div>';
                    }
                } else {
                    echo "<p style='color: red;'>Tato diskuze neobsahuje zatím žádné odpovědi.</p>";
                }
} catch (PDOException $e) {
    echo "Chyba při připojování k databázi: " . $e->getMessage();
}
?>
</div> 
<?php
if (empty($userId)) {
        echo "<p style='color: red;float:right;margin-top:34%'>Pro odeslání komentáře se musíte přihlásit.</p>";
    }
?>
    <div class="addcomments">   
        <form method="post" action="add_commenttoforum.php">
            <input type="hidden" name="discussion_id" value="<?php echo $_GET['id']; ?>">
            <div class="form-group">
                <textarea class="formcontrol" name="comment" rows="4" required></textarea>
            </div>
            
            <button type="submit" class="btn1" <?php echo empty($userId) ? 'disabled' : ''; ?>>Odeslat komentář</button >
            
        </form> 
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
