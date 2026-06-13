<?php
require_once 'includes/auth.php';
require_once 'includes/functions.php';
countVisit($pdo, basename($_SERVER['PHP_SELF']));
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Плантация Хлопка - <?= $page_title ?? 'Главная' ?></title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f5f5f5; }
        .container { max-width: 1200px; margin: 0 auto; padding: 0 20px; }
        
        header { background: linear-gradient(135deg, #2c5e2e 0%, #4a9f50 100%); color: white; padding: 20px 0; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .header-content { display: flex; justify-content: space-between; align-items: center; }
        .logo { font-size: 28px; font-weight: bold; text-decoration: none; color: white; }
        .logo:hover { color: #dff0d8; }
        .user-info { display: flex; align-items: center; gap: 15px; }
        .user-info a { color: white; text-decoration: none; }
        .user-info a:hover { text-decoration: underline; }
        .cart-count { background: #d9534f; color: white; padding: 2px 8px; border-radius: 50%; font-size: 12px; }
        
        nav { background: white; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        nav ul { list-style: none; display: flex; flex-wrap: wrap; }
        nav ul li a { display: block; padding: 15px 20px; color: #2c5e2e; text-decoration: none; font-weight: 500; transition: 0.3s; }
        nav ul li a:hover { background: #4a9f50; color: white; }
        nav ul li a.active { background: #2c5e2e; color: white; }
        
        main { padding: 30px 0; min-height: 500px; }
        .content-box { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px; }
        h1 { color: #2c5e2e; margin-bottom: 20px; }
        h2 { color: #4a9f50; margin-bottom: 15px; }
        
        footer { background: #2c5e2e; color: white; padding: 30px 0; margin-top: 40px; }
        .footer-content { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 30px; }
        .footer-section h3 { margin-bottom: 15px; }
        .footer-section a { color: #dff0d8; text-decoration: none; }
        .copyright { text-align: center; padding-top: 20px; border-top: 1px solid rgba(255,255,255,0.2); margin-top: 20px; }
        
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: 500; color: #333; }
        .form-group input, .form-group select, .form-group textarea { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; }
        .btn { display: inline-block; padding: 12px 25px; background: #4a9f50; color: white; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; font-size: 14px; transition: 0.3s; }
        .btn:hover { background: #2c5e2e; }
        .btn-danger { background: #d9534f; }
        .btn-danger:hover { background: #c9302c; }
        .btn-secondary { background: #f0ad4e; }
        .btn-secondary:hover { background: #ec971f; }
        
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        table th, table td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        table th { background: #2c5e2e; color: white; }
        table tr:nth-child(even) { background: #f9f9f9; }
        
        .message { padding: 15px; margin-bottom: 20px; border-radius: 4px; }
        .message-success { background: #dff0d8; color: #3c763d; border: 1px solid #d6e9c6; }
        .message-error { background: #f2dede; color: #a94442; border: 1px solid #ebccd1; }
        
        .products-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px; }
        .product-card { background: white; border: 1px solid #ddd; border-radius: 8px; overflow: hidden; transition: 0.3s; }
        .product-card:hover { box-shadow: 0 5px 15px rgba(0,0,0,0.2); }
        .product-card img { width: 100%; height: 200px; object-fit: cover; }
        .product-card-body { padding: 15px; }
        .product-card-title { font-size: 18px; font-weight: bold; margin-bottom: 10px; color: #2c5e2e; }
        .product-card-price { font-size: 20px; color: #4a9f50; font-weight: bold; }
        
        .visit-counter { background: #f0f0f0; padding: 10px 20px; border-radius: 4px; display: inline-block; margin-top: 10px; }
        .news-item { border-left: 4px solid #4a9f50; padding-left: 15px; margin-bottom: 20px; }
        .news-date { color: #666; font-size: 14px; }
		
		.message {
			padding: 15px;
			margin-bottom: 20px;
			border-radius: 4px;
			border: 1px solid;
		}
		.message-error {
			background-color: #f2dede;
			color: #a94442;
			border-color: #ebccd1;
		}
		.message-success {
			background-color: #dff0d8;
			color: #3c763d;
			border-color: #d6e9c6;
		}
		.message-info {
			background-color: #d9edf7;
			color: #31708f;
			border-color: #bce8f1;
		}
		input.error, textarea.error, select.error {
			border: 2px solid #d9534f;
			background-color: #fff5f5;
		}
		.error-text {
			color: #d9534f;
			font-size: 12px;
			display: block;
			margin-top: 5px;
		}
		
    </style>
</head>
<body>
    <header>
        <div class="container header-content">
            <a href="index.php" class="logo">🌿 Плантация Хлопка</a>
            <div class="user-info">
                <?php if (isAuthorized()): ?>
                    <span>👤 <?= htmlspecialchars($_SESSION['client_login']) ?></span>
                    <a href="cabinet.php">Личный кабинет</a>
                    <a href="cart.php">Корзина <span class="cart-count"><?= count($_SESSION['cart'] ?? []) ?></span></a>
                    <a href="logout.php">Выход</a>
                <?php else: ?>
                    <a href="login.php">Вход</a>
                    <a href="registration.php">Регистрация</a>
                <?php endif; ?>
            </div>
        </div>
    </header>
    
    <nav>
        <div class="container">
            <ul>
                <li><a href="index.php" class="<?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '' ?>">Главная</a></li>
                <li><a href="products.php" class="<?= basename($_SERVER['PHP_SELF']) == 'products.php' ? 'active' : '' ?>">Каталог</a></li>
                <li><a href="gallery.php" class="<?= basename($_SERVER['PHP_SELF']) == 'gallery.php' ? 'active' : '' ?>">Галерея</a></li>
                <li><a href="news.php" class="<?= basename($_SERVER['PHP_SELF']) == 'news.php' ? 'active' : '' ?>">Новости</a></li>
                <li><a href="contacts.php" class="<?= basename($_SERVER['PHP_SELF']) == 'contacts.php' ? 'active' : '' ?>">Контакты</a></li>
            </ul>
        </div>
    </nav>
    
    <main class="container">