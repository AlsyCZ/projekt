<?php
$host = 'localhost';
$dbname = 'Project';
$user = 'postgres';
$password_db = '4wnsdXJ1';

try {
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password_db);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    session_start();
    $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';

    if ($userId) {
        $sqlHardware = "SELECT * FROM hardware WHERE uzivatel_id = ?";
        $stmtHardware = $pdo->prepare($sqlHardware);
        $stmtHardware->execute([$userId]);
        $hardwareData = $stmtHardware->fetchAll(PDO::FETCH_ASSOC);
    }

    if (isset($_POST['game'])) {
        $selectedGame = $_POST['game'];
        $sqlGame = "SELECT * FROM hry WHERE nazev = ?";
        $stmtGame = $pdo->prepare($sqlGame);
        $stmtGame->execute([$selectedGame]);
        $gameData = $stmtGame->fetch(PDO::FETCH_ASSOC);
    }

    echo '<table border="1" style="margin-left:5%">
            <tr>
                <th></th>
                <th>Tvůj Hardware</th>
                <th>Doporučený Hardware</th>
                <th>Minimální Hardware</th>
            </tr>
            <tr>
                <td>Procesor:</td>
                <td>' . ($userId && $hardwareData ? $hardwareData[0]['procesor'] : 'Chybí přihlášení nebo registrace HW!') . '</td>
                <td>' . (isset($_POST['game']) && $gameData ? $gameData['doporuceny_processor'] : '') . '</td>
                <td>' . (isset($_POST['game']) && $gameData ? $gameData['min_processor'] : '') . '</td>
            </tr>
            <tr>
                <td>Ram:</td>
                <td>' . ($userId && $hardwareData ? $hardwareData[0]['ram'] . ' GB' : 'Chybí přihlášení nebo registrace HW!') . '</td>
                <td>' . (isset($_POST['game']) && $gameData ? $gameData['doporuceny_ram'] . ' GB' : '') . '</td>
                <td>' . (isset($_POST['game']) && $gameData ? $gameData['min_ram'] . ' GB' : '') . '</td>
            </tr>
            <tr>
                <td>Grafická karta:</td>
                <td>' . ($userId && $hardwareData ? $hardwareData[0]['graficka_karta'] : 'Chybí přihlášení nebo registrace HW!') . '</td>
                <td>' . (isset($_POST['game']) && $gameData ? $gameData['doporucena_graficka_karta'] : '') . '</td>
                <td>' . (isset($_POST['game']) && $gameData ? $gameData['min_graficka_karta'] : '') . '</td>
            </tr>
            <tr>
                <td>Grafická karta:</td>
                <td>' . ($userId && $hardwareData ? $hardwareData[0]['operacni_system'] : 'Chybí přihlášení nebo registrace HW!') . '</td>
                <td>' . (isset($_POST['game']) && $gameData ? $gameData['doporuceny_os'] : '') . '</td>
                <td>' . (isset($_POST['game']) && $gameData ? $gameData['min_os'] : '') . '</td>
            </tr>
        </table>';
} catch (PDOException $e) {
    echo "Chyba při připojování k databázi: " . $e->getMessage();
}
?>
