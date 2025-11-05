<?php
session_start();

/* KẾT NỐI DATABASE */
$con = mysqli_connect('localhost', 'root', '123456', 'ql_banhngot', '3306');
if (!$con) {
    die("Lỗi kết nối database: " . mysqli_connect_error());
}
mysqli_set_charset($con, "utf8");

// Kiểm tra session, nếu chưa đăng nhập thì chuyển hướng
if(!isset($_SESSION['username'])){
    header('location:login.php');
    exit();
}

// Truy vấn để lấy tất cả sản phẩm từ bảng 'products'
$query = "SELECT * FROM products ORDER BY id DESC";
$result = mysqli_query($con, $query);

if (!$result) {
    die("Lỗi truy vấn sản phẩm: " . mysqli_error($con));
}

// Lấy thông báo từ thao tác CRUD (nếu có)
$message = '';
if (isset($_GET['msg'])) {
    if ($_GET['msg'] == 'added') {
        $message = '<div class="alert alert-success">Thêm sản phẩm thành công!</div>';
    } elseif ($_GET['msg'] == 'updated') {
        $message = '<div class="alert alert-success">Cập nhật sản phẩm thành công!</div>';
    } elseif ($_GET['msg'] == 'deleted') {
        $message = '<div class="alert alert-warning">Xóa sản phẩm thành công!</div>';
    } elseif ($_GET['msg'] == 'error') {
        $message = '<div class="alert alert-danger">Đã xảy ra lỗi khi thực hiện thao tác.</div>';
    }
}
?>

<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Bánh Ngọt - Home</title>
    <!-- Link Bootstrap CSS (đơn giản, dễ dùng) -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .container { background-color: #ffffff; padding: 30px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); margin-top: 30px; }
        .table th, .table td { vertical-align: middle; }
        .product-image { max-width: 80px; height: auto; border-radius: 4px; object-fit: cover; }
    </style>
</head>
<body>
<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-primary">Quản Lý Bánh Ngọt</h2>
        <a href="logout.php" class="btn btn-secondary">Đăng Xuất</a>
    </div>

    <?php echo $message; // Hiển thị thông báo ?>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Danh Sách Sản Phẩm</h4>
        <!-- Nút thêm sản phẩm mới -->
        <a href="create.php" class="btn btn-success">+ Thêm Sản Phẩm Mới</a>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="thead-light">
                <tr>
                    <th>ID</th>
                    <th>Ảnh</th>
                    <th>Tên Bánh</th>
                    <th>Giá (VND)</th>
                    <th>Trạng Thái</th>
                    <th>Thời Gian Tạo</th>
                    <th class="text-center">Hành Động</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php while($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                            <td>
                                <!-- Hiển thị ảnh, dùng placeholder nếu ảnh không load được -->
                                <img src="<?php echo htmlspecialchars($row['image']) ?: 'https://placehold.co/80x80/cccccc/000000?text=No+Img'; ?>" 
                                     alt="<?php echo htmlspecialchars($row['name']); ?>" 
                                     class="product-image"
                                     onerror="this.onerror=null;this.src='https://placehold.co/80x80/cccccc/000000?text=No+Img';">
                            </td>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo number_format($row['price'], 0, ',', '.'); ?></td>
                            <td>
                                <span class="badge badge-<?php echo ($row['status'] == 'In Stock') ? 'success' : 'warning'; ?>">
                                    <?php echo htmlspecialchars($row['status']); ?>
                                </span>
                            </td>
                            <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                            <td class="text-center">
                                <!-- Nút Sửa -->
                                <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-primary">Sửa</a>
                                <!-- Nút Xóa (Dùng JavaScript để xác nhận) -->
                                <a href="delete.php?id=<?php echo $row['id']; ?>" 
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm ID <?php echo $row['id']; ?>?');">Xóa</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center">Chưa có sản phẩm nào. Hãy thêm sản phẩm mới!</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php mysqli_close($con); ?>
</div>

<!-- Link Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>