<?php
session_start();
require_once 'includes/auth.php';

$message = '';
$message_type = '';

if (!isAuthorized()) {
    header("Location: login.php");
    exit;
}

if (empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $delivery_address = trim($_POST['delivery_address'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    
    if (empty($delivery_address) || empty($phone) || empty($email)) {
        $message = 'Заполните все обязательные поля';
        $message_type = 'error';
    } else {
        try {
            require_once 'includes/db.php';
            
            $pdo->beginTransaction();
            
            $total_amount = 0;
            foreach ($_SESSION['cart'] as $item) {
                $total_amount += $item['price'] * $item['quantity'];
            }
            
            $stmt = $pdo->prepare("INSERT INTO orders (client_id, total_amount, delivery_address, phone, email) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$_SESSION['client_id'], $total_amount, $delivery_address, $phone, $email]);
            $order_id = $pdo->lastInsertId();
            
            foreach ($_SESSION['cart'] as $product_id => $item) {
                $subtotal = $item['price'] * $item['quantity'];
                $stmt = $pdo->prepare("INSERT INTO order_items (order_id, cotton_type_id, quantity_tons, price_at_sale, subtotal) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$order_id, $product_id, $item['quantity'], $item['price'], $subtotal]);
            }
            
            $pdo->commit();
            $_SESSION['cart'] = [];
            
            $message = "Заказ #{$order_id} успешно оформлен!";
            $message_type = 'success';
            
            require_once 'includes/functions.php';
            sendEmail($email, "Заказ #{$order_id}", "Ваш заказ успешно оформлен на сумму {$total_amount} ₽");
            
        } catch (Exception $e) {
            if (isset($pdo)) {
                $pdo->rollBack();
            }
            $message = 'Ошибка при оформлении заказа: ' . $e->getMessage();
            $message_type = 'error';
        }
    }
}

$page_title = 'Оформление заказа';
require_once 'includes/header.php';

require_once 'includes/db.php';
$client = $pdo->prepare("SELECT * FROM clients WHERE id = ?");
$client->execute([$_SESSION['client_id']]);
$client = $client->fetch();

$total_quantity = 0;
$total_amount = 0;
foreach ($_SESSION['cart'] as $item) {
    $total_quantity += $item['quantity'];
    $total_amount += $item['price'] * $item['quantity'];
}
?>

<div class="content-box">
    <h1>📦 Оформление заказа</h1>
    
    <?php if ($message): ?>
        <div class="message message-<?= $message_type ?>"><?= $message ?></div>
    <?php endif; ?>
    
    <?php if ($message_type != 'success'): ?>
        <h2>📋 Состав заказа</h2>
        <table>
            <thead>
                <tr>
                    <th>Товар</th>
                    <th>Количество (тонн)</th>
                    <th>Цена за тонну</th>
                    <th>Сумма</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($_SESSION['cart'] as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['name']) ?></td>
                    <td><?= $item['quantity'] ?></td>
                    <td><?= number_format($item['price'], 2, ',', ' ') ?> ₽</td>
                    <td><?= number_format($item['price'] * $item['quantity'], 2, ',', ' ') ?> ₽</td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3" style="text-align: right;">Итого:</th>
                    <th><?= number_format($total_amount, 2, ',', ' ') ?> ₽</th>
                </tr>
            </tfoot>
        </table>
        
        <h2 style="margin-top: 30px;">📍 Данные для доставки</h2>
        <form method="POST">
            <div class="form-group">
                <label>Email *</label>
                <input type="email" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? $client['email']) ?>">
            </div>
            
            <div class="form-group">
                <label>Телефон *</label>
                <input type="text" name="phone" required value="<?= htmlspecialchars($_POST['phone'] ?? $client['phone']) ?>">
            </div>
            
            <div class="form-group">
                <label>Адрес доставки *</label>
                <textarea name="delivery_address" rows="3" required><?= htmlspecialchars($_POST['delivery_address'] ?? $client['address']) ?></textarea>
            </div>
            
            <button type="submit" class="btn">Подтвердить заказ</button>
            <a href="cart.php" class="btn btn-secondary" style="margin-left: 10px;">Назад в корзину</a>
        </form>
    <?php else: ?>
        <div style="text-align: center; padding: 40px;">
            <p style="font-size: 18px; margin-bottom: 20px;">✅ <?= $message ?></p>
            <a href="cabinet.php" class="btn">В личный кабинет</a>
            <a href="products.php" class="btn btn-secondary" style="margin-left: 10px;">В каталог</a>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>