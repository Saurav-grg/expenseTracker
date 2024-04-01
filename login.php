<?php
include 'connect.php';
if ($_SERVER["REQUEST_METHOD"] == "POST"){
$emailInput = mysqli_real_escape_string($conn, $_POST['userEmail']);
$passwordInput = $_POST['password-inp'];

$sql = "SELECT user_id, email, password FROM users WHERE email = '$emailInput'";
$result = mysqli_query($conn, $sql);

if ($result) {
    $row = mysqli_fetch_assoc($result);
    $storedPassword = $row['password'];
    if (password_verify($passwordInput, $storedPassword)) /*&& $storedEmail == $emailInput*/ {
        messageLog("login successful!") ;
        session_start();  // Start the session
        $_SESSION['user_id'] = $row['user_id'];  // Store user ID in the session
        header("Location: main.php");  // Redirect to the home page
        exit();
    } else {
        $errMsg = "Login failed. Please check your email and password.";
        }
} else {
    $errMsg = "Error: " . mysqli_error($conn);
    messageLog($errMsg);
}
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expense Tracker</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="container">
        <div class="left-section">
            <h1>Expense Tracker</h1>
            <p>Your no 1 tool for financial clraity</p>
        </div>
        <form class="right-section" method="POST">
            <input type="text" id="userEmail" name="userEmail" placeholder="email">
            <div class="password-container">
                <input type="password" id="password-inp" name="password-inp" required autocomplete="new-password">
                <span class="toggle-password" onclick="togglePasswordVisibility()">
                    <img class="pass-visibility" src="icons/eye-close.avif">
                </span>
                <span id="passError" class="error">
            <?php echo $errMsg;
                    mysqli_close($conn);            
            ?></span>

            </div>
            <button type="submit">Log In</button>
            <p>forgot password?</p>
        <button><a href="registration.php">Sign In</a></button>
        </form>
    </div>
    <script>
        function togglePasswordVisibility() {
            var passwordInput = document.getElementById('password-inp');
            var toggleIcon = document.querySelector('.toggle-password img');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.src = 'icons/eye-open.jpg'; // Replace with your open eye icon
            } else {
                passwordInput.type = 'password';
                toggleIcon.src = 'icons/eye-close.avif'; // Replace with your closed eye icon
            }
        }
    </script>
</body>
</html>