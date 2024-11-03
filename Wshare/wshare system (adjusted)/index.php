<?php
session_start();
require_once 'functions.php';
?>

<!DOCTYPE html>

<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Redirecting...</title>
        <link rel="stylesheet" href="./css/index.css">
    </head>

<body>
            
    <div class="container">

        <?php

            if(isset($_SESSION['username'])) 
            
            {   
                //if logged in, redirect to homepage
                header('location: homepage.php');
                exit;

            } 
            
            else 
            
            {
                //if hindi nakalogin, redirect to guest page
                header('Location: guest.php');
                exit;
            
            }

        ?>

    </div>

</body>

</html>