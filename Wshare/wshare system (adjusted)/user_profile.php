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
    <link rel="stylesheet" href="./css/navbar.css ?v=<?php echo time(); ?>">
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
            </div>

            
        </div>
        
        

        <div class = "uname-elements">
            <div class="uname-elem">
                <p class = "Profile-uname"><b><?php echo $user['Username']; ?></b></p>
            </div>

            <p class = "Profile-email"><b><?php echo $user['Email']; ?></p>
            <p class = "Profile-joined"><b><?php echo "Joined ". timeAgo($user['JoinedAt']); ?></p><br><br>

            <button class="update-profile-btn"><a href="edit_profile.php">Edit Profile</a></button>
        </div>

        
        <br>
        
        <h3  class = "pfp-label" style="color: #007bff; text-align: left; padding-top:50px;">Bio</h3>       

        <?php
        if($user['Bio']) { 
        ?> <p class="bio"><?php echo htmlspecialchars($user['Bio']); ?></p>
        <?php
        }else { 
            echo '<p class = "bio">Hello! new member here.</p>';  // Display message if bio is empty.
        }?>

        <br>
        <hr />
        <br>
        

        <h3  class = "pfp-label" style="color: #007bff; text-align: left; padding-bottom:40px; padding-top:20px;">Your Posts</h3>

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
                                <form class="like" action="like_post.php" method="POST">
                                    <input type="hidden" name="postID" value="<?php echo $post['PostID']; ?>">
                                    <button type="submit" class="like-btn" name="like" style="background-color:transparent; border:none; padding: 10px;">
                                        <?php if (hasUserLikedPost($post['PostID'],  $_SESSION['user_id'])): ?>
                                            <img class="bulb" src="bulb_active.svg" style="height:20px; width:20px;">
                                        <?php else: ?>
                                            <img class="bulb" src="bulb.svg" style="height:20px; width:20px;">
                                        <?php endif; ?>
                                    </button>
                                </form>

                                <span class="like-count" style = "display:flex; align-self:center; color:#0056b3; font-weight: normal;"><?php echo getLikeCount($post['PostID']); ?> Brilliant Points</span>

                                <button class="like-btn" style = "background-color:transparent; border:none; padding: 10px;"><img class="bulb" src="comment.svg" style = "height:20px; width:20px; background-color:transparent; outline:none; border:none;"></button>

                                <span class="like-count" style = "display:flex; align-self:center; color:#0056b3; font-weight: normal;"><?php echo countComments($post['PostID']); ?> Comments</span>

                                <button class="like-btn" style = "background-color:transparent; border:none; padding: 10px;"><a href="view_post.php?id=<?php echo $post['PostID']; ?>" style = "display:flex; align-self:center; text-decoration:none;"><img class="bulb" src="view.svg" style = "height:20px; width:20px; background-color:transparent; outline:none; border:none;"><p class="like-count" style = "display:flex; align-self:center; color:#0056b3; margin-left:5px;"> See disscussion</p></a> </button>

                                <p class = "divider" style="display:flex; color: #007bff; font-weight:bold; align-self:center;"> | </p>

                                <form action="edit_post.php" method="GET">
                                    <input type="hidden" name="post_id" value="<?php echo $post['PostID']; ?>">
                                    <button class = "like-btn" type="submit" style = "display:flex; background-color:transparent; border:none; padding: 10px;"><img class = "bulb" src="edit.svg" style = "height:20px; width:20px; background-color:transparent; outline:none; border:none;"><p class="like-count" style = "display:flex; align-self:center; color:#0056b3; margin-left:5px;padding:5px;">Edit post</p></button>
                                </form>
                                
                                <button class = "non-nav-icon" onclick="confirmDelete(<?php echo $post['PostID']; ?>)"><img class = "non-nav-icon-img" src="delete.svg">
                                <p class="like-count" style = "display:flex; align-self:center; color:red; margin-left:5px;">Delete post</p></button>
                            </div>
                            

                        </div>
                    </li>
               
            <?php endforeach; ?>
        </ul>


    </div>

    <!-- Add Image Modal -->
    <div id="imageModal" class="image-modal">
        <span class="close-modal" onclick="closeModal()">&times;</span>
        <img id="modalImage" class="modal-content">
        <div class="zoom-controls">
            <button class="zoom-btn" onclick="zoom(1.2)">+</button>
            <button class="zoom-btn" onclick="zoom(0.8)">-</button>
            <button class="zoom-btn" onclick="resetZoom()">Reset</button>
        </div>
    </div>

    <script>
    let currentZoom = 1;
    let isDragging = false;
    let startX, startY, translateX = 0, translateY = 0;

    // Make images clickable
    document.querySelectorAll('.post-image-img, .profile_pic').forEach(img => {
        img.style.cursor = 'pointer';
        img.onclick = function() {
            openModal(this.src);
        }
    });

    function openModal(imgSrc) {
        const modal = document.getElementById('imageModal');
        const modalImg = document.getElementById('modalImage');
        modal.style.display = "block";
        modalImg.src = imgSrc;
        resetPosition();
    }

    function closeModal() {
        document.getElementById('imageModal').style.display = "none";
        resetPosition();
    }

    function zoom(factor) {
        currentZoom *= factor;
        currentZoom = Math.min(Math.max(0.5, currentZoom), 3);
        updateTransform();
    }

    function startDrag(e) {
        isDragging = true;
        if (e.type === "mousedown") {
            startX = e.clientX - translateX;
            startY = e.clientY - translateY;
        } else if (e.type === "touchstart") {
            startX = e.touches[0].clientX - translateX;
            startY = e.touches[0].clientY - translateY;
        }
        document.getElementById('modalImage').style.cursor = 'grabbing';
    }

    function drag(e) {
        if (!isDragging) return;
        e.preventDefault();
        
        const clientX = e.type === "mousemove" ? e.clientX : e.touches[0].clientX;
        const clientY = e.type === "mousemove" ? e.clientY : e.touches[0].clientY;
        
        translateX = clientX - startX;
        translateY = clientY - startY;
        
        updateTransform();
    }

    function stopDrag() {
        isDragging = false;
        document.getElementById('modalImage').style.cursor = 'grab';
    }

    function resetPosition() {
        translateX = 0;
        translateY = 0;
        currentZoom = 1;
        updateTransform();
    }

    function updateTransform() {
        document.getElementById('modalImage').style.transform = 
            `scale(${currentZoom}) translate(${translateX}px, ${translateY}px)`;
    }

    // Event listeners
    const modalImage = document.getElementById('modalImage');
    modalImage.addEventListener('mousedown', startDrag);
    modalImage.addEventListener('touchstart', startDrag);
    document.addEventListener('mousemove', drag);
    document.addEventListener('touchmove', drag, { passive: false });
    document.addEventListener('mouseup', stopDrag);
    document.addEventListener('touchend', stopDrag);

    // Keyboard controls
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeModal();
        if (e.key === '+' || e.key === '=') zoom(1.2);
        if (e.key === '-') zoom(0.8);
        if (e.key === '0') resetPosition();
    });
    </script>

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

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="./javascripts/index.js"></script>
</body>

</html>
