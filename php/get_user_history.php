<?php
session_start();
require_once 'db.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authorized']);
    exit;
}

$user_id = $_SESSION['user_id'];

try {
    // JOIN donations and cases to get the Title of the campaign
    $stmt = $pdo->prepare("
        SELECT d.id, d.amount, d.donated_at, c.title as case_title 
        FROM donations d 
        JOIN cases c ON d.case_id = c.id 
        WHERE d.user_id = ? 
        ORDER BY d.donated_at DESC
    ");
    $stmt->execute([$user_id]);
    $donations = $stmt->fetchAll();

    // Calculate total
    $total = 0;
    foreach($donations as $d) { $total += $d['amount']; }

    echo json_encode([
        'success' => true,
        'donations' => $donations,
        'total_formatted' => number_format($total, 2)
    ]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}