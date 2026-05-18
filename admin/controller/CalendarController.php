<?php
session_start();

if (!isset($_SESSION['admin_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: AuthController.php");
    exit();
}

require_once __DIR__ . '/../model/CalendarModel.php';

$action = $_POST['action'] ?? $_GET['action'] ?? 'list';

switch ($action) {
    case 'list':
        $events = getAllEvents();
        include __DIR__ . '/../view/calendar_manage.php';
        break;

    case 'add_form':
        $semesters = getSemestersForCalendar();
        include __DIR__ . '/../view/calendar_add.php';
        break;

    case 'add_submit':
        $semester_id = $_POST['semester_id'];
        $event_name = trim($_POST['event_name']);
        $event_date = $_POST['event_date'];
        $description = trim($_POST['description']);
        $visible_to = $_POST['visible_to'];
        
        if (!empty($semester_id) && !empty($event_name) && !empty($event_date)) {
            addEvent($semester_id, $event_name, $event_date, $description, $visible_to);
        }
        header("Location: CalendarController.php?action=list");
        exit();
        break;

    case 'delete':
        $id = $_POST['event_id'];
        deleteEvent($id);
        header("Location: CalendarController.php?action=list");
        exit();
        break;

    default:
        header("Location: CalendarController.php?action=list");
        exit();
}
?>