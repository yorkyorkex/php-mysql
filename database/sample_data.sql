USE course_management;

-- Insert sample users
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

-- Insert sample courses
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

-- Insert sample enrolments (creating at least 100 different combinations)
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

-- Update completion dates for completed courses
UPDATE enrolments 
SET completed_at = DATE_SUB(NOW(), INTERVAL FLOOR(RAND() * 365) DAY)
WHERE completion_status = 'completed';