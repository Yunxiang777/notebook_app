-- 建立資料庫
-- utf8mb4 編碼支援多語言字符集(4bytes)，適合儲存各種語言的文字及表情符號
--  COLLATE utf8mb4_unicode_ci 支援不區分大小寫的排序規則
CREATE DATABASE IF NOT EXISTS notebook_app CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE notebook_app;

-- 使用者資料表
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 記事本資料表
CREATE TABLE IF NOT EXISTS notes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(200) NOT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    -- 參照使用者資料表的外鍵，當使用者被刪除時，相關的記事本也會被刪除
    -- CASCADE 表示級聯刪除，確保資料完整性，是ACID SAFE
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 插入測試使用者 (密碼: test123)
INSERT INTO users (username, password, email) VALUES 
('testuser', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'test@example.com');