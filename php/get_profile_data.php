<?php
session_start();
require_once 'db.php';
header('Content-Type: application/json');

if(!isset($_SESSION['user_id'])) exit;

$stmt = $pdo->prepare("SELECT name, email, created_at FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

echo json_encode(["success" => true, "name" => $user['name'], "email" => $user['email']]);