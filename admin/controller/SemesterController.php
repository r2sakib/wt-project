<?php
session_start();

if (!isset($_SESSION['admin_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: AuthController.php");
    exit();
}

require_once __DIR__ . '/../model/SemesterModel.php';

$action = $_POST['action'] ?? $_GET['action'] ?? 'list';

switch ($action) {
    case 'list':
        $semesters = getAllSemesters();
        include __DIR__ . '/../view/semester_manage.php';
        break;

    case 'add_form':
        include __DIR__ . '/../view/semester_add.php';
        break;

    case 'add_submit':
        $name = trim($_POST['name']);
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
        $drop_deadline = $_POST['drop_deadline'];
        $grade_deadline = $_POST['grade_submission_deadline'];
        
        if (!empty($name) && !empty($start_date) && !empty($end_date)) {
            addSemester($name, $start_date, $end_date, $drop_deadline, $grade_deadline);
        }
        header("Location: SemesterController.php?action=list");
        exit();
        break;

    default:
        header("Location: SemesterController.php?action=list");
        exit();
}
?>