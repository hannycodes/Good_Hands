<?php
session_start();
require_once 'db.php';
header('Content-Type: application/json');

// Security Check: Only logged-in Admins
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false, 'error' => 'Unauthorized Access']);
    exit;
}

$action = $_GET['action'] ?? '';

try {
    // A. LIST ALL USERS
    if ($action === 'list') {
        $stmt = $pdo->query("SELECT id, name, email, role, created_at FROM users ORDER BY role ASC, created_at DESC");
        echo json_encode(['success' => true, 'users' => $stmt->fetchAll()]);
    } 
    
    // B. CREATE NEW ADMIN
    elseif ($action === 'add_admin') {
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'admin')");
        $stmt->execute([$name, $email, $password]);
        echo json_encode(['success' => true]);
    }

    // C. TOGGLE ROLE
    elseif ($action === 'toggle_role') {
        $id = $_GET['id'];
        $newRole = ($_GET['current'] === 'admin') ? 'donor' : 'admin';
        
        $stmt = $pdo->prepare("UPDATE users SET role = ? WHERE id = ?");
        $stmt->execute([$newRole, $id]);
        echo json_encode(['success' => true]);
    }

    // D. DELETE USER
    elseif ($action === 'delete') {
        $id = $_GET['id'];
        // Prevent admin from deleting themselves
        if ($id == $_SESSION['user_id']) {
            echo json_encode(['success' => false, 'error' => 'You cannot delete your own account.']);
            exit;
        }
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$id]);
        echo json_encode(['success' => true]);
    }

} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
}