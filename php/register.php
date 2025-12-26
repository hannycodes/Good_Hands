<?php
error_reporting(0);
ini_set('display_errors', 0);

require 'db.php'; // make sure this path is correct
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

// Debug: show all POST data
// Uncomment this if nothing is working
// var_dump($_POST); exit;

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$confirm = $_POST['confirm_password'] ?? '';

if (!$name || !$email || !$password || !$confirm) {
    echo json_encode(['error' => 'All fields required', 'post' => $_POST]);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['error' => 'Invalid email', 'post' => $_POST]);
    exit;
}

if ($password !== $confirm) {
    echo json_encode(['error' => 'Passwords do not match']);
    exit;
}

$hash = password_hash($password, PASSWORD_DEFAULT);

try {
    // Make sure to match your table column exactly
    $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    $stmt->execute([$name, $email, $hash]);
    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    // Check if error code is 23000 (Duplicate Entry)
    if ($e->getCode() == 23000) {
        echo json_encode(['success' => false, 'error' => 'This email is already registered.']);
    } else {
        echo json_encode(['success' => false, 'error' => 'Database error. Please try again.']);
    }
}
