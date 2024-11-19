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

    <?php
    session_start();
    $errors = isset($_SESSION['errors']) ? $_SESSION['errors'] : [];
    $old_input = isset($_SESSION['old_input']) ? $_SESSION['old_input'] : [];

    if (isset($errors['general'])) {
        echo '<div class="error-messages"><p>' . $errors['general'] . '</p></div>';
    }
    // Clear the session data after displaying
    unset($_SESSION['errors']);
    unset($_SESSION['old_input']);
    ?>

    <form action="signup_process.php" method="POST">
        <div>
            <?php if (isset($errors['username'])): ?>
                <span class="error"><?php echo $errors['username']; ?></span>
            <?php endif; ?>
            <input type="text" id="username" name="username" placeholder="Enter your username..." value="<?php echo htmlspecialchars($old_input['username'] ?? ''); ?>">
            
        </div>
        <br>

        <div>
            <?php if (isset($errors['email'])): ?>
                <span class="error"><?php echo $errors['email']; ?></span>
            <?php endif; ?>
            <input type="email" id="email" name="email" placeholder="Enter your WMSU email..." value="<?php echo htmlspecialchars($old_input['email'] ?? ''); ?>" pattern="[a-zA-Z]{2}(2018|2019|202\d){1}\d{5}@wmsu.edu.ph" title="Email must be in the format 'xx2024#####@wmsu.edu.ph'">
            
        </div>
        <br>

            <?php if (isset($errors['password'])): ?>
                <span class="error"><?php echo $errors['password']; ?></span><br>
            <?php endif; ?>
        <div class="passtog">
            <input class="pass" type="password" id="password" name="password" placeholder="Enter your password..." oninput="checkPasswordStrength()">
            <button id="passwordToggle" type="button" onclick="togglePassword(event)"><img src="passtoggle.svg" style="height:30px; width:30px;"></button><br>
        </div>

        <div id="strengthMessage">
            <p id="strengthText"></p>
            <div id="strengthBar"></div>
        </div>
        <br>

            <?php if (isset($errors['confirmPassword'])): ?>
                <span class="error"><?php echo $errors['confirmPassword']; ?></span>
            <?php endif; ?>
        <div class="passtog">
            <input class="pass" type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm your password..." value="<?php echo htmlspecialchars($old_input['confirmPassword'] ?? ''); ?>">
        </div>
        <br>

        <div class="checkbox-container">
            <input type="checkbox" id="termsCheckbox" name="termsCheckbox" <?php echo isset($old_input['termsCheckbox']) ? 'checked' : ''; ?> required>
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
            var passwordConfirmField = document.getElementById('confirmPassword');
            var passwordField = document.getElementById("password");
            var passwordToggle = document.getElementById("passwordToggle");

            if (passwordField.type === "password") {
                passwordField.type = "text";
                passwordConfirmField.type = "text"
            } else {
                passwordField.type = "password";
                passwordConfirmField.type = "password"
            }
        }

        // Password strength checker
        function checkPasswordStrength() {
            const password = document.getElementById('password').value;
            const strengthText = document.getElementById('strengthText');
            const strengthBar = document.getElementById('strengthBar');

            // Criteria for password strength
            let strength = 0;
            if (password.length >= 8) strength++;
            if (/[a-z]/.test(password)) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/\d/.test(password)) strength++;
            if (/[!@#$%^&*()_\-+=]/.test(password)) strength++;

            // Adjust the strength message based on criteria
            switch (strength) {
                case 0:
                case 1:
                    strengthText.textContent = "Password is Very Weak";
                    strengthBar.style.width = "20%";
                    strengthBar.style.backgroundColor = "red";
                    break;
                case 2:
                    strengthText.textContent = "Weak";
                    strengthBar.style.width = "40%";
                    strengthBar.style.backgroundColor = "orange";
                    break;
                case 3:
                    strengthText.textContent = "Fair";
                    strengthBar.style.width = "60%";
                    strengthBar.style.backgroundColor = "yellow";
                    break;
                case 4:
                    strengthText.textContent = "Good";
                    strengthBar.style.width = "80%";
                    strengthBar.style.backgroundColor = "lightgreen";
                    break;
                case 5:
                    strengthText.textContent = "Strong";
                    strengthBar.style.width = "100%";
                    strengthBar.style.backgroundColor = "green";
                    break;
            }
        }
    </script>
</body>
</html>
