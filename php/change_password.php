<?php
session_start();
require_once 'db.php';
header('Content-Type: application/json');

$old = $_POST['old_password'];
$new = password_hash($_POST['new_password'], PASSWORD_BCRYPT);
$id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch();

if(password_verify($old, $user['password'])) {
    $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
    $stmt->execute([$new, $id]);
    echo json_encode(["success" => true, "message" => "Password updated successfully!"]);
} else {
    echo json_encode(["success" => false, "message" => "Current password is incorrect."]);
}