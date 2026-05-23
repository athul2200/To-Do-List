<?php
session_start();
require_once 'db.php';

// Redirect to login if user is not authenticated
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = (int)$_POST['id'];
    $userId = $_SESSION['user_id'];

    try {
        // Verify ownership and toggle status
        $stmt = $pdo->prepare("UPDATE tasks SET is_completed = NOT is_completed WHERE id = :id AND user_id = :user_id");
        $stmt->execute(['id' => $id, 'user_id' => $userId]);
    } catch (PDOException $e) {
        // Log error or handle it silently for now to avoid 500 error
        // On many hosts, an uncaught exception triggers a 500 error.
    }
}

header("Location: index.php");
exit;
?>
