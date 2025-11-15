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

$errors = [];
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $due_date = $_POST['due_date'] ?: null;
    $status = $_POST['status'];

    if(!$title) $errors[] = "Tiêu đề là bắt buộc";

    if(!$errors){
        $stmt = $conn->prepare("UPDATE tasks SET title=:title, description=:desc, due_date=:due, status=:status WHERE id=:id AND user_id=:uid");
        $stmt->execute([
            ':title'=>$title,
            ':desc'=>$description,
            ':due'=>$due_date,
            ':status'=>$status,
            ':id'=>$id,
            ':uid'=>$user_id
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
<title>Chỉnh sửa công việc</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container">
    <h2>Chỉnh sửa công việc</h2>
    <?php if($errors): ?>
        <div class="alert alert-danger"><?php foreach($errors as $e) echo "<div>$e</div>"; ?></div>
    <?php endif; ?>
    <form method="post">
        <input type="text" name="title" placeholder="Tiêu đề" value="<?= htmlspecialchars($task['title']) ?>" required>
        <textarea name="description" placeholder="Mô tả"><?= htmlspecialchars($task['description']) ?></textarea>
        <input type="date" name="due_date" value="<?= $task['due_date'] ?>">
        <select name="status">
            <option value="pending" <?= $task['status']=='pending'?'selected':'' ?>>Pending</option>
            <option value="in_progress" <?= $task['status']=='in_progress'?'selected':'' ?>>In Progress</option>
            <option value="completed" <?= $task['status']=='completed'?'selected':'' ?>>Completed</option>
        </select>
        <input type="submit" value="Cập nhật công việc">
        <a href="dashboard.php" class="btn">Hủy</a>
    </form>
</div>
</body>
</html>
