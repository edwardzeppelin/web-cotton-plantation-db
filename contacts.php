<?php
session_start();
require_once 'includes/db.php';

$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message_text = trim($_POST['message'] ?? '');
    
    if (empty($name) || empty($email) || empty($subject) || empty($message_text)) {
        $message = 'Заполните все поля';
        $message_type = 'error';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = 'Некорректный email';
        $message_type = 'error';
    } else {

        $email_body = "Новое сообщение с сайта!\n\n";
        $email_body .= "Имя: $name\n";
        $email_body .= "Email: $email\n";
        $email_body .= "Тема: $subject\n\n";
        $email_body .= "Сообщение:\n$message_text\n\n";
        $email_body .= "Дата: " . date('d.m.Y H:i') . "\n";
        $email_body .= "IP: " . ($_SERVER['REMOTE_ADDR'] ?? 'unknown') . "\n";
        
        $headers = "From: $email\r\n";
        $headers .= "Reply-To: $email\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
        
        if (mail('info@cotton-plantation.uz', "Обратная связь: $subject", $email_body, $headers)) {
            $message = '✅ Сообщение отправлено! Мы свяжемся с вами в ближайшее время.';
            $message_type = 'success';

            $stmt = $pdo->prepare("INSERT INTO logs (action, log_date) VALUES (?, NOW())");
            $stmt->execute(["Отправлено письмо от $email: $subject"]);
        } else {
            $message = '❌ Ошибка при отправке сообщения';
            $message_type = 'error';
        }
    }
}

$page_title = 'Контакты';
require_once 'includes/header.php';
?>

<div class="content-box">
    <h1>📞 Контакты</h1>
    
    <?php if ($message): ?>
        <div class="message message-<?= $message_type ?>"><?= $message ?></div>
    <?php endif; ?>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px; margin-bottom: 30px;">
        <div>
            <h2>📍 Адрес</h2>
            <p>г. Ташкент, ул. Хлопковая, 15</p>
            
            <h2 style="margin-top: 20px;">📞 Телефоны</h2>
            <p>+998-71-123-45-67 (офис)</p>
            <p>+998-90-123-45-67 (менеджер)</p>
            
            <h2 style="margin-top: 20px;">✉️ Email</h2>
            <p>info@cotton-plantation.uz</p>
        </div>
        
        <div>
            <h2>🕒 Режим работы</h2>
            <p>Пн-Пт: 9:00 - 18:00</p>
            <p>Сб: 10:00 - 15:00</p>
            <p>Вс: Выходной</p>
        </div>
    </div>
    
    <h2>📩 Написать нам</h2>
    <form method="POST">
        <div class="form-group">
            <label>Ваше имя *</label>
            <input type="text" name="name" required value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
        </div>
        
        <div class="form-group">
            <label>Email *</label>
            <input type="email" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
        </div>
        
        <div class="form-group">
            <label>Тема *</label>
            <input type="text" name="subject" required value="<?= htmlspecialchars($_POST['subject'] ?? '') ?>">
        </div>
        
        <div class="form-group">
            <label>Сообщение *</label>
            <textarea name="message" rows="5" required><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>
        </div>
        
        <button type="submit" class="btn">Отправить сообщение</button>
    </form>
</div>

<?php require_once 'includes/footer.php'; ?>