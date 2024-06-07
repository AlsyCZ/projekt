<?php
require_once 'config.php';

try {
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $dbuser, $password_db);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    session_start();

    if (isset($_POST['game'])) {
        $selectedGame = $_POST['game'];
        $sqlGame = "SELECT * FROM hry WHERE nazev = ?";
        $stmtGame = $pdo->prepare($sqlGame);
        $stmtGame->execute([$selectedGame]);
        $gameData = $stmtGame->fetch(PDO::FETCH_ASSOC);
    }

    echo '<table border="1" style="margin-left:5%">
            <tr>
                <td>Vývojář:</td>
                <td>' . (isset($_POST['game']) && $gameData ? $gameData['vyvojar'] : '') . '</td>
            </tr>
            <tr>
                <td>Žánr:</td>
                <td>' . (isset($_POST['game']) && $gameData ? $gameData['zanr'] : '') . '</td>
            </tr>
            <tr>
                <td>Hodnocení:</td>
                <td>' . (isset($_POST['game']) && $gameData ? $gameData['hodnoceni'] : '') . '</td>
            </tr>
        </table>';
} catch (PDOException $e) {
    echo "Chyba při připojování k databázi: " . $e->getMessage();
}
?>
