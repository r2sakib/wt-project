<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="./assets/css/style.css">
    <link rel="stylesheet" href="./assets/css/login.css">

    <title>Department Head Login</title>
</head>

<body>
    <section class="loginContainer">
        <div class="loginUpper">
            <h1 class="title">Academic Portal</h1>
            <p>Department Head Portal Login</p>
        </div>
        
        <div class="errorMessage">
            <p>
                <?php 
                    if(isset($_SESSION['login_error'])) {
                        echo $_SESSION['login_error']; 
                        unset($_SESSION['login_error']);
                    }
                ?>
            </p>
        </div>
        
        <div class="loginLower">
            <form action="./controllers/authController.php" method="post">
                <div class="loginEmail">
                    <label for="loginEmailInput">
                        Email Address
                    </label>
                    <input type="email" name="loginEmailInput" id="loginEmailInput"
                        placeholder="Enter your institutional email">
                </div>
                <div class="loginPass">
                    <label for="loginPassInput">
                        Password
                    </label>
                    <div class="password-wrapper">
                        <input type="password" name="loginPassInput" id="loginPassInput"
                            placeholder="Enter your password" required>
                        <button type="button" class="toggle-password" id="togglePassword" aria-label="Show password">
                            👁️
                        </button>
                    </div>
                </div>
                <input type="submit" value="Login" class="loginBtn">
            </form>

            <div class="quick-access-section">
                <p class="quick-access-title">Other Portals & Links</p>
                <div class="quick-links-grid">
                    <a href="/wt-project/admin/view/login.php" class="portal-btn"><span>🛡️</span> Admin</a>
                    <a href="/wt-project/faculty/" class="portal-btn"><span>👨‍🏫</span> Faculty</a>
                    <a href="/wt-project/student/view/login.php" class="portal-btn"><span>🎓</span> Student</a>
                </div>
            </div>
        </div>
    </section>

    <script>
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('loginPassInput');

        togglePassword.addEventListener('click', function () {
            const isPassword = passwordInput.getAttribute('type') === 'password';
            passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
            this.textContent = isPassword ? '🙈' : '👁️';
        });
    </script>
</body>

</html>
        const togglePassword = document.getElementById('togglePa