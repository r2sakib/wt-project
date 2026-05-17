<?php include __DIR__ . '/templates/header.php'; ?>
<div class="login-card">
    <h2>Faculty Sign In</h2>
    <?php if (isset($_GET['error'])): ?>
        <p class="error-msg">Invalid login credentials provided.</p>
    <?php endif; ?>
    <form action="index.php?action=login_process" method="POST">
        <div class="form-group">
            <label>Institutional Email</label>
            <input type="text" name="username" required placeholder="toha3132@portal.edu">
        </div>
        <div class="form-group">
            <label>Password Account</label>
            <input type="password" name="password" required placeholder="••••••••">
        </div>
        <button type="submit" class="login-btn">Authenticate Session</button>
    </form>
</div>
<?php include __DIR__ . '/templates/footer.php'; ?>