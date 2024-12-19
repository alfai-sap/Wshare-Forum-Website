<?php 
session_start();
require_once 'functions.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}
require_once 'notifications_functions.php';
// Get current user ID
$currentUser = getUserByUsername($_SESSION['username']);
$currentUserID = $currentUser['UserID'];

// Handle follow/unfollow action
if (isset($_GET['action']) && isset($_GET['userID'])) {
    $action = $_GET['action'];
    $userID = intval($_GET['userID']);

    if ($action === 'follow') {
        followUser($currentUserID, $userID);
    } elseif ($action === 'unfollow') {
        unfollowUser($currentUserID, $userID);
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Search</title>
    <link rel="stylesheet" href="./css/search_users.css ?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="./css/navbar.css ?v=<?php echo time(); ?>" >
    <link rel="stylesheet" href="./css/left-navbar.css ?v=<?php echo time(); ?>" >
    <link rel="stylesheet" href="./css/right-sidebar.css ?v=<?php echo time(); ?>" >
    <!--<link rel="stylesheet" href="./css/notifications.css  ?v=<?php echo time(); ?>">-->
    
</head>

<body>
    
    <ul class="navbar">
        <form class="nav" action="" method="GET" style="display: flex;">
            <input class="search-input" type="text" id="search" name="query" placeholder="I'm looking for...">
            <div class="btn-search">
                <input type="submit" class="search-button" value="Search">
            </div>
        </form>
    </ul>

    
    <?php include 'navbar.php';?>
    <?php include 'suggested-users.php';?>
    <div class="container">
        <br><br><br><br>
        
        <?php
        // Check if the search query is set
        if (isset($_GET['query'])) {
            $query = $_GET['query'];

            // Call the searchUsers function
            $results = searchUsers($query);

            // Display search results
            foreach ($results as $result) {
                // Check if the user is already being followed
                $isFollowing = isFollowing($currentUserID, $result['UserID']);
                $buttonText = $isFollowing ? 'Unfollow' : 'Follow';
                $buttonAction = $isFollowing ? 'unfollow' : 'follow';
                $buttonClass = $isFollowing ? 'follow-btn unfollow' : 'follow-btn';
            
                echo '<div class="search-result">
                        <a class="view_profile" href="view_user.php?username=' . urlencode($result['Username']) . '">
                            <div class="prof_uname">';
                                if ($result['ProfilePic']) {
                                    echo '<img class="prof_pic" src="' . $result['ProfilePic'] . '">';
                                } else {
                                    echo '<img class="prof_pic" src="default_pic.svg">';
                                }
                                echo '<div class="uname_email">
                                        <p style="margin-left:10px;"><b>' . $result['Username'] . '<b></p>
                                        <p class="wmsu_email" style="margin:10px; font-size: small; opacity: 80%;">' . $result['Email'] . '</p>
                                    </div>
                            </div>
                        </a>
                        
                    </div>';
            }
        }
        ?>
    </div>

    <script>
        document.getElementById('logo-nav').addEventListener('click', function () {
            var element = document.getElementById('left-navbar');
            if (element.style.display === 'none') {
                element.style.display = 'block';
            } else {
                element.style.display = 'none';
            }
        });

        document.getElementById('logo-left-nav').addEventListener('click', function () {
            var element = document.getElementById('left-navbar');
            if (element.style.display === 'none') {
                element.style.display = 'block';
            } else {
                element.style.display = 'none';
            }
        });
    </script>

<!--<form action="" method="GET" style="display:inline;">
                            <input type="hidden" name="userID" value="' . $result['UserID'] . '">
                            <input type="hidden" name="action" value="' . $buttonAction . '">
                            <input type="submit" value="' . $buttonText . '" class="' . $buttonClass . '">
                        </form>-->
</body>
</html>
