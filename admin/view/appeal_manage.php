<?php
if (!isset($appeals)) {
    header("Location: ../controller/AppealController.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Escalated Appeals</title>
    <link rel="stylesheet" type="text/css" href="../view/style.css">
</head>
<body>
    <div class="container" style="max-width: 1000px;">
        <div class="justify-between">
            <h2>Escalated Grade Appeals</h2>
            <a href="../controller/DashboardController.php" style="text-decoration: none;">
                <button class="nav-btn" style="background: #6c757d;">Back to Dashboard</button>
            </a>
        </div>

        <table>
            <tr>
                <th>ID</th>
                <th>Student</th>
                <th>Course</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            <?php foreach ($appeals as $appeal) { ?>
            <tr>
                <td>#<?php echo $appeal['id']; ?></td>
                <td><?php echo $appeal['student_name']; ?></td>
                <td><?php echo $appeal['course_code']; ?> - <?php echo $appeal['course_title']; ?></td>
                <td style="color: #ff9800; font-weight: bold; text-transform: capitalize;"><?php echo $appeal['status']; ?></td>
                <td>
                    <form action="../controller/AppealController.php" method="GET" style="margin: 0;">
                        <input type="hidden" name="action" value="resolve_form">
                        <input type="hidden" name="appeal_id" value="<?php echo $appeal['id']; ?>">
                        <input type="submit" value="Review & Resolve" style="background: #007bff; color: white; border: none; padding: 5px 10px; border-radius: 3px; cursor: pointer;">
                    </form>
                </td>
            </tr>
            <?php } ?>
            <?php if (empty($appeals)) { echo "<tr><td colspan='5' class='text-center'>No escalated appeals.</td></tr>"; } ?>
        </table>
    </div>
</body>
</html>