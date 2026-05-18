<?php
if (!isset($programs)) {
    header("Location: ../controller/ProgramController.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Degree Programmes</title>
    <link rel="stylesheet" type="text/css" href="../view/style.css">
</head>
<body>
    <div class="container" style="max-width: 900px;">
        <div class="justify-between">
            <h2>Manage Degree Programmes</h2>
            <div>
                <a href="../controller/ProgramController.php?action=add_form" style="text-decoration: none;">
                    <button style="background: #28a745; color: white;">+ Add Programme</button>
                </a>
                <a href="../controller/DashboardController.php" style="text-decoration: none;">
                    <button>Back to Dashboard</button>
                </a>
            </div>
        </div>

        <table>
            <tr>
                <th>Code</th>
                <th>Programme Name</th>
                <th>Department</th>
                <th>Credits</th>
                <th>Duration (Yrs)</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($programs as $prog) { ?>
            <tr>
                <td><?php echo $prog['code']; ?></td>
                <td><?php echo $prog['name']; ?></td>
                <td><?php echo $prog['department_name']; ?></td>
                <td><?php echo $prog['total_credit_hours']; ?></td>
                <td><?php echo $prog['duration_years']; ?></td>
                <td>
                    <form action="../controller/ProgramController.php" method="GET" style="margin: 0;">
                        <input type="hidden" name="action" value="edit_form">
                        <input type="hidden" name="prog_id" value="<?php echo $prog['id']; ?>">
                        <input type="submit" value="Edit" style="background: #007bff; color: white; padding: 5px 10px;">
                    </form>
                </td>
            </tr>
            <?php } ?>
            <?php if (empty($programs)) { echo "<tr><td colspan='6' class='text-center'>No programmes found.</td></tr>"; } ?>
        </table>
    </div>
</body>
</html>