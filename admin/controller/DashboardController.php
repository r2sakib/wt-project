<?php
session_start();

// Role-based access control
if (!isset($_SESSION['admin_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../view/login.php");
    exit();
}

require_once '../model/DashboardModel.php';

// Fetch all the stats
$stats = getDashboardStats();

// Load the view and pass the data (the view will be able to use the $stats variable)
include '../view/dashboard.php';
?>