SET FOREIGN_KEY_CHECKS = 0;

-- Drop old schema instances to prevent conflicts
DROP TABLE IF EXISTS audit_logs;
DROP TABLE IF EXISTS system_announcements;
DROP TABLE IF EXISTS grading_scales;
DROP TABLE IF EXISTS advisor_notes;
DROP TABLE IF EXISTS academic_calendar;
DROP TABLE IF EXISTS academic_appeals;
DROP TABLE IF EXISTS course_announcements;
DROP TABLE IF EXISTS course_materials;
DROP TABLE IF EXISTS grade_entries;
DROP TABLE IF EXISTS enrollments;
DROP TABLE IF EXISTS courses;
DROP TABLE IF EXISTS semesters;
DROP TABLE IF EXISTS faculty;
DROP TABLE IF EXISTS students;
DROP TABLE IF EXISTS programs;
DROP TABLE IF EXISTS departments;
DROP TABLE IF EXISTS users;

-- 1. Users Table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    role ENUM('student', 'faculty', 'head', 'admin') NOT NULL,
    profile_pic VARCHAR(255),
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 2. Departments Table
CREATE TABLE IF NOT EXISTS departments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    code VARCHAR(50) NOT NULL UNIQUE,
    head_id INT,
    description TEXT,
    FOREIGN KEY (head_id) REFERENCES users(id) ON DELETE SET NULL
);

-- 3. Programs Table
CREATE TABLE IF NOT EXISTS programs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    department_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    code VARCHAR(50) NOT NULL UNIQUE,
    total_credit_hours INT NOT NULL,
    duration_years INT NOT NULL,
    description TEXT,
    FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE CASCADE
);

-- 4. Students Profile Table
CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL UNIQUE,
    student_id_number VARCHAR(50) NOT NULL UNIQUE,
    program_id INT NOT NULL,
    current_semester INT DEFAULT 1,
    admission_date DATE,
    academic_status ENUM('good_standing', 'probation', 'dismissed') DEFAULT 'good_standing',
    cgpa DECIMAL(4,2) DEFAULT 0.00,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (program_id) REFERENCES programs(id)
);

-- 5. Faculty Profile Table
CREATE TABLE IF NOT EXISTS faculty (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL UNIQUE,
    department_id INT NOT NULL,
    designation VARCHAR(100),
    office_room VARCHAR(100),
    office_hours VARCHAR(255),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (department_id) REFERENCES departments(id)
);

-- 6. Semesters Table
CREATE TABLE IF NOT EXISTS semesters (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    is_current BOOLEAN DEFAULT FALSE,
    drop_deadline DATE,
    grade_submission_deadline DATE
);

-- 7. Courses Table
CREATE TABLE IF NOT EXISTS courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    program_id INT NOT NULL,
    semester_id INT NOT NULL,
    faculty_id INT,
    code VARCHAR(50) NOT NULL,
    title VARCHAR(255) NOT NULL,
    credit_hours INT NOT NULL,
    max_seats INT NOT NULL,
    status ENUM('open', 'closed', 'completed') DEFAULT 'open',
    FOREIGN KEY (program_id) REFERENCES programs(id),
    FOREIGN KEY (semester_id) REFERENCES semesters(id),
    FOREIGN KEY (faculty_id) REFERENCES faculty(id) ON DELETE SET NULL
);

-- 8. Enrollments Table
CREATE TABLE IF NOT EXISTS enrollments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    course_id INT NOT NULL,
    enrolled_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('active', 'dropped', 'completed') DEFAULT 'active',
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
    UNIQUE KEY unique_enrollment (student_id, course_id)
);

-- 9. Grade Entries Table
CREATE TABLE IF NOT EXISTS grade_entries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    enrollment_id INT NOT NULL UNIQUE,
    ct_mark DECIMAL(5,2) DEFAULT 0.00,
    mid_mark DECIMAL(5,2) DEFAULT 0.00,
    final_mark DECIMAL(5,2) DEFAULT 0.00,
    attendance_pct DECIMAL(5,2) DEFAULT 0.00,
    total_mark DECIMAL(5,2) DEFAULT 0.00,
    letter_grade VARCHAR(5),
    gpa_point DECIMAL(4,2),
    is_published BOOLEAN DEFAULT FALSE,
    graded_by INT,
    graded_at DATETIME ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (enrollment_id) REFERENCES enrollments(id) ON DELETE CASCADE,
    FOREIGN KEY (graded_by) REFERENCES users(id) ON DELETE SET NULL
);

-- 10. Course Materials Table
CREATE TABLE IF NOT EXISTS course_materials (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT NOT NULL,
    uploaded_by INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    material_type VARCHAR(50),
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
    FOREIGN KEY (uploaded_by) REFERENCES users(id) ON DELETE CASCADE
);

-- 11. Course Announcements Table
CREATE TABLE IF NOT EXISTS course_announcements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT NOT NULL,
    author_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    body TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
    FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE CASCADE
);

-- 12. Academic Appeals Table
CREATE TABLE IF NOT EXISTS academic_appeals (
    id INT AUTO_INCREMENT PRIMARY KEY,
    enrollment_id INT NOT NULL,
    student_id INT NOT NULL,
    reason TEXT NOT NULL,
    status ENUM('pending', 'under_review', 'approved', 'rejected') DEFAULT 'pending',
    head_note TEXT,
    faculty_comment TEXT,
    is_escalated BOOLEAN DEFAULT FALSE,
    admin_note TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (enrollment_id) REFERENCES enrollments(id) ON DELETE CASCADE,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
);

