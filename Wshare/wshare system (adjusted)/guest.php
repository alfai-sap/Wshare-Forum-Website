<?php
require_once 'functions.php';

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>You're Not signed up.</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="./css/guest.css">
        <link rel="stylesheet" href = "./css/left-navbar.css">
        <style>
            * {
                margin: 0;
                padding: 0;
                font-family: Georgia;
            }

            body {
                background-color: #e1ebed;
            }

            /* Navbar */
            .navbar {
                position: fixed;
                z-index: 999;
                display: flex;
                flex-wrap: wrap;
                justify-content: center;
                background-color: #f0f1f1;
                height: 80px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                list-style: none;
                width: 100%;
            }

            .logo-navbar {
                list-style: none;
                display: flex;
                height: 80px;
                position: fixed;
                z-index: 999;
                width: 200px;
                background-color: transparent;
            }

            .logo-navbar-in-left {
                list-style: none;
                display: flex;
                height: 80px;
                width: 400px;
            }

            .logo-nav {
                border: none;
                font-weight: bolder;
                font-size: x-large;
                display: flex;
                padding: 10px;
                cursor: pointer;
                background-color: transparent;
            }

            .logo-left-nav {
                border: none;
                font-weight: bolder;
                display: flex;
                padding: 10px;
                cursor: pointer;
                background-color: transparent;
            }

            .logo-nav p {
                text-decoration: none;
                align-self: center;
                margin: 5px;
                padding: 12px;
                color: #0056b3;
            }

            .logo-navbar li a {
                text-decoration: none;
            }

            .toggle-icon {
                padding: 15px;
                height: 30px;
                width: 30px;
            }

            .nav ::placeholder {
                text-indent: 20px;
            }

            .nav .search {
                width: 950px;
                height: 30px;
                border: 1px solid #007bff;
                border-right: none;
                border-top-right-radius: 0px;
                border-bottom-right-radius: 0px;
                border-top-left-radius: 20px;
                border-bottom-left-radius: 20px;
            }

            .nav .search-btn {
                width: 80px;
                background-color: #007bff;
                color: #fff;
                border: none;
                border-top-right-radius: 20px;
                border-bottom-right-radius: 20px;
                cursor: pointer;
                padding: 10px;
            }

            .nav .search-btn:hover {
                background-color: #0056b3;
                color: #fff;
            }

            .nav .submit {
                width: 100px;
                height: 30px;
                float: right;
            }

            form {
                margin-top: 20px;
                margin-bottom: 20px;
            }

            .toggle-icon {
                padding: 15px;
                height: 30px;
                width: 30px;
            }

            /*dropdown*/
            .dropbtn {
                background-color: #007bff;
                color: #f0f1f1;
                font-size: 16px;
                border: none;
                cursor: pointer;
                border-radius: 5px;

                height: 30px;
                width: 100px;
            }



            .dropdown {
                position: relative;
                display: inline-block;
            }

            .dropdown-content {
                display: none;
                position: absolute;
                background-color: #f0f1f1;
                min-width: 160px;
                box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
                z-index: 1;

                
            }

            .dropdown-content a {
                color: #007bff;
                padding: 12px 16px;
                text-decoration: none;
                display: block;

                transition: 0.4s ease;
            }

            .dropdown-content a:hover {
                background-color: #0056b3;
                color: #f0f1f1;
            }

            .dropdown:hover .dropdown-content {
                display: block;
            }
            /* Content */
            .container {
                height: 100%;
                width: 1024px;
                margin: 90px auto 0;
                background-color: #e1ebed;
                border-radius: 10px;
            }

            h1 {
                padding-top: 125px;
                padding-bottom: 20px;
                text-align: center;
                color: #007bff;
            }

            h2 {
                padding-top: 100px;
                color: #007bff;
            }

            .search_results {
                color: #007bff;
                padding-top: 30px;
                padding-bottom: 20px;
            }

            .post_title {
                color: #007bff;
                text-align: center;
            }

            p {
                color: #eee2de;
            }

            .welcome {
                text-align: center;
            }

            .post-form {
                height: 100%;
                width: 100%;
                background-color: #f0f1f1;
                border-radius: 5px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            }

            .post_uname {
                color: #0056b3;
                font-weight: bold;
                text-decoration: none;
                padding: 10px;
            }

            form {
                margin-top: 20px;
                margin-bottom: 20px;
            }

            label {
                display: block;
                font-weight: bold;
                margin-bottom: 5px;
            }

            .create-label {
                padding: 20px;
                color: #0056b3;
            }

            .post-title-in {
                width: 95%;
                margin: 20px;
                padding: 10px;
                margin-bottom: 10px;
                border: 1px solid #ccc;
                border-radius: 5px;
                box-sizing: border-box;
            }

            .post-content-in {
                padding: 10px;
                margin-bottom: 10px;
                border: 1px solid #ccc;
                border-radius: 5px;
                box-sizing: border-box;
                height: 100%;
                width: 95%;
                margin: 20px;
            }

            .post-postbtn-in {
                margin: 20px;
                width: 95%;
                background-color: #007bff;
                color: #fff;
                padding: 10px 20px;
                border: none;
                border-radius: 5px;
                cursor: pointer;
            }

            .post-postbtn-in:hover {
                background-color: #0056b3;
                color: #fff;
            }

            .post-container {
                background-color: #e1ebed;
                border-radius: 5px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            }

            .post {
                height: 100%;
                background-color: #f0f1f1;
                border: 1px solid #ddd;
                border-radius: 5px;
                padding: 10px;
                margin-bottom: 10px;
            }

            .post h3 {
                margin: 0;
                cursor: pointer;
                padding: 20px 10px;
                color: #007bff;
            }

            hr {
                border: 1px solid #007bff;
                opacity: 20%;
            }

            .comments {
                height: 100%;
            }

            .count_comm {
                color: #007bff;
                text-align: center;
                font-size: medium;
                padding: 10px;
                opacity: 80%;
            }

            .comments h4 {
                margin-top: 0;
                margin-bottom: 5px;
            }

            .comments p {
                margin: 5px 0;
            }

            .post-comments {
                margin: 5px 0;
                padding-left: 20px;
                padding-top: 10px;
                padding-bottom: 10px;
                color: black;
                opacity: 60%;
                display: none;
            }

            .comment-form {
                padding: 20px;
                padding-left: 10px;
            }

            .user-comment {
                padding: 10px;
                border: 1px solid #ccc;
                border-radius: 5px;
                box-sizing: border-box;
                width: 90%;
                height: 50px;
            }

            .upload-comment-btn {
                background-color: #007bff;
                color: #fff;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                padding: 10px 20px;
            }

            .comm-input {
                display: flex;
                flex-wrap: wrap;
            }

            .upload-comment-btn:hover {
                background-color: #0056b3;
            }

            .post_username {
                margin-top: 10px;
                color: black;
            }

            .post_time {
                font-size: small;
                margin-left: 10px;
                margin-top: 5px;
                margin-right: 10px;
                transition: 0.4s ease;
                margin-bottom: 10px;
                color: black;
                opacity: 50%;
            }

            .author_pic {
                height: 35px;
                width: 35px;
                margin: 10px;
                
            }

            .user_post_info {
                display: flex;
                flex-wrap: wrap;
                flex-direction: column;
            }

            .viewth_label {
                text-decoration: none;
            }

            .viewthread {
                margin: 20px;
                width: 95%;
                background-color: #007bff;
                color: #fff;
                padding: 10px 20px;
                border: none;
                border-radius: 5px;
                cursor: pointer;
            }

            .viewthread:hover {
                background-color: #0056b3;
                color: #fff;
            }

            .post_content {
                margin-left: 10px;
                margin-top: 20px;
                text-align: justify;
                margin-right: 10px;
                margin-bottom: 20px;
                color: black;
                display: none;
                transition: 0.4s ease;
            }

            .pic_style {
                height: 500px;
            }

            .upload-btn {
                margin-top: 20px;
                margin-left: 20px;
                border-radius: 5px;
                cursor: pointer;
            }

            .login-form {
                width: 100%;
                margin: 0 auto;
            }

            .user-img {
                height: 200px;
                border-radius: 100px;
                margin-top: 10px;
                margin-left: 35px;
                margin-bottom: 10px;
            }

            .user-name {
                color: #0056b3;
                margin-top: 10px;
                text-align: center;
            }

            /* Form Styles */
            form {
                display: flex;
                flex-direction: column;
                margin: 20px;
            }

            input[type="text"],
            input[type="password"] {
                padding: 10px;
                margin-bottom: 10px;
                border: 1px solid #ccc;
                border-radius: 5px;
                box-sizing: border-box;
            }

            input[type="submit"] {
                background-color: #007bff;
                color: #fff;
                border: none;
                padding: 10px;
                border-radius: 5px;
                cursor: pointer;
            }

            input[type="submit"]:hover {
                background-color: #0056b3;
            }

            .pic_user{
                display:flex;
            }

            /* Media Queries */
            @media (max-width: 768px) {
                .navbar,
                .logo-navbar,
                .left-navbar {
                    width: 100%;
                }

                .left-navbar {
                    width: 100%;
                    height: auto;
                    position: static;
                }

                .logo-navbar {
                    flex-direction: column;
                }

                .search {
                    width: calc(100% - 80px);
                }

                .search-btn {
                    width: 100px;
                }
            }
        </style>
    </head>
    <body>

    <ul class="navbar">

                    <form class = "nav" action="" method="GET">

                        <input class = "search" type="text" id="search" name="search" placeholder="search a topic...">

                        <input type="submit" class = "search-btn" value="Search">

                    </form>

                </ul>

                <ul class = "logo-navbar">
                    <li>
                                <button id ="logo-nav" class="logo-nav" ><img class = "toggle-icon" src = "menu.svg"></button> 
                    </li>

                    <li>
                        <a href = "index.php">
                            <div class="logo-nav">
                                <p class = "logo-label-nav">Wshare</p>
                            </div>
                        </a>
                    </li>

                </ul>
                
                <ul class="left-navbar" id="left-navbar">
                    
                    <ul class = "logo-navbar-in-left">
                        <li>
                                    <button id ="logo-left-nav" class="logo-nav" ><img class = "toggle-icon" src = "menu.svg"></button> 
                        </li>

                        <li>
                            <a href = "index.php">
                                <div class="logo-nav">
                                    <p class = "logo-label-nav">Wshare</p>
                                </div>
                            </a>
                        </li>

                    </ul>
                        

                    <li>
                        <a href="#">

                            <div class = "left-nav">

                                <img class = "login_user_pic" src="default_pic.svg" >
                                <h3 class = "username-nav">Welcome,  <b>Guest</b>!</h3>
                            </div>
                        </a>
                
                    </li>

                    <li>
                        <a href = "#">
                            <div class = "left-nav">
                                <img class = "icons" src = "homepage.svg">
                                <p class = "label_nav">Home</p>
                            </div>
                        </a>
                    </li>

                    <li>
                        <a href="#">
                            <div class = "left-nav">
                                <img class = "icons" src = "chats2.svg">
                                <p class = "label_nav">Chats</p>
                            </div>
                        </a>
                    </li>

                    <li>
                        <a href="#">
                            <div class = "left-nav">
                                <img class = "icons" src = "searchpeople.svg">
                                <p class = "label_nav">Search a User</p>
                            </div>
                        </a>
                    </li>
                    
                    <li>
                        <a href="#">
                            <div class = "left-nav">
                                <img class = "icons" src = "twopeople.svg">
                                <p class = "label_nav">Network</p>
                            </div>
                        </a>
                    </li>

                </ul><br>

    <div class="container">

    <?php

        //if hindi nakalogin ito maglabas
        echo '<div class = "welcome_container" style = "width: 1000px;">';
        
        echo '<p class = "welcome" style = "margin:150px; color: #007bff; font-size: large;"><a href="login.php">Login</a> or <a href="signup.php"> Sign Up</a> now to join your fellow crimsons in interesting discussions.</p>';
        
        echo '</div>';

        echo '<div class="dropdown">

                        <button class="dropbtn">Sort post by</button>

                        <div class="dropdown-content">

                            <a href="?sort=time">Newest</a>
                            <a href="?sort=date">Oldest</a>
                            <a href="?sort=comments">Most Popular</a>
                            <a href="?sort=Bpts">Highest Brilliant Points</a>

                        </div>

                </div><br><br>';

                
                
                

                // Display ng mga posts or search result
                if(isset($_GET['search']) && !empty($_GET['search'])) 
                
                    {
                        // Search for posts sa database gamit ang searchPosts function
                        $search = $_GET['search'];
                        
                        $posts = searchPosts($search);
                        
                        echo '<h3 class = "search_results">Search Results</h3>';
                    
                    } 
                
                else 
                
                    {
                        // Display recent posts 
                        
                        $posts = getRecentPosts();

                        
                    }



                    
                    if(isset($_GET['sort'])) {

                        $sort = $_GET['sort'];
                        switch($sort) {
                            case 'time':
                                $posts = getPostsSortedByTime();
                                break;
                            case 'date':
                                $posts = getPostsSortedByDate();
                                break;
                            case 'comments':
                                $posts = getPostsSortedByComments();
                                break;
                            case 'Bpts';
                                $posts = getPostsSortedByBPTS();
                                break;
                            default:
                                $posts = getRecentPosts();
                                break;
                        }
                    }    

                if ($posts)
                
                {   
                    
                    
                    foreach ($posts as $post)
                    
                    {
                        
                        echo '<div class = "post-container">';

                            echo '<div class="post">';

                                $userProfile = getUserProfileById($post['UserID']);
                                $profilePic = $userProfile['ProfilePic'];
                               

                                echo '<div class = "pic_user">';

                                    //profile pict
                                    if (!empty($profilePic)) {
                                        echo '<img class ="author_pic" src="' . $profilePic . '" alt="Profile Picture" style="width: 50px; height: 50px; border-radius: 50%;">';
                                    } else {
                                        echo '<img class = "author_pic" src="default_pic.svg" alt="Profile Picture" style="width: 50px; height: 50px; border-radius: 50%;">';
                                    }

                                    //username title content timestamp etc

                                    echo '<div class = "user_post_info">';
                                    
                                        echo '<p class = "post_username"><a class = "post_uname" href = "#">' . $post['Username'] . '</a></p>';
                                        echo '<p class = "post_time">posted at: ' . $post['CreatedAt'] . '</p>';
                                        
                                    
                                    echo '</div>';
                                
                                
                                echo '</div>';

                                

                                
                                
                                echo '<hr/>';
                                echo '<h3 class = "post_title">' . $post['Title'] . '</h3>';
                                echo '<hr/>';

                                echo '<p class = "post_content">' . $post['Content'] . '</p>';
                                

                                echo '<div class = "lik" style = "display:flex;">';

                                    echo '<form class = "like" action="#">';

                                        echo '<input type="hidden" name="postID" value="' . $post['PostID'] . '" >';

                                        echo '<button type="submit" class="like-btn" name="like"><img class = "bulb" src ="bulb.svg" style = "height:30px; width:30px;"></button>';

                                    echo '</form>';

                                    echo '<span class="like-count">' . getLikeCount($post['PostID']) . '</span>';

                                    $num_comm = countComments($post['PostID']);

                                    echo '<button class ="like-btn"><img class = "bulb" src = "comment.svg" style = "height:30px; width:30px;outline:none; border:none;"></button>';

                                    echo '<span class = "like-count"> '. $num_comm .'</span>';
                                    
                                    echo '<button class = "like-btn"><a href="#"><img class = "bulb" src = "view.svg" style = "height:30px; width:30px;outline:none; border:none;"></a></button>';
                                    echo '<span class = "like-count">see thread</span>';

                                echo '</div>';

                                
                                    
                                

                            echo '</div>';

                                                
                        echo '</div>';
                    }

                } 
                
                else 
                
                {

                    echo '<h4 style = "color: #007bff; text-align:center; paddng-top:200px;padding-bottom:200px;">No topic yet... you may start the topic by posting.</h4>';

                }

    ?>

    </div>

    <script>
        document.getElementById('logo-nav').addEventListener('click', function() {
            var element = document.getElementById('left-navbar');
                if (element.style.display === 'none') {
                        element.style.display = 'block';
                        
                } else {
                        element.style.display = 'none';
                }
            }
        );                    
    </script>

    <script>
        document.getElementById('logo-left-nav').addEventListener('click', function() {
            var element = document.getElementById('left-navbar');
                if (element.style.display === 'none') {
                        element.style.display = 'block';
                        
                } else {
                        element.style.display = 'none';
                }
            }
        );                    
    </script>

    <script>
        document.getElementById('comm_label').addEventListener('click', function() {
            var element = document.getElementById('comments');
                if (element.style.display === 'none') {
                        element.style.display = 'block';
                        
                } else {
                        element.style.display = 'none';
                }
            }
        );                    
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="./javascripts/index.js"></script>
    
    </body>

</html>