<?php
session_start();
require_once 'db.php';

// Redirect to login if user is not authenticated
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$task_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$user_id = $_SESSION['user_id'];
$error = '';

// Fetch the task to ensure it exists and belongs to the user
$stmt = $pdo->prepare("SELECT * FROM tasks WHERE id = :id AND user_id = :user_id");
$stmt->execute(['id' => $task_id, 'user_id' => $user_id]);
$task = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$task) {
    header("Location: index.php");
    exit;
}

// Handle task update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['task'])) {
    $updated_task_text = trim($_POST['task']);
    
    if (!empty($updated_task_text)) {
        $update_stmt = $pdo->prepare("UPDATE tasks SET task = :task WHERE id = :id AND user_id = :user_id");
        $update_stmt->execute([
            'task' => $updated_task_text,
            'id' => $task_id,
            'user_id' => $user_id
        ]);
        
        header("Location: index.php");
        exit;
    } else {
        $error = "Task cannot be empty!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Task - Modern To-Do List</title>
    <link rel="icon" href="favicon.svg" type="image/svg+xml">
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        .edit-container {
            width: 100%;
            max-width: 500px;
            background: var(--container-bg);
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            margin: auto;
        }
        .edit-header {
            margin-bottom: 24px;
            text-align: center;
        }
        .edit-form input {
            width: 100%;
            padding: 12px 16px;
            background-color: var(--input-bg);
            border: 1px solid var(--border);
            border-radius: 8px;
            font-size: 16px;
            color: var(--text-primary);
            margin-bottom: 20px;
            box-sizing: border-box;
            outline: none;
        }
        .edit-form input:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
        }
        .actions {
            display: flex;
            gap: 12px;
        }
        .btn-cancel {
            flex: 1;
            text-align: center;
            padding: 0.75rem;
            border-radius: 8px;
            text-decoration: none;
            color: var(--text-secondary);
            border: 1px solid var(--border);
            font-weight: 500;
            transition: all 0.2s ease;
        }
        .btn-cancel:hover {
            background-color: rgba(255, 255, 255, 0.05);
            color: var(--text-primary);
        }
        .btn-primary {
            flex: 2;
        }
    </style>
</head>
<body>
    <div class="edit-container">
        <header class="edit-header">
            <h1 style="font-size: 1.75rem; margin-bottom: 8px; color: var(--text-primary);">Edit Task</h1>
            <p style="color: var(--text-secondary);">Update your task content below.</p>
        </header>

        <?php if ($error): ?>
            <div class="error-msg" style="margin-bottom: 20px;"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form action="edit.php?id=<?php echo $task_id; ?>" method="POST" class="edit-form">
            <input type="text" name="task" value="<?php echo htmlspecialchars($task['task']); ?>" placeholder="Enter task..." required autofocus>
            <div class="actions">
                <a href="index.php" class="btn-cancel">Cancel</a>
                <button type="submit" class="btn-primary">Update Task</button>
            </div>
        </form>
    </div>
</body>
</html>
