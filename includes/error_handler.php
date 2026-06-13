<?php

class ErrorHandler {
    private $errors = [];
    private $success = [];
    
    public function addError($field, $message) {
        $this->errors[$field] = $message;
    }
    
    public function addSuccess($message) {
        $this->success[] = $message;
    }
    
    public function hasErrors() {
        return !empty($this->errors);
    }

    public function getErrors() {
        return $this->errors;
    }
    
    public function getError($field) {
        return isset($this->errors[$field]) ? $this->errors[$field] : '';
    }
    
    public function displayErrors() {
        if ($this->hasErrors()) {
            echo '<div class="message message-error">';
            echo '<ul style="margin: 0; padding-left: 20px;">';
            foreach ($this->errors as $error) {
                echo '<li>' . htmlspecialchars($error) . '</li>';
            }
            echo '</ul></div>';
        }
    }

    public function displaySuccess() {
        if (!empty($this->success)) {
            echo '<div class="message message-success">';
            foreach ($this->success as $message) {
                echo '<p>' . $message . '</p>';
            }
            echo '</div>';
        }
    }
    
    public function validateEmail($email, $fieldName = 'Email') {
        if (empty($email)) {
            $this->addError('email', "$fieldName не может быть пустым");
            return false;
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->addError('email', "$fieldName имеет неверный формат");
            return false;
        }
        return true;
    }

    public function validatePassword($password, $confirmPassword = null) {
        if (empty($password)) {
            $this->addError('password', 'Пароль не может быть пустым');
            return false;
        } elseif (strlen($password) < 6) {
            $this->addError('password', 'Пароль должен быть не менее 6 символов');
            return false;
        } elseif (strlen($password) > 50) {
            $this->addError('password', 'Пароль не должен превышать 50 символов');
            return false;
        }
        
        if ($confirmPassword !== null && $password !== $confirmPassword) {
            $this->addError('password_confirm', 'Пароли не совпадают');
            return false;
        }
        
        return true;
    }
    
    public function validateString($value, $fieldName, $minLength = 1, $maxLength = 255, $required = true) {
        $value = trim($value);
        
        if (empty($value)) {
            if ($required) {
                $this->addError(strtolower(str_replace(' ', '_', $fieldName)), "$fieldName не может быть пустым");
                return false;
            }
            return true;
        }
        
        if (strlen($value) < $minLength) {
            $this->addError(strtolower(str_replace(' ', '_', $fieldName)), "$fieldName должен быть не менее $minLength символов");
            return false;
        }
        
        if (strlen($value) > $maxLength) {
            $this->addError(strtolower(str_replace(' ', '_', $fieldName)), "$fieldName не должен превышать $maxLength символов");
            return false;
        }
        
        return true;
    }

    public function validatePhone($phone, $required = false) {
        $phone = trim($phone);
        
        if (empty($phone)) {
            if ($required) {
                $this->addError('phone', 'Телефон не может быть пустым');
                return false;
            }
            return true;
        }
        
        // Разрешаем цифры, пробелы, скобки, плюс и дефис
        if (!preg_match('/^[\d\s\-\+\(\)]+$/', $phone)) {
            $this->addError('phone', 'Телефон должен содержать только цифры и специальные символы');
            return false;
        }
        
        return true;
    }

    public function validateCaptcha($userAnswer, $correctAnswer) {
        if (!isset($userAnswer) || (int)$userAnswer !== (int)$correctAnswer) {
            $this->addError('captcha', 'Неверно решён пример. Попробуйте ещё раз.');
            return false;
        }
        return true;
    }

    public function sanitize($data) {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = $this->sanitize($value);
            }
        } else {
            $data = htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
        }
        return $data;
    }
    
    public function logError($message, $level = 'ERROR') {
        $logMessage = date('Y-m-d H:i:s') . " [$level] $message\n";
        error_log($logMessage, 3, 'logs/error.log');
    }

    public static function handleFatalError() {
        $error = error_get_last();
        if ($error !== NULL && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
            error_log(date('Y-m-d H:i:s') . " [FATAL] " . $error['message'] . " in " . $error['file'] . " on line " . $error['line'] . "\n", 3, 'logs/fatal.log');
            
            if (!headers_sent()) {
                http_response_code(500);
                echo '<div style="padding: 20px; background: #f2dede; border: 1px solid #ebccd1; border-radius: 4px; margin: 20px;">';
                echo '<h2 style="color: #a94442;">⚠ Произошла фатальная ошибка</h2>';
                echo '<p>Извините, на сервере произошла ошибка. Попробуйте позже.</p>';
                echo '</div>';
            }
        }
    }
}

class DatabaseExceptionHandler {
    private $errorHandler;
    
    public function __construct($errorHandler) {
        $this->errorHandler = $errorHandler;
    }

    public function safeQuery($pdo, $sql, $params = []) {
        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            return ['success' => true, 'stmt' => $stmt];
        } catch (PDOException $e) {
            $this->errorHandler->logError("Database Error: " . $e->getMessage() . " | SQL: $sql", 'DB_ERROR');
            return ['success' => false, 'error' => 'Ошибка базы данных. Попробуйте позже.'];
        }
    }
    
    public function safeInsert($pdo, $table, $data) {
        try {
            $fields = array_keys($data);
            $placeholders = str_repeat('?,', count($fields) - 1) . '?';
            $sql = "INSERT INTO $table (" . implode(',', $fields) . ") VALUES ($placeholders)";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array_values($data));
            
            return ['success' => true, 'id' => $pdo->lastInsertId()];
        } catch (PDOException $e) {
            $this->errorHandler->logError("Insert Error: " . $e->getMessage() . " | Table: $table", 'DB_ERROR');
            return ['success' => false, 'error' => 'Ошибка при добавлении данных'];
        }
    }
    
    public function safeUpdate($pdo, $table, $data, $where, $whereParams) {
        try {
            $setParts = [];
            foreach ($data as $field => $value) {
                $setParts[] = "$field = ?";
            }
            $sql = "UPDATE $table SET " . implode(', ', $setParts) . " WHERE $where";
            
            $params = array_merge(array_values($data), $whereParams);
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            
            return ['success' => true, 'affected' => $stmt->rowCount()];
        } catch (PDOException $e) {
            $this->errorHandler->logError("Update Error: " . $e->getMessage() . " | Table: $table", 'DB_ERROR');
            return ['success' => false, 'error' => 'Ошибка при обновлении данных'];
        }
    }
}

register_shutdown_function(['ErrorHandler', 'handleFatalError']);


if (!is_dir('logs')) {
    mkdir('logs', 0777, true);
}
?>