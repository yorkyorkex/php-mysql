-- MySQL Workbench 查詢示例
-- 複製這些查詢到 MySQL Workbench 中執行

-- 1. 查看所有表
USE course_management;
SHOW TABLES;

-- 2. 用戶統計
SELECT COUNT(*) as total_users FROM users;

-- 3. 課程統計  
SELECT COUNT(*) as total_courses FROM courses;

-- 4. 註冊統計
SELECT COUNT(*) as total_enrolments FROM enrolments;

-- 5. 完成狀態分佈
SELECT 
    completion_status,
    COUNT(*) as count,
    ROUND(COUNT(*) * 100.0 / (SELECT COUNT(*) FROM enrolments), 2) as percentage
FROM enrolments 
GROUP BY completion_status;

-- 6. 查看前 10 個用戶
SELECT user_id, first_name, surname, email 
FROM users 
ORDER BY user_id 
LIMIT 10;

-- 7. 查看所有課程
SELECT course_id, description 
FROM courses 
ORDER BY course_id;

-- 8. 查看用戶註冊詳情（前 10 筆）
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

-- 9. 查找特定用戶（例如 Johnny Smith）
SELECT 
    u.first_name,
    u.surname,
    c.description as course,
    e.completion_status
FROM enrolments e
JOIN users u ON e.user_id = u.user_id  
JOIN courses c ON e.course_id = c.course_id
WHERE u.first_name = 'Johnny';

-- 10. 完成率最高的課程
SELECT 
    c.description as course_name,
    COUNT(*) as total_enrolments,
    SUM(CASE WHEN e.completion_status = 'completed' THEN 1 ELSE 0 END) as completed,
    ROUND(SUM(CASE WHEN e.completion_status = 'completed' THEN 1 ELSE 0 END) * 100.0 / COUNT(*), 2) as completion_rate
FROM enrolments e
JOIN courses c ON e.course_id = c.course_id
GROUP BY c.course_id, c.description
ORDER BY completion_rate DESC;