<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    require_once 'views/auth/login.php';
    exit(); 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Department Head Portal | AIUB</title>

    <link rel="shortcut icon" href="assets/img/favicon.pnp">

    <link rel="stylesheet" href="./assets/css/style.css">
    <link rel="stylesheet" href="./assets/css/sidebar.css">
    <link rel="stylesheet" href="./assets/css/header.css">
    <link rel="stylesheet" href="./assets/css/footer.css">
    <link rel="stylesheet" href="./assets/css/dashboard.css">
    <link rel="stylesheet" href="./assets/css/programIndex.css">
    <link rel="stylesheet" href="./assets/css/programForm.css">
    <link rel="stylesheet" href="./assets/css/coursesIndex.css">
    <link rel="stylesheet" href="./assets/css/coursesAssignFaculty.css">
    <link rel="stylesheet" href="./assets/css/studentIndex.css">
    <link rel="stylesheet" href="./assets/css/studentView.css">
    <link rel="stylesheet" href="./assets/css/appeals.css">
    <link rel="stylesheet" href="./assets/css/performence.css">
    <link rel="stylesheet" href="./assets/css/cgpa.css">
    <link rel="stylesheet" href="./assets/css/workload.css">
    <link rel="stylesheet" href="./assets/css/calender.css">
    <link rel="stylesheet" href="./assets/css/announcement.css">
    <link rel="stylesheet" href="./assets/css/programForm.css">
    
    <style>
        #main-content-container {
            padding: 3.2rem;
            flex: 1;
            width: 100%;
            box-sizing: border-box;
        }
    </style>
</head>
<body>

    <div class="app-wrapper">
        
        <div id="sidebar-container">
            </div>

        <div class="main-content">
            
            <div id="header-container">
                </div>

            <div id="main-content-container">
                <div style="display: flex; justify-content: center; align-items: center; height: 50vh;">
                    <h3 style="color: #64748b; font-weight: 500;">Loading Portal...</h3>
                </div>
            </div>

            <div id="footer-container">
                </div>

        </div>
    </div>

    <script src="./assets/js/ajaxHandler.js"></script>

</body>
</html>