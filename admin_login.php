<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = $_POST['login'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if ($login === 'admin' && $password === 'admin123') {
        $_SESSION['admin_logged'] = true;
        $_SESSION['admin_login'] = $login;
        header("Location: admin_panel.php");
        exit();
    } else {
        $error = "Неверный логин или пароль";
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Вход для администратора</title>
    <style>
        body { font-family: sans-serif; padding: 40px; background: #f5f5f5; }
        .login-box { 
            background: white; 
            padding: 40px; 
            border-radius: 8px; 
            max-width: 400px; 
            margin: 0 auto;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 { color: #2c5e2e; margin-bottom: 30px; text-align: center; }
        input { 
            width: 100%; 
            padding: 12px; 
            margin: 10px 0; 
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button { 
            width: 100%; 
            padding: 12px; 
            background: #2c5e2e; 
            color: white; 
            border: none; 
            cursor: pointer;
            border-radius: 4px;
            font-size: 16px;
        }
        button:hover { background: #1e4220; }
        .error { 
            background: #f2dede; 
            color: #a94442; 
            padding: 10px; 
            border-radius: 4px; 
            margin-bottom: 20px;
        }
        .back-link { 
            display: block; 
            margin-top: 20px; 
            text-align: center; 
            color: #2c5e2e;
            text-decoration: none;
        }
        .back-link:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="login-box">
        <h1>🔐 Вход для администратора</h1>
        
        <?php if (isset($error)): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <input type="text" name="login" placeholder="Логин" required autofocus>
            <input type="password" name="password" placeholder="Пароль" required>
            <button type="submit">Войти</button>
        </form>
        
        <a href="index.php" class="back-link">← На главную</a>
    </div>
</body>
</html>