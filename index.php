<?php
session_start();
require_once 'db.php';

// Redirect to login if user is not authenticated
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Handle adding a new task
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['task'])) {
    $task = trim($_POST['task']);
    
    // Prevent empty task submission
    if (!empty($task)) {
        $stmt = $pdo->prepare("INSERT INTO tasks (user_id, task) VALUES (:user_id, :task)");
        $stmt->execute(['user_id' => $_SESSION['user_id'], 'task' => $task]);
        
        // Redirect to avoid form resubmission on refresh
        header("Location: index.php");
        exit;
    } else {
        $error = "Task cannot be empty!";
    }
}

// Fetch all tasks from the database for the logged-in user
$stmt = $pdo->prepare("SELECT * FROM tasks WHERE user_id = :user_id ORDER BY created_at DESC");
$stmt->execute(['user_id' => $_SESSION['user_id']]);
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modern To-Do List</title>
    <link rel="icon" href="favicon.svg" type="image/svg+xml">
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
        <header style="text-align: left; padding: 2rem 2rem 1.5rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                <h1 style="font-size: 2.2rem; margin: 0; line-height: 1;">My Tasks</h1>
                <a href="logout.php" class="btn-logout" onmouseover="this.style.backgroundColor='rgba(239, 68, 68, 0.2)'" onmouseout="this.style.backgroundColor='rgba(239, 68, 68, 0.1)'" style="background-color: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.2); color: #ef4444; text-decoration: none; font-weight: 600; font-size: 0.95rem; padding: 0.5rem 1rem; border-radius: 8px; display: inline-flex; align-items: center; gap: 0.4rem; transition: background-color 0.2s ease;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                    Logout
                </a>
            </div>
            <p style="font-size: 1.05rem; line-height: 1.5; margin: 0; color: var(--text-secondary);">Welcome, <span style="color: var(--text-primary); font-weight: 600;"><?php echo htmlspecialchars($_SESSION['username']); ?></span> Stay organized, focused, and get things done.</p>
        </header>

        <!-- Display error message if task is empty -->
        <?php if (isset($error)): ?>
            <div class="error-msg"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <!-- Add Task Form -->
        <form action="index.php" method="POST" class="add-task-form">
            <input type="text" name="task" placeholder="What needs to be done?" autofocus>
            <button type="submit" class="btn-primary">Add Task</button>
        </form>

        <!-- Display Tasks -->
        <div class="task-list">
            <?php if (count($tasks) > 0): ?>
                <ul>
                    <?php foreach ($tasks as $task): ?>
                        <li class="task-item <?php echo $task['is_completed'] ? 'completed' : ''; ?>">
                            <div class="task-left">
                                <!-- Toggle Status Form -->
                                <form action="toggle_status.php" method="POST" class="toggle-form">
                                    <input type="hidden" name="id" value="<?php echo $task['id']; ?>">
                                    <button type="submit" class="btn-toggle" title="<?php echo $task['is_completed'] ? 'Mark as incomplete' : 'Mark as complete'; ?>">
                                        <?php if ($task['is_completed']): ?>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon-checked"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                        <?php else: ?>
                                            <div class="icon-uncheck"></div>
                                        <?php endif; ?>
                                    </button>
                                </form>
                                <span class="task-text"><?php echo htmlspecialchars($task['task']); ?></span>
                            </div>
                            <div class="task-actions">
                                <!-- Edit Task Button -->
                                <a href="edit.php?id=<?php echo $task['id']; ?>" class="btn-edit" title="Edit Task">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                </a>
                                <!-- Delete Task Form -->
                                <form action="delete.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="id" value="<?php echo $task['id']; ?>">
                                    <button type="submit" class="btn-delete" title="Delete Task">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                                    </button>
                                </form>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <div class="empty-state">
                    <p>No tasks yet. Add one above!</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
