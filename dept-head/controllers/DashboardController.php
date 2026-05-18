<?php
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'head') {
    exit("Unauthorized access.");
}

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Dashboard.php';

$stats = [
    'total_students'  => 0,
    'active_courses'  => 0,
    'pending_appeals' => 0,
    'avg_cgpa'        => 0.00
];

if (isset($conn)) {
    $stats = getDashboardStats($conn);
}
?>