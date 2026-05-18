<?php 
session_start();
if(!isset($_SESSION['user_id'])) exit; 

require_once __DIR__ . '/../../controllers/DashboardController.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/css/dashboard.css">
    <title>Department Dashboard - Academic Portal</title>
</head>
<body>
    <div class="dashboard-container">
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon bg-blue"><span>👥</span></div>
            <div class="stat-info">
                <span class="stat-value"><?php echo $stats['total_students']; ?></span>
                <span class="stat-label">Enrolled Students</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon bg-green"><span>📚</span></div>
            <div class="stat-info">
                <span class="stat-value"><?php echo $stats['active_courses']; ?></span>
                <span class="stat-label">Active Courses</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon bg-orange"><span>⚖️</span></div>
            <div class="stat-info">
                <span class="stat-value"><?php echo $stats['pending_appeals']; ?></span>
                <span class="stat-label">Pending Appeals</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon bg-purple"><span>📈</span></div>
            <div class="stat-info">
                <span class="stat-value"><?php echo number_format($stats['avg_cgpa'], 2); ?></span>
                <span class="stat-label">Average CGPA</span>
            </div>
        </div>
    </div>

    <div class="dashboard-widgets">
        <div class="widget-card">
            <div class="widget-header">
                <h3>Recent Grade Appeals</h3>
                <a href="#" class="btn-sm">View All</a>
            </div>
            <div class="widget-body">
                <p class="text-muted">No recent appeals require immediate attention.</p>
            </div>
        </div>
    </div>
</div>
</body>
</html>