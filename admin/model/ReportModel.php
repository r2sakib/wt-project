<?php
require_once __DIR__ . '/db.php';

function getEnrollmentsPerProgram() {
    $conn = getConnection();
    $sql = "SELECT p.name as program_name, p.code as program_code, d.code as dept_code, COUNT(s.id) as total_students 
            FROM programs p 
            JOIN departments d ON p.department_id = d.id 
            LEFT JOIN students s ON p.id = s.program_id 
            GROUP BY p.id 
            ORDER BY total_students DESC";
    $result = mysqli_query($conn, $sql);
    $data = [];
    while ($row = mysqli_fetch_assoc($result)) { $data[] = $row; }
    mysqli_close($conn);
    return $data;
}

function getAverageCgpaPerDepartment() {
    $conn = getConnection();
    $sql = "SELECT d.name as dept_name, d.code as dept_code, AVG(s.cgpa) as avg_cgpa 
            FROM departments d 
            LEFT JOIN programs p ON d.id = p.department_id 
            LEFT JOIN students s ON p.id = s.program_id 
            GROUP BY d.id 
            ORDER BY avg_cgpa DESC";
    $result = mysqli_query($conn, $sql);
    $data = [];
    while ($row = mysqli_fetch_assoc($result)) { $data[] = $row; }
    mysqli_close($conn);
    return $data;
}

function getPassRatePerCourse() {
    $conn = getConnection();
    $sql = "SELECT c.title, c.code, COUNT(e.id) as total_enrolled 
            FROM courses c 
            LEFT JOIN enrollments e ON c.id = e.course_id 
            GROUP BY c.id 
            ORDER BY c.title";
    $result = mysqli_query($conn, $sql);
    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $row['pass_rate'] = $row['total_enrolled'] > 0 ? 100 : 0;
        $data[] = $row;
    }
    mysqli_close($conn);
    return $data;
}

function getProbationStudents() {
    $conn = getConnection();
    $sql = "SELECT u.name, u.email, p.code as program_code, s.cgpa 
            FROM students s 
            JOIN users u ON s.user_id = u.id 
            JOIN programs p ON s.program_id = p.id 
            WHERE s.cgpa < 2.50 
            ORDER BY s.cgpa ASC";
    $result = mysqli_query($conn, $sql);
    $data = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) { $data[] = $row; }
    }
    mysqli_close($conn);
    return $data;
}
?>