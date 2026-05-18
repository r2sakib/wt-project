<?php
if (!isset($users)) {
    header("Location: ../controller/UserController.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Users</title>
    <link rel="stylesheet" type="text/css" href="../view/style.css">
</head>
<body>
    <div class="container" style="max-width: 1000px;">
        <div class="justify-between">
            <h2>Manage User Accounts</h2>
            <div>
                <a href="../controller/UserController.php?action=add_form" style="text-decoration: none;">
                    <button class="nav-btn" style="background: #28a745;">+ Add New User</button>
                </a>
                <a href="../controller/DashboardController.php" style="text-decoration: none;">
                    <button class="nav-btn" style="background: #6c757d;">Back to Dashboard</button>
                </a>
            </div>
        </div>

        <table>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Current Role</th>
                <th>Change Role</th>
                <th>Status</th>
            </tr>
            <?php foreach ($users as $user) { ?>
            <tr>
                <td><?php echo $user['name']; ?></td>
                <td><?php echo $user['email']; ?></td>
                <td style="text-transform: capitalize;"><?php echo $user['role']; ?></td>
                <td>
                    <form action="../controller/UserController.php" method="POST" style="margin: 0; display: flex; gap: 5px;">
                        <input type="hidden" name="action" value="change_role">
                        <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                        <select name="new_role" style="padding: 5px; border: 1px solid #ccc; border-radius: 3px;">
                            <option value="student" <?php if($user['role'] == 'student') echo 'selected'; ?>>Student</option>
                            <option value="faculty" <?php if($user['role'] == 'faculty') echo 'selected'; ?>>Faculty</option>
                            <option value="head" <?php if($user['role'] == 'head') echo 'selected'; ?>>Head</option>
                            <option value="admin" <?php if($user['role'] == 'admin') echo 'selected'; ?>>Admin</option>
                        </select>
                        <input type="submit" value="Update" style="background: #007bff; color: white; border: none; padding: 5px 10px; border-radius: 3px; cursor: pointer;">
                    </form>
                </td>
                <td>
                    <form action="../controller/UserController.php" method="POST" style="margin: 0;">
                        <input type="hidden" name="action" value="toggle_status">
                        <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                        <input type="hidden" name="current_status" value="<?php echo $user['is_active']; ?>">
                        <?php if ($user['is_active'] == 1) { ?>
                            <input type="submit" value="Deactivate" style="background: #dc3545; color: white; border: none; padding: 5px 10px; border-radius: 3px; cursor: pointer;">
                        <?php } else { ?>
                            <input type="submit" value="Activate" style="background: #28a745; color: white; border: none; padding: 5px 10px; border-radius: 3px; cursor: pointer;">
                        <?php } ?>
                    </form>
                </td>
            </tr>
            <?php } ?>
        </table>
    </div>
</body>
</html>