<?php
require_once 'db.php';

// Redirect to index if already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $error = 'Please fill in all fields.';
    } else {
        // Check if username exists
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        if ($stmt->fetchColumn() > 0) {
            $error = 'Username is already taken.';
        } else {
            // Hash the password and insert user
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
            if ($stmt->execute(['username' => $username, 'password' => $hashedPassword])) {
                $success = 'Registration successful! You can now <a href="login.php" style="color:#2563eb;">login</a>.';
            } else {
                $error = 'Something went wrong. Please try again.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Modern To-Do List</title>
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
        .auth-form button[type="submit"] {
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
        .success-msg {
            background-color: #dcfce7;
            color: #166534;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        .password-container {
            position: relative;
            margin-bottom: 16px;
        }
        .password-container input {
            margin-bottom: 0 !important;
            padding-right: 45px !important;
        }
        .password-toggle {
            position: absolute;
            top: 50%;
            right: 0px;
            transform: translateY(-50%);
            height: 100%;
            width: 45px;
            background: transparent;
            border: none;
            color: #9ca3af;
            cursor: pointer;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            border-top-right-radius: 8px;
            border-bottom-right-radius: 8px;
        }
        .password-toggle:hover {
            color: #4b5563;
        }
    </style>
</head>
<body style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); font-family: 'Inter', sans-serif; min-height: 100vh; margin: 0; display: flex; align-items: center; justify-content: center;">
    <div class="auth-container">
        <header class="auth-header">
            <h2 style="font-size: 2.5rem; font-weight: 800; color: #111827; margin: 0 0 10px 0; text-shadow: 1px 1px 2px rgba(0,0,0,0.1);">Create an Account</h2>
            <p style="color: #6b7280; margin-top: 8px;">Sign up to start organizing your life</p>
        </header>

        <?php if ($error): ?>
            <div class="error-msg" style="margin-bottom: 20px;"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="success-msg"><?php echo $success; ?></div>
        <?php endif; ?>

        <form action="register.php" method="POST" class="auth-form">
            <input type="text" name="username" placeholder="Username" required autofocus>
            <div class="password-container">
                <input type="password" name="password" id="password" placeholder="Password" required>
                <button type="button" class="password-toggle" onclick="togglePassword('password', this)" title="Toggle password visibility">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                </button>
            </div>
            <button type="submit" class="btn-primary">Register</button>
        </form>
        
        <div class="auth-link">
            Already have an account? <a href="login.php">Log in</a>
        </div>
    </div>

    <script>
        function togglePassword(inputId, button) {
            const input = document.getElementById(inputId);
            
            if (input.type === 'password') {
                input.type = 'text';
                button.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>';
            } else {
                input.type = 'password';
                button.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>';
            }
        }
    </script>
</body>
</html>
