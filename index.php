<?php
$page_title = 'Главная';
require_once 'includes/header.php';
?>

<div class="content-box">
    <h1>🌿 Добро пожаловать в «Плантацию Хлопка»!</h1>
    
    <p>Мы занимаемся выращиванием и продажей высококачественного хлопка с 2010 года. Наша продукция поставляется текстильным фабрикам по всему миру.</p>
    
    <h2 style="margin-top: 30px;">📊 Наша продукция</h2>
    <ul style="margin-left: 20px; line-height: 2;">
        <li><strong>Бухарский-3</strong> — средневолокнистый хлопок, устойчив к засухе</li>
        <li><strong>Андижан-38</strong> — длинноволокнистый хлопок премиум-класса</li>
        <li><strong>Наманган-77</strong> — коротковолокнистый хлопок, раннеспелый</li>
        <li><strong>Сурхан-9</strong> — средневолокнистый, высокая урожайность</li>
        <li><strong>Хорезм-12</strong> — универсальный сорт, адаптирован к местным условиям</li>
    </ul>
    
    <h2 style="margin-top: 30px;">✅ Преимущества работы с нами</h2>
    <ul style="margin-left: 20px; line-height: 2;">
        <li>Высокое качество продукции</li>
        <li>Конкурентные цены</li>
        <li>Своевременная доставка</li>
        <li>Гибкая система скидок</li>
        <li>Индивидуальный подход к каждому клиенту</li>
    </ul>
    
    <div style="margin-top: 30px; text-align: center;">
        <a href="products.php" class="btn">📋 Перейти в каталог</a>
        <a href="registration.php" class="btn btn-secondary" style="margin-left: 10px;">📝 Зарегистрироваться</a>
    </div>
</div>

<div class="content-box">
    <h2>📰 Последние новости</h2>
    <?php
    $news = getNews($pdo, 3);
    foreach ($news as $item):
    ?>
    <div class="news-item">
        <strong><?= htmlspecialchars($item['title']) ?></strong>
        <p class="news-date"><?= date('d.m.Y', strtotime($item['created_at'])) ?></p>
        <p style="color: #666; margin-top: 5px;"><?= htmlspecialchars(mb_substr($item['content'], 0, 150)) ?>...</p>
    </div>
    <?php endforeach; ?>
    <a href="news.php" class="btn" style="margin-top: 15px;">Все новости</a>
</div>

<nav>
    <a href="cotton_list.php">📋 1. Реестр договоров (многотабличный запрос + фильтры)</a>
    <a href="harvest_view.php">🌾 2. Урожай по полям и сортам</a>
    <a href="memory_table.php">💾 3. Временная таблица MEMORY (CRUD операции)</a>
    <a href="admin_login.php" style="color: #d9534f;">🔧 4. Административная панель</a>
</nav>

<?php require_once 'includes/footer.php'; ?>