<?php
ob_start(); // Prevents "Headers already sent" errors
session_start();
require_once 'db.php'; 

// Disable error display so they don't break the redirect
error_reporting(0);
ini_set('display_errors', 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $case_id = $_POST['case_id'] ?? null;
    $amount = $_POST['amount'] ?? null;
    $user_id = $_SESSION['user_id'] ?? null;

    if (!$user_id) {
        // Fallback for debugging if session is lost
        header("Location: ../public/login.html");
        exit();
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO donations (user_id, case_id, amount, donated_at) VALUES (?, ?, ?, NOW())");
        $stmt->execute([$user_id, $case_id, $amount]);
        
        $new_id = $pdo->lastInsertId();

        // THE REDIRECT: 
        // We use an absolute-style path to be safe
        $host = $_SERVER['HTTP_HOST'];
        $uri  = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        
        // This goes UP from /php/ then INTO /public/
        header("Location: http://$host/Good_Hands/public/success.php?donation_id=$new_id");
        exit();

    } catch (PDOException $e) {
        // If it fails, show the error instead of a white screen
        die("Critical Database Error: " . $e->getMessage());
    }
}
ob_end_flush();
?>