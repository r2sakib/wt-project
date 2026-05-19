<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'head') {
    exit("Unauthorized access.");
}

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Report.php';

$department_id = getDepartmentIdByHead($conn, $_SESSION['user_id']);

$performance_data = [];
$cgpa_data = [
    'excellent' => 0,
    'very_good' => 0,
    'good' => 0,
    'passing' => 0,
    'probation' => 0
];
$workload_data = [];

if ($department_id) {
    $performance_data = getCoursePerformanceReport($conn, $department_id);
    
    $fetched_cgpa = getCGPADistributionReport($conn, $department_id);
    if ($fetched_cgpa) {
        $cgpa_data = $fetched_cgpa;
    }
    
    $workload_data = getFacultyWorkloadReport($conn, $department_id);
}
?>