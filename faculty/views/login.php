<?php include __DIR__ . '/templates/header.php'; ?>

<div class="login-card">
    <div class="role-nav-container">
        <a href="/wt-project/admin/view/login.php" class="role-link">Admin login</a>
        <a href="/wt-project/faculty/views/login.php" class="role-link active-role">Faculty login</a>
        <a href="/wt-project/dept-head/" class="role-link">Dept head login</a>
        <a href="/wt-project/student/view/login.php" class="role-link">Student login</a>
    </div>

    <h2>Faculty Sign In</h2>
    
    <?php if (isset($_GET['error'])): ?>
        <p class="error-msg">Invalid login credentials provided.</p>
    <?php endif; ?>
    
    <form action="index.php?action=login_process" method="POST">
        <div class="form-group">
            <label>Institutional Email / Username</label>
            <input type="text" name="username" required placeholder="faculty">
        </div>
        <div class="form-group">
            <label>Password Account</label>
            <input type="password" name="password" required placeholder="••••••••">
        </div>
        <button type="submit" class="login-btn">LOG IN</button>
    </form>
</div>

<?php include __DIR__ . '/templates/footer.php'; ?>