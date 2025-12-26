<?php
session_start();
// Prevent PHP from echoing errors directly (breaks JSON)
error_reporting(0);
ini_set('display_errors', 0);

require 'db.php'; 

header('Content-Type: application/json');

try {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        echo json_encode(['success' => false, 'error' => 'All fields are required.']);
        exit;
    }

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // Correct Column names from your SQL dump: email, password, role, name
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['name'] = $user['name'];

        echo json_encode([
            'success' => true, 
            'role' => $user['role']
        ]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Invalid email or password.']);
    }

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'DB Error: ' . $e->getMessage()]);
}
exit;