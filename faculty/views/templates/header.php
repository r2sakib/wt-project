<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Academic ERP Portal</title>
    <link rel="stylesheet" href="assets/css/global.css">
    <?php
    // Conditional logic loading individual stylesheets cleanly depending on router state
    $current_action = isset($_GET['action']) ? $_GET['action'] : 'login';
    if ($current_action === 'login') {
        echo '<link rel="stylesheet" href="assets/css/login.css">';
    } elseif ($current_action === 'faculty_dashboard') {
        echo '<link rel="stylesheet" href="assets/css/dashboard.css">';
    } elseif ($current_action === 'course_manage') {
        echo '<link rel="stylesheet" href="assets/css/course_manage.css">';
    } elseif ($current_action === 'faculty_grades') {
        echo '<link rel="stylesheet" href="assets/css/grades.css">';
    }
    ?>
</head>
<body class="<?php echo ($current_action === 'login') ? 'login-body' : ''; ?>">
<?php if ($current_action !== 'login'): ?>
<nav>
    <strong>Academic ERP v2.0</strong>
    <div>
        <a href="index.php?action=faculty_dashboard">Dashboard Portal</a>
        <a href="index.php?action=logout" style="color: #ef4444;">Sign Out</a>
    </div>
</nav>
<div class="container">
<?php endif; ?>