<?php
session_start();

/* connect to database check user*/
$con=mysqli_connect('localhost','root', '123456', 'LoginReg', '3306');
mysqli_select_db($con,'LoginReg');

/* create variables to store data */
$name =$_POST['user'];
$pass =md5($_POST['password']);
$studentID = $_POST['studentID']; 
$DoB = $_POST['DoB'];
$country = $_POST['country'];

/* select data from DB */
$s="select * from userReg where name='$name'";

/* result variable to store data */
$result = mysqli_query($con,$s);

/* check for duplicate names and count records */
$num =mysqli_num_rows($result);
if($num==1){
    echo "Username Exists";
}else{
    //$reg ="insert into userReg(name,password) values ('$name','$pass')";
    $reg ="insert into userReg(name, password, studentID, DoB, Country) 
           values ('$name', '$pass', '$studentID', '$DoB', '$country')";
    mysqli_query($con,$reg);
    echo "registration successful";
}

 $_SESSION['username'] = $name;
header("location:login.php");
exit();

