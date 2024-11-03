<?php
    require_once 'functions.php';
    session_start();


    if (!isset($_SESSION['username'])) {
        header('Location: login.php');
        exit;
    }


    $username = $_SESSION['username'];

    // Handle profile picture upload
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["profile_picture"])) {
        
        $target_dir = "uploads/"; // Directory where uploaded images will be stored
        $target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is a actual image or fake image
        $check = getimagesize($_FILES["profile_picture"]["tmp_name"]);

        if ($check !== false) {
            // Allow certain file formats
            if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
                echo "Sorry, only JPG, JPEG, PNG files are allowed.";
            } else {
                // Move uploaded file to specified directory
                if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {

                    // Update user's profile picture path in the database
                    updateUserProfilePicture($username, $target_file);

                    // Redirect back to profile page
                    header('Location: user_profile.php');
                    exit;
                    
                } else {

                    echo "Sorry, there was an error uploading your file.";
                }
            }
        } else {
            echo "File is not an image.";
        }
    }

    // Get user profile information gamit ito 
    $user = getUserByUsername($username);
    $userID = $user['UserID']; 

    // Get posts created by the user
    $user_posts = getUserPosts($username);


    $id = getUserIdByUsername($username);
    $uid = $id;

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Profile</title>
    <link rel="stylesheet" href="./css/left-navbar.css ?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="./css/user_profile.css ?v=<?php echo time(); ?>">
</head>

<body>
    <?php include 'navbar.php';?>

    <div class="container">

        <h1>Your Profile</h1>
        
        
        <div class = "pfp-elements">

            <div class = "pfp-elements-child">
                <?php if (!empty($user['ProfilePic'])): ?>
                    <img class = "profile_pic" src="<?php echo $user['ProfilePic']; ?>" alt="Profile Picture">
                <?php else: ?>
                    <img class = "profile_pic" src="default_pic.svg">
                <?php endif; ?>

                <button class = "change-pfp-btn" id="change-pfp-btn" onclick="toggleEditPhoto()" ><img class = "change-pfp-btn-icon" src = "edit.svg"></button>
            </div>

        </div>

        <div class="choose-pfp">
            <form class = "change_pfp" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data" id = "change_pfp" style = "display:none;">
                <div class="choose-pfp-file">
                    <input type="file" class = "change-pfp-input" name="profile_picture" id="profile_picture" accept="image/*" required>
                    <button type="submit" class = "change-pfp-btn" name="submit" onclick = "toggleShowChangePfp()"><img class = "change-pfp-btn-upload" src = "upload.svg"></button>
                </div>
            </form>
        </div>
        
        
        <div class = "uname-elements">
            <div class="uname-elem">
                <p class = "Profile-uname"><b><?php echo $user['Username']; ?></b></p>
                <button class = "save-bio-btn" id="editBtn" onclick="toggleEdituname()" ><img class = "save-bio-btn-icon" src = "edit.svg"></button>
            </div>

            <p class = "Profile-email"><b><?php echo $user['Email']; ?></p><br>
            <p class = "Profile-email" style = "font-size:small;"><b><?php echo "Joined at: ". $user['JoinedAt']; ?></p>
        </div>
        
        <div class="forms">
            <form action="new_uname_email.php" method="post" class="profile-form" id="username" style="display: none;">

                <label for="new_username">New Username:</label>
                <input type="text" id="new_username" name="new_username" class="profile-input" required>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" class="profile-input" required>

                <button type="submit" name="submit" class="profile-btn">Update Username</button>
            </form>
        </div>

        
        <h3  class = "pfp-label" style="color: #007bff; text-align: left; padding-top:50px;">Bio</h3>       

        <form class = "bioForm" id="bioForm" action="functions.php" method="POST">

            <input type="hidden" name="userID" value="<?php echo $uid; ?>">

            <textarea id="bio" name="bio" rows="4" cols="50" style = "background-color:#f0f1f1;"><?php echo htmlspecialchars($user['Bio']); ?></textarea>

            <div class="btn-container">

            <button class="save-bio-btn" type="submit" name="submit" style="width: 100%; background-color:#007bff; height: 35px; color:#f0f1f1; ">Update Bio</button>

            </div>

        </form>

        

        <h3  class = "pfp-label" style="color: #007bff; text-align: left; padding-bottom:40px; padding-top:20px;">Your Posts</h3>

        <ul>
            <?php foreach ($user_posts as $post): ?>
                

                    <li style="list-style: none; width:100%; color: #2b2e4a; display: flex; align-items:center;">
                    
                        <div class="user-posts">


                            <b class = "post_title"><?php echo $post['Title']; ?></b>

                            <div class="options-post">

                                <form action="view_post.php" method="GET">
                                    <input type="hidden" name="id" value="<?php echo $post['PostID']; ?>">
                                    <button class = "non-nav-icon" type="submit"><img class = "non-nav-icon-img" src="view.svg"></button>
                                </form>

                                <form action="edit_post.php" method="GET">
                                    <input type="hidden" name="post_id" value="<?php echo $post['PostID']; ?>">
                                    <button class = "non-nav-icon" type="submit"><img class = "non-nav-icon-img" src="edit.svg"></button>
                                </form>
                                
                                <button class = "non-nav-icon" onclick="confirmDelete(<?php echo $post['PostID']; ?>)"><img class = "non-nav-icon-img" src="delete.svg"></button>

                            </div>

                        </div>
                    </li>
               
            <?php endforeach; ?>
        </ul>
        <br>
        <br>
        <br>
        <br>


    </div>


    <script>
    
        function confirmDelete(postId) {
            if (confirm("Are you sure you want to delete this post?")) {
                window.location.href = "delete_post.php?id=" + postId;
            }
        }

    
        function toggleEdituname() {
            var formsContainer = document.getElementById("username");
            if (formsContainer.style.display === "none") {
                formsContainer.style.display = "flex";
            } else {
                formsContainer.style.display = "none";
            }
        }
    

    
            function toggleEditPhoto() {

                var formsContainer = document.getElementById("change_pfp");
                if (formsContainer.style.display === "none") {
                    formsContainer.style.display = "block";
                    
                } else {
                    formsContainer.style.display = "none";
                }
        }


        function toggleShowChangePfp() {
               

                var formsContainer = document.getElementById("change_pfp");
                if (formsContainer.style.display === "none") {
                    formsContainer.style.display = "block";
                    editbtn.style.display = "none";
                } else {
                    formsContainer.style.display = "none";
                    editbtn.style.display = "block";

                }
        }
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

</body>

</html>
