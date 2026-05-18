<?php
session_start();

if (!isset($_SESSION['admin_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: AuthController.php");
    exit();
}

require_once __DIR__ . '/../model/ReportModel.php';

$action = $_POST['action'] ?? $_GET['action'] ?? 'view';

switch ($action) {
    case 'view':
        $enrollments = getEnrollmentsPerProgram();
        $cgpaData = getAverageCgpaPerDepartment();
        $passRates = getPassRatePerCourse();
        $probationStudents = getProbationStudents();
        include __DIR__ . '/../view/report_view.php';
        break;

    default:
        header("Location: ReportController.php?action=view");
        exit();
}
?>