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
    $query = "SELECT s.*, p.name AS program_name, p.code AS program_code, d.name AS department_name, u.name AS student_name, u.email AS student_email 
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

function updateStudentStatus($conn, $student_id, $status, $department_id) {
    $stmt = $conn->prepare("UPDATE students s
                            JOIN programs p ON s.program_id = p.id
                            SET s.academic_status = ? 
                            WHERE s.id = ? AND p.department_id = ?");
    $stmt->bind_param("sii", $status, $student_id, $department_id);
    $success = $stmt->execute();
    $stmt->close();
    return $success;
}

function getAdvisorNotesByStudent($conn, $student_id) {
    $notes = [];
    $query = "SELECT n.*, u.name AS head_name 
              FROM advisor_notes n
              JOIN users u ON n.head_id = u.id
              WHERE n.student_id = ?
              ORDER BY n.created_at DESC";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $notes[] = $row;
    }
    $stmt->close();
    return $notes;
}

function addAdvisorNote($conn, $student_id, $head_id, $note) {
    $stmt = $conn->prepare("INSERT INTO advisor_notes (student_id, head_id, note, created_at) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("iis", $student_id, $head_id, $note);
    $success = $stmt->execute();
    $stmt->close();
    return $success;
}
?>