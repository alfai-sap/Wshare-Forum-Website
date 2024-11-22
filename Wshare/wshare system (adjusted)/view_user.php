<?php
require_once 'functions.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Check if username is provided in the URL parameter
if (!isset($_GET['username'])) {
    header('Location: index.php');
    exit;
}

// Get the username from the URL parameter
$username = $_GET['username'];

// Get user profile information
$userview = getUserByUsername($username);

// Get posts created by the user
$user_posts = getUserPosts($username);

// Get the logged-in user's ID
$loggedInUserID = getUserByUsername($_SESSION['username'])['UserID'];

// Check if the logged-in user is following the profile owner
$isFollowing = isFollowing($loggedInUserID, $userview['UserID']);

// Determine button text and action
$buttonText = $isFollowing ? 'Unfollow' : 'Follow';
$buttonAction = $isFollowing ? 'unfollow' : 'follow';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['action'] === 'follow') {
        followUser($loggedInUserID, $userview['UserID']);
        header("Location: view_user.php?username=$username");
        exit;
    } elseif ($_POST['action'] === 'unfollow') {
        unfollowUser($loggedInUserID, $userview['UserID']);
        header("Location: view_user.php?username=$username");
        exit;
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $userview['Username']; ?>'s Profile</title>
    <link rel="stylesheet" href="./css/navbar.css ?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="./css/left-navbar.css  ?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="./css/view_user.css  ?v=<?php echo time(); ?>">
</head>
<body>


<?php include 'navbar.php';?>

<div class="container">

    

    <h1>Profile</h1>
    <?php
        if ($userview['ProfilePic']) {
            echo '<div class="photo">';
            echo '<img class="profile_pic" src="' . $userview['ProfilePic'] . '">';
            echo '</div>';
        } else {
            echo '<div class="photo">';
            echo '<img class="profile_pic" src="default_pic.svg">';
            echo '</div>';
        }
    ?>

    <p class="username"><b><?php echo $userview['Username']; ?></b></p>
    <p class="email"><b><?php echo $userview['Email']; ?></b></p>
    <p class = "Profile-joined" style = "font-size:small; margin-left:10px;"><b><?php echo "Joined ". timeAgo($userview['JoinedAt']); ?></p>
    
    <?php if($_SESSION['username'] != $userview['Username']){ ?>
    <form action="" method="POST">
        <input type="hidden" name="action" value="<?php echo $buttonAction; ?>">
        <button type="submit" class="<?php echo $isFollowing ? 'follow-btn unfollow' : 'follow-btn'; ?>">
            <?php echo $buttonText; ?>
        </button>
    </form>
    <?php }?>

    <br>
    <br>
    <h3 class="headers">Bio</h3>
    <p class="bio" id="bio"><?php echo htmlspecialchars($userview['Bio']); ?></p>
    
    <br>
    <hr />
    <br>
    <h3 class="headers" style="text-align:left; padding-bottom:40px;"><?php echo $userview['Username']; ?>'s Posts</h3>
    <ul>
            <?php foreach ($user_posts as $post): ?>
                

                    <li style="list-style: none;">
                    
                        <div class="post">

                            <div class="pic_user" style = "display:flex;">

                                <div class="user_post_info">
                                    <div style="display: flex;">
                                        
                                        <p class="post_time" style = "font-size:smaller;"><?php echo timeAgo($post['CreatedAt']); ?></p>
                                    </div>
                                </div>

                                

                            </div>


                            <h3 class="post_title"><?php echo $post['Title']; ?></h3>


                            <p class="post_content"><?php echo $post['Content']; ?></p>

                            <div class="lik" style = "display:flex;">

                                <form class="like" action="like_post.php" method="POST" style = "margin:0;">
                                    <input type="hidden" name="postID" value="<?php echo $post['PostID']; ?>">
                                    <button type="submit" class="like-btn" name="like" style = "background-color:transparent; border:none; padding: 5px;"><img class="bulb" src="bulb.svg" style = "height:25px; width:25px;"></button>
                                </form>

                                <span class="like-count" style = "display:flex; align-self:center; color:#007bff;"><?php echo getLikeCount($post['PostID']); ?> Brilliant Points</span>

                                <button class="like-btn" style = "background-color:transparent; border:none; padding: 5px;"><img class="bulb" src="comment.svg" style = "height:25px; width:25px; background-color:transparent; outline:none; border:none;"></button>

                                <span class="like-count" style = "display:flex; align-self:center; color:#007bff;"><?php echo countComments($post['PostID']); ?> Comments</span>

                                <button class="like-btn" style = "background-color:transparent; border:none; padding: 5px;"><a href="view_post.php?id=<?php echo $post['PostID']; ?>" style = "display:flex; align-self:center; text-decoration:none;"><img class="bulb" src="view.svg" style = "height:25px; width:25px; background-color:transparent; outline:none; border:none;"><p class="like-count" style = "display:flex; align-self:center; color:#007bff; margin-left:5px;"> See disscussion</p></a> </button>

                            </div>
                            

                        </div>
                    </li>
               
            <?php endforeach; ?>
        </ul>

    <!-- Follow/Unfollow button -->
    

</div>

<script>
    // JavaScript to handle back button functionality
    document.getElementById('backButton').addEventListener('click', function() {
        // Go back in browser history
        window.history.back();
    });
</script>

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

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="./javascripts/index.js"></script>
</body>
</html>
