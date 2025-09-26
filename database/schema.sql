-- Create database
CREATE DATABASE IF NOT EXISTS course_management;
USE course_management;

-- Create users table
CREATE TABLE IF NOT EXISTS users (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    first_name VARCHAR(100) NOT NULL,
    surname VARCHAR(100) NOT NULL,
    email VARCHAR(255) UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create courses table
CREATE TABLE IF NOT EXISTS courses (
    course_id INT PRIMARY KEY AUTO_INCREMENT,
    description TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create enrolments table
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

-- Create indexes for better performance
CREATE INDEX idx_enrolments_user_id ON enrolments(user_id);
CREATE INDEX idx_enrolments_course_id ON enrolments(course_id);
CREATE INDEX idx_enrolments_status ON enrolments(completion_status);
CREATE INDEX idx_users_name ON users(first_name, surname);