<?php
session_start();
// Ensure NO spaces or lines exist before the <?php tag
require_once 'db.php'; 

// Hide PHP errors from the output to prevent breaking JSON
error_reporting(0);
ini_set('display_errors', 0);

header('Content-Type: application/json');

// Admin Security Check
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

$action = $_GET['action'] ?? '';

try {
    // 1. LIST CASES
    if ($action === 'list') {
        $stmt = $pdo->query("SELECT * FROM cases ORDER BY created_at DESC");
        echo json_encode(['success' => true, 'cases' => $stmt->fetchAll()]);
    } 
    
    // 2. CREATE OR UPDATE CASE
    elseif ($action === 'create' || $action === 'update') {
        $title = $_POST['title'];
        $goal = $_POST['goal_amount'];
        $cat = $_POST['category'];
        $desc = $_POST['description'];
        $status = $_POST['status'] ?? 'active';
        $video = $_POST['video_url'] ?? '';

        // Handle Image Upload
        $image_path = $_POST['existing_image'] ?? null;
        if (!empty($_FILES['case_image']['name'])) {
            $imgName = time() . '_' . preg_replace("/[^a-zA-Z0-9.]/", "_", $_FILES['case_image']['name']);
            if (move_uploaded_file($_FILES['case_image']['tmp_name'], '../uploads/images/' . $imgName)) {
                $image_path = 'uploads/images/' . $imgName;
            }
        }

        // Handle Document Upload
        $doc_path = $_POST['existing_doc'] ?? null;
        if (!empty($_FILES['case_doc']['name'])) {
            $docName = time() . '_' . preg_replace("/[^a-zA-Z0-9.]/", "_", $_FILES['case_doc']['name']);
            if (move_uploaded_file($_FILES['case_doc']['tmp_name'], '../uploads/docs/' . $docName)) {
                $doc_path = 'uploads/docs/' . $docName;
            }
        }

        if ($action === 'create') {
            // NOTE: Ensure you ran the ALTER TABLE command to add video_url and document_path
            $sql = "INSERT INTO cases (title, goal_amount, category, description, video_url, image_path, document_path, status) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$title, $goal, $cat, $desc, $video, $image_path, $doc_path, $status]);
        } else {
            $sql = "UPDATE cases SET title=?, goal_amount=?, category=?, description=?, video_url=?, image_path=?, document_path=?, status=? WHERE id=?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$title, $goal, $cat, $desc, $video, $image_path, $doc_path, $status, $_POST['id']]);
        }
        echo json_encode(['success' => true]);
    }

    // 3. DELETE CASE
    elseif ($action === 'delete') {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $stmt = $pdo->prepare("DELETE FROM cases WHERE id = ?");
            $stmt->execute([$id]);
            echo json_encode(['success' => true]);
        }
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
exit;