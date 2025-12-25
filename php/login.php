<?php
require 'db.php';
header('Content-Type: application/json');

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (!$email || !$password) {
    echo json_encode(['error' => 'All fields required']);
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();

if ($user && password_verify($password, $user['password'])) {
    echo json_encode(['success' => true, 'user' => $user]);
} else {
    echo json_encode(['error' => 'Invalid email or password']);
}
