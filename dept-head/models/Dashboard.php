<?php
function getTotalStudentsCount($conn, $department_id) {
    $stmt = $conn->prepare("SELECT COUNT(s.id) AS count FROM students s JOIN programs p ON s.program_id = p.id WHERE p.department_id = ? AND s.academic_status != 'dismissed'");
    $stmt->bind_param("i", $department_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    return $result['count'] ?? 0;
}

function getActiveCoursesCount($conn, $department_id) {
    $stmt = $conn->prepare("SELECT COUNT(c.id) AS count FROM courses c JOIN programs p ON c.program_id = p.id WHERE p.department_id = ? AND c.status = 'open'");
    $stmt->bind_param("i", $department_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    return $result['count'] ?? 0;
}

function getPendingAppealsCount($conn, $department_id) {
    $stmt = $conn->prepare("SELECT COUNT(a.id) AS count FROM academic_appeals a JOIN courses c ON a.course_id = c.id JOIN programs p ON c.program_id = p.id WHERE p.department_id = ? AND a.status = 'pending'");
    $stmt->bind_param("i", $department_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    return $result['count'] ?? 0;
}

function getAverageCgpa($conn, $department_id) {
    $stmt = $conn->prepare("SELECT AVG(s.cgpa) AS average FROM students s JOIN programs p ON s.program_id = p.id WHERE p.department_id = ? AND s.academic_status != 'dismissed'");
    $stmt->bind_param("i", $department_id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    return isset($row['average']) ? round($row['average'], 2) : 0.00;
}

function getRecentPendingAppeals($conn, $department_id, $limit = 3) {
    $appeals = [];
    $query = "SELECT a.*, c.code AS course_code, u.name AS student_name FROM academic_appeals a JOIN courses c ON a.course_id = c.id JOIN programs p ON c.program_id = p.id JOIN students s ON a.student_id = s.id JOIN users u ON s.user_id = u.id WHERE p.department_id = ? AND a.status = 'pending' ORDER BY a.created_at DESC LIMIT ?";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $department_id, $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $appeals[] = $row;
    }
    $stmt->close();
    return $appeals;
}
?>