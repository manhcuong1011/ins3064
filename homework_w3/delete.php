<?php
include "connection.php";

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    $sql = "DELETE FROM laptops WHERE id=$id";
    if (mysqli_query($link, $sql)) {
        header("Location: index.php");
        exit;
    } else {
        echo "Error deleting record: " . mysqli_error($link);
    }
} else {
    echo "Invalid ID";
}
?>
