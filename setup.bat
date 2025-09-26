@echo off
echo ===================================
echo Course Management System Setup
echo ===================================

REM Check if PHP is installed
php --version >nul 2>&1
if %errorlevel% neq 0 (
    echo ❌ PHP is not installed. Please install PHP first.
    pause
    exit /b 1
)
echo ✅ PHP is installed

REM Check if MySQL is installed
mysql --version >nul 2>&1
if %errorlevel% neq 0 (
    echo ❌ MySQL is not installed. Please install MySQL first.
    pause
    exit /b 1
)
echo ✅ MySQL is installed

echo.
echo 📊 Setting up database...
echo Please enter your MySQL credentials:

set /p DB_USER=MySQL username: 
set /p DB_PASS=MySQL password: 

REM Create database
mysql -u %DB_USER% -p%DB_PASS% -e "CREATE DATABASE IF NOT EXISTS course_management;" 2>nul
if %errorlevel% neq 0 (
    echo ❌ Failed to create database. Please check your credentials.
    pause
    exit /b 1
)
echo ✅ Database created successfully

REM Import schema
mysql -u %DB_USER% -p%DB_PASS% course_management < database\schema.sql
if %errorlevel% neq 0 (
    echo ❌ Failed to import database schema
    pause
    exit /b 1
)
echo ✅ Database schema imported successfully

REM Import sample data
mysql -u %DB_USER% -p%DB_PASS% course_management < database\sample_data.sql
if %errorlevel% neq 0 (
    echo ❌ Failed to import sample data
    pause
    exit /b 1
)
echo ✅ Sample data imported successfully

echo.
echo ⚙️  Updating database configuration...

REM Create backup of original config
copy api\config\database.php api\config\database.php.backup >nul

REM Update database configuration (simplified version for Windows)
echo ✅ Database configuration needs to be updated manually
echo Please edit api\config\database.php and update:
echo   - private $username = '%DB_USER%';
echo   - private $password = '%DB_PASS%';

echo.
echo 🚀 Setup completed successfully!
echo.
echo To start the application:
echo 1. Run: php -S localhost:8000
echo 2. Open your browser and go to: http://localhost:8000
echo.
echo Or place the files in your web server's document root.
echo.
echo ===================================
pause