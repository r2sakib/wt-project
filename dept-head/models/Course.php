<?php
function getDepartmentIdByHead($conn, $head_id) {
    $department_id = null;
    $stmt = $conn->prepare("SELECT id FROM departments WHERE head_id = ? LIMIT 1");
    $stmt->bind_param("i", $head_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $department_id = $row['id'];
    }
    $stmt->close();
    return $department_id;
}

function getCoursesByDepartment($conn, $department_id) {
    $courses = [];
    $query = "SELECT c.*, p.name AS program_name, p.code AS program_code, s.name AS semester_name, u.name AS faculty_name 
              FROM courses c
              JOIN programs p ON c.program_id = p.id
              LEFT JOIN semesters s ON c.semester_id = s.id
              LEFT JOIN faculty f ON c.faculty_id = f.id
              LEFT JOIN users u ON f.user_id = u.id
              WHERE p.department_id = ?
              ORDER BY c.code ASC";
              
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $department_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $courses[] = $row;
    }
    $stmt->close();
    return $courses;
}

function getCourseById($conn, $id, $department_id) {
    $course = null;
    $query = "SELECT c.* FROM courses c 
              JOIN programs p ON c.program_id = p.id 
              WHERE c.id = ? AND p.department_id = ? LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $id, $department_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $course = $row;
    }
    $stmt->close();
    return $course;
}

function addCourse($conn, $program_id, $semester_id, $faculty_id, $code, $title, $credit_hours, $max_seats, $status) {
    $stmt = $conn->prepare("INSERT INTO courses (program_id, semester_id, faculty_id, code, title, credit_hours, max_seats, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iiissiis", $program_id, $semester_id, $faculty_id, $code, $title, $credit_hours, $max_seats, $status);
    $success = $stmt->execute();
    $stmt->close();
    return $success;
}

function updateCourse($conn, $id, $program_id, $semester_id, $faculty_id, $code, $title, $credit_hours, $max_seats, $status) {
    $stmt = $conn->prepare("UPDATE courses SET program_id = ?, semester_id = ?, faculty_id = ?, code = ?, title = ?, credit_hours = ?, max_seats = ?, status = ? WHERE id = ?");
    $stmt->bind_param("iiissiisi", $program_id, $semester_id, $faculty_id, $code, $title, $credit_hours, $max_seats, $status, $id);
    $success = $stmt->execute();
    $stmt->close();
    return $success;
}

function assignFacultyToCourse($conn, $course_id, $faculty_id) {
    $stmt = $conn->prepare("UPDATE courses SET faculty_id = ? WHERE id = ?");
    $stmt->bind_param("ii", $faculty_id, $course_id);
    $success = $stmt->execute();
    $stmt->close();
    return $success;
}

function deleteCourse($conn, $id) {
    $stmt = $conn->prepare("DELETE FROM courses WHERE id = ?");
    $stmt->bind_param("i", $id);
    $success = $stmt->execute();
    $stmt->close();
    return $success;
}

function getProgramsByDepartmentList($conn, $department_id) {
    $programs = [];
    $stmt = $conn->prepare("SELECT id, name, code FROM programs WHERE department_id = ? ORDER BY name ASC");
    $stmt->bind_param("i", $department_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $programs[] = $row;
    }
    $stmt->close();
    return $programs;
}

function getSemestersList($conn) {
    $semesters = [];
    $result = $conn->query("SELECT id, name FROM semesters ORDER BY start_date DESC");
    while ($row = $result->fetch_assoc()) {
        $semesters[] = $row;
    }
    return $semesters;
}

function getFacultyByDepartmentList($conn, $department_id) {
    $faculty = [];
    $query = "SELECT f.id, u.name FROM faculty f 
              JOIN users u ON f.user_id = u.id 
              WHERE f.department_id = ? AND u.is_active = TRUE ORDER BY u.name ASC";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $department_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $faculty[] = $row;
    }
    $stmt->close();
    return $faculty;
}

function filterCourses($conn, $department_id, $program_name, $status) {
    $courses = [];
    $query = "SELECT c.*, p.name AS program_name, u.name AS faculty_name 
              FROM courses c
              JOIN programs p ON c.program_id = p.id
              LEFT JOIN faculty f ON c.faculty_id = f.id
              LEFT JOIN users u ON f.user_id = u.id
              WHERE p.department_id = ?";
    
    $params = [$department_id];
    $types = "i";

    if (!empty($program_name)) {
        $query .= " AND p.name = ?";
        $params[] = $program_name;
        $types .= "s";
    }

    if (!empty($status)) {
        $query .= " AND c.status = ?";
        $params[] = strtolower($status);
        $types .= "s";
    }

    $query .= " ORDER BY c.code ASC";
    $stmt = $conn->prepare($query);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $courses[] = $row;
    }
    
    $stmt->close();
    return $courses;
}
?>