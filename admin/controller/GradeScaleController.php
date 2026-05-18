<?php
session_start();

if (!isset($_SESSION['admin_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: AuthController.php");
    exit();
}

require_once __DIR__ . '/../model/GradeScaleModel.php';

$action = $_POST['action'] ?? $_GET['action'] ?? 'list';

switch ($action) {
    case 'list':
        $scales = getAllGradeScales();
        include __DIR__ . '/../view/grade_scale_manage.php';
        break;

    case 'add_form':
        include __DIR__ . '/../view/grade_scale_add.php';
        break;

    case 'add_submit':
        $min_mark = $_POST['min_mark'];
        $max_mark = $_POST['max_mark'];
        $letter_grade = trim($_POST['letter_grade']);
        $gpa_point = $_POST['gpa_point'];
        
        if (isset($min_mark) && isset($max_mark) && !empty($letter_grade) && isset($gpa_point)) {
            addGradeScale($min_mark, $max_mark, $letter_grade, $gpa_point);
        }
        header("Location: GradeScaleController.php?action=list");
        exit();
        break;

    case 'edit_form':
        $scale_id = $_GET['scale_id'];
        $scale = getGradeScaleById($scale_id);
        include __DIR__ . '/../view/grade_scale_edit.php';
        break;

    case 'edit_submit':
        $id = $_POST['scale_id'];
        $min_mark = $_POST['min_mark'];
        $max_mark = $_POST['max_mark'];
        $letter_grade = trim($_POST['letter_grade']);
        $gpa_point = $_POST['gpa_point'];
        
        if (isset($min_mark) && isset($max_mark) && !empty($letter_grade) && isset($gpa_point)) {
            updateGradeScale($id, $min_mark, $max_mark, $letter_grade, $gpa_point);
        }
        header("Location: GradeScaleController.php?action=list");
        exit();
        break;

    case 'delete':
        $id = $_POST['scale_id'];
        deleteGradeScale($id);
        header("Location: GradeScaleController.php?action=list");
        exit();
        break;

    default:
        header("Location: GradeScaleController.php?action=list");
        exit();
}
?>