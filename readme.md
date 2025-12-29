# 📝 記事本系統

一個使用原生 PHP、MySQL、HTML、CSS 和 JavaScript 開發的全功能記事本應用程式。

## ✨ 功能特色

### 使用者功能

- 🔐 使用者註冊與登入
- 🚪 安全的登出機制
- 🔒 Session 驗證保護

### 記事管理 (CRUD)

- ➕ **Create** - 新增記事
- 📖 **Read** - 檢視記事內容
- ✏️ **Update** - 編輯記事
- 🗑️ **Delete** - 刪除記事

### 使用者介面

- 🎨 現代化漸層設計
- 📱 響應式佈局
- 📋 側邊欄記事列表
- ⚡ 即時編輯體驗
- 🕐 時間戳記顯示

## 🛠️ 技術架構

### 前端

- **HTML5** - 結構標記
- **CSS3** - 樣式設計（無框架，純手寫）
- **JavaScript (ES6+)** - 互動邏輯

### 後端

- **PHP 7.4+** - 伺服器端邏輯
- **PDO** - 資料庫操作
- **RESTful API** - 記事 CRUD 端點

### 資料庫

- **MySQL/MariaDB** - 資料儲存
- 兩個主要資料表：
  - `users` - 使用者資訊
  - `notes` - 記事內容

### 安全性

- 🔐 密碼使用 bcrypt 雜湊加密
- 🛡️ PDO Prepared Statements 防止 SQL Injection
- 🔑 Session 驗證機制
- ✅ XSS 防護 (htmlspecialchars)

## 📁 專案結構

```
notebook_app/
│
├── config/
│   └── db.php                 # 資料庫連線設定
│
├── includes/
│   └── auth.php               # 認證功能模組
│
├── api/
│   └── notes.php              # 記事 CRUD API
│
├── css/
│   └── style.css              # 主要樣式表
│
├── js/
│   ├── auth.js                # 登入/註冊功能
│   └── notes.js               # 記事操作功能
│
├── database.sql               # 資料庫結構與初始資料
├── login.php                  # 登入/註冊頁面
├── index.php                  # 主應用程式頁面
├── logout.php                 # 登出處理
└── README.md                  # 專案說明文件
```

## 🚀 安裝指南

### 系統需求

- XAMPP (或其他 PHP 開發環境)
- PHP 7.4 或更高版本
- MySQL/MariaDB 5.7 或更高版本
- 現代瀏覽器 (Chrome, Firefox, Edge, Safari)

### 安裝步驟

#### 1. 下載並設定 XAMPP

- 下載 XAMPP：https://www.apachefriends.org/
- 安裝並啟動 Apache 和 MySQL 服務

#### 2. 部署專案檔案

```bash
# 將專案檔案複製到 XAMPP 的 htdocs 目錄
C:\xampp\htdocs\notebook_app\
```

#### 3. 建立資料庫

1. 開啟瀏覽器，前往 `http://localhost/phpmyadmin`
2. 點擊「SQL」標籤
3. 複製並執行 `database.sql` 中的 SQL 指令
4. 確認已建立 `notebook_app` 資料庫及相關資料表

#### 4. 設定資料庫連線

檢查 `config/db.php` 中的資料庫設定：

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');           // XAMPP 預設無密碼
define('DB_NAME', 'notebook_app');
```

#### 5. 啟動應用程式

在瀏覽器中開啟：

```
http://localhost/notebook_app/login.php
```

## 👤 預設測試帳號

系統已預先建立一個測試帳號：

- **帳號**: `testuser`
- **密碼**: `test123`
- **Email**: `test@example.com`

您也可以透過註冊頁面建立新帳號。

## 💡 使用說明

### 註冊新帳號

1. 在登入頁面點擊「註冊」標籤
2. 填寫使用者名稱（至少 3 個字元）
3. 輸入有效的 Email 地址
4. 設定密碼（至少 6 個字元）
5. 點擊「註冊」按鈕

### 登入系統

1. 在登入頁面輸入帳號和密碼
2. 點擊「登入」按鈕
3. 成功後將導向主頁面

### 新增記事

1. 點擊「✚ 新增記事」按鈕
2. 輸入標題和內容
3. 點擊「建立」按鈕

### 編輯記事

1. 在左側列表點擊要編輯的記事
2. 直接在編輯器中修改標題或內容
3. 點擊「儲存」按鈕保存變更

### 刪除記事

1. 選擇要刪除的記事
2. 點擊「刪除」按鈕
3. 確認刪除操作

### 登出

點擊右上角的「登出」按鈕

## 🔧 API 端點

### 記事 API (`api/notes.php`)

#### 取得所有記事

```
GET /api/notes.php
```

#### 取得單一記事

```
GET /api/notes.php?id={note_id}
```

#### 新增記事

```
POST /api/notes.php
Content-Type: application/json

