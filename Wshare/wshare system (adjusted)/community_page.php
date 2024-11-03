<?php
require_once 'functions.php';
session_start();

$communityID = isset($_GET['community_id']) ? intval($_GET['community_id']) : 0;
$userID = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : 0;

// Validate community ID
if ($communityID <= 0) {
    header('Location: homepage.php');
    exit();
}

// Fetch community details
$query = "SELECT * FROM communities WHERE CommunityID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $communityID);
$stmt->execute();
$communityResult = $stmt->get_result();
$community = $communityResult->fetch_assoc();

if (!$community) {
    echo "Community not found.";
    exit();
}

// Check if the user is a member of the community and get their role
$query = "SELECT Role FROM community_members WHERE CommunityID = ? AND UserID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('ii', $communityID, $userID);
$stmt->execute();
$membershipResult = $stmt->get_result();

// Set the isMember and currentUserRole variables based on the result
if ($membershipResult->num_rows > 0) {
    $membership = $membershipResult->fetch_assoc();
    $isMember = true;
    $currentUserRole = $membership['Role']; // 'admin' or 'member'
} else {
    $isMember = false;
    $currentUserRole = null;
}

// Fetch posts
$query = "SELECT p.*, up.ProfilePic, u.Username FROM community_posts p 
          JOIN users u ON p.UserID = u.UserID 
          JOIN userprofiles up ON up.UserID = u.UserID
          WHERE p.CommunityID = ?
          ORDER BY p.CreatedAt DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $communityID);
$stmt->execute();
$postsResult = $stmt->get_result();

// Fetch members and admins with emails and profile pictures
$query = "SELECT u.UserID, u.Username, u.Email, up.ProfilePic, cm.Role 
          FROM community_members cm 
          JOIN users u ON cm.UserID = u.UserID 
          JOIN userprofiles up ON u.UserID = up.UserID
          WHERE cm.CommunityID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $communityID);
$stmt->execute();
$membersResult = $stmt->get_result();

$members = [];
$admins = [];

