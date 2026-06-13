<?php
$page_title = 'Каталог продукции';
require_once 'includes/header.php';

$search = trim($_GET['search'] ?? '');
$min_price = $_GET['min_price'] ?? '';
$max_price = $_GET['max_price'] ?? '';
$sort = $_GET['sort'] ?? 'name';

$sql = "SELECT * FROM cotton_types WHERE 1=1";
$params = [];

if ($search) {
    $sql .= " AND (sort_name LIKE ? OR description LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if ($min_price !== '') {
    $sql .= " AND price_per_ton >= ?";
    $params[] = $min_price;
}

if ($max_price !== '') {
    $sql .= " AND price_per_ton <= ?";
    $params[] = $max_price;
}

$allowed_sorts = ['name', 'price_asc', 'price_desc', 'length'];
if (!in_array($sort, $allowed_sorts)) $sort = 'name';

switch ($sort) {
    case 'price_asc': $sql .= " ORDER BY price_per_ton ASC"; break;
    case 'price_desc': $sql .= " ORDER BY price_per_ton DESC"; break;
    case 'length': $sql .= " ORDER BY fiber_length DESC"; break;
    default: $sql .= " ORDER BY sort_name ASC";
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();
?>

<div class="content-box">
    <h1>📋 Каталог продукции</h1>
    
    <form method="GET" style="background: #f9f9f9; padding: 20px; border-radius: 4px; margin-bottom: 20px;">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
            <div>
                <label>Поиск</label>
                <input type="text" name="search" placeholder="Название сорта..." value="<?= htmlspecialchars($search) ?>">
            </div>
            <div>
                <label>Мин. цена (₽)</label>
                <input type="number" name="min_price" placeholder="0" value="<?= htmlspecialchars($min_price) ?>">
            </div>
            <div>
                <label>Макс. цена (₽)</label>
                <input type="number" name="max_price" placeholder="1000000" value="<?= htmlspecialchars($max_price) ?>">
            </div>
            <div>
                <label>Сортировка</label>
                <select name="sort">
                    <option value="name" <?= $sort == 'name' ? 'selected' : '' ?>>По названию</option>
                    <option value="price_asc" <?= $sort == 'price_asc' ? 'selected' : '' ?>>Цена (возрастание)</option>
                    <option value="price_desc" <?= $sort == 'price_desc' ? 'selected' : '' ?>>Цена (убывание)</option>
                    <option value="length" <?= $sort == 'length' ? 'selected' : '' ?>>Длина волокна</option>
                </select>
            </div>
        </div>
        <button type="submit" class="btn" style="margin-top: 15px;">Применить фильтры</button>
        <a href="products.php" class="btn btn-secondary" style="margin-top: 15px;">Сбросить</a>
    </form>
    
    <div class="products-grid">
        <?php foreach ($products as $product): ?>
        <div class="product-card">
            <img src="images/cotton<?= $product['id'] ?>.jpg" alt="<?= htmlspecialchars($product['sort_name']) ?>" style="width: 100%; height: 200px; object-fit: cover;">
            <div class="product-card-body">
                <div class="product-card-title"><?= htmlspecialchars($product['sort_name']) ?></div>
                <p style="color: #666; font-size: 14px; margin-bottom: 10px;"><?= htmlspecialchars($product['description']) ?></p>
                <p><strong>Длина волокна:</strong> <?= $product['fiber_length'] ?> мм</p>
                <div class="product-card-price"><?= number_format($product['price_per_ton'], 0, ',', ' ') ?> ₽/тонна</div>
                
                <?php if (isAuthorized()): ?>
                    <form method="POST" action="cart.php" style="margin-top: 15px;">
                        <input type="hidden" name="action" value="add">
                        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                        <input type="hidden" name="product_name" value="<?= htmlspecialchars($product['sort_name']) ?>">
                        <input type="hidden" name="product_price" value="<?= $product['price_per_ton'] ?>">
                        <div style="display: flex; gap: 10px; margin-top: 10px;">
                            <input type="number" name="quantity" value="1" min="0.01" step="0.01" style="width: 80px;">
                            <button type="submit" class="btn" style="flex: 1;">В корзину</button>
                        </div>
                    </form>
                <?php else: ?>
                    <a href="login.php" class="btn" style="margin-top: 15px; display: block; text-align: center;">Войдите для заказа</a>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>