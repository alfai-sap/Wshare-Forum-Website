<?php

require_once 'functions.php';

session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect the user to the login page or display an error message
    echo "Error: You must be logged in to edit a post.";
    exit;
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['post_id'])) 

{
    $post_id = $_POST['post_id'];

    // Fetch post details from the database
    $post = getPostById($post_id);

    // Check if the post exists
    if ($post) 
    
    {
        // Check if the logged-in user is the author of the post
        if ($post['UserID'] == $_SESSION['user_id']) 

            {
                   // Retrieve updated post data from the form
                    $title = $_POST['title'];
                    $content = $_POST['content'];

                    // Update the post in the database
                    if (updatePost($post_id, $title, $content)) 
                    
                    {
                        // Post updated successfully
                        header('Location: user_profile.php');
                        exit;

                    } 
                    
                    else 
                    
                    {
                        // Error updating post
                        echo "Error: Unable to update post.";
                    }
            } 
            
            else 
            
            {
            // User is not authorized to edit the post
            echo "Error: You are not authorized to edit this post.";

            }
    }
    
    else 
    
    {
        // Post not found
        echo "Error: Post not found.";
    }


} 

else 

{
    // Redirect to profile page if the form is not submitted
    header('Location: user_profile.php');
    exit;
}

?>
