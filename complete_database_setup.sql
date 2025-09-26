-- ================================================================
-- 課程管理系統 - 完整數據庫建置 SQL
-- 包含：創建數據庫、創建表、插入數據
-- 日期：2025-09-27
-- ================================================================

-- ================================================================
-- 第一部分：創建數據庫
-- ================================================================

-- 創建數據庫
CREATE DATABASE IF NOT EXISTS course_management;
USE course_management;

-- ================================================================
-- 第二部分：創建表結構
-- ================================================================

-- 創建用戶表
CREATE TABLE IF NOT EXISTS users (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    first_name VARCHAR(100) NOT NULL,
    surname VARCHAR(100) NOT NULL,
    email VARCHAR(255) UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 創建課程表
CREATE TABLE IF NOT EXISTS courses (
    course_id INT PRIMARY KEY AUTO_INCREMENT,
    description TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 創建註冊表
CREATE TABLE IF NOT EXISTS enrolments (
    enrolment_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    course_id INT NOT NULL,
    completion_status ENUM('not started', 'in progress', 'completed') DEFAULT 'not started',
    enrolled_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    completed_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES courses(course_id) ON DELETE CASCADE,
    UNIQUE KEY unique_enrolment (user_id, course_id)
);

-- 創建索引以提升性能
CREATE INDEX idx_enrolments_user_id ON enrolments(user_id);
CREATE INDEX idx_enrolments_course_id ON enrolments(course_id);
CREATE INDEX idx_enrolments_status ON enrolments(completion_status);
CREATE INDEX idx_users_name ON users(first_name, surname);

-- ================================================================
-- 第三部分：插入用戶數據
-- ================================================================

INSERT INTO users (first_name, surname, email) VALUES
('John', 'Smith', 'john.smith@example.com'),
('Jane', 'Doe', 'jane.doe@example.com'),
('Michael', 'Johnson', 'michael.johnson@example.com'),
('Emily', 'Brown', 'emily.brown@example.com'),
('David', 'Wilson', 'david.wilson@example.com'),
('Sarah', 'Davis', 'sarah.davis@example.com'),
('Chris', 'Miller', 'chris.miller@example.com'),
('Lisa', 'Garcia', 'lisa.garcia@example.com'),
('Robert', 'Martinez', 'robert.martinez@example.com'),
('Amanda', 'Taylor', 'amanda.taylor@example.com'),
('Kevin', 'Anderson', 'kevin.anderson@example.com'),
('Michelle', 'Thomas', 'michelle.thomas@example.com'),
('Brian', 'Jackson', 'brian.jackson@example.com'),
('Jennifer', 'White', 'jennifer.white@example.com'),
('Daniel', 'Harris', 'daniel.harris@example.com'),
('Jessica', 'Martin', 'jessica.martin@example.com'),
('Matthew', 'Thompson', 'matthew.thompson@example.com'),
('Ashley', 'Robinson', 'ashley.robinson@example.com'),
('Ryan', 'Clark', 'ryan.clark@example.com'),
('Stephanie', 'Rodriguez', 'stephanie.rodriguez@example.com');

-- ================================================================
-- 第四部分：插入課程數據
-- ================================================================

INSERT INTO courses (description) VALUES
('Introduction to Web Development'),
('Advanced JavaScript Programming'),
('Database Design and Management'),
('PHP Backend Development'),
('React Frontend Framework'),
('Node.js Server Development'),
('Mobile App Development'),
('Data Science with Python'),
('Machine Learning Fundamentals'),
('Cloud Computing Basics'),
('Cybersecurity Essentials'),
('UI/UX Design Principles'),
('Digital Marketing Strategy'),
('Project Management'),
('Software Testing'),
('DevOps and CI/CD'),
('Blockchain Technology'),
('Artificial Intelligence'),
('Game Development'),
('E-commerce Development');

-- ================================================================
-- 第五部分：插入註冊數據（100條記錄）
-- ================================================================

INSERT INTO enrolments (user_id, course_id, completion_status) VALUES
-- User 1 enrolments
(1, 1, 'completed'),
(1, 2, 'in progress'),
(1, 3, 'not started'),
(1, 4, 'completed'),
(1, 5, 'in progress'),

-- User 2 enrolments
(2, 1, 'completed'),
(2, 6, 'completed'),
(2, 7, 'in progress'),
(2, 8, 'not started'),
(2, 9, 'completed'),

-- User 3 enrolments
(3, 2, 'in progress'),
(3, 3, 'completed'),
(3, 10, 'not started'),
(3, 11, 'in progress'),
(3, 12, 'completed'),

-- User 4 enrolments
(4, 4, 'completed'),
(4, 5, 'completed'),
(4, 13, 'in progress'),
(4, 14, 'not started'),
(4, 15, 'completed'),

-- User 5 enrolments
(5, 6, 'in progress'),
(5, 7, 'completed'),
(5, 16, 'not started'),
(5, 17, 'in progress'),
(5, 18, 'completed'),

-- User 6 enrolments
(6, 8, 'completed'),
(6, 9, 'in progress'),
(6, 19, 'not started'),
(6, 20, 'completed'),
(6, 1, 'in progress'),

-- User 7 enrolments
(7, 10, 'completed'),
(7, 11, 'completed'),
(7, 2, 'in progress'),
(7, 3, 'not started'),
(7, 4, 'completed'),

-- User 8 enrolments
(8, 12, 'in progress'),
(8, 13, 'completed'),
(8, 5, 'not started'),
(8, 6, 'in progress'),
(8, 7, 'completed'),

-- User 9 enrolments
(9, 14, 'completed'),
(9, 15, 'in progress'),
(9, 8, 'not started'),
(9, 9, 'completed'),
(9, 10, 'in progress'),

-- User 10 enrolments
(10, 16, 'completed'),
(10, 17, 'completed'),
(10, 11, 'in progress'),
(10, 12, 'not started'),
(10, 13, 'completed'),

-- User 11 enrolments
(11, 18, 'in progress'),
(11, 19, 'completed'),
(11, 14, 'not started'),
(11, 15, 'in progress'),
(11, 16, 'completed'),

-- User 12 enrolments
(12, 20, 'completed'),
(12, 1, 'completed'),
(12, 17, 'in progress'),
(12, 18, 'not started'),
(12, 19, 'completed'),

-- User 13 enrolments
(13, 2, 'in progress'),
(13, 3, 'completed'),
(13, 20, 'not started'),
(13, 1, 'in progress'),
(13, 4, 'completed'),

-- User 14 enrolments
(14, 5, 'completed'),
(14, 6, 'in progress'),
(14, 2, 'not started'),
(14, 3, 'completed'),
(14, 7, 'in progress'),

-- User 15 enrolments
(15, 8, 'completed'),
(15, 9, 'completed'),
(15, 4, 'in progress'),
(15, 5, 'not started'),
(15, 10, 'completed'),

-- User 16 enrolments
(16, 11, 'in progress'),
(16, 12, 'completed'),
(16, 6, 'not started'),
(16, 7, 'in progress'),
(16, 13, 'completed'),

-- User 17 enrolments
(17, 14, 'completed'),
(17, 15, 'in progress'),
(17, 8, 'not started'),
(17, 9, 'completed'),
(17, 16, 'in progress'),

-- User 18 enrolments
(18, 17, 'completed'),
(18, 18, 'completed'),
(18, 10, 'in progress'),
(18, 11, 'not started'),
(18, 19, 'completed'),

-- User 19 enrolments
(19, 20, 'in progress'),
(19, 1, 'completed'),
(19, 12, 'not started'),
(19, 13, 'in progress'),
(19, 14, 'completed'),

-- User 20 enrolments
(20, 2, 'completed'),
(20, 3, 'in progress'),
(20, 15, 'not started'),
(20, 16, 'completed'),
(20, 17, 'in progress');

-- ================================================================
-- 第六部分：更新完成日期
-- ================================================================

-- 為已完成的課程設置隨機的完成日期（在過去一年內）
UPDATE enrolments 
SET completed_at = DATE_SUB(NOW(), INTERVAL FLOOR(RAND() * 365) DAY)
WHERE completion_status = 'completed';

-- ================================================================
-- 第七部分：驗證數據
-- ================================================================

-- 檢查數據插入結果
SELECT 'Users' as table_name, COUNT(*) as record_count FROM users
UNION ALL
SELECT 'Courses' as table_name, COUNT(*) as record_count FROM courses
UNION ALL
SELECT 'Enrolments' as table_name, COUNT(*) as record_count FROM enrolments;

-- 檢查完成狀態分佈
SELECT 
    completion_status,
    COUNT(*) as count,
    ROUND(COUNT(*) * 100.0 / (SELECT COUNT(*) FROM enrolments), 2) as percentage
FROM enrolments 
GROUP BY completion_status
ORDER BY completion_status;

-- ================================================================
-- 第八部分：有用的查詢範例
-- ================================================================

-- 查看用戶註冊詳情
SELECT 
    u.first_name,
    u.surname,
    c.description as course,
    e.completion_status,
    e.enrolled_at,
    e.completed_at
FROM enrolments e
JOIN users u ON e.user_id = u.user_id
JOIN courses c ON e.course_id = c.course_id
ORDER BY e.enrolled_at DESC
LIMIT 10;

-- 查看完成率最高的課程
SELECT 
    c.description as course_name,
    COUNT(*) as total_enrolments,
    SUM(CASE WHEN e.completion_status = 'completed' THEN 1 ELSE 0 END) as completed,
    ROUND(SUM(CASE WHEN e.completion_status = 'completed' THEN 1 ELSE 0 END) * 100.0 / COUNT(*), 2) as completion_rate
FROM enrolments e
JOIN courses c ON e.course_id = c.course_id
GROUP BY c.course_id, c.description
ORDER BY completion_rate DESC;

-- 查看最活躍的用戶
SELECT 
    u.first_name,
    u.surname,
    COUNT(*) as total_enrolments,
    SUM(CASE WHEN e.completion_status = 'completed' THEN 1 ELSE 0 END) as completed_courses
FROM enrolments e
JOIN users u ON e.user_id = u.user_id
GROUP BY u.user_id, u.first_name, u.surname
ORDER BY total_enrolments DESC, completed_courses DESC;

-- ================================================================
-- 完成！
-- 執行完此 SQL 文件後，您將擁有：
-- - 1 個數據庫 (course_management)
-- - 3 個表 (users, courses, enrolments)
-- - 20 個用戶
-- - 20 門課程
-- - 100 條註冊記錄
-- ================================================================