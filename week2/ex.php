<!DOCTYPE html>
<html>
    <h1>This is my first PHP file</h1>
    <?php
    //http://localhost/ins3064/week2/ex.php
    $x = $_GET["x"];
    $y = $_GET["y"];
    //arthimetic operators + - * / %
    echo "x + y = " . ($x + $y) . "<br>";
    //others
    //comparison operators == != > < >= <=
    echo "x == y: " . ($x == $y) . "<br>";
?>
</html>