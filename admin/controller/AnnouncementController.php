<?php
session_start();

if (!isset($_SESSION['admin_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: AuthController.php");
    exit();
}

require_once __DIR__ . '/../model/AnnouncementModel.php';

$action = $_POST['action'] ?? $_GET['action'] ?? 'list';

switch ($action) {
    case 'list':
        $announcements = getAllAnnouncements();
        include __DIR__ . '/../view/announcement_manage.php';
        break;

    case 'add_form':
        include __DIR__ . '/../view/announcement_add.php';
        break;

    case 'add_submit':
        $title = trim($_POST['title']);
        $body = trim($_POST['body']);
        $scope = $_POST['scope'];
        $author_id = $_SESSION['admin_id'];
        
        if (!empty($title) && !empty($body)) {
            addAnnouncement($author_id, $scope, $title, $body);
        }
        header("Location: AnnouncementController.php?action=list");
        exit();
        break;

    case 'delete':
        $id = $_POST['announcement_id'];
        deleteAnnouncement($id);
        header("Location: AnnouncementController.php?action=list");
        exit();
        break;

    default:
        header("Location: AnnouncementController.php?action=list");
        exit();
}
?>