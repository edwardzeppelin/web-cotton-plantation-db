<?php
session_start();
require_once 'includes/db.php';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action == 'add') {
        $product_id = (int)$_POST['product_id'];
        $product_name = $_POST['product_name'];
        $product_price = (float)$_POST['product_price'];
        $quantity = (float)$_POST['quantity'];
        
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]['quantity'] += $quantity;
        } else {
            $_SESSION['cart'][$product_id] = [
                'name' => $product_name,
                'price' => $product_price,
                'quantity' => $quantity
            ];
        }
    } elseif ($action == 'remove') {
        $product_id = (int)$_POST['product_id'];
        unset($_SESSION['cart'][$product_id]);
    } elseif ($action == 'update') {
        $product_id = (int)$_POST['product_id'];
        $quantity = (float)$_POST['quantity'];
        if ($quantity > 0) {
            $_SESSION['cart'][$product_id]['quantity'] = $quantity;
        } else {
            unset($_SESSION['cart'][$product_id]);
        }
    } elseif ($action == 'clear') {
        $_SESSION['cart'] = [];
    }
    
    header("Location: cart.php");
    exit;
}

$page_title = 'Корзина';
require_once 'includes/header.php';

$total_quantity = 0;
$total_amount = 0;
foreach ($_SESSION['cart'] as $item) {
    $total_quantity += $item['quantity'];
    $total_amount += $item['price'] * $item['quantity'];
}
?>

<div class="content-box">
    <h1>🛒 Корзина</h1>
    
    <?php if (count($_SESSION['cart']) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Товар</th>
                    <th>Цена за тонну</th>
                    <th>Количество (тонн)</th>
                    <th>Сумма</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($_SESSION['cart'] as $id => $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['name']) ?></td>
                    <td><?= number_format($item['price'], 2, ',', ' ') ?> ₽</td>
                    <td>
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="action" value="update">
                            <input type="hidden" name="product_id" value="<?= $id ?>">
                            <input type="number" name="quantity" value="<?= $item['quantity'] ?>" min="0.01" step="0.01" style="width: 80px;">
                            <button type="submit" class="btn" style="padding: 5px 10px;">OK</button>
                        </form>
                    </td>
                    <td><?= number_format($item['price'] * $item['quantity'], 2, ',', ' ') ?> ₽</td>
                    <td>
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="action" value="remove">
                            <input type="hidden" name="product_id" value="<?= $id ?>">
                            <button type="submit" class="btn btn-danger" style="padding: 5px 10px;">Удалить</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="2" style="text-align: right;">Итого:</th>
                    <th><?= number_format($total_quantity, 2, ',', ' ') ?> тонн</th>
                    <th colspan="2"><?= number_format($total_amount, 2, ',', ' ') ?> ₽</th>
                </tr>
            </tfoot>
        </table>
        
        <div style="margin-top: 20px; display: flex; gap: 10px;">
            <form method="POST" style="display: inline;">
                <input type="hidden" name="action" value="clear">
                <button type="submit" class="btn btn-danger">Очистить корзину</button>
            </form>
            <a href="order.php" class="btn" style="flex: 1; text-align: center;">Оформить заказ</a>
            <a href="products.php" class="btn btn-secondary">Продолжить покупки</a>
        </div>
    <?php else: ?>
        <p style="text-align: center; padding: 40px; color: #666;">Ваша корзина пуста</p>
        <div style="text-align: center;">
            <a href="products.php" class="btn">Перейти в каталог</a>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>