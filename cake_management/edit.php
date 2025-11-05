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

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$product = null;
$message = '';

// 1. Lấy dữ liệu sản phẩm hiện tại để điền vào form
if ($id > 0) {
    $query_select = "SELECT * FROM products WHERE id = ?";
    $stmt_select = mysqli_prepare($con, $query_select);
    mysqli_stmt_bind_param($stmt_select, "i", $id);
    mysqli_stmt_execute($stmt_select);
    $result_select = mysqli_stmt_get_result($stmt_select);
    $product = mysqli_fetch_assoc($result_select);

    if (!$product) {
        $message = '<div class="alert alert-danger">ID sản phẩm không hợp lệ hoặc không tồn tại.</div>';
        $id = 0; 
    }
    mysqli_stmt_close($stmt_select);
} else {
    $message = '<div class="alert alert-danger">Không tìm thấy ID sản phẩm để sửa.</div>';
}

// 2. Xử lý khi form được gửi đi (POST)
if ($_SERVER["REQUEST_METHOD"] == "POST" && $id > 0) {
    // Lấy và làm sạch dữ liệu
    $new_name = safe_input($con, $_POST['name']);
    $new_price = safe_input($con, $_POST['price']);
    $new_status = safe_input($con, $_POST['status']);
    $new_image = safe_input($con, $_POST['image']);
    
    // Kiểm tra dữ liệu
    if (!is_numeric($new_price) || $new_price < 0 || empty($new_name) || empty($new_status)) {
        $message = '<div class="alert alert-warning">Dữ liệu nhập vào không hợp lệ. Vui lòng kiểm tra lại.</div>';
    } else {
        // Chuẩn bị câu truy vấn UPDATE
        $query_update = "UPDATE products SET name = ?, price = ?, status = ?, image = ? WHERE id = ?";
        
        $stmt_update = mysqli_prepare($con, $query_update);
        // "sdssi": string, double, string, string, integer
        mysqli_stmt_bind_param($stmt_update, "sdssi", $new_name, $new_price, $new_status, $new_image, $id);
        
        if (mysqli_stmt_execute($stmt_update)) {
            // Cập nhật thành công, chuyển hướng về trang chủ
            header('location: home.php?msg=updated');
            exit();
        } else {
            $message = '<div class="alert alert-danger">Lỗi khi cập nhật sản phẩm: ' . mysqli_error($con) . '</div>';
        }
        mysqli_stmt_close($stmt_update);
    }
    // Tải lại dữ liệu tạm thời để hiển thị dữ liệu người dùng vừa nhập
    $product['name'] = $new_name;
    $product['price'] = $new_price;
    $product['status'] = $new_status;
    $product['image'] = $new_image;
}
mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa Sản Phẩm</title>
    <!-- Link Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        .container { margin-top: 30px; }
        .product-preview { max-width: 150px; height: auto; border-radius: 4px; border: 1px solid #ccc; padding: 5px; }
    </style>
</head>
<body>

<div class="container">
    <div class="card p-4">
        <h3 class="mb-4 text-primary">Sửa Sản Phẩm (ID: <?php echo htmlspecialchars($id); ?>)</h3>
        
        <?php echo $message; ?>

        <?php if ($product && $id > 0): ?>
        <form method="POST" action="edit.php?id=<?php echo $id; ?>">
            <!-- Tên Bánh -->
            <div class="form-group">
                <label for="name">Tên Bánh:</label>
                <input type="text" class="form-control" id="name" name="name" 
                       value="<?php echo htmlspecialchars($product['name']); ?>" required>
            </div>
            
            <!-- Giá -->
            <div class="form-group">
                <label for="price">Giá (VND):</label>
                <input type="number" step="0.01" class="form-control" id="price" name="price" 
                       value="<?php echo htmlspecialchars($product['price']); ?>" required min="0">
            </div>
            
            <!-- Trạng Thái -->
            <div class="form-group">
                <label for="status">Trạng Thái:</label>
                <select class="form-control" id="status" name="status" required>
                    <option value="In Stock" <?php echo ($product['status'] == 'In Stock') ? 'selected' : ''; ?>>Trong kho (In Stock)</option>
                    <option value="Out of Stock" <?php echo ($product['status'] == 'Out of Stock') ? 'selected' : ''; ?>>Hết hàng (Out of Stock)</option>
                </select>
            </div>
            
            <!-- URL Ảnh -->
            <div class="form-group">
                <label for="image">URL Ảnh:</label>
                <input type="text" class="form-control" id="image" name="image" 
                       value="<?php echo htmlspecialchars($product['image']); ?>" placeholder="Nhập đường dẫn ảnh sản phẩm">
                <?php if (!empty($product['image'])): ?>
                    <small class="form-text text-muted">Ảnh hiện tại:</small>
                    <img src="<?php echo htmlspecialchars($product['image']); ?>" class="product-preview">
                <?php endif; ?>
            </div>
            
            <button type="submit" class="btn btn-primary">Cập Nhật Sản Phẩm</button>
            <a href="home.php" class="btn btn-secondary">Quay Lại</a>
        </form>
        <?php else: ?>
             <a href="home.php" class="btn btn-secondary">Quay Lại Trang Chủ</a>
        <?php endif; ?>
    </div>
</div>

</body>
</html>