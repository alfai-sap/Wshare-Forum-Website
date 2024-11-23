<?php
require_once 'functions.php';
session_start();

$communityID = isset($_GET['community_id']) ? intval($_GET['community_id']) : 0;
$userID = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : 0;

$username = $_SESSION['username'];

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

// Fetch community join request
$pendingQuery = "
    SELECT cj.RequestID, u.UserID, u.Username, u.Email, up.ProfilePic 
    FROM community_join_requests cj
    JOIN users u ON cj.UserID = u.UserID
    LEFT JOIN userprofiles up ON u.UserID = up.UserID
    WHERE cj.CommunityID = ? AND cj.status = 'pending'";
$stmt = $conn->prepare($pendingQuery);
$stmt->bind_param('i' , $communityID);
$stmt->execute();
$pendingRequests = $stmt->get_result();

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
    <link rel="stylesheet" href="./css/community_page.css?v=<?php echo time(); ?>">
</head>
<body>

    <?php include 'navbar.php'; ?>	
    <div class="container">
    <img src="<?php echo htmlspecialchars($community['Thumbnail']); ?>" alt="Community Thumbnail" class="community-thumbnail">
        <h1><?php echo htmlspecialchars($community['Title']); ?></h1><br><br>
        <p class="community-description"><?php echo htmlspecialchars($community['Description']); ?></p>
        <br><br>

        <div>
            <button class="top-menu-btn" onclick="toggleCreatePost()">Create Post</button>
            <button class="top-menu-btn" onclick="toggleMembers()">Members</button>
            <button class="top-menu-btn" onclick="toggleAdmins()">Admins</button>

            <?php if ($isMember && $currentUserRole === 'admin'): ?>
                <button class="top-menu-btn" onclick="toggleRequests()">Requests</button>
                <button class="top-menu-btn" >
                    <a href="community_edit.php?community_id=<?php echo $communityID; ?>">Update details</a>
                </button>
            <?php endif;?>
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
                                <span style="color: #007bff; padding-top:10px; font-size:small;">(You)</span>
                            <?php } ?>
                        </div>
                        <?php if ($isMember && $currentUserRole === 'admin') { ?>
                            <!-- Admin-only actions -->
                            <form method="POST" action="community_manage_member.php" class="admin-actions" style="display: flex;">
                                <input type="hidden" name="community_id" value="<?php echo $communityID; ?>">
                                <input type="hidden" name="member_id" value="<?php echo $member['UserID']; ?>">
                                
                                <!-- Set as Admin button (only if the member is not already an admin) -->
                                <?php if ($member['Role'] !== 'admin') { ?>
                                    <button type="submit" name="action" value="set_admin" class="top-menu-btn" onclick="return confirm('Are you sure you want to promote this user to admin?');">Set as Admin</button>
                                <?php } ?>
                                
                                <!-- Remove Member button (prevent removing self) -->
                                <?php if ($member['UserID'] != $userID) { ?>
                                    <button type="submit" name="action" value="remove_member" class="top-menu-btn" onclick="return confirm('Are you sure you want to remove this member from the community?');">Remove</button>
                                <?php } ?>
                            </form>
                        <?php } ?>
                    </li>
                <?php } ?>
            </ul>
        </div>

        <div id="pendingRequestsList" style="display:none;">
            <h2>Pending Requests</h2>
            <ul class="profile-list">
                <?php foreach ($pendingRequests as $request) { ?>
                    <li class="profile-item">
                        <img class="profile-pic" src="<?php echo !empty($request['ProfilePic']) ? htmlspecialchars($request['ProfilePic']) : 'default_pic.svg'; ?>" alt="Profile Picture">
                        <div class="profile-details">
                            <a class="profile-username" href="view_user.php?username=<?php echo urlencode($request['Username']); ?>">
                                <?php echo htmlspecialchars($request['Username']); ?>
                            </a>
                            <p class="profile-email"><?php echo htmlspecialchars($request['Email']); ?></p>
                        </div>
                        
                        <!-- Accept and Decline buttons -->
                        <form method="POST" action="community_manage_request.php" class="admin-actions">
                            <input type="hidden" name="community_id" value="<?php echo $communityID; ?>">
                            <input type="hidden" name="user_id" value="<?php echo $request['UserID']; ?>">
                            
                            <!-- Accept button -->
                            <button type="submit" name="action" value="accept" class="action-btn" onclick="return confirm('Are you sure you want to accept this request?');">Accept</button>
                            
                            <!-- Decline button -->
                            <button type="submit" name="action" value="decline" class="action-btn remove" onclick="return confirm('Are you sure you want to decline this request?');">Decline</button>
                        </form>
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
            
            <p class="message">Welcome <?php echo $username ?>! share your story to the community.</p>

            <form id = "create_post_form" action="community_create_post.php" method="POST" style = "display:none;">
                <input type="hidden" name="community_id" value="<?php echo $communityID; ?>">
                <label for="title">Post Title:</label>
                <input type="text" id="title" name="title" required>
                
                <label for="content">Content:</label>
                <textarea id="content" name="content" required></textarea>
                
                <input type="submit" value="Create Post">
            </form>
            <br>
            <br>

        <?php } else { ?>
            <p class="message">You must be a member of this community to create posts.</p>
        <?php } ?>

        <?php if ($isMember): ?>
            <button class="leave-button"><a href="leave_community.php?community_id=<?php echo $communityID ?>" >Leave</a></button>
            <?php elseif (!$isMember): ?> 
            <button class="join-button"><a href="join_community.php?community_id=<?php echo $communityID?>" >Join</a></button>
        <?php endif;?>

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
            document.getElementById('pendingRequestsList').style.display = 'none';
            document.getElementById('edit-community').style.display = 'none';
        }

        function toggleAdmins() {
            var adminsList = document.getElementById('adminsList');

            
            adminsList.style.display = adminsList.style.display === 'none' ? 'block' : 'none';
            document.getElementById('membersList').style.display = 'none'; // Hide members if showing admins
            document.getElementById('create_post_form').style.display = 'none';
            document.getElementById('pendingRequestsList').style.display = 'none';
            document.getElementById('edit-community').style.display = 'none';
        }

        function toggleRequests() {
            var requestList = document.getElementById('pendingRequestsList');

            requestList.style.display = requestList.style.display === 'none' ? 'block' : 'none';
            document.getElementById('adminsList').style.display = 'none';
            document.getElementById('membersList').style.display = 'none'; // Hide members if showing admins
            document.getElementById('create_post_form').style.display = 'none';
            document.getElementById('edit-community').style.display = 'none';
        }
        
        function toggleEditForm() {
            var editForm = document.getElementById('edit-community');

            editForm.style.display = editForm.style.display === 'none'? 'block' : 'none';
            document.getElementById('adminsList').style.display = 'none';
            document.getElementById('membersList').style.display = 'none';
            document.getElementById('pendingRequestsList').style.display = 'none';
        }

        function toggleCreatePost() {
            var postForm = document.getElementById('create_post_form');

            postForm.style.display = postForm.style.display === 'none'? 'block' : 'none';
            document.getElementById('adminsList').style.display = 'none';
            document.getElementById('membersList').style.display = 'none';
            document.getElementById('pendingRequestsList').style.display = 'none';
            document.getElementById('edit-community').style.display = 'none';
        }
    </script>
</body>
</html>
