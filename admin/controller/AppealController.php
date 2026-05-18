<?php
session_start();

if (!isset($_SESSION['admin_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: AuthController.php");
    exit();
}

require_once __DIR__ . '/../model/AppealModel.php';

$action = $_POST['action'] ?? $_GET['action'] ?? 'list';

switch ($action) {
    case 'list':
        $appeals = getEscalatedAppeals();
        include __DIR__ . '/../view/appeal_manage.php';
        break;

    case 'resolve_form':
        $appeal_id = $_GET['appeal_id'];
        $appeal = getAppealById($appeal_id);
        include __DIR__ . '/../view/appeal_resolve.php';
        break;

    case 'resolve_submit':
        $id = $_POST['appeal_id'];
        $status = $_POST['status'];
        $admin_note = trim($_POST['admin_note']);
        
        if (!empty($status)) {
            resolveAppeal($id, $status, $admin_note);
        }
        header("Location: AppealController.php?action=list");
        exit();
        break;

    default:
        header("Location: AppealController.php?action=list");
        exit();
}
?>