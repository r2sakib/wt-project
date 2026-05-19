<?php
session_start();
if(!isset($_SESSION['user_id'])) exit;
require __DIR__ . '/../../controllers/ReportController.php';

if (!isset($performance_data)) {
    $performance_data = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/css/programIndex.css">
    <title>Performance Report</title>
</head>
<body>
    <div class="programs-container">
        <div class="page-header">
            <div class="header-info">
                <h2>Department Analytics: Course Performance</h2>
                <p class="text-muted">Calculated structural metric distributions and grade averages.</p>
            </div>
        </div>

        <div style="display: flex; gap: 1rem; margin-bottom: 2.5rem; background: #fff; padding: 1rem; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
            <button class="btn-primary report-nav-btn" data-target="views/reports/performance.php" style="padding: 0.8rem 1.5rem; cursor: pointer;">📈 Course Performance</button>
            <button class="btn-secondary report-nav-btn" data-target="views/reports/cgpa.php" style="padding: 0.8rem 1.5rem; cursor: pointer;">📊 CGPA Distribution</button>
            <button class="btn-secondary report-nav-btn" data-target="views/reports/workload.php" style="padding: 0.8rem 1.5rem; cursor: pointer;">💼 Faculty Workload</button>
        </div>

        <div class="table-card">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Course Code</th>
                        <th>Course Title</th>
                        <th>Operational Target Capacity</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($performance_data)): ?>
                        <tr><td colspan="4" style="text-align: center; padding: 3rem; color: #777;">No metrics recorded.</td></tr>
                    <?php else: ?>
                        <?php foreach ($performance_data as $row): ?>
                            <tr>
                                <td class="font-medium"><?php echo htmlspecialchars($row['code']); ?></td>
                                <td><?php echo htmlspecialchars($row['title']); ?></td>
                                <td><?php echo htmlspecialchars($row['max_seats']); ?> Students/Sections</td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>