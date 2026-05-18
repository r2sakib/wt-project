<?php
session_start();

// Security check
if (!isset($_SESSION['admin_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: AuthController.php");
    exit();
}

require_once __DIR__ . '/../model/CourseModel.php';

$action = $_POST['action'] ?? $_GET['action'] ?? 'list';

switch ($action) {
    case 'list':
        $courses = getAllCourses();
        include __DIR__ . '/../view/course_manage.php';
        break;

    case 'add_form':
        $formData = getCourseFormData();
        include __DIR__ . '/../view/course_add.php';
        break;

    case 'add_submit':
        $name = trim($_POST['name']);
        $code = trim($_POST['code']);
        $semester_id = $_POST['semester_id'];
        $program_id = $_POST['program_id'];
        $faculty_id = !empty($_POST['faculty_id']) ? $_POST['faculty_id'] : null;
        $max_seats = $_POST['max_seats'];
        
        if (!empty($name) && !empty($code) && !empty($semester_id) && !empty($program_id)) {
            addCourse($name, $code, $semester_id, $program_id, $faculty_id, $max_seats);
        }
        header("Location: CourseController.php?action=list");
        exit();
        break;

    case 'edit_form':
        $course_id = $_GET['course_id'];
        $course = getCourseById($course_id);
        $formData = getCourseFormData();
        include __DIR__ . '/../view/course_edit.php';
        break;

    case 'edit_submit':
        $id = $_POST['course_id'];
        $name = trim($_POST['name']);
        $code = trim($_POST['code']);
        $semester_id = $_POST['semester_id'];
        $program_id = $_POST['program_id'];
        $faculty_id = !empty($_POST['faculty_id']) ? $_POST['faculty_id'] : null;
        $max_seats = $_POST['max_seats'];
        
        if (!empty($name) && !empty($code)) {
            updateCourse($id, $name, $code, $semester_id, $program_id, $faculty_id, $max_seats);
        }
        header("Location: CourseController.php?action=list");
        exit();
        break;

    case 'toggle_status':
        $id = $_POST['course_id'];
        $current_status = $_POST['current_status'];
        $new_status = ($current_status == 'open') ? 'closed' : 'open'; 
        
        toggleCourseStatus($id, $new_status);
        header("Location: CourseController.php?action=list");
        exit();
        break;

    default:
        header("Location: CourseController.php?action=list");
        exit();
}
?>