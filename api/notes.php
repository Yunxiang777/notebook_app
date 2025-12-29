<?php
/**
 * 載入資料庫連線設定
 * 會產生 $pdo（PDO 物件）
 */
require_once '../config/db.php';

/**
 * 載入認證與 Session 相關函式
 * 包含 requireLogin()、isLoggedIn() 等
 */
require_once '../includes/auth.php';

/**
 * 強制要求使用者必須登入
 * 若未登入，會被導向 login.php 並中止程式
 */
requireLogin();

/**
 * 設定回傳內容型態為 JSON
 * 前端（fetch / AJAX）才知道怎麼解析
 */
header('Content-Type: application/json');

/**
 * 取得目前 HTTP 請求方法
 * 可能值：GET / POST / PUT / DELETE
 */
$method = $_SERVER['REQUEST_METHOD'];

/**
 * 讀取 request body（JSON）
 * php://input 是原始請求資料流
 * 第二個參數 true 表示轉成 associative array
 */
$data = json_decode(file_get_contents('php://input'), true);

try {

    /**
     * 根據 HTTP Method 分流處理
     * 這是一種簡易 RESTful API 設計
     */
    switch ($method) {

        /**
         * ===== 取得記事 =====
         * GET /api/notes.php
         * GET /api/notes.php?id=123
         */
        case 'GET':

            if (isset($_GET['id'])) {
                // 取得單一記事（且必須屬於目前登入使用者）
                $stmt = $pdo->prepare(
                    "SELECT * FROM notes WHERE id = ? AND user_id = ?"
                );
                $stmt->execute([$_GET['id'], $_SESSION['user_id']]);

                $note = $stmt->fetch();
                
                if ($note) {
                    echo json_encode([
                        'success' => true,
                        'data' => $note
                    ]);
                } else {
                    echo json_encode([
                        'success' => false,
                        'message' => '找不到記事'
                    ]);
                }

            } else {
                // 取得目前使用者的所有記事
                $stmt = $pdo->prepare(
                    "SELECT * FROM notes WHERE user_id = ? ORDER BY updated_at DESC"
                );
                $stmt->execute([$_SESSION['user_id']]);

                $notes = $stmt->fetchAll();

                echo json_encode([
                    'success' => true,
                    'data' => $notes
                ]);
            }
            break;

        /**
         * ===== 新增記事 =====
         * POST /api/notes.php
         */
        case 'POST':

            // 從 JSON request body 取得資料
            $title   = $data['title'] ?? '';
            $content = $data['content'] ?? '';
            
            // 基本驗證
            if (empty($title) || empty($content)) {
                echo json_encode([
                    'success' => false,
                    'message' => '標題和內容不能為空'
                ]);
                break;
            }
            
            // 寫入資料庫
            $stmt = $pdo->prepare(
                "INSERT INTO notes (user_id, title, content) VALUES (?, ?, ?)"
            );
            $stmt->execute([
                $_SESSION['user_id'],
                $title,
                $content
            ]);
            
            // 回傳新記事 ID
            echo json_encode([
                'success' => true,
                'id' => $pdo->lastInsertId()
            ]);
            break;

        /**
         * ===== 更新記事 =====
         * PUT /api/notes.php
         */
        case 'PUT':

            $id      = $data['id'] ?? 0;
            $title   = $data['title'] ?? '';
            $content = $data['content'] ?? '';
            
            if (empty($title) || empty($content)) {
                echo json_encode([
                    'success' => false,
                    'message' => '標題和內容不能為空'
                ]);
                break;
            }
            
            // 只能更新屬於自己的記事
            $stmt = $pdo->prepare(
                "UPDATE notes 
                 SET title = ?, content = ? 
                 WHERE id = ? AND user_id = ?"
            );
            $stmt->execute([
                $title,
                $content,
                $id,
                $_SESSION['user_id']
            ]);
            
            // rowCount > 0 代表有資料被更新
            if ($stmt->rowCount() > 0) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => '更新失敗'
                ]);
            }
            break;

        /**
         * ===== 刪除記事 =====
         * DELETE /api/notes.php
         */
        case 'DELETE':

            $id = $data['id'] ?? 0;
            
            // 只能刪除屬於自己的記事
            $stmt = $pdo->prepare(
                "DELETE FROM notes WHERE id = ? AND user_id = ?"
            );
            $stmt->execute([
                $id,
                $_SESSION['user_id']
            ]);
            
            if ($stmt->rowCount() > 0) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => '刪除失敗'
                ]);
            }
            break;

        /**
         * ===== 不支援的 HTTP Method =====
         */
        default:
            echo json_encode([
                'success' => false,
                'message' => '不支援的方法'
            ]);
    }

} catch (Exception $e) {

    // 捕捉任何例外並回傳錯誤訊息（JSON）
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>
