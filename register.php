<?php
session_start();
require 'db.php';
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm'];

    if($password !== $confirm) $errors[] = "Mật khẩu xác nhận không khớp";

    $stmt = $conn->prepare("SELECT * FROM users WHERE username=:username");
    $stmt->execute([':username'=>$username]);
    if($stmt->fetch()) $errors[] = "Tên đăng nhập đã tồn tại";

    if(!$errors){
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users(username,password,created_at) VALUES(:username,:password,NOW())");
        $stmt->execute([':username'=>$username, ':password'=>$hash]);
        header("Location: login.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Đăng ký</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body class="register">
<div class="container">
    <h2 class="register-title">Đăng ký</h2>
    <?php if($errors): ?>
        <div class="alert alert-danger"><?php foreach($errors as $e) echo "<div>$e</div>"; ?></div>
    <?php endif; ?>
    <form method="post">
        <input type="text" name="username" placeholder="Tên đăng nhập" required>
        <input type="password" name="password" placeholder="Mật khẩu" required>
        <input type="password" name="confirm" placeholder="Xác nhận mật khẩu" required>
        <input type="submit" value="Đăng ký">
    </form>
    <p>Đã có tài khoản? <a href="login.php">Đăng nhập</a></p>
</div>
</body>
</html>
