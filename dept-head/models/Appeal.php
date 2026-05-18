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

function getAppealsByDepartment($conn, $department_id) {
    $appeals = [];
    $query = "SELECT a.*, c.code AS course_code, c.title AS course_title, u.name AS student_name, s.student_id_number 
              FROM academic_appeals a
              JOIN courses c ON a.course_id = c.id
              JOIN programs p ON c.program_id = p.id
              JOIN students s ON a.student_id = s.id
              JOIN users u ON s.user_id = u.id
              WHERE p.department_id = ?
              ORDER BY a.created_at DESC";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $department_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $appeals[] = $row;
    }
    $stmt->close();
    return $appeals;
}

function updateAppealStatus($conn, $appeal_id, $status, $department_id) {
    $stmt = $conn->prepare("UPDATE academic_appeals a 
                            JOIN courses c ON a.course_id = c.id
                            JOIN programs p ON c.program_id = p.id
                            SET a.status = ? 
                            WHERE a.id = ? AND p.department_id = ?");
    $stmt->bind_param("sii", $status, $appeal_id, $department_id);
    $success = $stmt->execute();
    $stmt->close();
    return $success;
}
?>