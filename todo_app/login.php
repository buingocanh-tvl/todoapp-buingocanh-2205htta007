<?php
session_start();
require 'db.php';
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username=:username");
    $stmt->execute([':username'=>$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header("Location: dashboard.php");
        exit;
    } else {
        $errors[] = "Tên đăng nhập hoặc mật khẩu không đúng";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Đăng nhập</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body class="login">
<div class="container">
    <h2 class="login-title">Đăng nhập</h2>
    <?php if($errors): ?>
        <div class="alert alert-danger"><?php foreach($errors as $e) echo "<div>$e</div>"; ?></div>
    <?php endif; ?>
    <form method="post">
        <input type="text" name="username" placeholder="Tên đăng nhập" required>
        <input type="password" name="password" placeholder="Mật khẩu" required>
        <input type="submit" value="Đăng nhập">
    </form>
    <p>Chưa có tài khoản? <a href="register.php">Đăng ký</a></p>
</div>
</body>
</html>
