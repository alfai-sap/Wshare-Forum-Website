<?php
require_once 'functions.php';
session_start();

if (isset($_SESSION['username'])) 

{
    $content = $_POST['content'];
    $post_id = $_POST['post_id']; 
    $username = $_SESSION['username'];

    // Insert the comment
    if (createComment(getUserIdByUsername($username), $post_id, $content)) 
    {
        header('Location: index.php');
        exit;

    } 
    
    else 
    
    {
        echo "Error posting comment.";
    }
} 

else 

{
    header('Location: login.php');
    exit;
}

?>
