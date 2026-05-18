<?php
session_start();

if (!isset($_SESSION['admin_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: AuthController.php");
    exit();
}

require_once __DIR__ . '/../model/SemesterReportModel.php';

$action = $_POST['action'] ?? $_GET['action'] ?? 'view';

switch ($action) {
    case 'view':
        $reports = getSemesterPerformanceSummary();
        include __DIR__ . '/../view/semester_report_view.php';
        break;

    default:
        header("Location: SemesterReportController.php?action=view");
        exit();
}
?>