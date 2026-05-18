<?php
if (!isset($reports)) {
    header("Location: ../controller/SemesterReportController.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Print Semester Report</title>
    <style>
        body { font-family: Arial, sans-serif; color: #333; line-height: 1.6; margin: 40px; }
        .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 15px; margin-bottom: 30px; }
        .header h1 { margin: 0; padding: 0; font-size: 24px; }
        .header p { margin: 5px 0 0 0; color: #666; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 12px; text-align: left; }
        th { background-color: #f8f9fa; font-weight: bold; }
        .text-center { text-align: center; }
        @media print {
            .no-print { display: none !important; }
            body { margin: 0; }
        }
        .print-btn { background: #007bff; color: white; padding: 10px 20px; border: none; cursor: pointer; border-radius: 4px; font-size: 16px; margin-right: 10px; }
        .close-btn { background: #6c757d; color: white; padding: 10px 20px; border: none; cursor: pointer; border-radius: 4px; font-size: 16px; }
    </style>
</head>
<body onload="window.print()">

    <div class="no-print" style="text-align: right; margin-bottom: 20px;">
        <button class="print-btn" onclick="window.print()">Print Document</button>
        <button class="close-btn" onclick="window.close()">Close Window</button>
    </div>

    <div class="header">
        <h1>Institution Academic Report</h1>
        <p>Semester Performance Summary</p>
        <p>Generated on: <?php echo date('F j, Y, g:i a'); ?></p>
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
            <td><?php echo $report['is_current'] == 1 ? 'Current' : 'Archived'; ?></td>
            <td class="text-center"><?php echo $report['enrolment_count']; ?></td>
            <td class="text-center"><?php echo number_format($report['pass_rate'], 1); ?>%</td>
            <td class="text-center"><?php echo number_format($report['avg_cgpa'], 2); ?></td>
        </tr>
        <?php } ?>
        <?php if (empty($reports)) { echo "<tr><td colspan='5' class='text-center'>No semester data found.</td></tr>"; } ?>
    </table>

</body>
</html>