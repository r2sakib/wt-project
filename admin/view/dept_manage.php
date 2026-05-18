<?php
if (!isset($departments)) {
    header("Location: ../controller/DepartmentController.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Departments</title>
    <link rel="stylesheet" type="text/css" href="../view/style.css">
</head>
<body>
    <div class="container" style="max-width: 800px;">
        <div class="justify-between">
            <h2>Manage Departments</h2>
            <div>
                <a href="../controller/DepartmentController.php?action=add_form" style="text-decoration: none;">
                    <button style="background: #28a745; color: white;">+ Add Department</button>
                </a>
                <a href="../controller/DashboardController.php" style="text-decoration: none;">
                    <button>Back to Dashboard</button>
                </a>
            </div>
        </div>

        <table>
            <tr>
                <th>Code</th>
                <th>Department Name</th>
                <th>Department Head</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($departments as $dept) { ?>
            <tr>
                <td><?php echo $dept['code']; ?></td>
                <td><?php echo $dept['name']; ?></td>
                <td><?php echo $dept['head_name'] ? $dept['head_name'] : '<em>Unassigned</em>'; ?></td>
                <td>
                    <form action="../controller/DepartmentController.php" method="GET" style="display:inline;">
                        <input type="hidden" name="action" value="edit_form">
                        <input type="hidden" name="dept_id" value="<?php echo $dept['id']; ?>">
                        <input type="submit" value="Edit" style="background: #007bff; color: white; padding: 5px 10px;">
                    </form>

                    <form action="../controller/DepartmentController.php" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to deactivate/delete this department?');">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="dept_id" value="<?php echo $dept['id']; ?>">
                        <input type="submit" value="Deactivate" style="background: #dc3545; color: white; padding: 5px 10px;">
                    </form>
                </td>
            </tr>
            <?php } ?>
            <?php if (empty($departments)) { echo "<tr><td colspan='4' class='text-center'>No departments found.</td></tr>"; } ?>
        </table>
    </div>
</body>
</html>