<?php
session_start();
require_once 'db.php';
header('Content-Type: application/json');

// 1. Admin Security Check
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false, 'error' => 'Unauthorized Access']);
    exit;
}

try {
    // A. Stats: Total Funds
    $stmt = $pdo->query("SELECT SUM(amount) as total FROM donations");
    $totalFunds = $stmt->fetch()['total'] ?? 0;

    // B. Stats: Active Cases
    $stmt = $pdo->query("SELECT COUNT(*) as active FROM cases WHERE status = 'active'");
    $activeCases = $stmt->fetch()['active'];

    // C. Stats: Total Users
    $stmt = $pdo->query("SELECT COUNT(*) as users FROM users");
    $totalUsers = $stmt->fetch()['users'];

    // D. Table: Get all cases + their current raised amount
    $stmt = $pdo->query("
        SELECT c.*, COALESCE(SUM(d.amount), 0) as raised 
        FROM cases c 
        LEFT JOIN donations d ON c.id = d.case_id 
        GROUP BY c.id 
        ORDER BY c.created_at DESC
    ");
    $campaigns = $stmt->fetchAll();

    echo json_encode([
        'success' => true,
        'stats' => [
            'funds' => number_format($totalFunds, 2),
            'active' => $activeCases,
            'users' => $totalUsers,
            'pending' => 0 // Can be expanded later for case approvals
        ],
        'campaigns' => $campaigns
    ]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}