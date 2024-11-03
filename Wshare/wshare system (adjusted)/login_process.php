<?php
require_once 'db_connection.php';
require_once 'functions.php';

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") 

{
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare a SQL statement to retrieve the user's data
    $sql = "SELECT UserID, Username, Password FROM Users WHERE Username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) 
        
        {
            // Fetch the user's data from the result set
            $user = $result->fetch_assoc();
            
            // Verify the password
            if (password_verify($password, $user['Password'])) 
            
            {
                // Password is correct, start a session and store user data
                $_SESSION['user_id'] = $user['UserID']; // Store user ID in session
                $_SESSION['username'] = $user['Username'];
                // Redirect to the forum webpage
                logUserLogin($_SESSION['user_id']);

                $bio = '';
                updateOrInsertUserBio($_SESSION['user_id'], $bio);
                header('Location: index.php');
                exit;

            } 
            
            else 
            
            {
                // Password is incorrect
                echo "Incorrect password. Please try again.";
                header('location: login.php');
            }
        } 
    
    else 
    
        {
            // User not found
            echo "User not found. Please check your inputs. if error persist let us know through contacting us.";
            header('location: login.php');
        }
}

?>
