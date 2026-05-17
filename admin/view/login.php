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
            
            <label>Email:</label>
            <input type="email" name="email" required>

            <label>Password:</label>
            <input type="password" name="password" required>

            <input type="submit" value="Login">
        </form>
    </div>
</body>
</html>