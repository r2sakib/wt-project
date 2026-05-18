<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'head') {
    exit("Unauthorized access.");
}

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Course.php';

$department_id = null;
if (isset($conn)) {
    $department_id = getDepartmentIdByHead($conn, $_SESSION['user_id']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if (!$department_id) {
        $_SESSION['error_msg'] = "You are not assigned to any department yet.";
        header("Location: ../index.php?page=courses");
        exit();
    }

    $action = $_POST['action'];

    if ($action === 'add') {
        $program_id = (int)$_POST['program_id'];
        $semester_id = (int)$_POST['semester_id'];
        $faculty_id = !empty($_POST['faculty_id']) ? (int)$_POST['faculty_id'] : null;
        $code = strtoupper(trim($_POST['code']));
        $title = trim($_POST['title']);
        $credit_hours = (int)$_POST['credit_hours'];
        $max_seats = (int)$_POST['max_seats'];
        $status = $_POST['status'];

        if (addCourse($conn, $program_id, $semester_id, $faculty_id, $code, $title, $credit_hours, $max_seats, $status)) {
            $_SESSION['success_msg'] = "Course '$title' added successfully.";
        } else {
            $_SESSION['error_msg'] = "Error adding course.";
        }
        header("Location: ../index.php?page=courses");
        exit();
    }

    if ($action === 'edit') {
        $id = (int)$_POST['course_id'];
        $program_id = (int)$_POST['program_id'];
        $semester_id = (int)$_POST['semester_id'];
        $faculty_id = !empty($_POST['faculty_id']) ? (int)$_POST['faculty_id'] : null;
        $code = strtoupper(trim($_POST['code']));
        $title = trim($_POST['title']);
        $credit_hours = (int)$_POST['credit_hours'];
        $max_seats = (int)$_POST['max_seats'];
        $status = $_POST['status'];

        if (updateCourse($conn, $id, $program_id, $semester_id, $faculty_id, $code, $title, $credit_hours, $max_seats, $status)) {
            $_SESSION['success_msg'] = "Course updated successfully.";
        } else {
            $_SESSION['error_msg'] = "Error updating course.";
        }
        header("Location: ../index.php?page=courses");
        exit();
    }

    if ($action === 'assign_faculty') {
        $course_id = (int)$_POST['course_id'];
        $faculty_id = !empty($_POST['faculty_id']) ? (int)$_POST['faculty_id'] : null;

        if (assignFacultyToCourse($conn, $course_id, $faculty_id)) {
            $_SESSION['success_msg'] = "Faculty allocation updated successfully.";
        } else {
            $_SESSION['error_msg'] = "Error mapping faculty.";
        }
        header("Location: ../index.php?page=courses");
        exit();
    }

    if ($action === 'delete') {
        $id = (int)$_POST['course_id'];
        if (deleteCourse($conn, $id)) {
            $_SESSION['success_msg'] = "Course deleted successfully.";
        } else {
            $_SESSION['error_msg'] = "Cannot delete course. Operational records dependent.";
        }
        header("Location: ../index.php?page=courses");
        exit();
    }
}

$courses = [];
$programs = [];
$semesters = [];
$faculty_list = [];

if (isset($conn) && $department_id) {
    $courses = getCoursesByDepartment($conn, $department_id);
    $programs = getProgramsByDepartmentList($conn, $department_id);
    $semesters = getSemestersList($conn);
    $faculty_list = getFacultyByDepartmentList($conn, $department_id);
}
?>