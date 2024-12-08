<?php
require_once 'functions.php'; // Include the file that contains the getAllSettings function
session_start();
$settings = getAllSettings();
$maintenanceMode = $settings['maintenance_mode'] ?? '0';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login to WShare</title>
    <link rel="stylesheet" href="./css/login.css?v=<?php echo time(); ?>">
</head>

<body>

    <div class="container">
        <h1>Login</h1><br><br>

        <?php
            if (isset($_SESSION['login_error'])) {
                echo '<div class="error">' . $_SESSION['login_error'] . '</div><br>';
                unset($_SESSION['login_error']);
            }
        ?>

        <?php if ($maintenanceMode == '1'): ?>
            <div class="alert alert-warning">The system is currently in maintenance mode. Please try again later.</div>
        <?php endif; ?>
        <form action="login_process.php" method="POST" <?php echo $maintenanceMode == '1' ? 'style="display:none;"' : ''; ?>>
            <input type="text" id="username" name="username" placeholder="username..."><br><br>
            <input type="password" id="password" name="password" placeholder="password..."><br><br>
            <input class="btn" type="submit" value="Login">
        </form>

        <p class="create">Not signed in? <a href="signup.php">Create an account</a></p>
        <p class="create">Forgot your <a href="#">password?</a></p>
        <p class="create">Back to <a href="../landing page/wshare_Landing_page.php">Home page</a></p>
    </div>

</body>
</html>
