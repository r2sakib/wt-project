<?php
if (!isset($scales)) {
    header("Location: ../controller/GradeScaleController.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Grading Scale</title>
    <link rel="stylesheet" type="text/css" href="../view/style.css">
</head>
<body>
    <div class="container" style="max-width: 800px;">
        <div class="justify-between">
            <h2>Institutional Grading Scale</h2>
            <div>
                <a href="../controller/GradeScaleController.php?action=add_form" style="text-decoration: none;">
                    <button class="nav-btn" style="background: #28a745;">+ Add Scale</button>
                </a>
                <a href="../controller/DashboardController.php" style="text-decoration: none;">
                    <button class="nav-btn" style="background: #6c757d;">Back to Dashboard</button>
                </a>
            </div>
        </div>

        <table>
            <tr>
                <th>Marks Range</th>
                <th>Letter Grade</th>
                <th>GPA Point</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($scales as $scale) { ?>
            <tr>
                <td><?php echo $scale['min_mark'] . ' - ' . $scale['max_mark']; ?></td>
                <td><strong><?php echo $scale['letter_grade']; ?></strong></td>
                <td><?php echo number_format($scale['gpa_point'], 2); ?></td>
                <td>
                    <form action="../controller/GradeScaleController.php" method="GET" style="display:inline;">
                        <input type="hidden" name="action" value="edit_form">
                        <input type="hidden" name="scale_id" value="<?php echo $scale['id']; ?>">
                        <input type="submit" value="Edit" style="background: #007bff; color: white; padding: 5px 10px; border: none; border-radius: 3px; cursor: pointer;">
                    </form>
                    <form action="../controller/GradeScaleController.php" method="POST" style="display:inline;">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="scale_id" value="<?php echo $scale['id']; ?>">
                        <input type="submit" value="Delete" style="background: #dc3545; color: white; padding: 5px 10px; border: none; border-radius: 3px; cursor: pointer;">
                    </form>
                </td>
            </tr>
            <?php } ?>
            <?php if (empty($scales)) { echo "<tr><td colspan='4' class='text-center'>No grading scale defined.</td></tr>"; } ?>
        </table>
    </div>
</body>
</html>