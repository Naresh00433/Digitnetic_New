<?php
session_start();

// Redirect to dashboard if already logged in
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
if (isset($_SESSION['is_login']) && $_SESSION['is_login'] === true) {
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html>

<?php
$pageTitle = "Blog Admin - Analytics AI ";
@include 'pre/head.php';
?>

<link rel="stylesheet" href="assets/css/index.css">

<body>
    <div class="login-wrapper">
        <div class="login-card card">
            <div class="login-header">
                <h3>Digitnetic</h3>
                <h6>- Blog Admin - </h6>
                <!-- <p>Please login to continue</p> -->
            </div>
            <?php if (isset($error)): ?>
                <div class="alert alert-danger">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
            <form action="auth-process/login-process.php" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <div class="form-group">
                    <label for="email">Email address</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-block">Login</button>
                </div>
            </form>
        </div>
    </div>

    <?php @include 'pre/footerScript.php'; ?>
</body>

</html>