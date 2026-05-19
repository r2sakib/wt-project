<?php
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'head') {
    exit("Unauthorized access.");
}

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Student.php';
require_once __DIR__ . '/../models/Dashboard.php';

$department_id = getDepartmentIdByHead($conn, $_SESSION['user_id']);

$stats = [
    'total_students'  => 0,
    'active_courses'  => 0,
    'pending_appeals' => 0,
    'avg_cgpa'        => 0.00
];

$recent_appeals = [];

if ($department_id) {
    $stats['total_students'] = getTotalStudentsCount($conn, $department_id);
    $stats['active_courses'] = getActiveCoursesCount($conn, $department_id);
    $stats['pending_appeals'] = getPendingAppealsCount($conn, $department_id);
    $stats['avg_cgpa'] = getAverageCgpa($conn, $department_id);
    
    $recent_appeals = getRecentPendingAppeals($conn, $department_id, 3);
}
?>