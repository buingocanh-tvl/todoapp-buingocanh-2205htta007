<?php
// Bước 1: Thông tin kết nối
$host = "localhost";  // máy chủ MySQL (mặc định)
$dbname = "todo_app"; // tên database bạn đã tạo
$username = "root";   // tài khoản mặc định của MySQL
$password = "";       // mật khẩu mặc định (thường để trống)

// Bước 2: Kết nối đến MySQL bằng PDO
try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    // Thiết lập chế độ lỗi để hiển thị lỗi nếu có
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Kết nối thành công đến cơ sở dữ liệu!";
} catch (PDOException $e) {
    echo "❌ Kết nối thất bại: " . $e->getMessage();
}
?>
