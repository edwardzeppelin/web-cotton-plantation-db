<?php
$page_title = 'Новости';
require_once 'includes/header.php';

$news = getNews($pdo, 20);

if (isset($_GET['id'])) {
    $news_id = (int)$_GET['id'];
    incrementNewsViews($pdo, $news_id);
    $stmt = $pdo->prepare("SELECT * FROM news WHERE id = ?");
    $stmt->execute([$news_id]);
    $current_news = $stmt->fetch();
} else {
    $current_news = null;
}
?>

<div class="content-box">
    <h1>📰 Новостная лента</h1>
    
    <?php if ($current_news): ?>
        <div class="news-item" style="border: 1px solid #ddd; padding: 20px; margin-bottom: 30px;">
            <h2><?= htmlspecialchars($current_news['title']) ?></h2>
            <p class="news-date">
                <?= date('d.m.Y H:i', strtotime($current_news['created_at'])) ?> | 
                Автор: <?= htmlspecialchars($current_news['author'] ?? 'Не указан') ?> | 
                Просмотров: <?= $current_news['views'] ?>
            </p>
            <p style="margin-top: 15px; line-height: 1.8;"><?= nl2br(htmlspecialchars($current_news['content'])) ?></p>
            <a href="news.php" class="btn" style="margin-top: 15px;">← Все новости</a>
        </div>
    <?php else: ?>
        <?php foreach ($news as $item): ?>
        <div class="news-item">
            <h3><a href="news.php?id=<?= $item['id'] ?>" style="color: #2c5e2e; text-decoration: none;"><?= htmlspecialchars($item['title']) ?></a></h3>
            <p class="news-date"><?= date('d.m.Y H:i', strtotime($item['created_at'])) ?> | Автор: <?= htmlspecialchars($item['author'] ?? 'Не указан') ?></p>
            <p style="color: #666; margin-top: 10px;"><?= htmlspecialchars(mb_substr($item['content'], 0, 200)) ?>... <a href="news.php?id=<?= $item['id'] ?>">Читать далее</a></p>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>