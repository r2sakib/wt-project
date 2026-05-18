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

function getStudentsByDepartment($conn, $department_id) {
    $students = [];
    $query = "SELECT s.*, p.name AS program_name, p.code AS program_code, u.name AS student_name 
              FROM students s
              JOIN programs p ON s.program_id = p.id
              JOIN users u ON s.user_id = u.id
              WHERE p.department_id = ?
              ORDER BY u.name ASC";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $department_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }
    $stmt->close();
    return $students;
}

function getStudentById($conn, $student_id, $department_id) {
    $student = null;
    $query = "SELECT s.*, p.name AS program_name, p.code AS program_code, d.name AS department_name, u.name AS student_name 
              FROM students s
              JOIN programs p ON s.program_id = p.id
              JOIN departments d ON p.department_id = d.id
              JOIN users u ON s.user_id = u.id
              WHERE s.id = ? AND p.department_id = ? LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $student_id, $department_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $student = $row;
    }
    $stmt->close();
    return $student;
}
?>