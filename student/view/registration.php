<?php
require_once("../controller/AuthController.php");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Student Registration</title>
</head>
<body>
    <h2>Student Registration</h2>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <table>
            <tr>
                <td><label>Full Name</label></td>
                <td><input type="text" name="name" value="<?php echo htmlspecialchars($_POST['name'] ?? '') ?>" required></td>
                <td><span style="color:red;"><?php echo $nameErr; ?></span></td>
            </tr>
            <tr>
                <td><label>Email</label></td>
                <td><input type="email" name="email" value="<?php echo htmlspecialchars($_POST['email'] ?? '') ?>" required></td>
                <td><span style="color:red;"><?php echo $emailErr; ?></span></td>
            </tr>
            <tr>
                <td><label>ID Number</label></td>
                <td><input type="text" name="student_id" value="<?php echo htmlspecialchars($_POST['student_id'] ?? '') ?>" required></td>
                <td><span style="color:red;"> <?php echo $idErr; ?></span></td>
            </tr>
            <tr>
                <td><label>Role</label></td>
                <td>
                    <select name="role" required>
                        <option value="">-- Select Role --</option>
                        <option value="student" <?php echo (($_POST['role'] ?? '') == 'student') ? 'selected' : '' ?>>Student</option>
                        <option value="faculty" <?php echo (($_POST['role'] ?? '') == 'faculty') ? 'selected' : '' ?>>Faculty</option>
                        <option value="head" <?php echo (($_POST['role'] ?? '') == 'head') ? 'selected' : '' ?>>Head</option>
                        <option value="admin" <?php echo (($_POST['role'] ?? '') == 'admin') ? 'selected' : '' ?>>Admin</option>
                    </select>
                </td>
                <td><span style="color:red;"> <?php echo $roleErr; ?></span></td>
            </tr>
            
            <tr>
                <td><label>Academic Program</label></td>
                <td>
                    <select name="program_id" required>
                        <option value="">-- Select Program --</option>
                        <option value="1" <?php echo (($_POST['program_id'] ?? '') == '1') ? 'selected' : '' ?>>B.Sc. in Computer Science & Engineering (CSE)</option>
                    </select>
                </td>
                <td></td>
            </tr>

            <tr>
                <td><label>Password</label></td>
                <td><input type="password" name="password" required minlength="6"></td>
                <td><span style="color:red;"> <?php echo $passErr; ?></span></td>
            </tr>
            <tr>
                <td><label>Confirm Password</label></td>
                <td><input type="password" name="confirm_password" required></td>
                <td><span style="color:red;"> <?php echo $cpassErr; ?></span></td>
            </tr>
            <tr>
                <td><input type="submit" name="register_btn" value="Register"></td>
                <td></td>
                <td></td>
            </tr>
        </table>
    </form>
    <br><i>Already have an account? </i><a href="login.php"><i>Login here</i></a>
</body>
</html>