<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

if (!isset($_GET['id']) || !isset($_GET['status'])) {
    die("Thiếu dữ liệu.");
}

$task_id = intval($_GET['id']);
$status = $_GET['status'];

$allowed = ['pending', 'in_progress', 'completed'];
if (!in_array($status, $allowed)) {
    die("Trạng thái không hợp lệ.");
}

$stmt = $conn->prepare("UPDATE tasks SET status = :status WHERE id = :id AND user_id = :user_id");
$stmt->execute([
    ':status' => $status,
    ':id' => $task_id,
    ':user_id' => $user_id,
]);

header("Location: dashboard.php?status_changed=1");
exit;
