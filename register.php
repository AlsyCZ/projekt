<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Styles/registrace.css">
    <title>HardwareHub</title>
    <script>
        function redirectToHomePage() {
            window.location.href = 'login.php';
        }
</script>
</head>
<body>
<div class="page-transition">
<div class="col-md-6 center-container">
    <div class="container mt-5">
        <div class="row justify-content-center">
                <h2 class="textik">Registrační formulář</h2>
                <form class="form" action="registrace.php" method="post">
                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" class="form-control" id="password" name="password" pattern="(?=.*\d).{8,}" title="Musí obsahovat nejméně jedno číslo a 8 znaků!!!" required>
                        <p id="length">Minimálně 8 znaků!!!</p>
                        <p id="number">Minimálně 1 číslo</p>
                    </div>
                    <button type="submit" id="submit-button" class="btn btn-primary btn-block submit" onclick="checkPasswordStrength()">Registrace</button>
                </form>
            </div>
        </div>
        <div class="text-center button-group">
            <label for="account">Máte účet?</label>
            <button type="submit" class="btn btn-primary btn-sm back-home-btn" onclick="redirectToHomePage()">Login</button>
        </div>
    </div>
</div>
<script>
var myInput = document.getElementById("password");
var number = document.getElementById("number");
var length = document.getElementById("length");

myInput.onkeyup = function() {
  var numbers = /[0-9]/g;
  if(myInput.value.match(numbers)) {  
    number.classList.remove("invalid");
    number.classList.add("valid");
  } else {
    number.classList.remove("valid");
    number.classList.add("invalid");
  }
  
  if(myInput.value.length >= 8) {
    length.classList.remove("invalid");
    length.classList.add("valid");
  } else {
    length.classList.remove("valid");
    length.classList.add("invalid");
  }
}
</script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