{
  "title": "記事標題",
  "content": "記事內容"
}
```

#### 更新記事

```
PUT /api/notes.php
Content-Type: application/json

{
  "id": 1,
  "title": "更新的標題",
  "content": "更新的內容"
}
```

#### 刪除記事

```
DELETE /api/notes.php
Content-Type: application/json

{
  "id": 1
}
```

## 🗄️ 資料庫結構

### users 資料表

| 欄位       | 類型         | 說明               |
| ---------- | ------------ | ------------------ |
| id         | INT          | 主鍵，自動遞增     |
| username   | VARCHAR(50)  | 使用者名稱，唯一值 |
| password   | VARCHAR(255) | 加密後的密碼       |
| email      | VARCHAR(100) | Email 地址         |
| created_at | TIMESTAMP    | 建立時間           |

### notes 資料表

| 欄位       | 類型         | 說明              |
| ---------- | ------------ | ----------------- |
| id         | INT          | 主鍵，自動遞增    |
| user_id    | INT          | 使用者 ID（外鍵） |
| title      | VARCHAR(200) | 記事標題          |
| content    | TEXT         | 記事內容          |
| created_at | TIMESTAMP    | 建立時間          |
| updated_at | TIMESTAMP    | 更新時間          |

## 🐛 常見問題

### 無法連線到資料庫

- 確認 MySQL 服務已在 XAMPP 中啟動
- 檢查 `config/db.php` 中的連線設定
- 確認資料庫 `notebook_app` 已建立

### 登入失敗

- 確認使用正確的帳號密碼
- 檢查資料庫中是否有該使用者資料
- 使用測試帳號 `testuser` / `test123` 測試

### 頁面顯示錯誤

- 檢查 PHP 錯誤訊息
- 確認所有檔案都已正確放置
- 檢查檔案權限設定

### API 無回應

- 開啟瀏覽器開發者工具查看 Network 標籤
- 檢查 PHP 錯誤日誌
- 確認 Session 是否有效

## 🔐 安全性說明

本專案已實作以下安全措施：

1. **密碼安全**

   - 使用 PHP `password_hash()` 和 `password_verify()`
   - 採用 bcrypt 演算法

2. **SQL Injection 防護**

   - 使用 PDO Prepared Statements
   - 參數綁定機制

3. **XSS 防護**

   - 輸出時使用 `htmlspecialchars()`
   - 防止惡意腳本注入

4. **Session 管理**

   - 登入驗證機制
   - 未登入自動導向登入頁

5. **存取控制**
   - 使用者只能存取自己的記事
   - 資料庫外鍵約束

## 📝 開發筆記

### 設計決策

- 採用 RESTful API 設計原則
- 前後端分離架構（透過 AJAX 通訊）
- 漸進式增強的使用者介面
- Mobile-first 響應式設計

### 學習重點

- PHP Session 管理
- PDO 資料庫操作
- AJAX 非同步請求
- RESTful API 設計
- 密碼安全最佳實踐

## 🚀 未來改進方向

- [ ] 新增富文本編輯器
- [ ] 實作記事分類/標籤功能
- [ ] 新增搜尋功能
- [ ] 支援記事匯出（PDF/TXT）
- [ ] 實作記事分享功能
- [ ] 新增深色模式
- [ ] 加入記事備份功能
- [ ] 實作密碼找回機制

## 📄 授權

本專案為教育用途開發，可自由使用和修改。

## 👨‍💻 作者

練習專案 - 學習 PHP、MySQL 與 Web 開發
