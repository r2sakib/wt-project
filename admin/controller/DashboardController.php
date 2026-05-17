<?php
session_start();

if (!isset($_SESSION['admin_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../view/login.php");
    exit();
}

require_once '../model/DashboardModel.php';

$stats = getDashboardStats();

include '../view/dashboard.php';
?>