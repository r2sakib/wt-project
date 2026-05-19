<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Login - Academic Portal</title>
    <link rel="stylesheet" type="text/css" href="./style.css">
</head>
<body>
    <div class="container">
        <h2>Admin Portal Login</h2>
        
        <?php
        if (isset($_SESSION['login_error'])) {
            echo "<p style='color: red;'>" . $_SESSION['login_error'] . "</p>";
            unset($_SESSION['login_error']); 
        }
        ?>

        <form method="POST" action="../controller/AuthController.php">
            <input type="hidden" name="action" value="login">
            
            <label>Email</label>
            <input type="email" name="email" required>

            <label>Password</label>
            <input type="password" name="password" required>

            <input type="submit" value="Login">
        </form>
    </div>
    <div class="container">
        <p style="font-weight: bold;">Other Role Logins</p>
        
        <a href="/wt-project/student/view/login.php" style="text-decoration: none;">
            <button type="button" style="background: #6c757d; color: white; padding: 8px 15px; border: none; border-radius: 3px; cursor: pointer; margin: 5px;">Student</button>
        </a>
        
        <a href="/wt-project/faculty/" style="text-decoration: none;">
            <button type="button" style="background: #6c757d; color: white; padding: 8px 15px; border: none; border-radius: 3px; cursor: pointer; margin: 5px;">Faculty</button>
        </a>
        
        <a href="/wt-project/dept-head/" style="text-decoration: none;">
            <button type="button" style="background: #6c757d; color: white; padding: 8px 15px; border: none; border-radius: 3px; cursor: pointer; margin: 5px;">Dept. Head</button>
        </a>
        
        <a href="/wt-project/admin/view/login.php" style="text-decoration: none;">
            <button type="button" style="background: #007bff; color: white; padding: 8px 15px; border: none; border-radius: 3px; cursor: pointer; margin: 5px;">Admin</button>
        </a>
    </div>
</body>
</html>