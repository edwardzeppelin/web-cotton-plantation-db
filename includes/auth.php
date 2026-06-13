<?php
session_start();
require_once 'db.php';


function registerClient($login, $password, $company_name, $email, $phone = '', $address = '') {
    global $pdo;
    
    $stmt = $pdo->prepare("SELECT id FROM clients WHERE login = ?");
    $stmt->execute([$login]);
    if ($stmt->fetch()) {
        return ['success' => false, 'message' => 'Логин уже занят'];
    }
    
    $stmt = $pdo->prepare("SELECT id FROM clients WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        return ['success' => false, 'message' => 'Email уже зарегистрирован'];
    }
    
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    
    $stmt = $pdo->prepare("INSERT INTO clients (login, password, company_name, email, phone, address) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$login, $password_hash, $company_name, $email, $phone, $address]);
    
    return ['success' => true, 'message' => 'Регистрация успешна'];
}

function loginClient($login, $password) {
    global $pdo;
    
    $stmt = $pdo->prepare("SELECT * FROM clients WHERE login = ?");
    $stmt->execute([$login]);
    $client = $stmt->fetch();
    
    if ($client && password_verify($password, $client['password'])) {
        $stmt = $pdo->prepare("UPDATE clients SET last_login = NOW() WHERE id = ?");
        $stmt->execute([$client['id']]);
        
        $_SESSION['client_id'] = $client['id'];
        $_SESSION['client_login'] = $client['login'];
        $_SESSION['client_email'] = $client['email'];
        $_SESSION['client_name'] = $client['company_name'];
        
        return ['success' => true, 'message' => 'Вход выполнен'];
    }
    
    return ['success' => false, 'message' => 'Неверный логин или пароль'];
}

function logoutClient() {
    session_destroy();
    header("Location: index.php");
    exit;
}

function isAuthorized() {
    return isset($_SESSION['client_id']);
}


function requireAuth() {
    if (!isAuthorized()) {
        header("Location: login.php");
        exit;
    }
}
?>