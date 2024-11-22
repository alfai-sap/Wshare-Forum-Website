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
                    header('Location: edit_profile.php');
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
    <link rel="stylesheet" href="./css/edit_profile.css ?v=<?php echo time(); ?>">
</head>

<body>
    <?php include 'navbar.php';?>

    <div class="container">

        <h1>Update Your Profile</h1>
        
        <br>
        <br>
        <h1 style="text-align: center;">Profile Picture</h1>
        <br>


        <div class = "pfp-elements">

            <div class = "pfp-elements-child">
                <?php if (!empty($user['ProfilePic'])): ?>
                    <img class = "profile_pic" src="<?php echo $user['ProfilePic']; ?>" alt="Profile Picture">
                <?php else: ?>
                    <img class = "profile_pic" src="default_pic.svg">
                <?php endif; ?>
            </div>

        </div>



        <div class="choose-pfp">
            <form class = "change_pfp" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data" id = "change_pfp">
                <div class="choose-pfp-file">
                    <input type="file" class = "change-pfp-input" name="profile_picture" id="profile_picture" accept="image/*" required>
                    <button type="submit" class = "change-pfp-btn" name="submit" onclick = "toggleShowChangePfp()"><img class = "change-pfp-btn-upload" src = "upload.svg"></button>
                </div>
            </form>
        </div>
        
        <hr />

        <br>

        <h1 style="text-align: center;">Username</h1>
        
        <div class = "uname-elements">
            <div class="uname-elem">
                <p class = "Profile-uname"><b><?php echo $user['Username']; ?></b></p>
            </div>
        </div>

        
        
        
        <div class="forms">
            <form action="new_uname_email.php" method="post" class="profile-form" id="username">

                <label for="new_username">New Username:</label>
                <input type="text" id="new_username" name="new_username" class="profile-input" required>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" class="profile-input" required>

                <button type="submit" name="submit" class="profile-btn">Update Username</button>
            </form>
        </div>

        <br>
        <br>

        <hr />

        <br>

        <h1 style="text-align: center;">Bio</h1>       

        <form class = "bioForm" id="bioForm" action="functions.php" method="POST">

            <input type="hidden" name="userID" value="<?php echo $uid; ?>">

            <textarea id="bio" name="bio" rows="4" cols="50" style = "background-color:#f0f1f1;"><?php echo htmlspecialchars($user['Bio']); ?></textarea>

            <div class="btn-container">

            <button class="save-bio-btn" type="submit" name="submit" style="width: 100%; background-color:#007bff; height: 35px; color:#f0f1f1; margin:10px; ">Update Bio</button>

            </div>

        </form>
        
        <br>
        <hr />
        <br>
        <button class="save-bio-btn" style="width: 100%; background-color:#ffffff; height: 35px; color:#f0f1f1; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24); "><a href = "user_profile.php" style="text-decoration: none; color:#007bff;;">Back to profile</a></button>
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

</body>

</html>
