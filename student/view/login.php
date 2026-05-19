<?php
require_once("../controller/AuthController.php");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title>
</head>
<body>
    <h2>Login</h2>
    <form method="POST" action="">
        <table>
            <tr>
                <td><label>Email</label></td>
                <td><input type="email" name="email" required></td>
                <td><span> <?php echo $emailErr ?? '';; ?></span></td>
            </tr>
            <tr>
                <td><label>Password</label></td>
                <td><input type="password" name="password" required></td>
                <td><span> <?php echo $passErr ?? '';; ?></span></td>
            </tr>

            <tr>
                <td><button type="submit">Login</button></td>
            </tr>
        </table>
    </form>

    <br><i>Don't have an account? </i><a href="registration.php"><i>Register here</i></a>
<hr>
<footer >
    <p>
        <a href="/wt-project/faculty/">Faculty Login</a> | 
        <a href="/wt-project/admin/view/login.php">Admin Login</a> | 
        <a href="/wt-project/dept-head/">Head Login</a>
    </p>
</footer>

</body>

</html>