while ($member = $membersResult->fetch_assoc()) {
    if ($member['Role'] === 'admin') {
        $admins[] = $member;
    } else {
        $members[] = $member;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($community['Title']); ?></title>
    <link rel="stylesheet" href="./css/navbar.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="./css/left-navbar.css?v=<?php echo time(); ?>">
    <style>
        body {
            font-family: Georgia, serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        h1 {
            background-color: #007BFF; /* Your primary color */
            color: white;
            padding: 20px;
            text-align: center;
            margin: 0;
        }

        h2 {
            color: #007BFF;
            margin-top: 20px;
            margin-bottom: 20px;
        }

        .container {
            width: 900px;
            margin: 0 auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .community-thumbnail {
            width: 100%;
            height: 300px; /* Fixed height */
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .community-description {
            font-size: 1.2em;
            margin-bottom: 20px;
            color: #333;
        }

        /*form {
        
        }*/

        label {
            display: block;
            margin: 10px 0 5px;
            font-size: 1em;
            color: #333;
        }

        input[type="text"], textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        input[type="submit"] {
            width:100%;
            background-color: #007BFF; /* Your primary color */
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover {
            background-color: #0056b3; /* Darker shade of your primary color */
        }

        .post-container {
            margin-bottom: 20px;
        }

        .post {
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #fff;
            padding: 15px;
        }

        .pic_user {
            display: flex;
            margin-bottom: 10px;
        }

        .author_pic {
            height: 50px;
            width: 50px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .user_post_info {
            display: flex;
            flex-direction: column;
        }

        .post_username a {
            color: #007BFF;
            text-decoration: none;
        }

        .post_username a:hover {
            text-decoration: underline;
        }

        .post_time {
            font-size: smaller;
            color: #666;
        }

        .post_title {
            color: #007BFF;
            margin-top: 0;
        }

        .post_content {
            margin: 10px 0;
            color: #333;
        }

        /* Like button styling */
        .like-btn {
            background-color: transparent;
            border: none;
            color: #007bff;
            cursor: pointer;
            transition: 0.4s ease;
        }

        .like-count {
            opacity: 70%;
            color: #007bff;
        }

        .lik {
            display: flex;
            align-items: center;
        }

        .bulb:hover {
            height: 30px;
            width: 30px;
        }

        .bulb {
            width: 25px;
            height: 25px;
            padding: 10px;
            transition: 0.4s ease;
        }

        .profile-list {
            list-style: none;
            padding: 0;
        }

        .profile-item {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }

        .profile-pic {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-right: 15px;
        }

        .profile-details {
            flex-grow: 1;
        }

        .profile-username {
            font-size: 1.2em;
            font-weight: bold;
            color: #007BFF;
            text-decoration: none;
        }

        .profile-username:hover {
            text-decoration: underline;
        }

        .profile-email {
            color: #555;
            font-size: 0.9em;
        }

        button {
            background-color: #007BFF;
            color: white;
            border: none;
            padding: 10px 15px;
            margin-right: 10px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #0056b3;
        }

        @media screen and (max-width: 768px){
            .container{
                width: 95%;
                padding: 10px;
            }
            
        }
    </style>
</head>
<body>

    <?php include 'navbar.php'; ?>	
    <div class="container">
    <img src="<?php echo htmlspecialchars($community['Thumbnail']); ?>" alt="Community Thumbnail" class="community-thumbnail">
        <h1><?php echo htmlspecialchars($community['Title']); ?></h1><br><br>
        <p class="community-description"><?php echo htmlspecialchars($community['Description']); ?></p>
        <br><br>

        <div>
            <button onclick="toggleCreatePost()">Create Post</button>
            <button onclick="toggleMembers()">Members</button>
            <button onclick="toggleAdmins()">Admins</button>
            
        </div>

        <div id="membersList" style="display:none;">
            <h2>Members</h2>
            <ul class="profile-list">
                <?php foreach ($members as $member) { ?>
                    <li class="profile-item">
                        <img class="profile-pic" src="<?php echo !empty($member['ProfilePic']) ? htmlspecialchars($member['ProfilePic']) : 'default_pic.svg'; ?>" alt="Profile Picture">
                        <div class="profile-details">
                            <a class="profile-username" href="view_user.php?username=<?php echo urlencode($member['Username']); ?>"><?php echo htmlspecialchars($member['Username']); ?></a>
                            <p class="profile-email"><?php echo htmlspecialchars($member['Email']); ?></p>
                            <?php if ($member['UserID'] == $userID) { ?>
                                <span style="color: #007bff; padding-top:10px;">(You)</span>
                            <?php } ?>
                        </div>
                        <?php if ($isMember && $currentUserRole === 'admin') { ?>
                            <!-- Admin-only actions -->
                            <form method="POST" action="community_manage_member.php" class="admin-actions">
                                <input type="hidden" name="community_id" value="<?php echo $communityID; ?>">
                                <input type="hidden" name="member_id" value="<?php echo $member['UserID']; ?>">
                                
                                <!-- Set as Admin button (only if the member is not already an admin) -->
                                <?php if ($member['Role'] !== 'admin') { ?>
                                    <button type="submit" name="action" value="set_admin" class="action-btn" onclick="return confirm('Are you sure you want to promote this user to admin?');">Set as Admin</button>
                                <?php } ?>
                                
                                <!-- Remove Member button (prevent removing self) -->
                                <?php if ($member['UserID'] != $userID) { ?>
                                    <button type="submit" name="action" value="remove_member" class="action-btn remove" onclick="return confirm('Are you sure you want to remove this member from the community?');">Remove Member</button>
                                <?php } ?>
                            </form>
                        <?php } ?>
                    </li>
                <?php } ?>
            </ul>
        </div>

        <!-- Admins list -->
        <div id="adminsList" style="display:none;">
            <h2>Admins</h2>
            <ul class="profile-list">
                <?php foreach ($admins as $admin) { ?>
                    <li class="profile-item">
                        <img class="profile-pic" src="<?php echo !empty($admin['ProfilePic']) ? $admin['ProfilePic'] : 'default_pic.svg'; ?>" alt="Profile Picture">
                        <div class="profile-details">
                            <a class="profile-username" href="view_user.php?username=<?php echo urlencode($admin['Username']); ?>"><?php echo htmlspecialchars($admin['Username']); ?></a>
                            <p class="profile-email"><?php echo htmlspecialchars($admin['Email']); ?></p>
                            <?php if ($admin['UserID'] == $userID) { ?>
                                <br>
                                <span style="color: #007bff; padding-top:5px;">(You)</span><br>
                                <form method="POST" action="community_manage_member.php" class="admin-actions">
                                    <input type="hidden" name="community_id" value="<?php echo $communityID; ?>">
                                    <input type="hidden" name="member_id" value="<?php echo $userID; ?>">
                                    <button type="submit" name="action" value="leave_admin_role" onclick="return confirm('Are you sure you want to leave the admin role?');">Leave Admin Role</button>
                                </form>
                            <?php } ?>
                        </div>
                    </li>
                <?php } ?>
            </ul>
        </div>

        <!-- Only show the post form if the user is a member -->
        <?php if ($isMember) { ?>
            
            <form id = "create_post_form" action="community_create_post.php" method="POST" style = "display:none;">
                <input type="hidden" name="community_id" value="<?php echo $communityID; ?>">
                <label for="title">Post Title:</label>
                <input type="text" id="title" name="title" required>
                
                <label for="content">Content:</label>
                <textarea id="content" name="content" required></textarea>
                
                <input type="submit" value="Create Post">
            </form>

        <?php } else { ?>
            <p>You must be a member of this community to create posts.</p>
        <?php } ?>

        <br><br>
        <h2>Posts</h2><br><br>
        <?php while ($post = $postsResult->fetch_assoc()) { ?>
            <div class="post-container">
                <div class="post">
                    <div class="pic_user">
                        <?php if (!empty($post['ProfilePic'])): ?>
                            <img class="author_pic" src="<?php echo $post['ProfilePic']; ?>" alt="Profile Picture">
                        <?php else: ?>
                            <img class="author_pic" src="default_pic.svg" alt="Profile Picture">
                        <?php endif; ?>
                        <div class="user_post_info">
                            <p class="post_username"><a class="post_uname" href="view_user.php?username=<?php echo urlencode($post['Username']); ?>"><?php echo $post['Username']; ?></a></p>
                            <p class="post_time">posted at: <?php echo $post['CreatedAt']; ?></p>
                            <p class="post_time">updated at: <?php echo $post['UpdatedAt']; ?></p>
                        </div>
                    </div>
                    <hr/><br>
                    <h3 class="post_title"><?php echo htmlspecialchars($post['Title']); ?></h3><br>
                    <hr/><br>
                    <p class="post_content"><?php echo htmlspecialchars($post['Content']); ?></p>
                    <br>
                    <div class="lik">
                        <form class="like" action="like_post.php" method="POST">
                            <input type="hidden" name="postID" value="<?php echo $post['PostID']; ?>">
                            <button type="submit" class="like-btn" name="like"><img class="bulb" src="bulb.svg"></button>
                        </form>
                        <span class="like-count"><?php echo getLikeCount($post['PostID']); ?></span>
                        <button class="like-btn"><img class="bulb" src="comment.svg"></button>
                        <span class="like-count"><?php echo countComments($post['PostID']); ?></span>
                        <button class="like-btn"><a href="view_community_post.php?id=<?php echo $post['PostID']; ?>"><img class="bulb" src="view.svg"></a></button>
                    </div>
                </div>
            </div>
        <?php } ?>
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

    <script>
        function toggleMembers() {
            var membersList = document.getElementById('membersList');
            membersList.style.display = membersList.style.display === 'none' ? 'block' : 'none';
            document.getElementById('adminsList').style.display = 'none'; // Hide admins if showing members
            document.getElementById('create_post_form').style.display = 'none';
        }

        function toggleAdmins() {
            var adminsList = document.getElementById('adminsList');
            adminsList.style.display = adminsList.style.display === 'none' ? 'block' : 'none';
            document.getElementById('membersList').style.display = 'none'; // Hide members if showing admins
            document.getElementById('create_post_form').style.display = 'none';
        }

        function toggleCreatePost() {
            var postForm = document.getElementById('create_post_form');
            postForm.style.display = postForm.style.display === 'none'? 'block' : 'none';
            document.getElementById('adminsList').style.display = 'none';
            document.getElementById('membersList').style.display = 'none';
        }
    </script>
</body>
</html>
