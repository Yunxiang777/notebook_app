<?php
// db.php 連線成功取得 $pdo 物件
require_once 'config/db.php';
require_once 'includes/auth.php';

requireLogin();

// 取得使用者的所有記事
$stmt = $pdo->prepare("SELECT * FROM notes WHERE user_id = ? ORDER BY updated_at DESC");
$stmt->execute([$_SESSION['user_id']]);
$notes = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>我的記事本</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>📝 我的記事本</h1>
            <div class="user-info">
                <span>歡迎, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                <a href="logout.php" class="btn btn-secondary">登出</a>
            </div>
        </header>
        
        <div class="main-content">
            <div class="sidebar">
                <button class="btn btn-primary btn-block" onclick="showCreateModal()">
                    ✚ 新增記事
                </button>
                
                <div class="notes-list">
                    <?php if (empty($notes)): ?>
                        <p class="no-notes">還沒有任何記事</p>
                    <?php else: ?>
                        <?php foreach ($notes as $note): ?>
                            <div class="note-item" onclick="selectNote(<?php echo $note['id']; ?>)">
                                <h3><?php echo htmlspecialchars($note['title']); ?></h3>
                                <p><?php echo htmlspecialchars(substr($note['content'], 0, 50)); ?>...</p>
                                <small><?php echo date('Y-m-d H:i', strtotime($note['updated_at'])); ?></small>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="note-content">
                <div id="empty-state" class="empty-state">
                    <h2>選擇一則記事開始編輯</h2>
                    <p>或建立新的記事</p>
                </div>
                
                <div id="note-editor" class="note-editor" style="display: none;">
                    <input type="hidden" id="note-id">
                    <input type="text" id="note-title" class="note-title" placeholder="標題">
                    <textarea id="note-text" class="note-textarea" placeholder="開始寫下你的想法..."></textarea>
                    <div class="editor-actions">
                        <button class="btn btn-primary" onclick="saveNote()">儲存</button>
                        <button class="btn btn-danger" onclick="deleteNote()">刪除</button>
                        <button class="btn btn-secondary" onclick="cancelEdit()">取消</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- 新增記事 Modal -->
    <div id="createModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeCreateModal()">&times;</span>
            <h2>新增記事</h2>
            <input type="text" id="new-title" class="form-control" placeholder="標題">
            <textarea id="new-content" class="form-control" placeholder="內容" rows="5"></textarea>
            <button class="btn btn-primary" onclick="createNote()">建立</button>
        </div>
    </div>
    
    <script src="js/notes.js"></script>
</body>
</html>