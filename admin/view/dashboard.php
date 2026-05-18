<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - Academic Portal</title>
    <link rel="stylesheet" type="text/css" href="../view/style.css">
</head>
<body>
    <div class="container" style="max-width: 900px;"> 
        <div class="justify-between">
            <h2>Admin Dashboard</h2>
            <form action="../controller/AuthController.php" method="POST" style="margin: 0;">
                <input type="hidden" name="action" value="logout">
                <input type="submit" value="Logout" style="background-color: #dc3545; color: white;">
            </form>
        </div>
        
        <p>Welcome back, <strong><?php echo $_SESSION['admin_name']; ?></strong>.</p>
        <hr><br>

        <div style="margin-bottom: 20px;">
            <a href="../controller/DepartmentController.php?action=list" style="text-decoration: none;">
                <button class="nav-btn">Manage Departments</button>
            </a>
            <a href="../controller/ProgramController.php?action=list" style="text-decoration: none;">
                <button class="nav-btn">Manage Programmes</button>
            </a>
            <a href="../controller/SemesterController.php?action=list" style="text-decoration: none;">
                <button class="nav-btn">Manage Semesters</button>
            </a>
            <a href="../controller/UserController.php?action=list" style="text-decoration: none;">
                <button class="nav-btn">Manage Users</button>
            </a>
            <a href="../controller/CourseController.php?action=list" style="text-decoration: none;">
                <button class="nav-btn">Manage Courses</button>
            </a>
            <a href="../controller/GradeScaleController.php?action=list" style="text-decoration: none;">
                <button class="nav-btn">Manage Grade Scale</button>
            </a>
            <a href="../controller/ReportController.php?action=list" style="text-decoration: none;">
                <button class="nav-btn">View Reports</button>
            </a>
            <a href="../controller/SemesterReportController.php?action=list" style="text-decoration: none;">
                <button class="nav-btn">Semester Reports</button>
            </a>
        </div>

        <div class="row">
            <div class="col card">
                <h3>Active Students</h3>
                <h1><?php echo $stats['active_students']; ?></h1>
            </div>
            <div class="col card">
                <h3>Active Courses</h3>
                <h1><?php echo $stats['active_courses']; ?></h1>
            </div>
            <div class="col card">
                <h3>Pending Appeals</h3>
                <h1 style="color: #ff9800;"><?php echo $stats['pending_appeals']; ?></h1>
            </div>
        </div>

        <div class="row">
            <div class="col card" style="text-align: left;">
                <h3 class="text-center">Users by Role</h3>
                <ul>
                    <li>Admins: <?php echo $stats['users_by_role']['admin'] ?? 0; ?></li>
                    <li>Heads: <?php echo $stats['users_by_role']['head'] ?? 0; ?></li>
                    <li>Faculty: <?php echo $stats['users_by_role']['faculty'] ?? 0; ?></li>
                    <li>Students: <?php echo $stats['users_by_role']['student'] ?? 0; ?></li>
                </ul>
            </div>

            <div class="col card" style="text-align: left;">
                <h3 class="text-center">Institution CGPA Distribution</h3>
                <ul>
                    <li>Great (3.7 - 4.0): <?php echo $stats['cgpa_distribution']['great'] ?? 0; ?></li>
                    <li>Good (3.0 - 3.69): <?php echo $stats['cgpa_distribution']['good'] ?? 0; ?></li>
                    <li>Average (2.5 - 2.99): <?php echo $stats['cgpa_distribution']['average'] ?? 0; ?></li>
                    <li style="color: red;">Probation (< 2.5): <?php echo $stats['cgpa_distribution']['probation'] ?? 0; ?></li>
                </ul>
            </div>
        </div>

    </div>
</body>
</html>