<?php
function getAppealsByDepartment($conn, $department_id) {
    $appeals = [];
    $query = "SELECT a.*, c.code AS course_code, c.title AS course_title, 
                     u.name AS student_name, s.student_id_number 
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

function processAppealDecision($conn, $appeal_id, $status, $head_note, $department_id) {
    $query = "UPDATE academic_appeals a 
              JOIN courses c ON a.course_id = c.id
              JOIN programs p ON c.program_id = p.id
              SET a.status = ?, a.head_note = ? 
              WHERE a.id = ? AND p.department_id = ?";
              
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssii", $status, $head_note, $appeal_id, $department_id);
    $success = $stmt->execute();
    $stmt->close();
    return $success;
}
?>