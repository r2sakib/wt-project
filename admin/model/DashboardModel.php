<?php
require_once 'db.php';

function getDashboardStats() {
    $conn = getConnection();
    $stats = [];

    $stats['users_by_role'] = [];
    $result = mysqli_query($conn, "SELECT role, COUNT(id) as count FROM users GROUP BY role");
    while ($row = mysqli_fetch_assoc($result)) {
        $stats['users_by_role'][$row['role']] = $row['count'];
    }

    $result = mysqli_query($conn, "SELECT COUNT(s.id) as count FROM students s JOIN users u ON s.user_id = u.id WHERE u.is_active = 1");
    $stats['active_students'] = mysqli_fetch_assoc($result)['count'] ?? 0;

    $result = mysqli_query($conn, "SELECT COUNT(c.id) as count FROM courses c JOIN semesters s ON c.semester_id = s.id WHERE s.is_current = 1 AND c.status = 'open'");
    $stats['active_courses'] = mysqli_fetch_assoc($result)['count'] ?? 0;

    $result = mysqli_query($conn, "SELECT COUNT(id) as count FROM academic_appeals WHERE status = 'pending'");
    $stats['pending_appeals'] = mysqli_fetch_assoc($result)['count'] ?? 0;

    $query = "SELECT 
                SUM(CASE WHEN cgpa >= 3.75 THEN 1 ELSE 0 END) as great,
                SUM(CASE WHEN cgpa >= 3.0 AND cgpa < 3.75 THEN 1 ELSE 0 END) as good,
                SUM(CASE WHEN cgpa >= 2.5 AND cgpa < 3.0 THEN 1 ELSE 0 END) as average,
                SUM(CASE WHEN cgpa < 2.5 THEN 1 ELSE 0 END) as probation
              FROM students";
    $result = mysqli_query($conn, $query);
    $stats['cgpa_distribution'] = mysqli_fetch_assoc($result);

    mysqli_close($conn);
    return $stats;
}
?>