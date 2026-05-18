<?php
if (!isset($semesters)) {
    header("Location: ../controller/SemesterController.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Semesters</title>
    <link rel="stylesheet" type="text/css" href="../view/style.css">
</head>
<body>
    <div class="container" style="max-width: 900px;">
        <div class="justify-between">
            <h2>Manage Semesters</h2>
            <div>
                <a href="../controller/SemesterController.php?action=add_form" style="text-decoration: none;">
                    <button class="nav-btn" style="background: #28a745;">+ Add Semester</button>
                </a>
                <a href="../controller/DashboardController.php" style="text-decoration: none;">
                    <button class="nav-btn" style="background: #6c757d;">Back to Dashboard</button>
                </a>
            </div>
        </div>

        <table>
            <tr>
                <th>Semester Name</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($semesters as $sem) { ?>
            <tr>
                <td><?php echo $sem['name']; ?></td>
                <td><?php echo $sem['start_date']; ?></td>
                <td><?php echo $sem['end_date']; ?></td>
                <td>
                    <?php 
                        if ($sem['is_current'] == 1) {
                            echo "<strong style='color: #28a745;'>Current</strong>";
                        } else {
                            echo "<span style='color: #6c757d;'>Archived / Inactive</span>";
                        }
                    ?>
                </td>
                <td>
                    <?php if ($sem['is_current'] == 0) { ?>
                        <form action="../controller/SemesterController.php" method="POST" style="display:inline;" onsubmit="return confirm('Make this the current semester? This will archive the currently active one.');">
                            <input type="hidden" name="action" value="set_current">
                            <input type="hidden" name="sem_id" value="<?php echo $sem['id']; ?>">
                            <input type="submit" value="Set as Current" style="background: #007bff; color: white; padding: 5px 10px; border: none; border-radius: 3px; cursor: pointer;">
                        </form>
                    <?php } else { ?>
                        <form action="../controller/SemesterController.php" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to archive this semester?');">
                            <input type="hidden" name="action" value="archive">
                            <input type="hidden" name="sem_id" value="<?php echo $sem['id']; ?>">
                            <input type="submit" value="Archive" style="background: #dc3545; color: white; padding: 5px 10px; border: none; border-radius: 3px; cursor: pointer;">
                        </form>
                    <?php } ?>
                </td>
            </tr>
            <?php } ?>
            <?php if (empty($semesters)) { echo "<tr><td colspan='5' class='text-center'>No semesters found.</td></tr>"; } ?>
        </table>
    </div>
</body>
</html>