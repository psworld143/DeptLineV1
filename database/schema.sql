-- DeptLine Database Schema

-- Create database
CREATE DATABASE IF NOT EXISTS deptline_db;
USE deptline_db;

-- Departments table
CREATE TABLE IF NOT EXISTS departments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    email VARCHAR(100),
    phone VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Categories table
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    priority_level ENUM('low', 'medium', 'high', 'urgent') DEFAULT 'medium',
    department_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE SET NULL
);

-- Concerns table
CREATE TABLE IF NOT EXISTS concerns (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tracking_number VARCHAR(20) UNIQUE NOT NULL,
    title VARCHAR(200) NOT NULL,
    description TEXT NOT NULL,
    category_id INT,
    department_id INT,
    student_name VARCHAR(100),
    student_email VARCHAR(100),
    student_id VARCHAR(20),
    is_anonymous BOOLEAN DEFAULT FALSE,
    priority_level ENUM('low', 'medium', 'high', 'urgent') DEFAULT 'medium',
    status ENUM('pending', 'in_progress', 'resolved', 'closed') DEFAULT 'pending',
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL,
    FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE SET NULL
);

-- Responses table
CREATE TABLE IF NOT EXISTS responses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    concern_id INT NOT NULL,
    response_text TEXT NOT NULL,
    responded_by VARCHAR(100),
    responded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (concern_id) REFERENCES concerns(id) ON DELETE CASCADE
);

-- Insert sample departments
INSERT INTO departments (name, description, email, phone) VALUES
('Academic Affairs', 'Handles academic-related concerns, course issues, and academic policies', 'academic@school.edu', '+1-555-0101'),
('Student Services', 'Manages student life, housing, and general student support', 'studentservices@school.edu', '+1-555-0102'),
('Mental Health Services', 'Provides mental health support and counseling services', 'mentalhealth@school.edu', '+1-555-0103'),
('Campus Safety', 'Handles security concerns, safety issues, and emergency situations', 'safety@school.edu', '+1-555-0104'),
('IT Support', 'Technical support and computer-related issues', 'itsupport@school.edu', '+1-555-0105'),
('Financial Aid', 'Financial aid, scholarships, and payment concerns', 'financialaid@school.edu', '+1-555-0106');

-- Insert sample categories
INSERT INTO categories (name, description, priority_level, department_id) VALUES
-- Academic Affairs
('Course Registration', 'Issues with course registration and enrollment', 'medium', 1),
('Grade Disputes', 'Grade-related concerns and appeals', 'high', 1),
('Academic Policies', 'Questions about academic policies and procedures', 'medium', 1),
('Faculty Issues', 'Concerns about faculty members or teaching', 'high', 1),

-- Student Services
('Housing Issues', 'Dormitory and housing-related problems', 'medium', 2),
('Student Life', 'General student life and activities', 'low', 2),
('Transportation', 'Campus transportation and parking issues', 'medium', 2),
('Food Services', 'Dining hall and food-related concerns', 'low', 2),

-- Mental Health Services
('Counseling Request', 'Request for mental health counseling', 'high', 3),
('Crisis Support', 'Urgent mental health crisis situations', 'urgent', 3),
('Wellness Programs', 'Wellness and mental health programs', 'low', 3),

-- Campus Safety
('Security Incident', 'Security-related incidents and concerns', 'urgent', 4),
('Safety Hazard', 'Physical safety hazards on campus', 'urgent', 4),
('Harassment', 'Harassment or bullying incidents', 'high', 4),
('Emergency', 'Emergency situations requiring immediate attention', 'urgent', 4),

-- IT Support
('Technical Issues', 'Computer and technology problems', 'medium', 5),
('Network Problems', 'Internet and network connectivity issues', 'medium', 5),
('Software Support', 'Software installation and usage help', 'low', 5),

-- Financial Aid
('Scholarship Questions', 'Questions about scholarships and financial aid', 'medium', 6),
('Payment Issues', 'Tuition and payment-related concerns', 'high', 6),
('Financial Planning', 'Financial planning and budgeting advice', 'low', 6);
