-- =====================================================
-- Student Registration Management System
-- MySQL Database Schema
-- =====================================================

CREATE DATABASE IF NOT EXISTS student_registration
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE student_registration;

-- Drop table if it already exists (for clean re-import)
DROP TABLE IF EXISTS students;

CREATE TABLE students (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    full_name     VARCHAR(150)       NOT NULL,
    email         VARCHAR(150)       NOT NULL UNIQUE,
    phone         VARCHAR(15)        NOT NULL,
    gender        ENUM('Male','Female','Other') NOT NULL,
    dob           DATE               NOT NULL,
    country       VARCHAR(100)       NOT NULL,
    skills        VARCHAR(255)       DEFAULT NULL,
    address       TEXT               DEFAULT NULL,
    profile_image VARCHAR(255)       DEFAULT NULL,
    created_at    TIMESTAMP          DEFAULT CURRENT_TIMESTAMP,
    updated_at    TIMESTAMP          DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Sample data (optional)
INSERT INTO students (full_name, email, phone, gender, dob, country, skills, address)
VALUES
('John Doe', 'john@example.com', '9876543210', 'Male', '1998-05-12', 'India', 'PHP, MySQL, JavaScript', '123 Main Street, Mumbai'),
('Jane Smith', 'jane@example.com', '9123456780', 'Female', '2000-09-23', 'United States', 'HTML, CSS, React', '45 Oak Avenue, New York');
