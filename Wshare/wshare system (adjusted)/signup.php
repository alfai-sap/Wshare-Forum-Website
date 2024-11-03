<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up to WShare</title>
    <link rel="stylesheet" href="./css/signup.css?v=<?php echo time(); ?>">
</head>

<body>

    <div class="container">

        <h1>Sign Up</h1>

        <form action="signup_process.php" method="POST">

            <input type="text" id="username" name="username" placeholder="Enter your username..." required><br><br>
            <input type="email" id="email" name="email" placeholder="Enter your WMSU email..." pattern="[a-zA-Z]{2}(2018|2019|202\d){1}\d{5}@wmsu.edu.ph" title="Email must be in the format 'xx2024#####@wmsu.edu.ph'" required><br><br>
            <div class = passtog>
            <input class="pass" type="password" id="password" name="password" placeholder="Enter your password..." required>
            <button id="passwordToggle" type="button" onclick="togglePassword(event)"><img src = "passtoggle.svg" style = "height:30px; width:30px;"></button>
            </div><br><br>
            
            
            <div class="checkbox-container">
                <input type="checkbox" id="termsCheckbox" name="termsCheckbox" required>
                <p>I agree to the <a href="tos.php">Terms of Service</a> and <a href="pp.php">Privacy Policy</a></p>
            </div>
            
            <input class="btn" type="submit" value="Sign Up">
        </form>

        <p class="create">Already have an account? <a href="login.php">Log in</a></p>
        <p class="create">Back to <a href="../landing page/wshare_Landing_page.php">Home page</a></p>
    </div>
    
    <script>
        function togglePassword(event) {
            event.preventDefault();
            var passwordField = document.getElementById("password");
            var passwordToggle = document.getElementById("passwordToggle");

            if (passwordField.type === "password") {
                passwordField.type = "text";
                
            } else {
                passwordField.type = "password";
                
            }
        }
    </script>

</body>
</html>
