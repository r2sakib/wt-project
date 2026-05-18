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
            <h2>Manage System Users</h2>
            <div>
                <a href="../controller/UserController.php?action=add_form" style="text-decoration: none;">
                    <button class="nav-btn" style="background: #28a745;">+ Add User</button>
                </a>
                <a href="../controller/DashboardController.php" style="text-decoration: none;">
                    <button class="nav-btn" style="background: #6c757d;">Back to Dashboard</button>
                </a>
            </div>
        </div>

        <input type="text" id="userSearch" placeholder="Search by Name or Email..." style="width: 100%; padding: 12px; margin-bottom: 20px; border: 1px solid #ccc; border-radius: 4px; font-size: 16px;">

        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="userTableBody">
                <?php foreach ($users as $user) { ?>
                <tr>
                    <td><strong><?php echo $user['name']; ?></strong></td>
                    <td><?php echo $user['email']; ?></td>
                    <td style="text-transform: capitalize;"><?php echo $user['role']; ?></td>
                    <td>
                        <?php if ($user['is_active'] == 1) { ?>
                            <span style="color: #28a745; font-weight: bold;">Active</span>
                        <?php } else { ?>
                            <span style="color: #dc3545; font-weight: bold;">Inactive</span>
                        <?php } ?>
                    </td>
                    <td>
                        <form action="../controller/UserController.php" method="GET" style="display:inline;">
                            <input type="hidden" name="action" value="edit_form">
                            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                            <input type="submit" value="Edit" style="background: #007bff; color: white; padding: 5px 10px; border: none; border-radius: 3px; cursor: pointer;">
                        </form>

                        <?php if ($user['id'] != $_SESSION['admin_id']) { ?>
                        <form action="../controller/UserController.php" method="POST" style="display:inline;">
                            <input type="hidden" name="action" value="toggle_status">
                            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                            <input type="hidden" name="current_status" value="<?php echo $user['is_active']; ?>">
                            <?php if ($user['is_active'] == 1) { ?>
                                <input type="submit" value="Deactivate" style="background: #dc3545; color: white; padding: 5px 10px; border: none; border-radius: 3px; cursor: pointer;" onclick="return confirm('Deactivate this user account?');">
                            <?php } else { ?>
                                <input type="submit" value="Activate" style="background: #28a745; color: white; padding: 5px 10px; border: none; border-radius: 3px; cursor: pointer;">
                            <?php } ?>
                        </form>
                        <?php } ?>
                    </td>
                </tr>
                <?php } ?>
                <?php if (empty($users)) { echo "<tr><td colspan='5' class='text-center'>No users found.</td></tr>"; } ?>
            </tbody>
        </table>
    </div>

    <script>
        document.getElementById('userSearch').addEventListener('input', function() {
            let keyword = this.value;

            let xhr = new XMLHttpRequest();
            
            xhr.open('GET', '../controller/UserController.php?action=ajax_search&keyword=' + encodeURIComponent(keyword), true);

            xhr.onload = function() {
                if (this.status === 200) {
                    let data = JSON.parse(this.responseText);
                    let tbody = document.getElementById('userTableBody');
                    tbody.innerHTML = '';

                    if (data.length === 0) {
                        tbody.innerHTML = "<tr><td colspan='5' style='text-align:center;'>No users found matching your search.</td></tr>";
                        return;
                    }

                    let currentAdminId = <?php echo $_SESSION['admin_id']; ?>;

                    data.forEach(user => {
                        let roleCapitalized = user.role.charAt(0).toUpperCase() + user.role.slice(1);
                        
                        let statusSpan = user.is_active == 1 
                            ? '<span style="color: #28a745; font-weight: bold;">Active</span>' 
                            : '<span style="color: #dc3545; font-weight: bold;">Inactive</span>';
                        
                        let toggleBtn = '';
                        if (user.id != currentAdminId) {
                            toggleBtn = user.is_active == 1
                                ? `<input type="submit" value="Deactivate" style="background: #dc3545; color: white; padding: 5px 10px; border: none; border-radius: 3px; cursor: pointer;" onclick="return confirm('Deactivate this user account?');">`
                                : `<input type="submit" value="Activate" style="background: #28a745; color: white; padding: 5px 10px; border: none; border-radius: 3px; cursor: pointer;">`;
                        }

                        let row = document.createElement('tr');
                        row.innerHTML = `
                            <td><strong>${user.name}</strong></td>
                            <td>${user.email}</td>
                            <td style="text-transform: capitalize;">${roleCapitalized}</td>
                            <td>${statusSpan}</td>
                            <td>
                                <form action="../controller/UserController.php" method="GET" style="display:inline;">
                                    <input type="hidden" name="action" value="edit_form">
                                    <input type="hidden" name="user_id" value="${user.id}">
                                    <input type="submit" value="Edit" style="background: #007bff; color: white; padding: 5px 10px; border: none; border-radius: 3px; cursor: pointer;">
                                </form>
                                ${user.id != currentAdminId ? `
                                <form action="../controller/UserController.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="action" value="toggle_status">
                                    <input type="hidden" name="user_id" value="${user.id}">
                                    <input type="hidden" name="current_status" value="${user.is_active}">
                                    ${toggleBtn}
                                </form>` : ''}
                            </td>
                        `;
                        tbody.appendChild(row);
                    });
                }
            };
            xhr.onerror = function() {
                console.error('Error fetching search results with XMLHttpRequest.');
            };
            xhr.send();
        });
    </script>
</body>
</html>