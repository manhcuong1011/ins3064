<?php
session_start();

/* KẾT NỐI DATABASE */
$con = mysqli_connect('localhost', 'root', '123456', 'ql_banhngot', '3306');
if (!$con) {
    die("Lỗi kết nối database: " . mysqli_connect_error());
}
mysqli_set_charset($con, "utf8");

// Hàm dọn dẹp dữ liệu đầu vào
function safe_input($con, $data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return mysqli_real_escape_string($con, $data);
}

// Kiểm tra session
if(!isset($_SESSION['username'])){
    header('location:login.php');
    exit();
}

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy và làm sạch dữ liệu
    $name = safe_input($con, $_POST['name']);
    $price = safe_input($con, $_POST['price']);
    $status = safe_input($con, $_POST['status']);
    $image = safe_input($con, $_POST['image']); 
    
    // Kiểm tra dữ liệu hợp lệ
    if (empty($name) || empty($status) || !is_numeric($price) || $price < 0) {
        $message = '<div class="alert alert-danger">Dữ liệu nhập vào không hợp lệ. Vui lòng kiểm tra lại.</div>';
    } else {
        // Sử dụng Prepared Statements để thêm dữ liệu an toàn
        $query = "INSERT INTO products (name, price, status, image, created_at) 
                  VALUES (?, ?, ?, ?, NOW())";
        
        $stmt = mysqli_prepare($con, $query);
        // "sdss": string, double, string, string 
        mysqli_stmt_bind_param($stmt, "sdss", $name, $price, $status, $image);
        
        if (mysqli_stmt_execute($stmt)) {
            // Thêm thành công, chuyển hướng về trang chủ
            header('location: home.php?msg=added');
            exit();
        } else {
            $message = '<div class="alert alert-danger">Lỗi khi thêm sản phẩm: ' . mysqli_error($con) . '</div>';
        }
        mysqli_stmt_close($stmt);
    }
}
mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Sản Phẩm Mới</title>
    <!-- Link Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        .container { margin-top: 30px; }
    </style>
</head>
<body>

<div class="container">
    <div class="card p-4">
        <h3 class="mb-4 text-success">Thêm Sản Phẩm Mới</h3>
        
        <?php echo $message; ?>

        <form method="POST" action="create.php">
            <!-- Tên Bánh -->
            <div class="form-group">
                <label for="name">Tên Bánh:</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            
            <!-- Giá -->
            <div class="form-group">
                <label for="price">Giá (VND):</label>
                <input type="number" step="0.01" class="form-control" id="price" name="price" required min="0">
            </div>
            
            <!-- Trạng Thái -->
            <div class="form-group">
                <label for="status">Trạng Thái:</label>
                <select class="form-control" id="status" name="status" required>
                    <option value="In Stock">Trong kho (In Stock)</option>
                    <option value="Out of Stock">Hết hàng (Out of Stock)</option>
                </select>
            </div>
            
            <!-- URL Ảnh (Giả định nhập URL) -->
            <div class="form-group">
                <label for="image">URL Ảnh:</label>
                <input type="text" class="form-control" id="image" name="image" placeholder="Nhập đường dẫn ảnh sản phẩm (URL)">
            </div>
            
            <button type="submit" class="btn btn-success">Thêm Sản Phẩm</button>
            <a href="home.php" class="btn btn-secondary">Quay Lại</a>
        </form>
    </div>
</div>

</body>
</html>