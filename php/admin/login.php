<?php
session_start();

define('ADMIN_USER', 'Iperalta');
define('ADMIN_PASS_HASH', '$2y$12$jc33dzqvYu/qpobdCqHOROdZrbG5bxXQ8G4CKnLhIixnUR6AMLtNO');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = isset($_POST['username']) ? trim($_POST['username']) : '';
    $pass = isset($_POST['password']) ? $_POST['password'] : '';

    if (hash_equals(ADMIN_USER, $user) && password_verify($pass, ADMIN_PASS_HASH)) {
        session_regenerate_id(true);
        $_SESSION['admin'] = true;
        header('Location: admin.php');
        exit();
    }
    $error = 'Invalid credentials';
}

if (!empty($_SESSION['admin'])) {
    header('Location: admin.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: #0a0a0a;
            color: #fff;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card {
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 16px;
            padding: 2.5rem 2rem;
            width: 100%;
            max-width: 360px;
        }
        h1 {
            font-size: 1.2rem;
            font-weight: 600;
            color: #a78bfa;
            margin-bottom: 1.75rem;
            text-align: center;
        }
        .form-group { display: flex; flex-direction: column; gap: 0.35rem; margin-bottom: 1rem; }
        label { font-size: 0.75rem; font-weight: 500; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.05em; }
        input {
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 8px;
            padding: 0.65rem 0.875rem;
            color: #fff;
            font-family: inherit;
            font-size: 0.9rem;
            transition: border-color 0.2s;
        }
        input:focus { outline: none; border-color: #7c3aed; }
        .error {
            background: rgba(220,38,38,0.15);
            border: 1px solid rgba(220,38,38,0.3);
            border-radius: 8px;
            color: #f87171;
            font-size: 0.82rem;
            padding: 0.5rem 0.75rem;
            margin-bottom: 1rem;
        }
        button {
            width: 100%;
            padding: 0.7rem;
            background: #7c3aed;
            border: none;
            border-radius: 8px;
            color: #fff;
            font-family: inherit;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
            margin-top: 0.5rem;
        }
        button:hover { background: #6d28d9; }
    </style>
</head>
<body>
    <div class="card">
        <h1>Projects Admin</h1>
        <?php if (!empty($error)): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" autocomplete="username" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" autocomplete="current-password" required>
            </div>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
