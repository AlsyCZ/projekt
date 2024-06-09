<?php
require_once 'xp_system.php';
require_once 'config.php';
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nazev = $_POST['nazevinp'];
    $obsah = $_POST['obsahinp'];
    $uzivatel = isset($_SESSION['username']) ? $_SESSION['username'] : null;
    $datum = date("Y-m-d H:i:s");
    $hra = $_POST['selectgame'];

    try {
        $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $dbuser, $password_db);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $user_id = $_SESSION['user_id'];
        $role = $_SESSION['role'];

        if($role == "user"){
            add_xp_for_discussion($pdo, $user_id, $XP_VALUES);
        }
        $sql = "INSERT INTO prispevky_na_foru (nazev, obsah, datum, hra_name, uzivatel_name) VALUES (?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([ $nazev, $obsah, $datum, $hra, $uzivatel]);

        echo '<script>';
        echo 'var count = ' . (isset($_SESSION['div_count']) ? $_SESSION['div_count'] : 0) . ';';
        echo 'count++;';
        echo 'window.location.href = "forum.php?add_div=" + count;';
        echo '</script>';

        exit();
    } catch (PDOException $e) {
        echo "Chyba při připojování k databázi: " . $e->getMessage();
    }
}
?>
