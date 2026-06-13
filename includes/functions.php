<?php

function countVisit($pdo, $page_name) {
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $stmt = $pdo->prepare("INSERT INTO visits (page_name, ip_address) VALUES (?, ?)");
    $stmt->execute([$page_name, $ip]);
}


function getVisitCount($pdo) {
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM visits");
    return $stmt->fetch()['count'];
}


function getNews($pdo, $limit = 10) {
    $stmt = $pdo->prepare("SELECT * FROM news ORDER BY created_at DESC LIMIT ?");
    $stmt->execute([$limit]);
    return $stmt->fetchAll();
}


function incrementNewsViews($pdo, $news_id) {
    $stmt = $pdo->prepare("UPDATE news SET views = views + 1 WHERE id = ?");
    $stmt->execute([$news_id]);
}


function sendEmail($to, $subject, $message) {
    error_log("Email to: $to, Subject: $subject, Message: $message");
    return true;
}
?>