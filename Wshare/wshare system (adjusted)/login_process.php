<?php
require_once 'db_connection.php';
require_once 'functions.php';

session_start();

// Use HTTPS for cookies if available
ini_set('session.cookie_secure', 1);
ini_set('session.cookie_httponly', 1);

// Limit the maximum session duration
ini_set('session.gc_maxlifetime', 1440); // 24 minutes
session_set_cookie_params([
    'lifetime' => 1440,
    'secure' => true,
    'httponly' => true,
]);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize user input
    $username = htmlspecialchars(trim($_POST['username']));
    $password = trim($_POST['password']);

    // Prevent brute force attacks by implementing a delay
    sleep(1); // Introduce a 1-second delay to slow down brute-force attacks

    // Prepare a SQL statement to retrieve the user's data
    $sql = "SELECT UserID, Username, Password FROM Users WHERE Username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the user exists
    if ($result->num_rows == 1) {
        // Fetch the user's data from the result set
        $user = $result->fetch_assoc();
        
        // Verify the password
        if (password_verify($password, $user['Password'])) {
            // Password is correct, regenerate session ID for security
            session_regenerate_id(true);

            // Store user data in the session
            $_SESSION['user_id'] = $user['UserID'];
            $_SESSION['username'] = $user['Username'];

            // Log the login attempt
            logUserLogin($_SESSION['user_id']);


            // Redirect to the forum page securely
            header('Location: index.php');
            exit;
        } else {
            // Log the failed login attempt (optional for monitoring)
            error_log("Failed login attempt for user: $username");

            // Redirect with a generic error message
            $_SESSION['login_error'] = "Invalid username or password.";
            header('Location: login.php');
            exit;
        }
    } else {
        // Log the failed login attempt
        error_log("User not found: $username");

        // Redirect with a generic error message
        $_SESSION['login_error'] = "Invalid username or password.";
        header('Location: login.php');
        exit;
    }
} else {
    // If the form was not submitted via POST, redirect to login
    header('Location: login.php');
    exit;
}
?>
