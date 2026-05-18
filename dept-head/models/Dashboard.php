<?php
function getDashboardStats($conn) {
    $stats = [
        'total_students'  => 0,
        'active_courses'  => 0,
        'pending_appeals' => 0,
        'avg_cgpa'        => 0.00
    ];

    $res = $conn->query("SELECT COUNT(id) AS count FROM students WHERE academic_status != 'dismissed'");
    if ($res) $stats['total_students'] = $res->fetch_assoc()['count'];

    $res = $conn->query("SELECT COUNT(id) AS count FROM courses WHERE status = 'open'");
    if ($res) $stats['active_courses'] = $res->fetch_assoc()['count'];

    $res = $conn->query("SELECT COUNT(id) AS count FROM academic_appeals WHERE status = 'pending'");
    if ($res) $stats['pending_appeals'] = $res->fetch_assoc()['count'];

    $res = $conn->query("SELECT AVG(cgpa) AS average FROM students WHERE academic_status != 'dismissed'");
    if ($res) {
        $row = $res->fetch_assoc();
        $stats['avg_cgpa'] = round($row['average'] ?? 0, 2);
    }

    return $stats;
}
?>