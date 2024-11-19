


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
            session_start();
            if (isset($_SESSION['login_error'])) {
                echo '<div class="error">' . $_SESSION['login_error'] . '</div><br>';
                unset($_SESSION['login_error']);
            }
        ?>

        <form action="login_process.php" method="POST">
            <input type="text" id="username" name="username" placeholder="username..." required><br><br>
            <input type="password" id="password" name="password" placeholder="password..." required><br><br>
            <input class="btn" type="submit" value="Login">
        </form>

        <p class="create">Not signed in? <a href="signup.php">Create an account</a></p>
        <p class="create">Forgot your <a href="#">password?</a></p>
        <p class="create">Back to <a href="../landing page/wshare_Landing_page.php">Home page</a></p>
    </div>

</body>
</html>
