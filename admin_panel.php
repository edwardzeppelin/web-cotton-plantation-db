<?php
session_start();
require_once 'includes/db.php';

if (!isset($_SESSION['admin_logged']) || $_SESSION['admin_logged'] !== true) {
    header("Location: admin_login.php");
    exit();
}

$message = '';
$message_type = '';
$current_tab = $_GET['tab'] ?? 'orders';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    
    if ($_POST['action'] == 'edit_order') {
        $_SESSION['edit_order_id'] = (int)$_POST['order_id'];
        $current_tab = 'orders';
    }
    
    elseif ($_POST['action'] == 'save_order') {
        $order_id = (int)$_POST['order_id'];
        $total_amount = (float)$_POST['total_amount'];
        $status = $_POST['status'];
        
        try {
            $stmt = $pdo->prepare("UPDATE orders SET total_amount = ?, status = ? WHERE id = ?");
            $stmt->execute([$total_amount, $status, $order_id]);
            $message = "✅ Заказ #$order_id обновлен";
            $message_type = 'success';
            unset($_SESSION['edit_order_id']);
        } catch (PDOException $e) {
            $message = "❌ Ошибка: " . $e->getMessage();
            $message_type = 'error';
        }
        $current_tab = 'orders';
    }
    
    elseif ($_POST['action'] == 'cancel_edit_order') {
        unset($_SESSION['edit_order_id']);
        $current_tab = 'orders';
    }
    
    elseif ($_POST['action'] == 'add_client') {
        $company_name = trim($_POST['company_name'] ?? '');
        $contact_person = trim($_POST['contact_person'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $address = trim($_POST['address'] ?? '');
        
        if (empty($company_name)) {
            $message = "❌ Название компании обязательно для заполнения";
            $message_type = 'error';
        } elseif (empty($email)) {
            $message = "❌ Email обязателен для заполнения";
            $message_type = 'error';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $message = "❌ Неверный формат email";
            $message_type = 'error';
        } else {
            try {
                $stmt = $pdo->prepare("INSERT INTO clients (company_name, contact_person, phone, email, address) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$company_name, $contact_person, $phone, $email, $address]);
                $message = "✅ Клиент добавлен";
                $message_type = 'success';
            } catch (PDOException $e) {
                $message = "❌ Ошибка: " . $e->getMessage();
                $message_type = 'error';
            }
        }
        $current_tab = 'clients';
    }
    
    elseif ($_POST['action'] == 'edit_client') {
        $client_id = (int)$_POST['client_id'];
        $company_name = trim($_POST['company_name'] ?? '');
        $contact_person = trim($_POST['contact_person'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $address = trim($_POST['address'] ?? '');
        
        if (empty($company_name)) {
            $message = "❌ Название компании обязательно для заполнения";
            $message_type = 'error';
        } elseif (empty($email)) {
            $message = "❌ Email обязателен для заполнения";
            $message_type = 'error';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $message = "❌ Неверный формат email";
            $message_type = 'error';
        } else {
            try {
                $stmt = $pdo->prepare("UPDATE clients SET company_name = ?, contact_person = ?, phone = ?, email = ?, address = ? WHERE id = ?");
                $stmt->execute([$company_name, $contact_person, $phone, $email, $address, $client_id]);
                $message = "✅ Клиент обновлен";
                $message_type = 'success';
            } catch (PDOException $e) {
                $message = "❌ Ошибка: " . $e->getMessage();
                $message_type = 'error';
            }
        }
        $current_tab = 'clients';
    }
    
    elseif ($_POST['action'] == 'delete_client') {
        $client_id = (int)$_POST['client_id'];
        
        try {
            $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM orders WHERE client_id = ?");
            $stmt->execute([$client_id]);
            $result = $stmt->fetch();
            
            if ($result['count'] > 0) {
                $message = "❌ Нельзя удалить клиента: у него есть заказы (#" . $result['count'] . "). Сначала удалите заказы.";
                $message_type = 'error';
            } else {
                $stmt = $pdo->prepare("DELETE FROM clients WHERE id = ?");
                $stmt->execute([$client_id]);
                $message = "✅ Клиент удален";
                $message_type = 'success';
            }
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $message = "❌ Нельзя удалить: есть связанные записи в других таблицах";
                $message_type = 'error';
            } else {
                $message = "❌ Ошибка: " . $e->getMessage();
                $message_type = 'error';
            }
        }
        $current_tab = 'clients';
    }
    
    elseif ($_POST['action'] == 'add_cotton_type') {
        $sort_name = trim($_POST['sort_name'] ?? '');
        $fiber_length = $_POST['fiber_length'] ?? null;
        $price_per_ton = $_POST['price_per_ton'] ?? null;
        $description = trim($_POST['description'] ?? '');
        
        if (empty($sort_name)) {
            $message = "❌ Название сорта обязательно для заполнения";
            $message_type = 'error';
        } else {
            try {
                $stmt = $pdo->prepare("INSERT INTO cotton_types (sort_name, fiber_length, price_per_ton, description) VALUES (?, ?, ?, ?)");
                $stmt->execute([$sort_name, $fiber_length, $price_per_ton, $description]);
                $message = "✅ Сорт хлопка добавлен";
                $message_type = 'success';
            } catch (PDOException $e) {
                $message = "❌ Ошибка: " . $e->getMessage();
                $message_type = 'error';
            }
        }
        $current_tab = 'cotton';
    }
    
    elseif ($_POST['action'] == 'edit_cotton_type') {
        $cotton_id = (int)$_POST['cotton_id'];
        $sort_name = trim($_POST['sort_name'] ?? '');
        $fiber_length = $_POST['fiber_length'] ?? null;
        $price_per_ton = $_POST['price_per_ton'] ?? null;
        $description = trim($_POST['description'] ?? '');
        
        if (empty($sort_name)) {
            $message = "❌ Название сорта обязательно для заполнения";
            $message_type = 'error';
        } else {
            try {
                $stmt = $pdo->prepare("UPDATE cotton_types SET sort_name = ?, fiber_length = ?, price_per_ton = ?, description = ? WHERE id = ?");
                $stmt->execute([$sort_name, $fiber_length, $price_per_ton, $description, $cotton_id]);
                $message = "✅ Сорт хлопка обновлен";
                $message_type = 'success';
            } catch (PDOException $e) {
                $message = "❌ Ошибка: " . $e->getMessage();
                $message_type = 'error';
            }
        }
        $current_tab = 'cotton';
    }
    
    elseif ($_POST['action'] == 'delete_cotton_type') {
        $cotton_id = (int)$_POST['cotton_id'];
        
        try {
            $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM harvest WHERE cotton_type_id = ?");
            $stmt->execute([$cotton_id]);
            $result = $stmt->fetch();
            
            if ($result['count'] > 0) {
                $message = "❌ Нельзя удалить сорт: есть записи об урожае (#" . $result['count'] . "). Сначала удалите урожай.";
                $message_type = 'error';
            } else {
                $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM contract_items WHERE cotton_type_id = ?");
                $stmt->execute([$cotton_id]);
                $result = $stmt->fetch();
                
                if ($result['count'] > 0) {
                    $message = "❌ Нельзя удалить сорт: есть позиции в договорах (#" . $result['count'] . "). Сначала удалите позиции.";
                    $message_type = 'error';
                } else {
                    $stmt = $pdo->prepare("DELETE FROM cotton_types WHERE id = ?");
                    $stmt->execute([$cotton_id]);
                    $message = "✅ Сорт хлопка удален";
                    $message_type = 'success';
                }
            }
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $message = "❌ Нельзя удалить: есть связанные записи в других таблицах";
                $message_type = 'error';
            } else {
                $message = "❌ Ошибка: " . $e->getMessage();
                $message_type = 'error';
            }
        }
        $current_tab = 'cotton';
    }
    
    elseif ($_POST['action'] == 'bulk_increase_price') {
        $percentage = (float)($_POST['percentage'] ?? 0);
        $selected_ids = $_POST['selected_cotton'] ?? [];
        
        if (empty($selected_ids)) {
            $message = "❌ Выберите хотя бы один сорт для изменения цены";
            $message_type = 'error';
        } elseif ($percentage <= 0) {
            $message = "❌ Процент увеличения должен быть больше 0";
            $message_type = 'error';
        } else {
            try {
                $pdo->beginTransaction();
                $count = 0;
                foreach ($selected_ids as $cotton_id) {
                    $cotton_id = (int)$cotton_id;
                    $stmt = $pdo->prepare("UPDATE cotton_types SET price_per_ton = price_per_ton * (1 + ?/100) WHERE id = ?");
                    $stmt->execute([$percentage, $cotton_id]);
                    $count += $stmt->rowCount();
                }
                $pdo->commit();
                $message = "✅ Цена увеличена на {$percentage}% для {$count} сорт(ов)";
                $message_type = 'success';
            } catch (PDOException $e) {
                $pdo->rollBack();
                $message = "❌ Ошибка: " . $e->getMessage();
                $message_type = 'error';
            }
        }
        $current_tab = 'cotton';
    }
    
    elseif ($_POST['action'] == 'delete_order') {
        $order_id = (int)$_POST['order_id'];
        
        try {
            $pdo->beginTransaction();
            $stmt = $pdo->prepare("DELETE FROM order_items WHERE order_id = ?");
            $stmt->execute([$order_id]);
            $stmt = $pdo->prepare("DELETE FROM orders WHERE id = ?");
            $stmt->execute([$order_id]);
            $pdo->commit();
            $message = "✅ Заказ #$order_id удален";
            $message_type = 'success';
            unset($_SESSION['edit_order_id']);
        } catch (PDOException $e) {
            $pdo->rollBack();
            $message = "❌ Ошибка: " . $e->getMessage();
            $message_type = 'error';
        }
        $current_tab = 'orders';
    }
}

$all_clients = $pdo->query("SELECT * FROM clients ORDER BY company_name")->fetchAll();
$all_cotton_types = $pdo->query("SELECT * FROM cotton_types ORDER BY sort_name")->fetchAll();
$all_orders = $pdo->query("
    SELECT o.*, c.company_name 
    FROM orders o 
    LEFT JOIN clients c ON o.client_id = c.id 
    ORDER BY o.order_date DESC
")->fetchAll();

$edit_order_id = $_SESSION['edit_order_id'] ?? null;
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Административная панель</title>
    <style>
        body { font-family: sans-serif; padding: 20px; background: #f5f5f5; }
        .container { max-width: 1400px; margin: 0 auto; }
        h1 { color: #2c5e2e; }
        .header { background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; }
        .section { background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; font-size: 14px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background: #2c5e2e; color: white; }
        tr:nth-child(even) { background: #f9f9f9; }
        .btn { padding: 6px 12px; background: #2c5e2e; color: white; border: none; cursor: pointer; border-radius: 4px; text-decoration: none; display: inline-block; font-size: 13px; margin: 2px; }
        .btn:hover { background: #1e4220; }
        .btn-danger { background: #d9534f; }
        .btn-danger:hover { background: #c9302c; }
        .btn-warning { background: #f0ad4e; }
        .btn-warning:hover { background: #ec971f; }
        .btn-success { background: #4a9f50; }
        .btn-success:hover { background: #3d8b40; }
        .btn-small { padding: 4px 8px; font-size: 12px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group input, .form-group select, .form-group textarea { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        .form-group input.required { border-left: 3px solid #d9534f; }
        .form-inline { display: inline-flex; gap: 10px; align-items: center; }
        .message { padding: 15px; margin-bottom: 20px; border-radius: 4px; }
        .message-success { background: #dff0d8; color: #3c763d; border: 1px solid #d6e9c6; }
        .message-error { background: #f2dede; color: #a94442; border: 1px solid #ebccd1; }
        .status-new { background: #f0ad4e; color: white; padding: 3px 10px; border-radius: 3px; }
        .status-processing { background: #5bc0de; color: white; padding: 3px 10px; border-radius: 3px; }
        .status-completed { background: #4a9f50; color: white; padding: 3px 10px; border-radius: 3px; }
        .status-cancelled { background: #d9534f; color: white; padding: 3px 10px; border-radius: 3px; }
        .tabs { display: flex; gap: 10px; margin-bottom: 20px; flex-wrap: wrap; }
        .tab { padding: 10px 20px; background: #e0e0e0; border: none; cursor: pointer; border-radius: 4px; }
        .tab.active { background: #2c5e2e; color: white; }
        .tab-content { display: none; }
        .tab-content.active { display: block; }
        .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; }
        .modal-content { background: white; padding: 30px; border-radius: 8px; max-width: 600px; margin: 50px auto; max-height: 90vh; overflow-y: auto; }
        .close-modal { float: right; font-size: 28px; cursor: pointer; color: #999; }
        .close-modal:hover { color: #000; }
        .required-mark { color: #d9534f; font-weight: bold; }
        .bulk-action { background: #f9f9f9; padding: 15px; border-radius: 4px; margin-bottom: 15px; border: 1px solid #ddd; }
        input[disabled] { background-color: #e0e0e0; cursor: not-allowed; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔧 Административная панель</h1>
            <div>
                <span>👤 <?= htmlspecialchars($_SESSION['admin_login']) ?></span>
                <a href="admin_logout.php" class="btn btn-danger" style="margin-left: 10px;">Выйти</a>
            </div>
        </div>
        
        <?php if ($message): ?>
            <div class="message message-<?= $message_type ?>"><?= $message ?></div>
        <?php endif; ?>

        <div class="tabs">
            <a href="?tab=orders" class="tab <?= $current_tab == 'orders' ? 'active' : '' ?>">📦 Заказы</a>
            <a href="?tab=clients" class="tab <?= $current_tab == 'clients' ? 'active' : '' ?>">👥 Клиенты</a>
            <a href="?tab=cotton" class="tab <?= $current_tab == 'cotton' ? 'active' : '' ?>">🌾 Сорта хлопка</a>
        </div>

        <div id="orders" class="tab-content <?= $current_tab == 'orders' ? 'active' : '' ?>">
            <div class="section">
                <h2>📦 Все заказы</h2>
                
                <table>
                    <thead>
                        <tr>
                            <th>№</th>
                            <th>Клиент</th>
                            <th>Дата</th>
                            <th>Сумма</th>
                            <th>Статус</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($all_orders as $order): ?>
                        <?php $is_editing = ($edit_order_id == $order['id']); ?>
                        <tr>
                            <td>#<?= $order['id'] ?></td>
                            <td><?= htmlspecialchars($order['company_name'] ?? 'Не указан') ?></td>
                            <td><?= date('d.m.Y', strtotime($order['order_date'])) ?></td>
                            <td>
                                <?php if ($is_editing): ?>
                                    <form method="POST" id="edit_order_form_<?= $order['id'] ?>">
                                        <input type="hidden" name="action" value="save_order">
                                        <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                        <input type="number" name="total_amount" value="<?= $order['total_amount'] ?>" step="0.01" style="width: 120px;" required>
                                    </form>
                                <?php else: ?>
                                    <?= number_format($order['total_amount'], 2, ',', ' ') ?> ₽
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($is_editing): ?>
                                    <select name="status" form="edit_order_form_<?= $order['id'] ?>" style="padding: 5px;">
                                        <option value="new" <?= $order['status'] == 'new' ? 'selected' : '' ?>>Новый</option>
                                        <option value="processing" <?= $order['status'] == 'processing' ? 'selected' : '' ?>>В обработке</option>
                                        <option value="completed" <?= $order['status'] == 'completed' ? 'selected' : '' ?>>Выполнен</option>
                                        <option value="cancelled" <?= $order['status'] == 'cancelled' ? 'selected' : '' ?>>Отменён</option>
                                    </select>
                                <?php else: ?>
                                    <span class="status-<?= $order['status'] ?>">
                                        <?php
                                        $status_names = ['new' => 'Новый', 'processing' => 'В обработке', 'completed' => 'Выполнен', 'cancelled' => 'Отменён'];
                                        echo $status_names[$order['status']] ?? $order['status'];
                                        ?>
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($is_editing): ?>
                                    <button type="submit" form="edit_order_form_<?= $order['id'] ?>" class="btn btn-success btn-small">✅ Сохранить</button>
                                    <form method="POST" style="display: inline;">
                                        <input type="hidden" name="action" value="cancel_edit_order">
                                        <button type="submit" class="btn btn-warning btn-small">❌ Отмена</button>
                                    </form>
                                <?php else: ?>
                                    <form method="POST" style="display: inline;">
                                        <input type="hidden" name="action" value="edit_order">
                                        <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                        <button type="submit" class="btn btn-warning btn-small">✏️ Редактировать</button>
                                    </form>
                                    <form method="POST" style="display: inline;">
                                        <input type="hidden" name="action" value="delete_order">
                                        <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                        <button type="submit" class="btn btn-danger btn-small" onclick="return confirm('Удалить заказ #<?= $order['id'] ?>?');">🗑️ Удалить</button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($all_orders)): ?>
                            <tr><td colspan="6" style="text-align: center;">Нет заказов</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <div id="clients" class="tab-content <?= $current_tab == 'clients' ? 'active' : '' ?>">
            <div class="section">
                <h2>👥 Все клиенты</h2>
                <button class="btn" onclick="document.getElementById('addClientModal').style.display='block'">➕ Добавить клиента</button>
                
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Компания</th>
                            <th>Контактное лицо</th>
                            <th>Телефон</th>
                            <th>Email</th>
                            <th>Адрес</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($all_clients as $client): ?>
                        <tr>
                            <td><?= $client['id'] ?></td>
                            <td><?= htmlspecialchars($client['company_name']) ?></td>
                            <td><?= htmlspecialchars($client['contact_person']) ?></td>
                            <td><?= htmlspecialchars($client['phone']) ?></td>
                            <td><?= htmlspecialchars($client['email']) ?></td>
                            <td><?= htmlspecialchars($client['address']) ?></td>
                            <td>
                                <button class="btn btn-warning btn-small" onclick='document.getElementById("edit_client_id").value=<?= $client['id'] ?>;document.getElementById("edit_company_name").value="<?= htmlspecialchars($client['company_name']) ?>";document.getElementById("edit_contact_person").value="<?= htmlspecialchars($client['contact_person']) ?>";document.getElementById("edit_phone").value="<?= htmlspecialchars($client['phone']) ?>";document.getElementById("edit_email").value="<?= htmlspecialchars($client['email']) ?>";document.getElementById("edit_address").value="<?= htmlspecialchars($client['address']) ?>";document.getElementById("editClientModal").style.display="block"'>✏️ Редактировать</button>
                                <form method="POST" onsubmit="return confirm('Удалить клиента <?= htmlspecialchars($client['company_name']) ?>?');" style="display: inline;">
                                    <input type="hidden" name="action" value="delete_client">
                                    <input type="hidden" name="client_id" value="<?= $client['id'] ?>">
                                    <button type="submit" class="btn btn-danger btn-small">🗑️ Удалить</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
<div id="cotton" class="tab-content <?= $current_tab == 'cotton' ? 'active' : '' ?>">
    <div class="section">
        <h2>🌾 Сорта хлопка</h2>
        
        <form method="POST" id="bulkPriceForm" style="margin-bottom: 15px;">
            <input type="hidden" name="action" value="bulk_increase_price">
            <div class="bulk-action">
                <label><strong>Массовое изменение цен:</strong></label>
                <input type="number" name="percentage" step="0.1" min="0.1" placeholder="%" style="width: 80px;" required>
                <button type="submit" class="btn btn-success">📈 Увеличить цену на %</button>
                <small style="color: #666; margin-left: 10px;">Отметьте чекбоксами сорта ниже</small>
            </div>
        </form>

        <table>
            <thead>
                <tr>
                    <th><input type="checkbox" id="selectAllCotton" onclick="toggleAllCheckboxes(this)"></th>
                    <th>ID</th>
                    <th>Название</th>
                    <th>Длина волокна (мм)</th>
                    <th>Цена за тонну (₽)</th>
                    <th>Описание</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($all_cotton_types as $cotton): ?>
                <tr>
                    <td>
                        <input type="checkbox" name="selected_cotton[]" value="<?= $cotton['id'] ?>" class="cotton-checkbox" form="bulkPriceForm">
                    </td>
                    <td><?= $cotton['id'] ?></td>
                    <td><?= htmlspecialchars($cotton['sort_name']) ?></td>
                    <td><?= $cotton['fiber_length'] ?></td>
                    <td><?= number_format($cotton['price_per_ton'], 2, ',', ' ') ?></td>
                    <td><?= htmlspecialchars($cotton['description']) ?></td>
                    <td>
                        <button type="button" class="btn btn-warning btn-small" onclick='document.getElementById("edit_cotton_id").value=<?= $cotton['id'] ?>;document.getElementById("edit_sort_name").value="<?= htmlspecialchars($cotton['sort_name']) ?>";document.getElementById("edit_fiber_length").value=<?= $cotton['fiber_length'] ?>;document.getElementById("edit_price_per_ton").value=<?= $cotton['price_per_ton'] ?>;document.getElementById("edit_description").value="<?= htmlspecialchars($cotton['description']) ?>";document.getElementById("editCottonModal").style.display="block"'>✏️ Редактировать</button>
                        
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="action" value="delete_cotton_type">
                            <input type="hidden" name="cotton_id" value="<?= $cotton['id'] ?>">
                            <button type="submit" class="btn btn-danger btn-small" onclick="return confirm('Удалить сорт <?= htmlspecialchars($cotton['sort_name']) ?>?');">🗑️ Удалить</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <button class="btn" onclick="document.getElementById('addCottonModal').style.display='block'" style="margin-top: 15px;">➕ Добавить сорт</button>
    </div>
</div>
    
    <div id="addClientModal" class="modal">
        <div class="modal-content">
            <span class="close-modal" onclick="document.getElementById('addClientModal').style.display='none'">&times;</span>
            <h2>➕ Добавить клиента</h2>
            <form method="POST">
                <input type="hidden" name="action" value="add_client">
                <div class="form-group">
                    <label>Название компании <span class="required-mark">*</span></label>
                    <input type="text" name="company_name" class="required" required>
                </div>
                <div class="form-group">
                    <label>Контактное лицо</label>
                    <input type="text" name="contact_person">
                </div>
                <div class="form-group">
                    <label>Телефон</label>
                    <input type="text" name="phone">
                </div>
                <div class="form-group">
                    <label>Email <span class="required-mark">*</span></label>
                    <input type="email" name="email" class="required" required>
                </div>
                <div class="form-group">
                    <label>Адрес</label>
                    <textarea name="address" rows="3"></textarea>
                </div>
                <button type="submit" class="btn">💾 Добавить</button>
            </form>
        </div>
    </div>
    
    <div id="editClientModal" class="modal">
        <div class="modal-content">
            <span class="close-modal" onclick="document.getElementById('editClientModal').style.display='none'">&times;</span>
            <h2>✏️ Редактировать клиента</h2>
            <form method="POST">
                <input type="hidden" name="action" value="edit_client">
                <input type="hidden" name="client_id" id="edit_client_id">
                <div class="form-group">
                    <label>Название компании <span class="required-mark">*</span></label>
                    <input type="text" name="company_name" id="edit_company_name" class="required" required>
                </div>
                <div class="form-group">
                    <label>Контактное лицо</label>
                    <input type="text" name="contact_person" id="edit_contact_person">
                </div>
                <div class="form-group">
                    <label>Телефон</label>
                    <input type="text" name="phone" id="edit_phone">
                </div>
                <div class="form-group">
                    <label>Email <span class="required-mark">*</span></label>
                    <input type="email" name="email" id="edit_email" class="required" required>
                </div>
                <div class="form-group">
                    <label>Адрес</label>
                    <textarea name="address" id="edit_address" rows="3"></textarea>
                </div>
                <button type="submit" class="btn">💾 Сохранить</button>
            </form>
        </div>
    </div>
    
    <div id="addCottonModal" class="modal">
        <div class="modal-content">
            <span class="close-modal" onclick="document.getElementById('addCottonModal').style.display='none'">&times;</span>
            <h2>➕ Добавить сорт хлопка</h2>
            <form method="POST">
                <input type="hidden" name="action" value="add_cotton_type">
                <div class="form-group">
                    <label>Название сорта <span class="required-mark">*</span></label>
                    <input type="text" name="sort_name" class="required" required>
                </div>
                <div class="form-group">
                    <label>Длина волокна (мм)</label>
                    <input type="number" name="fiber_length" step="0.01">
                </div>
                <div class="form-group">
                    <label>Цена за тонну (₽)</label>
                    <input type="number" name="price_per_ton" step="0.01">
                </div>
                <div class="form-group">
                    <label>Описание</label>
                    <textarea name="description" rows="3"></textarea>
                </div>
                <button type="submit" class="btn">💾 Добавить</button>
            </form>
        </div>
    </div>
    
    <div id="editCottonModal" class="modal">
        <div class="modal-content">
            <span class="close-modal" onclick="document.getElementById('editCottonModal').style.display='none'">&times;</span>
            <h2>✏️ Редактировать сорт хлопка</h2>
            <form method="POST">
                <input type="hidden" name="action" value="edit_cotton_type">
                <input type="hidden" name="cotton_id" id="edit_cotton_id">
                <div class="form-group">
                    <label>Название сорта <span class="required-mark">*</span></label>
                    <input type="text" name="sort_name" id="edit_sort_name" class="required" required>
                </div>
                <div class="form-group">
                    <label>Длина волокна (мм)</label>
                    <input type="number" name="fiber_length" id="edit_fiber_length" step="0.01">
                </div>
                <div class="form-group">
                    <label>Цена за тонну (₽)</label>
                    <input type="number" name="price_per_ton" id="edit_price_per_ton" step="0.01">
                </div>
                <div class="form-group">
                    <label>Описание</label>
                    <textarea name="description" id="edit_description" rows="3"></textarea>
                </div>
                <button type="submit" class="btn">💾 Сохранить</button>
            </form>
        </div>
    </div>
    
    <div style="margin-top: 20px;">
        <a href="index.php" class="btn btn-warning">← На главную</a>
    </div>
    
    <script>

        function toggleAllCheckboxes(source) {
            const checkboxes = document.querySelectorAll('.cotton-checkbox');
            checkboxes.forEach(checkbox => checkbox.checked = source.checked);
        }
        

        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.style.display = 'none';
            }
        }
    </script>
</body>
</html>