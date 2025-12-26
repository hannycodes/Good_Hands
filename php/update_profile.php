<?php
session_start();
require_once 'db.php';
header('Content-Type: application/json');

$name = trim($_POST['name']);
$email = trim($_POST['email']);
$id = $_SESSION['user_id'];

try {
    $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
    $stmt->execute([$name, $email, $id]);
    $_SESSION['name'] = $name; // Update session name
    echo json_encode(["success" => true, "message" => "Profile updated!"]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Email might be in use."]);
}