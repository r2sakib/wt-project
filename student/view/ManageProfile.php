<?php require_once("../controller/ProfileController.php"); ?>
<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Profile</title>
</head>
<body>

<table width="100%">
    <tr>
        <td><h2>Welcome, <?php echo htmlspecialchars($_SESSION['user']['name'] ?? 'Student'); ?>!</h2></td>
        <td align="right"><a href="dashboard.php"><h3><b>Back</b></h3></a></td>
    </tr>
</table>
<hr>

<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
    <input type="hidden" name="action" value="upload_pic">
    <table>
        <tr>
            <td>
                <img src="<?= (!empty($_SESSION['user']['profile_pic']) && file_exists('../uploads/' . $_SESSION['user']['profile_pic'])) ? '../uploads/' . htmlspecialchars($_SESSION['user']['profile_pic']) : 'default.jpg' ?>" width="100" height="100" alt="Profile Picture">
            </td>
        </tr>
        <tr>
            <td><input type="file" name="profile_pic" required></td>
        </tr>
        <tr>
            <td><button type="submit" name="upload_pic_btn">Upload Picture</button></td>
        </tr>
    </table>
</form>
<hr>

<h3>Update Personal Information</h3>
<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <input type="hidden" name="action" value="update_info">
    <table>
        <tr>
            <td><label>Full Name</label></td>
            <td><input type="text" name="name" value="<?php echo htmlspecialchars($_SESSION['user']['name'] ?? ''); ?>" required></td>
        </tr>
        <tr>
            <td><label>Student ID</label></td>
            <td><input type="text" value="<?php echo htmlspecialchars($_SESSION['user']['student_id_number'] ?? 'N/A'); ?>" readonly></td>
        </tr>
        <tr>
            <td><label>Email</label></td>
            <td><input type="email" name="email" value="<?php echo htmlspecialchars($_SESSION['user']['email'] ?? ''); ?>" required></td>
        </tr>
        <tr>
            <td><label>Phone Number</label></td>
            <td><input type="text" name="phone" value="<?php echo htmlspecialchars($_SESSION['user']['phone'] ?? ''); ?>"></td>
        </tr>
        <tr>
            <td><br><button type="submit" name="update_profile_btn">Save Changes</button></td>
        </tr>
        
        <tr>
            <td colspan="2">
                <?php if (isset($_SESSION['profile_msg'])): ?>
                    <p style="color: green;"> <?php echo $_SESSION['profile_msg']; unset($_SESSION['profile_msg']); ?></p>
                <?php endif; ?>

                <?php if (isset($_SESSION['profile_error'])): ?>
                    <p style="color: red;"> <?php echo $_SESSION['profile_error']; unset($_SESSION['profile_error']); ?></p>
                <?php endif; ?>
            </td>
        </tr>
    </table>
</form>
<hr>

<h3>Change Password</h3>
<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <input type="hidden" name="action" value="change_password">
    <table>
        <tr>
            <td><label>Current Password</label></td>
            <td><input type="password" name="current_password" required></td>
        </tr>
        <tr>
            <td><label>New Password</label></td>
            <td><input type="password" name="new_password" required minlength="6"></td>
        </tr>
        <tr>
            <td><label>Confirm Password</label></td>
            <td><input type="password" name="confirm_password" required></td>
        </tr>
        <tr>
            <td colspan="2">
                <?php if (!empty($passMsg)): ?>
                    <p style="color: red;"><i><?php echo htmlspecialchars($passMsg); ?></i></p>
                <?php endif; ?>

                <?php if (isset($_SESSION['pass_success'])): ?>
                    <p style="color: green;"><i><?php echo htmlspecialchars($_SESSION['pass_success']); ?></i></p>
                    <?php unset($_SESSION['pass_success']); ?>
                <?php endif; ?>
            </td>
        </tr>
        <tr>
            <td><br><button type="submit" name="change_password_btn">Change Password</button></td>
        </tr>
    </table>
</form>

</body>
</html>