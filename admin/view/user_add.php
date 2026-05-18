<!DOCTYPE html>
<html>
<head>
    <title>Add User</title>
    <link rel="stylesheet" type="text/css" href="../view/style.css">
</head>
<body>
    <div class="container">
        <h2>Create New User Account</h2>
        
        <form action="../controller/UserController.php" method="POST">
            <input type="hidden" name="action" value="add_submit">
            
            <label>Full Name:</label>
            <input type="text" name="name" required placeholder="e.g. John Doe">
            
            <label>Email Address:</label>
            <input type="email" name="email" required placeholder="e.g. user@portal.com">
            
            <div class="row">
                <div class="col">
                    <label>Temporary Password:</label>
                    <input type="password" name="password" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                </div>
                <div class="col">
                    <label>Assign Role:</label>
                    <select name="role" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                        <option value="student">Student</option>
                        <option value="faculty">Faculty</option>
                        <option value="head">Department Head</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
            </div><br>
            
            <div class="justify-between">
                <a href="UserController.php?action=list" style="text-decoration: none; color: #555;">Cancel</a>
                <input type="submit" value="Create Account" class="nav-btn" style="background: #28a745;">
            </div>
        </form>
    </div>
</body>
</html>