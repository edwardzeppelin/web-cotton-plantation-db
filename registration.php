<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/error_handler.php';

$errorHandler = new ErrorHandler();
$dbHandler = new DatabaseExceptionHandler($errorHandler);

if (!isset($_SESSION['captcha_result'])) {
    $num1 = rand(1, 10);
    $num2 = rand(1, 10);
    $_SESSION['captcha_result'] = $num1 + $num2;
    $_SESSION['captcha_text'] = "$num1 + $num2";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $login = trim($_POST['login'] ?? '');
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';
    $company_name = trim($_POST['company_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $captcha_input = $_POST['captcha'] ?? '';
    
    $errorHandler->validateString($login, 'Логин', 3, 50);
    $errorHandler->validatePassword($password, $password_confirm);
    $errorHandler->validateString($company_name, 'Название компании', 2, 200);
    $errorHandler->validateEmail($email);
    $errorHandler->validatePhone($phone, false);
    $errorHandler->validateCaptcha($captcha_input, $_SESSION['captcha_result']);
    
    if (preg_match('/[\'";\\\\]/', $login . $company_name . $email)) {
        $errorHandler->addError('security', 'Обнаружены подозрительные символы в данных');
        $errorHandler->logError("Possible SQL injection attempt: login=$login, email=$email", 'SECURITY');
    }
    
    if (!$errorHandler->hasErrors()) {

        $result = $dbHandler->safeQuery($pdo, "SELECT id FROM clients WHERE login = ? OR email = ?", [$login, $email]);
        
        if ($result['success']) {
            if ($result['stmt']->fetch()) {
                $errorHandler->addError('login', 'Этот логин или email уже зарегистрирован');
            } else {

                $password_hash = password_hash($password, PASSWORD_DEFAULT);
                

                $insertResult = $dbHandler->safeInsert($pdo, 'clients', [
                    'login' => $login,
                    'password' => $password_hash,
                    'company_name' => $company_name,
                    'email' => $email,
                    'phone' => $phone,
                    'address' => $address,
                    'register_date' => date('Y-m-d H:i:s')
                ]);
                
                if ($insertResult['success']) {
                    $errorHandler->addSuccess('✅ Регистрация успешна! <a href="login.php">Войти</a>');
                    $errorHandler->logError("New user registered: login=$login, email=$email", 'USER');
                    
                    $num1 = rand(1, 10);
                    $num2 = rand(1, 10);
                    $_SESSION['captcha_result'] = $num1 + $num2;
                    $_SESSION['captcha_text'] = "$num1 + $num2";
                    
                    $_POST = [];
                } else {
                    $errorHandler->addError('register', $insertResult['error']);
                }
            }
        } else {
            $errorHandler->addError('database', $result['error']);
        }
    } else {

        $num1 = rand(1, 10);
        $num2 = rand(1, 10);
        $_SESSION['captcha_result'] = $num1 + $num2;
        $_SESSION['captcha_text'] = "$num1 + $num2";
    }
}

$page_title = 'Регистрация';
require_once 'includes/header.php';
?>

<div class="content-box">
    <h1>📝 Регистрация клиента</h1>
    
    <?php 
    $errorHandler->displayErrors();
    $errorHandler->displaySuccess();
    ?>
    
    <form method="POST" style="max-width: 600px;">
        <div class="form-group">
            <label>Логин *</label>
            <input type="text" 
                   name="login" 
                   value="<?= htmlspecialchars($_POST['login'] ?? '') ?>" 
                   class="<?= $errorHandler->getError('login') ? 'error' : '' ?>"
                   required>
            <?php if ($errorHandler->getError('login')): ?>
                <small class="error-text"><?= $errorHandler->getError('login') ?></small>
            <?php endif; ?>
        </div>
        
        <div class="form-group">
            <label>Пароль *</label>
            <input type="password" 
                   name="password" 
                   class="<?= $errorHandler->getError('password') ? 'error' : '' ?>"
                   required>
            <?php if ($errorHandler->getError('password')): ?>
                <small class="error-text"><?= $errorHandler->getError('password') ?></small>
            <?php endif; ?>
            <small style="color: #666;">Минимум 6 символов</small>
        </div>
        
        <div class="form-group">
            <label>Подтверждение пароля *</label>
            <input type="password" 
                   name="password_confirm" 
                   class="<?= $errorHandler->getError('password_confirm') ? 'error' : '' ?>"
                   required>
            <?php if ($errorHandler->getError('password_confirm')): ?>
                <small class="error-text"><?= $errorHandler->getError('password_confirm') ?></small>
            <?php endif; ?>
        </div>
        
        <div class="form-group">
            <label>Название компании *</label>
            <input type="text" 
                   name="company_name" 
                   value="<?= htmlspecialchars($_POST['company_name'] ?? '') ?>"
                   class="<?= $errorHandler->getError('company_name') ? 'error' : '' ?>"
                   required>
            <?php if ($errorHandler->getError('company_name')): ?>
                <small class="error-text"><?= $errorHandler->getError('company_name') ?></small>
            <?php endif; ?>
        </div>
        
        <div class="form-group">
            <label>Email *</label>
            <input type="email" 
                   name="email" 
                   value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                   class="<?= $errorHandler->getError('email') ? 'error' : '' ?>"
                   required>
            <?php if ($errorHandler->getError('email')): ?>
                <small class="error-text"><?= $errorHandler->getError('email') ?></small>
            <?php endif; ?>
        </div>
        
        <div class="form-group">
            <label>Телефон</label>
            <input type="text" 
                   name="phone" 
                   value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>"
                   class="<?= $errorHandler->getError('phone') ? 'error' : '' ?>"
                   placeholder="+7 (999) 123-45-67">
            <?php if ($errorHandler->getError('phone')): ?>
                <small class="error-text"><?= $errorHandler->getError('phone') ?></small>
            <?php endif; ?>
        </div>
        
        <div class="form-group">
            <label>Адрес</label>
            <textarea name="address" rows="3"><?= htmlspecialchars($_POST['address'] ?? '') ?></textarea>
        </div>
        
        <div class="form-group" style="background: #f0f0f0; padding: 15px; border-radius: 4px;">
            <label>🤔 Решите пример: <strong style="font-size: 18px;"><?= $_SESSION['captcha_text'] ?></strong> = *</label>
            <input type="number" 
                   name="captcha" 
                   required 
                   style="width: 100px;"
                   class="<?= $errorHandler->getError('captcha') ? 'error' : '' ?>">
            <?php if ($errorHandler->getError('captcha')): ?>
                <small class="error-text"><?= $errorHandler->getError('captcha') ?></small>
            <?php endif; ?>
            <br><small style="color: #666;">Это защита от роботов</small>
        </div>
        
        <button type="submit" class="btn">Зарегистрироваться</button>
        <a href="login.php" class="btn btn-secondary" style="margin-left: 10px;">Уже есть аккаунт?</a>
    </form>
</div>

<style>
input.error, textarea.error {
    border: 2px solid #d9534f !important;
    background-color: #fff5f5;
}
.error-text {
    color: #d9534f;
    font-size: 12px;
    display: block;
    margin-top: 5px;
}
.message-error ul {
    margin: 0;
    padding-left: 20px;
}
.message-error li {
    margin: 5px 0;
}
</style>

<?php require_once 'includes/footer.php'; ?>