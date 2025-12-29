<?php
/**
 * 啟動 Session
 * 
 * session_start() 必須在任何輸出（echo / HTML）之前呼叫
 * 用來讓 PHP 能夠使用 $_SESSION 超全域變數
 */
session_start();

/**
 * 檢查使用者是否已登入
 *
 * @return bool
 *   true  => 已登入（Session 中有 user_id）
 *   false => 未登入
 */
function isLoggedIn(): bool {
    return isset($_SESSION['user_id']);
}

/**
 * 要求使用者必須登入
 *
 * 如果尚未登入，會立即導向到 login.php
 * 並使用 exit 中止後續程式碼執行
 */
function requireLogin(): void {
    if (!isLoggedIn()) {
        // PHP 在 Server 端 執行 header()，Server 回傳 HTTP Response，狀態碼通常是 302(臨時重定向)，永久重定向則是 301
        // Response Header 內包含 Location 欄位，瀏覽器收到後會自動導向到指定的 URL
        header('Location: login.php');
        exit;
    }
}


/**
 * 使用者登入驗證
 *
 * @param string $username 使用者輸入的帳號
 * @param string $password 使用者輸入的密碼（明文）
 * @param PDO    $pdo      PDO 資料庫連線物件
 *
 * @return bool
 *   true  => 登入成功
 *   false => 登入失敗（帳號不存在或密碼錯誤）
 */
function login(string $username, string $password, PDO $pdo): bool {
    $stmt = $pdo->prepare("SELECT id, username, password FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    
    // password_verify() 用來驗證明文密碼與雜湊密碼是否相符，是php內建函式
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        return true;
    }
    return false;
}

/**
 * 使用者註冊
 *
 * @param string $username 使用者帳號
 * @param string $password 使用者密碼（明文）
 * @param string $email    使用者 Email
 * @param PDO    $pdo      PDO 資料庫連線物件
 *
 * @return bool
 *   true  => 註冊成功
 *   false => 註冊失敗（例如帳號重複）
 */
function register(string $username, string $password, string $email, PDO $pdo): bool {
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    try {
        $stmt = $pdo->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
        $stmt->execute([$username, $hashedPassword, $email]);
        return true;
    } catch (PDOException $e) {
        return false;
    }
}

/**
 * 使用者登出
 *
 * 1. 清除所有 Session 資料
 * 2. 導向回登入頁
 */
function logout() {
    session_destroy();
    header('Location: login.php');
    exit;
}
?>