-- 13. Academic Calendar Table
CREATE TABLE IF NOT EXISTS academic_calendar (
    id INT AUTO_INCREMENT PRIMARY KEY,
    semester_id INT,
    event_name VARCHAR(255) NOT NULL,
    event_date DATE NOT NULL,
    description TEXT,
    visible_to ENUM('all', 'student', 'faculty') DEFAULT 'all',
    FOREIGN KEY (semester_id) REFERENCES semesters(id) ON DELETE CASCADE
);

-- 14. Advisor Notes Table
CREATE TABLE IF NOT EXISTS advisor_notes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    head_id INT NOT NULL,
    note TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (head_id) REFERENCES users(id) ON DELETE CASCADE
);

-- 15. Grading Scales Table
CREATE TABLE IF NOT EXISTS grading_scales (
    id INT AUTO_INCREMENT PRIMARY KEY,
    min_mark DECIMAL(5,2) NOT NULL,
    max_mark DECIMAL(5,2) NOT NULL,
    letter_grade VARCHAR(5) NOT NULL,
    gpa_point DECIMAL(4,2) NOT NULL
);

-- 16. System Announcements Table
CREATE TABLE IF NOT EXISTS system_announcements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    author_id INT NOT NULL,
    scope ENUM('department', 'platform') NOT NULL,
    department_id INT,
    title VARCHAR(255) NOT NULL,
    body TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE CASCADE
);

-- 17. Audit Logs Table
CREATE TABLE IF NOT EXISTS audit_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    action_details TEXT NOT NULL,
    ip_address VARCHAR(45),
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

SET FOREIGN_KEY_CHECKS = 1;

-- ========================================================
-- SYSTEM SEED ROWS CONFIGURATION
-- ========================================================

-- Dynamic Grading Matrix Values
INSERT INTO grading_scales (min_mark, max_mark, letter_grade, gpa_point) VALUES
(90.00, 100.00, 'A+', 4.00),
(85.00, 89.99, 'A', 3.75),
(80.00, 84.99, 'B+', 3.50),
(75.00, 79.99, 'B', 3.25),
(70.00, 74.99, 'C+', 3.00),
(65.00, 69.99, 'C', 2.75),
(60.00, 64.99, 'D+', 2.50),
(50.00, 59.99, 'D', 2.25),
(0.00, 49.99, 'F', 0.00);

-- Core Department and Educational Program
INSERT INTO departments (id, name, code, description) VALUES (1, 'Computer Science & Engineering', 'CSE', 'Core Technology Wing');
INSERT INTO programs (id, department_id, name, code, total_credit_hours, duration_years) VALUES (1, 1, 'Bachelor of Science in CSE', 'BS-CSE', 148, 4);

-- Semesters Configuration
INSERT INTO semesters (id, name, start_date, end_date, is_current, grade_submission_deadline) VALUES 
(1, 'Spring 2026', '2026-01-10', '2026-05-30', 1, '2026-06-15');

-- Base User Accounts
-- Password hash represents 'Toha12344' for your custom faculty node profile
INSERT INTO users (id, name, email, password_hash, role, profile_pic) VALUES
(1, 'System Administrator', 'admin@portal.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', NULL),
(2, 'Department Chairman', 'head@portal.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'head', NULL),
(3, 'MD TOHA', 'toha3132@portal.edu', '$2y$10$X87S10A1H7b.2YxUp6vXOuM0qZ/vj2fVvB9XhWd9gK9uH8rIeC6G.', 'faculty', 'uploads/profiles/default.png'),
(4, 'Student Node A', 'student01@portal.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student', NULL),
(5, 'Student Node B', 'student02@portal.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student', NULL);

-- Sub-Profile Tables Reference Map
INSERT INTO faculty (id, user_id, department_id, designation, office_room, office_hours) VALUES
(1, 3, 1, 'Assistant Professor', 'Building A, Room 313', 'Mon/Wed 11:00 AM - 01:00 PM');

INSERT INTO students (id, user_id, student_id_number, program_id, current_semester) VALUES
(1, 4, '23-50001-1', 1, 8),
(2, 5, '23-50002-1', 1, 8);

-- Courses Injection Mapping
INSERT INTO courses (id, program_id, semester_id, faculty_id, code, title, credit_hours, max_seats) VALUES
(1, 1, 1, 1, 'CSC3112', 'Web Technologies', 3, 40),
(2, 1, 1, 1, 'CSC4118', 'Advanced Database Systems', 3, 40);

-- Linking Matrix Enrollment & Grading Records
INSERT INTO enrollments (id, student_id, course_id, status) VALUES 
(1, 1, 1, 'active'),
(2, 2, 1, 'active'),
(3, 1, 2, 'active');

INSERT INTO grade_entries (enrollment_id, ct_mark, mid_mark, final_mark, attendance_pct, total_mark, letter_grade, gpa_point, is_published) VALUES
(1, 18.00, 26.00, 44.00, 95.00, 88.00, 'A', 3.75, 0),
(2, 14.00, 21.00, 36.00, 82.00, 71.00, 'C+', 3.00, 0),
(3, 19.00, 28.00, 46.00, 98.00, 93.00, 'A+', 4.00, 0);