<?php
include "connection.php";

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$brand = $model = $price = $stock = "";

if ($id > 0) {
    $sql = "SELECT * FROM laptops WHERE id=$id";
    $result = mysqli_query($link, $sql);
    if ($row = mysqli_fetch_assoc($result)) {
        $brand = $row["brand"];
        $model = $row["model"];
        $price = $row["price"];
        $stock = $row["stock"];
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $brand = $_POST["brand"];
    $model = $_POST["model"];
    $price = $_POST["price"];
    $stock = $_POST["stock"];

    $sql = "UPDATE laptops SET brand='$brand', model='$model', price='$price', stock='$stock' WHERE id=$id";

    if (mysqli_query($link, $sql)) {
        header("Location: index.php");
        exit;
    } else {
        echo "Error updating record: " . mysqli_error($link);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Laptop</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <div class="col-lg-6">
        <h2>Edit Laptop</h2>
        <form method="post">
            <div class="form-group">
                <label for="brand">Brand:</label>
                <input type="text" class="form-control" id="brand" name="brand" value="<?php echo $brand; ?>" required>
            </div>
            <div class="form-group">
                <label for="model">Model:</label>
                <input type="text" class="form-control" id="model" name="model" value="<?php echo $model; ?>" required>
            </div>
            <div class="form-group">
                <label for="price">Price ($):</label>
                <input type="number" step="0.01" class="form-control" id="price" name="price" value="<?php echo $price; ?>" required>
            </div>
            <div class="form-group">
                <label for="stock">Stock:</label>
                <input type="number" class="form-control" id="stock" name="stock" value="<?php echo $stock; ?>" required>
            </div>
            <button type="submit" class="btn btn-success">Save</button>
            <a href="index.php" class="btn btn-default">Cancel</a>
        </form>
    </div>
</div>
</body>
</html>
