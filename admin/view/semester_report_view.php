<?php
if (!isset($reports)) {
    header("Location: ../controller/SemesterReportController.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Semester Performance</title>
    <link rel="stylesheet" type="text/css" href="../view/style.css">
</head>
<body>
    <div class="container" style="max-width: 900px;">
        <div class="justify-between">
            <h2>Semester Performance Summary</h2>
            <div>
                <a href="../controller/SemesterReportController.php?action=print" target="_blank" style="text-decoration: none;">
                    <button class="nav-btn" style="background: #17a2b8;">Print / Export Report</button>
                </a>
                <a href="../controller/DashboardController.php" style="text-decoration: none;">
                    <button class="nav-btn" style="background: #6c757d;">Back to Dashboard</button>
                </a>
            </div>
        </div>

        <table>
            <tr>
                <th>Semester</th>
                <th>Status</th>
                <th>Total Enrolments</th>
                <th>Pass Rate</th>
                <th>Average CGPA</th>
            </tr>
            <?php foreach ($reports as $report) { ?>
            <tr>
                <td><strong><?php echo $report['name']; ?></strong></td>
                <td>
                    <?php if ($report['is_current'] == 1) { ?>
                        <span style="color: #28a745; font-weight: bold;">Current</span>
                    <?php } else { ?>
                        <span style="color: #6c757d;">Archived</span>
                    <?php } ?>
                </td>
                <td class="text-center"><?php echo $report['enrolment_count']; ?></td>
                <td class="text-center">
                    <strong style="color: <?php echo $report['pass_rate'] >= 50 ? '#28a745' : '#dc3545'; ?>">
                        <?php echo number_format($report['pass_rate'], 1); ?>%
                    </strong>
                </td>
                <td class="text-center">
                    <strong><?php echo number_format($report['avg_cgpa'], 2); ?></strong>
                </td>
            </tr>
            <?php } ?>
            <?php if (empty($reports)) { echo "<tr><td colspan='5' class='text-center'>No semester data found.</td></tr>"; } ?>
        </table>
    </div>
</body>
</html>