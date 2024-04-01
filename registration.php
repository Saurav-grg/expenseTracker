<?php
include 'connect.php';
$errors = array();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['fname']) && isset($_POST['lname']) && isset($_POST['gender']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['confirmPassword'])) {
    $fname = mysqli_real_escape_string($conn, $_POST['fname']);
    $lname = mysqli_real_escape_string($conn, $_POST['lname']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $confirmPassword = $_POST['confirmPassword'];
    
    $checkEmailQuery = "SELECT * FROM users WHERE email='$email'";
    $checkEmailResult = mysqli_query($conn, $checkEmailQuery);
    
    if (mysqli_num_rows($checkEmailResult) > 0) {
      $errors["emailUsedErr"][] = "Email is already associated with an existing account.";
    
    } 
    elseif (!password_verify($confirmPassword, $password)) {
        $errors["confirmPasswordErr"][]  = "Passwords do not match";
        
    } else {
        
    $sql = "INSERT INTO users (fname, lname, gender, email, password) VALUES ('$fname', '$lname', '$gender', '$email', '$password')";
    
    $result = mysqli_query($conn, $sql);
    if ($result) {
        messageLog("Registration successful!") ;
      header("Location: main.php");
        exit();
    } 
    else {
        $errMsg = "Error: " . mysqli_error($conn);
        messageLog($errMsg) ;
    }}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration Form</title>
    <link rel="stylesheet" href="signin.css"> <!-- You can link to your own stylesheet if needed -->
    

    
</head>
<body>
    <div class="container">
        <h2>User Registration Form</h2>
        <form method="post">
            <label for="fname">First Name:</label>
            <input type="text" id="fname" name="fname" required>

            <label for="lname">Last Name:</label>
            <input type="text" id="lname" name="lname" required>
            <div class="genderBox">
            <span>Gender:</span>
            <input type="radio" id="male" name="gender" value="male" required>
            <label for="male">Male</label>
            <input type="radio" id="female" name="gender" value="female" required>
            <label for="female">Female</label>
            </div>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" autocomplete="off" required>
            <span id="emailError" class="error">
            <?php echo isset($errors['emailUsedErr']) ? implode('<br>', $errors['emailUsedErr']) : ''; ?></span>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <label for="confirmPassword">Re-enter password:</label>
            <input type="password" id="confirmPassword" name="confirmPassword" required>
            <span id="passError" class="error">
            <?php echo isset($errors['confirmPasswordErr']) ? implode('<br>', $errors['confirmPasswordErr']) : ''; 
            mysqli_close($conn);?></span>

            <button type="submit">Register</button>
        </form>
    </div>
   
</body>
</html>
