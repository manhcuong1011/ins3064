<?php
session_start();
if(!isset($_SESSION['username'])){
    header('location:login.php');
}

$con=mysqli_connect('localhost','root', '123456', 'LoginReg', '3306');
mysqli_select_db($con,'LoginReg');

$username = $_SESSION['username'];
$s="select * from userReg where name='$username'";
$result = mysqli_query($con,$s);
$userData = mysqli_fetch_assoc($result);
?>


<html lang='en'>
<head>
    <title>Home page</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">


</head>
<body>
<div class="container">

    <a class="float-right" href="logout.php">LOGOUT</a>
    <h1>Welcome <? echo $_SESSION['username']; ?> </h1>

<div class="card" style="margin-top: 50px; padding: 20px;">
        <h2>User Profile</h2>
        <p><strong>Username:</strong> <?php echo htmlspecialchars($userData['name']); ?></p>
        <p><strong>Student ID:</strong> <?php echo htmlspecialchars($userData['studentID']); ?></p>
        <p><strong>DoB</strong> <?php echo htmlspecialchars($userData['DoB']); ?></p>
        <p><strong>country:</strong> <?php echo htmlspecialchars($userData['country']); ?></p>
    </div>
</div>

</body>