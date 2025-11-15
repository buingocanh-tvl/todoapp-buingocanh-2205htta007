<?php
session_start();
require 'db.php';
if(!isset($_SESSION['user_id'])) header("Location: login.php");

$user_id = $_SESSION['user_id'];
$id = $_GET['id'] ?? null;

// Lấy thông tin task
$stmt = $conn->prepare("SELECT * FROM tasks WHERE id=:id AND user_id=:uid");
$stmt->execute([':id'=>$id, ':uid'=>$user_id]);
$task = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$task) { header("Location: dashboard.php"); exit; }

if($_SERVER['REQUEST_METHOD']==='POST'){
    if(isset($_POST['confirm'])){
        $stmt = $conn->prepare("DELETE FROM tasks WHERE id=:id AND user_id=:uid");
        $stmt->execute([':id'=>$id, ':uid'=>$user_id]);
    }
    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Xóa công việc</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container">
    <h2>Xác nhận xóa công việc</h2>
    <p>Bạn có chắc chắn muốn xóa công việc sau không?</p>
    <p><strong>Tiêu đề:</strong> <?= htmlspecialchars($task['title']) ?></p>
    <p><strong>Mô tả:</strong> <?= htmlspecialchars($task['description']) ?></p>

    <form method="post" style="display:flex; gap:10px;">
        <button type="submit" name="confirm">Xác nhận xóa</button>
        <a href="dashboard.php" class="btn">Hủy</a>
    </form>
</div>
</body>
</html>
