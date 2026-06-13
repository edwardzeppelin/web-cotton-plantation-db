<?php
session_start();
require_once 'includes/auth.php';

$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($login) || empty($password)) {
        $message = 'Введите логин и пароль';
        $message_type = 'error';
    } else {
        $result = loginClient($login, $password);
        if ($result['success']) {
            header("Location: cabinet.php");
            exit;
        } else {
            $message = $result['message'];
            $message_type = 'error';
        }
    }
}

if (isAuthorized()) {
    header("Location: cabinet.php");
    exit;
}

$page_title = 'Вход';
require_once 'includes/header.php';
?>

<div class="content-box">
    <h1>🔐 Вход в систему</h1>
    
    <?php if ($message): ?>
        <div class="message message-<?= $message_type ?>"><?= $message ?></div>
    <?php endif; ?>
    
    <form method="POST">
        <div class="form-group">
            <label>Логин</label>
            <input type="text" name="login" required value="<?= htmlspecialchars($_POST['login'] ?? '') ?>">
        </div>
        
        <div class="form-group">
            <label>Пароль</label>
            <input type="password" name="password" required>
        </div>
        
        <button type="submit" class="btn">Войти</button>
        <a href="registration.php" class="btn btn-secondary" style="margin-left: 10px;">Регистрация</a>
    </form>
    
    <div style="margin-top: 20px; padding: 15px; background: #f0f0f0; border-radius: 4px;">
        <strong>Тестовые данные:</strong><br>
        Логин: <code>test</code><br>
        Пароль: <code>password</code>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>