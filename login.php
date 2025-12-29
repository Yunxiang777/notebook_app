<?php
/**
 * 載入資料庫連線設定
 * 會建立 $pdo 物件供後續使用
 */
require_once 'config/db.php';

/**
 * 載入認證相關函式
 * 包含：login()、register()、logout()、isLoggedIn() 等
 */
require_once 'includes/auth.php';

/**
 * 用來存放錯誤與成功訊息
 * 預設為空字串，避免未定義變數警告
 */
$error = '';
$success = '';

/**
 * 僅在表單使用 POST 方法送出時才處理
 * 避免使用者直接用 GET 存取造成誤判
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    /**
     * ===== 登入流程 =====
     * 判斷是否按下「登入」按鈕
     */
    if (isset($_POST['login'])) {

        // 取得登入表單欄位，若不存在則給空字串
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        
        // 呼叫 auth.php 中的 login() 函式驗證帳密
        if (login($username, $password, $pdo)) {

            // 登入成功，導向主頁
            header('Location: index.php');
            exit;

        } else {
            // 登入失敗，顯示錯誤訊息
            $error = '帳號或密碼錯誤';
        }

    /**
     * ===== 註冊流程 =====
     * 判斷是否按下「註冊」按鈕
     */
    } elseif (isset($_POST['register'])) {

        // 取得註冊表單欄位
        $username = $_POST['reg_username'] ?? '';
        $password = $_POST['reg_password'] ?? '';
        $email    = $_POST['reg_email'] ?? '';
        
        // 基本伺服器端驗證
        if (strlen($username) < 3) {
            $error = '使用者名稱至少需要3個字元';

        } elseif (strlen($password) < 6) {
            $error = '密碼至少需要6個字元';

        // 呼叫 auth.php 中的 register() 函式
        } elseif (register($username, $password, $email, $pdo)) {

            // 註冊成功
            $success = '註冊成功！請登入';

        } else {
            // 註冊失敗（常見原因：帳號重複）
            $error = '註冊失敗，使用者名稱可能已存在';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <!-- 設定網頁編碼為 UTF-8（支援中文） -->
    <meta charset="UTF-8">

    <!-- 行動裝置自適應 -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- 頁面標題 -->
    <title>登入 - 記事本系統</title>

    <!-- 載入樣式表 -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <!-- 整個登入/註冊區塊的容器 -->
    <div class="auth-container">
        <div class="auth-box">

            <!-- 系統標題 -->
            <h1>📝 記事本系統</h1>
            
            <!-- 顯示錯誤訊息（如果有） -->
            <?php if ($error): ?>
                <div class="alert alert-error">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            
            <!-- 顯示成功訊息（如果有） -->
            <?php if ($success): ?>
                <div class="alert alert-success">
                    <?php echo htmlspecialchars($success); ?>
                </div>
            <?php endif; ?>
            
            <!-- 登入 / 註冊 切換按鈕 -->
            <div class="tabs">
                <button class="tab-btn active" onclick="showTab('login')">
                    登入
                </button>
                <button class="tab-btn" onclick="showTab('register')">
                    註冊
                </button>
            </div>
            
            <!-- ===== 登入表單 ===== -->
            <div id="login-form" class="tab-content active">
                <form method="POST">

                    <div class="form-group">
                        <label>使用者名稱</label>
                        <input type="text" name="username" required>
                    </div>

                    <div class="form-group">
                        <label>密碼</label>
                        <input type="password" name="password" required>
                    </div>

                    <!-- name="login" 用來讓 PHP 判斷是哪個表單 -->
                    <button type="submit" name="login" class="btn btn-primary">
                        登入
                    </button>

                </form>
            </div>
            
            <!-- ===== 註冊表單 ===== -->
            <div id="register-form" class="tab-content">
                <form method="POST">

                    <div class="form-group">
                        <label>使用者名稱</label>
                        <input type="text" name="reg_username" required minlength="3">
                    </div>

                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="reg_email" required>
                    </div>

                    <div class="form-group">
                        <label>密碼</label>
                        <input type="password" name="reg_password" required minlength="6">
                    </div>

                    <!-- name="register" 用來讓 PHP 判斷是哪個表單 -->
                    <button type="submit" name="register" class="btn btn-primary">
                        註冊
                    </button>

                </form>
            </div>

        </div>
    </div>
    
    <!-- 載入前端 JS，用來切換登入/註冊頁籤 -->
    <script src="js/auth.js"></script>
</body>
</html>
