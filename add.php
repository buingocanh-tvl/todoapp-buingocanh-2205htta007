<?php
session_start();
require 'db.php';
if(!isset($_SESSION['user_id'])) header("Location: login.php");

$user_id = $_SESSION['user_id'];
$errors = [];

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $due_date = $_POST['due_date'] ?: null;
    $status = $_POST['status'];

    if(!$title) $errors[] = "Tiêu đề là bắt buộc";

    if(!$errors){
        $stmt = $conn->prepare("INSERT INTO tasks(user_id,title,description,due_date,status,created_at) VALUES(:uid,:title,:desc,:due,:status,NOW())");
        $stmt->execute([
            ':uid'=>$user_id,
            ':title'=>$title,
            ':desc'=>$description,
            ':due'=>$due_date,
            ':status'=>$status
        ]);
        header("Location: dashboard.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Thêm công việc</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container">
    <h2>Thêm công việc mới</h2>
    <?php if($errors): ?>
        <div class="alert alert-danger"><?php foreach($errors as $e) echo "<div>$e</div>"; ?></div>
    <?php endif; ?>
    <form method="post">
        <input type="text" name="title" placeholder="Tiêu đề" required>
        <textarea name="description" placeholder="Mô tả"></textarea>
        <input type="date" name="due_date">
        <select name="status">
            <option value="pending">Pending</option>
            <option value="in_progress">In Progress</option>
            <option value="completed">Completed</option>
        </select>
        <input type="submit" value="Thêm công việc">
        <a href="dashboard.php" class="btn">Hủy</a>
    </form>
</div>
</body>
</html>
