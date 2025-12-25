<?php
session_start();
require_once 'db.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $case_id = $_POST['case_id'] ?? null;
    $amount = $_POST['amount'] ?? null;
    $user_id = $_SESSION['user_id'] ?? null;

    if (!$user_id) { die("Session Expired. Please login again."); }

    try {
        $stmt = $pdo->prepare("INSERT INTO donations (user_id, case_id, amount, donated_at) VALUES (?, ?, ?, NOW())");
        $stmt->execute([$user_id, $case_id, $amount]);
        
        $new_id = $pdo->lastInsertId();
        header("Location: ../success.php?donation_id=" . $new_id);
        exit();
    } catch (PDOException $e) {
        die("Database Error: " . $e->getMessage());
    }
}