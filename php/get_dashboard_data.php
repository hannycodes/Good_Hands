<?php
session_start();
require_once 'db.php';
header('Content-Type: application/json');

// LOGGING FOR DEBUGGING: This helps us see if the session is empty
// Check if user_id exists. If not, the session is failing to persist.
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'No session found. Please login again.']);
    exit;
}

try {
    // 1. Get the name from the session
    // If 'name' is empty, we try to fetch it again from the DB just to be safe
    $display_name = "Donor";
    
    if (isset($_SESSION['name']) && !empty($_SESSION['name'])) {
        $display_name = $_SESSION['name'];
    } else {
        // Backup: Fetch from DB if session name is somehow lost
        $stmt = $pdo->prepare("SELECT name FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch();
        if ($user) {
            $display_name = $user['name'];
            $_SESSION['name'] = $display_name; // Fix the session for next time
        }
    }

    // 2. Fetch Totals
    $stmt = $pdo->prepare("SELECT SUM(amount) as total FROM donations WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $total_row = $stmt->fetch();
    $total_donated = $total_row['total'] ?? 0;

    // 3. Send Response
    echo json_encode([
        'success' => true,
        'user_name' => $display_name,
        'stats' => [
            'total' => number_format($total_donated, 2),
            'lives' => floor($total_donated / 500),
            'active' => 8 // Placeholder
        ],
        'history' => [] // We will add history later
    ]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}