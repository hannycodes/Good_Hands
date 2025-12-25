<?php
header('Content-Type: application/json');
require_once 'db.php';

$data = json_decode(file_get_contents("php://input"));
$token = $data->token;
$newPassword = password_hash($data->password, PASSWORD_BCRYPT);

// Verify token and time
$stmt = $pdo->prepare("SELECT id FROM users WHERE reset_token = ? AND token_expiry > NOW()");
$stmt->execute([$token]);
$user = $stmt->fetch();

if ($user) {
    $update = $pdo->prepare("UPDATE users SET password = ?, reset_token = NULL, token_expiry = NULL WHERE id = ?");
    $update->execute([$newPassword, $user['id']]);
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => "Link is invalid or has expired."]);
}