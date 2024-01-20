<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Styles/hardwareedit.css">
    <title>Register</title>
    <script>
        function redirectToHomePage() {
            window.location.href = 'index.php';
        }
    </script>
</head>
<body>
<div class="page-transition">
<div class="col-md-6 center-container">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6"><br>
                <h2 class="textik text-center mb-4">Přidat/Změnit Hardware</h2>
                <form action="hardware_edit.php" method="post">
                    <div class="form-group">
                        <label for="username">Procesor:</label>
                        <input type="text" class="form-control" id="cpu" name="cpu" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Základní deska:</label>
                        <input type="text" class="form-control" id="mobo" name="mobo" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Grafická karta:</label>
                        <input type="text" class="form-control" id="gpu" name="gpu" required>
                    </div>
                    <div class="form-group">
                        <label for="password">RAM[Počet GB]</label>
                        <input type="number" class="form-control" id="ram" name="ram" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block submit">Změnit</button>
                </form>
            </div>  
        </div>
        <div class="text-center button-group">
            <label for="account">Nechcete nic měnit?</label>
            <button type="submit" class="btn btn-primary btn-sm back-home-btn" onclick="redirectToHomePage()" >Redirect</button>
        </div>
    </div>
</div>
</div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>