<?php
session_start();
require_once 'db.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

try {
    // We select everything from cases
    // We also want to know how much has been raised for each case from the donations table
    $stmt = $pdo->query("
        SELECT c.*, COALESCE(SUM(d.amount), 0) as raised_amount 
        FROM cases c 
        LEFT JOIN donations d ON c.id = d.case_id 
        WHERE c.status = 'active'
        GROUP BY c.id 
        ORDER BY c.created_at DESC
    ");
    $cases = $stmt->fetchAll();

    echo json_encode(['success' => true, 'cases' => $cases]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}