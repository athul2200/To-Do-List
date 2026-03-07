<?php
require_once 'db.php';

// Redirect to login if user is not authenticated
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Handle deleting a task
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = (int)$_POST['id'];
    
    // Ensure ID is valid before deleting
    if ($id > 0) {
        $stmt = $pdo->prepare("DELETE FROM tasks WHERE id = :id AND user_id = :user_id");
        $stmt->execute(['id' => $id, 'user_id' => $_SESSION['user_id']]);
    }
}

// Redirect back to the main page
header("Location: index.php");
exit;
?>
