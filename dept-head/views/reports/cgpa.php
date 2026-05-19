<?php
session_start();
if(!isset($_SESSION['user_id'])) exit;
require __DIR__ . '/../../controllers/ReportController.php';

if (!isset($cgpa_data) || !$cgpa_data) {
    $cgpa_data = [
        'excellent' => 0,
        'very_good' => 0,
        'good' => 0,
        'passing' => 0,
        'probation' => 0
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/css/programIndex.css">
    <title>CGPA Distribution</title>
</head>
<body>
    <div class="programs-container">
        <div class="page-header">
            <div class="header-info">
                <h2>Department Analytics: CGPA Distribution</h2>
                <p class="text-muted">Enrolled student volume divided into specific academic standings.</p>
            </div>
        </div>

        <div style="display: flex; gap: 1rem; margin-bottom: 2.5rem; background: #fff; padding: 1rem; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
            <button class="btn-secondary report-nav-btn" data-target="views/reports/performance.php" style="padding: 0.8rem 1.5rem; cursor: pointer;">📈 Course Performance</button>
            <button class="btn-primary report-nav-btn" data-target="views/reports/cgpa.php" style="padding: 0.8rem 1.5rem; cursor: pointer;">📊 CGPA Distribution</button>
            <button class="btn-secondary report-nav-btn" data-target="views/reports/workload.php" style="padding: 0.8rem 1.5rem; cursor: pointer;">💼 Faculty Workload</button>
        </div>

        <div class="table-card">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Performance Bracket Range</th>
                        <th>Total Student Count</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="font-medium" style="color: #16a34a;">Excellent standing (3.75 - 4.00)</td>
                        <td style="font-weight: 600;"><?php echo (int)$cgpa_data['excellent']; ?> Students</td>
                    </tr>
                    <tr>
                        <td class="font-medium" style="color: #2563eb;">Very Good standing (3.50 - 3.74)</td>
                        <td style="font-weight: 600;"><?php echo (int)$cgpa_data['very_good']; ?> Students</td>
                    </tr>
                    <tr>
                        <td class="font-medium">Good standing (3.00 - 3.49)</td>
                        <td style="font-weight: 600;"><?php echo (int)$cgpa_data['good']; ?> Students</td>
                    </tr>
                    <tr>
                        <td class="font-medium" style="color: #d97706;">Passing standing (2.20 - 2.99)</td>
                        <td style="font-weight: 600;"><?php echo (int)$cgpa_data['passing']; ?> Students</td>
                    </tr>
                    <tr>
                        <td class="font-medium" style="color: #dc2626;">Academic Probation Matrix (&lt; 2.20)</td>
                        <td style="font-weight: 600; color: #dc2626;"><?php echo (int)$cgpa_data['probation']; ?> Students</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>