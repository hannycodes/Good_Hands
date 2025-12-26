<?php
session_start();
require_once 'db.php';
header('Content-Type: application/json');

// 1. Get the ID from the query string
$case_id = $_GET['case_id'] ?? null;

if (!$case_id) {
    echo json_encode(['success' => false, 'error' => 'No case ID provided']);
    exit;
}

try {
    // 2. Fetch the case details
    $stmt = $pdo->prepare("SELECT * FROM cases WHERE id = ?");
    $stmt->execute([$case_id]);
    $case = $stmt->fetch();

    if (!$case) {
        echo json_encode(['success' => false, 'error' => 'Case not found']);
        exit;
    }

    // 3. Fetch the total amount raised for THIS specific case
    $stmt_sum = $pdo->prepare("SELECT SUM(amount) as total_raised FROM donations WHERE case_id = ?");
    $stmt_sum->execute([$case_id]);
    $raised = $stmt_sum->fetch()['total_raised'] ?? 0;

    echo json_encode([
        'success' => true,
        'data' => [
            'id' => $case['id'],
            'title' => $case['title'],
            'description' => $case['description'],
            'category' => $case['category'],
            'goal' => $case['goal_amount'],
            'raised' => $raised,
            'image' => $case['image_path']
        ]
    ]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}