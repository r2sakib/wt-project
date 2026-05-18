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
            <?php if (empty($recent_appeals)): ?>
                    <p class="text-muted">No recent appeals require immediate attention.</p>
                <?php else: ?>
                    <div class="recent-appeals-list" style="display: flex; flex-direction: column; gap: 1rem; width: 100%; text-align: left;">
                        <?php foreach ($recent_appeals as $appeal): ?>
                            <div class="appeal-item" style="display: flex; justify-content: space-between; align-items: center; background: #f8fafc; padding: 1.2rem; border-radius: 6px; border: 1px solid #e2e8f0; border-left: 4px solid #f59e0b;">
                                <div>
                                    <strong style="font-size: 1.3rem; color: #1e293b;"><?php echo htmlspecialchars($appeal['student_name']); ?></strong>
                                    <span style="color: #64748b; font-size: 1.1rem; margin-left: 0.5rem;">(<?php echo htmlspecialchars($appeal['course_code']); ?>)</span>
                                    <p style="margin: 0.4rem 0 0 0; color: #475569; font-size: 1.2rem; font-style: italic; line-height: 1.4;">
                                        "<?php echo htmlspecialchars(mb_strimwidth($appeal['reason'], 0, 80, "...")); ?>"
                                    </p>
                                </div>
                                <span style="font-size: 1.1rem; color: #94a3b8; font-weight: 500; white-space: nowrap; margin-left: 1rem;">
                                    <?php echo date('M d', strtotime($appeal['created_at'])); ?>
                                </span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>