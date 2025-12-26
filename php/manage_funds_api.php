<?php
session_start();
require_once 'db.php';
header('Content-Type: application/json');

// Admin Check
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false, 'error' => 'Unauthorized']); exit;
}

try {
    // 1. Fetch joined donation records
    $stmt = $pdo->query("
        SELECT d.id, d.amount, d.donated_at, u.name as donor_name, c.title as case_title 
        FROM donations d 
        JOIN users u ON d.user_id = u.id 
        JOIN cases c ON d.case_id = c.id 
        ORDER BY d.donated_at DESC
    ");
    $donations = $stmt->fetchAll();

    // 2. Calculate Totals
    $totalSum = 0;
    $count = count($donations);
    foreach($donations as $d) { $totalSum += $d['amount']; }
    $avg = ($count > 0) ? ($totalSum / $count) : 0;

    echo json_encode([
        'success' => true,
        'donations' => $donations,
        'stats' => [
            'total' => number_format($totalSum, 2),
            'count' => $count,
            'avg' => number_format($avg, 2)
        ]
    ]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}