<?php
header('Content-Type: application/json');
require_once 'db.php'; // Your PDO connection file

$data = json_decode(file_get_contents("php://input"));
$email = $data->email;

$stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();

if ($user) {
    $token = bin2hex(random_bytes(32));
    $expiry = date("Y-m-d H:i:s", strtotime("+1 hour"));

    $update = $pdo->prepare("UPDATE users SET reset_token = ?, token_expiry = ? WHERE email = ?");
    $update->execute([$token, $expiry, $email]);

    // Since we are on localhost XAMPP:
    $link = "http://localhost/Good_Hands/public/reset-password.html?token=" . $token;
    
    echo json_encode([
        "success" => true, 
        "message" => "Link created: <a href='$link' style='color:#4FD1B5; font-weight:bold;'>Click Here to Reset</a>"
    ]);
} else {
    echo json_encode(["success" => false, "message" => "Email not found."]);
}