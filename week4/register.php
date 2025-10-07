<?php
include("db_connect.php");

if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = md5($_POST['password']); 

    $check_query = "SELECT * FROM users WHERE username='$username'";
    $check_result = mysqli_query($link, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        echo "Username already exists!";
    } else {
        $insert_query = "INSERT INTO users (username, password) VALUES ('$username', '$password')";
        if (mysqli_query($link, $insert_query)) {
            echo "Registration successful! <a href='login.php'>Login now</a>";
        } else {
            echo "Error: " . mysqli_error($link);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>
<body>
    <h2>Register</h2>
    <form action="" method="post">
        <label>Username</label>
        <input type="text" name="username" required> <br>

        <label>Password</label>
        <input type="password" name="password" required> <br>

        <input type="submit" name="register" value="Register">
    </form>

    <p>Already have an account? <a href="login.php">Login here</a></p>
</body>
</html>
