<?php
session_start();
require_once 'db.php';
header('Content-Type: application/json');

if ($_SESSION['role'] !== 'admin') exit;

try {
    // 1. Get Categories Breakdown
    $stmt1 = $pdo->query("SELECT category, SUM(amount) as total FROM donations d JOIN cases c ON d.case_id = c.id GROUP BY category");
    $categories = $stmt1->fetchAll();

    // 2. Get Campaign Progress
    $stmt2 = $pdo->query("SELECT title, goal_amount as goal, COALESCE((SELECT SUM(amount) FROM donations WHERE case_id = cases.id), 0) as raised FROM cases LIMIT 5");
    $campaigns = $stmt2->fetchAll();

    // 3. Totals
    $stmt3 = $pdo->query("SELECT SUM(amount) as total, COUNT(*) as cnt FROM donations");
    $totals = $stmt3->fetch();

    echo json_encode([
        'success' => true,
        'categories' => $categories,
        'campaigns' => $campaigns,
        'total_revenue' => number_format($totals['total'], 2),
        'total_count' => $totals['cnt']
    ]);
} catch (Exception $e) {
    echo json_encode(['success' => false]);
}