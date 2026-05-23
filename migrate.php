<?php
require_once 'db.php';

try {
    $stmt = $pdo->query("SHOW COLUMNS FROM tasks LIKE 'is_completed'");
    if (!$stmt->fetch()) {
        $pdo->exec("ALTER TABLE tasks ADD COLUMN is_completed TINYINT(1) DEFAULT 0 AFTER task");
        echo "Successfully added 'is_completed' column to 'tasks' table.\n";
    } else {
        echo "'is_completed' column already exists.\n";
    }
} catch (PDOException $e) {
    echo "Error during migration: " . $e->getMessage() . "\n";
}
?>
