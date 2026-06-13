<?php
session_start();
require_once 'includes/auth.php';

$message = '';
$message_type = '';

requireAuth();

$client_id = $_SESSION['client_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] == 'update_profile') {
        $company_name = trim($_POST['company_name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $address = trim($_POST['address'] ?? '');
        
        if (empty($company_name) || empty($email)) {
            $message = 'Заполните обязательные поля';
            $message_type = 'error';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $message = 'Некорректный email';
            $message_type = 'error';
        } else {
            $stmt = $pdo->prepare("UPDATE clients SET company_name = ?, email = ?, phone = ?, address = ? WHERE id = ?");
            $stmt->execute([$company_name, $email, $phone, $address, $client_id]);
            
            // Обновляем сессию
            $_SESSION['client_name'] = $company_name;
            $_SESSION['client_email'] = $email;
            
            $message = '✅ Данные обновлены!';
            $message_type = 'success';
        }
    } elseif ($_POST['action'] == 'change_password') {
        $old_password = $_POST['old_password'] ?? '';
        $new_password = $_POST['new_password'] ?? '';
        $new_password_confirm = $_POST['new_password_confirm'] ?? '';
        
        // Получаем текущий пароль
        $stmt = $pdo->prepare("SELECT password FROM clients WHERE id = ?");
        $stmt->execute([$client_id]);
        $client = $stmt->fetch();
        
        if (!password_verify($old_password, $client['password'])) {
            $message = 'Неверный текущий пароль';
            $message_type = 'error';
        } elseif (strlen($new_password) < 6) {
            $message = 'Пароль должен быть не менее 6 символов';
            $message_type = 'error';
        } elseif ($new_password !== $new_password_confirm) {
            $message = 'Пароли не совпадают';
            $message_type = 'error';
        } else {
            $password_hash = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE clients SET password = ? WHERE id = ?");
            $stmt->execute([$password_hash, $client_id]);
            $message = '✅ Пароль изменён!';
            $message_type = 'success';
        }
    }
}

$stmt = $pdo->prepare("SELECT * FROM clients WHERE id = ?");
$stmt->execute([$client_id]);
$client = $stmt->fetch();

$stmt = $pdo->prepare("
    SELECT o.*, COUNT(oi.id) as items_count 
    FROM orders o 
    LEFT JOIN order_items oi ON o.id = oi.order_id 
    WHERE o.client_id = ? 
    GROUP BY o.id 
    ORDER BY o.order_date DESC
");
$stmt->execute([$client_id]);
$orders = $stmt->fetchAll();

$page_title = 'Личный кабинет';
require_once 'includes/header.php';
?>

<div class="content-box">
    <h1>👤 Личный кабинет</h1>
    
    <?php if ($message): ?>
        <div class="message message-<?= $message_type ?>"><?= $message ?></div>
    <?php endif; ?>

    <h2>📋 Редактирование данных</h2>
    <form method="POST" style="margin-bottom: 30px;">
        <input type="hidden" name="action" value="update_profile">
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
            <div class="form-group">
                <label>Логин</label>
                <input type="text" value="<?= htmlspecialchars($client['login']) ?>" disabled style="background: #f0f0f0;">
                <small style="color: #666;">Логин изменить нельзя</small>
            </div>
            
            <div class="form-group">
                <label>Название компании *</label>
                <input type="text" name="company_name" required value="<?= htmlspecialchars($client['company_name']) ?>">
            </div>
            
            <div class="form-group">
                <label>Email *</label>
                <input type="email" name="email" required value="<?= htmlspecialchars($client['email']) ?>">
            </div>
            
            <div class="form-group">
                <label>Телефон</label>
                <input type="text" name="phone" value="<?= htmlspecialchars($client['phone'] ?? '') ?>">
            </div>
            
            <div class="form-group" style="grid-column: 1 / -1;">
                <label>Адрес</label>
                <textarea name="address" rows="3"><?= htmlspecialchars($client['address'] ?? '') ?></textarea>
            </div>
        </div>
        
        <button type="submit" class="btn">💾 Сохранить изменения</button>
    </form>
    
    <h2>🔐 Смена пароля</h2>
    <form method="POST" style="margin-bottom: 30px; max-width: 500px;">
        <input type="hidden" name="action" value="change_password">
        
        <div class="form-group">
            <label>Текущий пароль *</label>
            <input type="password" name="old_password" required>
        </div>
        
        <div class="form-group">
            <label>Новый пароль *</label>
            <input type="password" name="new_password" required>
        </div>
        
        <div class="form-group">
            <label>Подтверждение нового пароля *</label>
            <input type="password" name="new_password_confirm" required>
        </div>
        
        <button type="submit" class="btn btn-secondary">🔑 Изменить пароль</button>
    </form>
    
    <h2>📊 Информация об аккаунте</h2>
    <table style="max-width: 600px;">
        <tr>
            <th>Дата регистрации</th>
            <td><?= date('d.m.Y H:i', strtotime($client['register_date'])) ?></td>
        </tr>
        <tr>
            <th>Последний вход</th>
            <td><?= $client['last_login'] ? date('d.m.Y H:i', strtotime($client['last_login'])) : '—' ?></td>
        </tr>
    </table>
    
    <h2 style="margin-top: 30px;">📦 Мои заказы</h2>
    <?php if (count($orders) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>№ Заказа</th>
                    <th>Дата</th>
                    <th>Товаров</th>
                    <th>Сумма</th>
                    <th>Статус</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                <tr>
                    <td>#<?= $order['id'] ?></td>
                    <td><?= date('d.m.Y H:i', strtotime($order['order_date'])) ?></td>
                    <td><?= $order['items_count'] ?></td>
                    <td><?= number_format($order['total_amount'], 2, ',', ' ') ?> ₽</td>
                    <td>
                        <?php
                        $status_colors = ['new' => '#f0ad4e', 'processing' => '#5bc0de', 'completed' => '#4a9f50', 'cancelled' => '#d9534f'];
                        $status_names = ['new' => 'Новый', 'processing' => 'В обработке', 'completed' => 'Выполнен', 'cancelled' => 'Отменён'];
                        ?>
                        <span style="background: <?= $status_colors[$order['status']] ?>; color: white; padding: 3px 10px; border-radius: 3px;">
                            <?= $status_names[$order['status']] ?>
                        </span>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>У вас пока нет заказов. <a href="products.php">Перейти в каталог</a></p>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>