<?php
require_once 'db.php';

// Redirect to index if already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $error = 'Please fill in all fields.';
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // Password is correct, start a session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            
            header("Location: index.php");
            exit;
        } else {
            $error = 'Invalid username or password.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Modern To-Do List</title>
    <link rel="icon" href="favicon.svg" type="image/svg+xml">
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        .auth-container {
            width: 100%;
            max-width: 400px;
            background: rgba(255, 255, 255, 0.95);
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(10px);
            margin: auto;
        }
        .auth-header {
            text-align: center;
            margin-bottom: 24px;
        }
        .auth-form input {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.2s;
            margin-bottom: 16px;
            box-sizing: border-box;
        }
        .auth-form input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        .auth-form button {
            width: 100%;
        }
        .auth-link {
            display: block;
            text-align: center;
            margin-top: 16px;
            font-size: 14px;
            color: #6b7280;
        }
        .auth-link a {
            color: #3b82f6;
            text-decoration: none;
        }
        .auth-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); font-family: 'Inter', sans-serif; min-height: 100vh; margin: 0; display: flex; align-items: center; justify-content: center;">
    <div class="auth-container">
        <header class="auth-header">
            <h2 style="font-size: 2.5rem; font-weight: 800; color: #111827; margin: 0 0 10px 0; text-shadow: 1px 1px 2px rgba(0,0,0,0.1);">Welcome Back</h2>
            <p style="color: #6b7280; margin-top: 8px;">Log in to manage your tasks</p>
        </header>

        <?php if ($error): ?>
            <div class="error-msg" style="margin-bottom: 20px;"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form action="login.php" method="POST" class="auth-form">
            <input type="text" name="username" placeholder="Username" required autofocus>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" class="btn-primary">Log In</button>
        </form>
        
        <div class="auth-link">
            Don't have an account? <a href="register.php">Sign up</a>
        </div>
    </div>
</body>
</html>
