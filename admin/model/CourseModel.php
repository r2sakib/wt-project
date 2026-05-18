<?php
require_once __DIR__ . '/db.php';

function getAllCourses() {
    $conn = getConnection();
    $sql = "SELECT c.*, s.name as semester_name, p.code as program_code, u.name as faculty_name 
            FROM courses c 
            JOIN semesters s ON c.semester_id = s.id 
            JOIN programs p ON c.program_id = p.id 
            LEFT JOIN users u ON c.faculty_id = u.id
            ORDER BY s.start_date DESC, c.title"; // Changed c.name to c.title
    $result = mysqli_query($conn, $sql);
    
    $courses = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $courses[] = $row;
    }
    mysqli_close($conn);
    return $courses;
}

function getCourseFormData() {
    $conn = getConnection();
    $data = ['semesters' => [], 'programs' => [], 'faculty' => []];

    $res = mysqli_query($conn, "SELECT id, name FROM semesters ORDER BY start_date DESC");
    while ($row = mysqli_fetch_assoc($res)) { $data['semesters'][] = $row; }

    $res = mysqli_query($conn, "SELECT id, name, code FROM programs ORDER BY name");
    while ($row = mysqli_fetch_assoc($res)) { $data['programs'][] = $row; }
    
    $res = mysqli_query($conn, "SELECT id, name FROM users WHERE role = 'faculty' AND is_active = 1 ORDER BY name");
    while ($row = mysqli_fetch_assoc($res)) { $data['faculty'][] = $row; }
    
    mysqli_close($conn);
    return $data;
}

function addCourse($title, $code, $semester_id, $program_id, $faculty_id, $max_seats) {
    $conn = getConnection();
    $status = 'open'; 
    $stmt = mysqli_prepare($conn, "INSERT INTO courses (title, code, semester_id, program_id, faculty_id, max_seats, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "ssiiiis", $title, $code, $semester_id, $program_id, $faculty_id, $max_seats, $status);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return $success;
}

function getCourseById($id) {
    $conn = getConnection();
    $stmt = mysqli_prepare($conn, "SELECT * FROM courses WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $course = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return $course;
}

function updateCourse($id, $title, $code, $semester_id, $program_id, $faculty_id, $max_seats) {
    $conn = getConnection();
    $stmt = mysqli_prepare($conn, "UPDATE courses SET title=?, code=?, semester_id=?, program_id=?, faculty_id=?, max_seats=? WHERE id=?");
    mysqli_stmt_bind_param($stmt, "ssiiiii", $title, $code, $semester_id, $program_id, $faculty_id, $max_seats, $id);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return $success;
}

function toggleCourseStatus($id, $new_status) {
    $conn = getConnection();
    $stmt = mysqli_prepare($conn, "UPDATE courses SET status=? WHERE id=?");
    mysqli_stmt_bind_param($stmt, "si", $new_status, $id);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return $success;
}
?>