<?php
include "connection.php";

$id=$_GET["id"];

if(isset($_GET["confirm"]) && $_GET["confirm"]=="Yes"){
    $delete_query="DELETE FROM table1 WHERE id=$id";
    mysqli_query($link,$delete_query) or die(mysqli_error($link));
    header("Location: index.php");
}
?>

<h1>Are you sure to delete item <?php echo $id ?> ?</h1>
<button><a href="delete.php?id=<?php echo $id ?>&confirm=Yes">Yes</a></button>
<button><a href="index.php">No</a></button>