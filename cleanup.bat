@echo off
echo ========================================
echo 清理多餘文件
echo ========================================

echo 正在刪除 React/Vite 相關文件...
if exist "eslint.config.js" del "eslint.config.js"
if exist "package.json" del "package.json"
if exist "package-lock.json" del "package-lock.json"
if exist "vite.config.js" del "vite.config.js"
if exist "src" rmdir /s /q "src"
if exist "node_modules" rmdir /s /q "node_modules"
if exist "public\vite.svg" del "public\vite.svg"

echo 正在刪除測試文件...
if exist "test_api.php" del "test_api.php"
if exist "test_connection.php" del "test_connection.php"
if exist "test_enrolments.php" del "test_enrolments.php"
if exist "test_mysql_api.php" del "test_mysql_api.php"
if exist "test_mysql_password.bat" del "test_mysql_password.bat"
if exist "test_mysql_passwords.php" del "test_mysql_passwords.php"

echo 正在刪除備用 API 文件...
if exist "api\api_mysqli.php" del "api\api_mysqli.php"
if exist "api\index_mysql.php" del "api\index_mysql.php"
if exist "api\config" rmdir /s /q "api\config"

echo 正在刪除設置腳本...
if exist "setup.bat" del "setup.bat"
if exist "setup.sh" del "setup.sh"

echo.
echo ✅ 清理完成！
echo.
echo 📂 保留的核心文件：
echo   - index.html (主頁面)
echo   - styles.css (樣式)
echo   - script.js (JavaScript)
echo   - api/index.php (API)
echo   - database/ (數據庫文件)
echo   - README.md (說明文檔)
echo   - 運行指南.md
echo   - 執行步驟.md
echo.
echo 🚀 您的系統依然完全可用！
pause