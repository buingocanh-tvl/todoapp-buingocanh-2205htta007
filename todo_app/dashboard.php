<?php
session_start();
require 'db.php';
if(!isset($_SESSION['user_id'])) header("Location: login.php");

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

$stmt = $conn->prepare("SELECT * FROM tasks WHERE user_id=:uid ORDER BY due_date ASC");
$stmt->execute([':uid'=>$user_id]);
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total = count($tasks);
$done = 0;
foreach($tasks as $t) { if ($t['status'] == 'completed') $done++; }

$percent = $total > 0 ? round($done / $total * 100) : 0;
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Bảng điều khiển</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="container">

    <div class="user-box">
        👋 Xin chào, <b><?= htmlspecialchars($username) ?></b>
        <a href="logout.php" class="btn logout" style="float:right;">Đăng xuất</a>
    </div>

    <h3>Tiến độ: <?= $percent ?>%</h3>
    <div class="progress-container">
        <div class="progress-bar" style="width: <?= $percent ?>%"></div>
    </div>

    <a href="add.php" class="btn add">+ Thêm công việc</a>

    <table>
        <tr>
            <th>Tiêu đề</th>
            <th>Mô tả</th>
            <th>Hạn</th>
            <th>Trạng thái</th>
            <th>Thao tác</th>
        </tr>

        <?php foreach ($tasks as $t): ?>
        <tr>
            <td><?= htmlspecialchars($t['title']) ?></td>
            <td><?= htmlspecialchars($t['description']) ?></td>
            <td><?= $t['due_date'] ?></td>

            <td>
                <?php
                $cls = [
                    "pending" => "badge-pending",
                    "in_progress" => "badge-in_progress",
                    "completed" => "badge-completed"
                ][$t['status']];

                $statusText = [
                    "pending" => "Đang chờ",
                    "in_progress" => "Đang làm",
                    "completed" => "Hoàn thành"
                ];
                ?>
                <span class="badge <?= $cls ?>"><?= $statusText[$t['status']] ?></span>
            </td>

            <td>
                <a href="edit_task.php?id=<?= $t['id'] ?>" class="btn edit">Sửa</a>
                <a href="delete_task.php?id=<?= $t['id'] ?>" class="btn delete">Xóa</a>
            </td>
        </tr>
        <?php endforeach; ?>

    </table>

</div>

</body>
</html>
