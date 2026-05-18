<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

require_once(__DIR__ . "/../model/UserModel.php");
$model = new UserModel();
$success = "";
$passMsg = "";
$user = $_SESSION['user'];

// update personal information

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'update_info') {
    if (isset($_POST['update_profile_btn'])) {
        $id = $_SESSION['user']['id'];
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $phone = trim($_POST['phone']);

        if ($model->updateProfile($id, $name, $email, $phone)) {
    
            $currentStudentID = $_SESSION['user']['student_id_number'] ?? 'N/A';

            $_SESSION['user']['name'] = $name;
            $_SESSION['user']['email'] = $email;
            $_SESSION['user']['phone'] = $phone;
            $_SESSION['user']['student_id_number'] = $currentStudentID; 

            $_SESSION['profile_msg'] = "Personal information updated successfully!";
            
            header("Location: ManageProfile.php");
            exit;
        } else {
            $_SESSION['profile_error'] = "Failed to update profile. Please try again.";
        }
    }
}

// profile picture
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'upload_pic') {
    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == 0) {
        $targetDir = "../uploads/";
        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
        
        $fileName = time() . '_' . basename($_FILES["profile_pic"]["name"]);
        $targetFilePath = $targetDir . $fileName;
        
        if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $targetFilePath)) {
            if ($model->updatePicture($user['id'], $fileName)) {
                $_SESSION['user']['profile_pic'] = $fileName;
                header("Location: ManageProfile.php");
                exit;
            }
        }
    }
}

// password change
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'change_password') {
    $current = isset($_POST['current_password']) ? $_POST['current_password'] : '';
    $new = isset($_POST['new_password']) ? $_POST['new_password'] : '';
    $confirm = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';
    
    if (password_verify($current, $user['password_hash'])) {
        
        if ($new === $confirm) {
            $newHashed = password_hash($new, PASSWORD_DEFAULT);
            
            if ($model->changePassword($user['id'], $newHashed)) {
                $_SESSION['user']['password_hash'] = $newHashed;
                $_SESSION['pass_success'] = "Password Changed Successfully";
                header("Location: ManageProfile.php");
                exit;
            }
        } else {
            $passMsg = "Passwords do not match";
        }
    } else {
        $passMsg = "Current Password Incorrect";
    }
}
?>