<?php
require_once __DIR__ . '/db.php';

function getEscalatedAppeals() {
    $conn = getConnection();
    $sql = "SELECT a.id, a.reason, a.status, u.name as student_name, c.code as course_code, c.title as course_title
            FROM academic_appeals a
            JOIN students s ON a.student_id = s.id
            JOIN users u ON s.user_id = u.id
            JOIN enrollments e ON a.enrollment_id = e.id
            JOIN courses c ON e.course_id = c.id
            WHERE a.is_escalated = 1
            ORDER BY a.created_at ASC";
    $result = mysqli_query($conn, $sql);
    $appeals = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $appeals[] = $row;
    }
    mysqli_close($conn);
    return $appeals;
}

function getAppealById($id) {
    $conn = getConnection();
    $stmt = mysqli_prepare($conn, "SELECT a.*, u.name as student_name, u.email as student_email, c.code as course_code, c.title as course_title 
                                   FROM academic_appeals a
                                   JOIN students s ON a.student_id = s.id
                                   JOIN users u ON s.user_id = u.id
                                   JOIN enrollments e ON a.enrollment_id = e.id
                                   JOIN courses c ON e.course_id = c.id
                                   WHERE a.id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $appeal = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return $appeal;
}

function resolveAppeal($id, $status, $admin_note) {
    $conn = getConnection();
    $stmt = mysqli_prepare($conn, "UPDATE academic_appeals SET status = ?, admin_note = ? WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "ssi", $status, $admin_note, $id);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return $success;
}
?>