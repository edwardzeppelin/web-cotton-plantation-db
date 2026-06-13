<?php
$page_title = 'Галерея';
require_once 'includes/header.php';

$products = $pdo->query("SELECT * FROM cotton_types ORDER BY sort_name")->fetchAll();
?>

<div class="content-box">
    <h1>🖼️ Галерея продукции</h1>
    
    <div class="products-grid">
        <?php foreach ($products as $product): ?>
        <div class="product-card">
            <?php
            $img_file = 'images/cotton' . $product['id'] . '.jpg';
            $img_src = file_exists($img_file) ? $img_file : 'https://via.placeholder.com/400x300?text=' . urlencode($product['sort_name']);
            ?>
            <img src="<?= $img_src ?>" alt="<?= htmlspecialchars($product['sort_name']) ?>" style="width: 100%; height: 200px; object-fit: cover;">
            <div class="product-card-body">
                <div class="product-card-title"><?= htmlspecialchars($product['sort_name']) ?></div>
                <p style="color: #666; font-size: 14px; line-height: 1.6;"><?= htmlspecialchars($product['description'] ?? 'Описание отсутствует') ?></p>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>