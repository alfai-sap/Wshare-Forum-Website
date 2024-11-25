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


// Fetch tags from the database
$tags = [];
$sql = "SELECT TagName FROM tags ORDER BY TagName ASC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $tags[] = $row['TagName'];
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


        <p class="message">Welcome <?php echo $username ?>! Share your story with the community.</p>
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

            <div class="community-form" id = "create_post_form" style="margin: 10px;">
                <form id="post-form" action="community_create_post.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="community_id" value="<?php echo $communityID; ?>">

                    <label for="title">Title:</label>
                    <input class="post-title-in" type="text" id="title" name="title" placeholder="Title..." required>

                    <label for="content">Content:</label>
                    <textarea class="post-content-in" id="content" name="content" placeholder="What am I thinking?..." required></textarea>

                    <label for="photo">Photo:</label>
                    <input class="post-image-in" type="file" id="photo" name="photo" accept="image/*">

                    <label for="tags">Tags:</label>
                    <!-- Tag Selection Dropdown -->
                    <div class="tag-dropdown-container">
                        <input type="text" class="tag-dropdown" placeholder="Select tags" readonly onclick="toggleDropdown()">
                        <div class="tag-dropdown-menu">
                            <input type="text" class="tag-search" placeholder="Search Tags..." onkeyup="filterTags()">
                            <div id="tag-list">
                                <?php foreach ($tags as $tag): ?>
                                    <div class="tag-dropdown-item">
                                        <input type="checkbox" value="<?php echo $tag; ?>" onchange="toggleTag(this)">
                                        <?php echo htmlspecialchars($tag); ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Hidden input to store selected tags -->
                    <input type="hidden" name="selected_tags" id="selected-tags">

                    <!-- Display selected tags -->
                    <div class="selected-tags" id="selected-tags-display"></div>

                    <input type="submit" class="post-postbtn-in" value="Post">
                </form>
            </div>

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
                        <div class="user_post_info" style="display: flex;">
                            <p class="post_username"><a class="post_uname" href="view_user.php?username=<?php echo urlencode($post['Username']); ?>"><?php echo $post['Username']; ?></a></p>
                            <p class="post_time"><?php echo timeAgo($post['CreatedAt']); ?></p>
                        </div>
                    </div>
                    <br>
                    <!-- Display tags associated with the post -->
                    <?php                                                          
                        $postID = $post['PostID'];

                        // Query to get tags associated with the post
                        $tagsQuery = "SELECT t.TagName FROM community_post_tags pt
                                    INNER JOIN tags t ON pt.TagID = t.TagID
                                    WHERE pt.PostID = ?";
                        $tagsStmt = $conn->prepare($tagsQuery);
                        $tagsStmt->bind_param('i', $postID);
                        $tagsStmt->execute();
                        $tagsResult = $tagsStmt->get_result();

                        $tags = [];
                        while ($row = $tagsResult->fetch_assoc()) {
                            $tags[] = $row['TagName'];
                        }

                        $tagsStmt->close();

                        if (!empty($tags)): ?>
                            <div class="post-tags">
                                <?php foreach ($tags as $tag): ?>
                                    <span class="tag-label"><?php echo htmlspecialchars($tag); ?></span>
                                <?php endforeach; ?>
                            </div>
                    <?php endif; ?>
                    <h3 class="post_title"><?php echo htmlspecialchars($post['Title']); ?></h3><br>
                    
                    <p class="post_content"><?php echo htmlspecialchars($post['Content']); ?></p>
                    <?php if (!empty($post['PhotoPath'])): ?>
                        <div class="post-image">
                            <img class = "post-image-img" src="<?php echo $post['PhotoPath']; ?>" alt="Post Image">
                        </div>
                    <?php endif; ?>
                    <br>
                    <div class="lik" style="display:flex;">
                        <form class="like" action="like_post.php" method="POST">
                            <input type="hidden" name="postID" value="<?php echo $post['PostID']; ?>">
                            <button type="submit" class="like-btn" name="like" style="background-color:transparent; border:none; padding: 10px;">
                                <img class="bulb" src="bulb.svg" style="height: 20px; width: 20px; border-radius: 50%; transition: all 0.3s ease;"
                                onmouseover="this.style.height='30px'; this.style.width='30px';"
                                onmouseout="this.style.height='20px'; this.style.width='20px';">
                            </button>
                        </form>

                        <span class="like-count" style="display:flex; align-self:center; color:#007bff;">
                            <?php echo getCommunityLikeCount($post['PostID']); ?> Brilliant Points
                        </span>

                        <button class="like-btn" style="background-color:transparent; border:none; padding: 10px;">
                            <img class="bulb" src="comment.svg" style="height:20px; width:20px; background-color:transparent; outline:none; border:none;">
                        </button>

                        <span class="like-count" style="display:flex; align-self:center; color:#007bff;">
                            <?php echo getCommunityCountComment($post['PostID']); ?> Comments
                        </span>

                        <button class="like-btn" style="background-color:transparent; border:none; padding: 10px;">
                            <a href="view_community_post.php?id=<?php echo $post['PostID']; ?>" style="display:flex; align-self:center; text-decoration:none;">
                                <img class="bulb" src="view.svg" style="height:20px; width:20px; background-color:transparent; outline:none; border:none;">
                                <p class="like-count" style="display:flex; align-self:center; color:#007bff; margin-left:5px;">See discussion</p>
                            </a>
                        </button>

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

    <script>
        // JavaScript for handling the tag dropdown functionality
        const dropdownMenu = document.querySelector('.tag-dropdown-menu');
        const tagDropdown = document.querySelector('.tag-dropdown');
        const selectedTagsInput = document.getElementById('selected-tags');
        const selectedTagsDisplay = document.getElementById('selected-tags-display');
        let selectedTags = [];

        function toggleDropdown() {
            dropdownMenu.style.display = dropdownMenu.style.display === 'block' ? 'none' : 'block';
        }

        function filterTags() {
            const searchValue = document.querySelector('.tag-search').value.toLowerCase();
            const items = document.querySelectorAll('.tag-dropdown-item');
            items.forEach(item => {
                const tagName = item.textContent.toLowerCase();
                item.style.display = tagName.includes(searchValue) ? 'flex' : 'none';
            });
        }

        function toggleTag(checkbox) {
            const tagValue = checkbox.value;
            if (checkbox.checked) {
                if (!selectedTags.includes(tagValue)) {
                    selectedTags.push(tagValue);
                    displaySelectedTags();
                }
            } else {
                selectedTags = selectedTags.filter(tag => tag !== tagValue);
                displaySelectedTags();
            }
        }

        function displaySelectedTags() {
            selectedTagsDisplay.innerHTML = '';
            selectedTags.forEach(tag => {
                const tagElement = document.createElement('div');
                tagElement.className = 'selected-tag';
                tagElement.innerHTML = `${tag} <span class="selected-tag-remove" onclick="removeTag('${tag}')">&times;</span>`;
                selectedTagsDisplay.appendChild(tagElement);
            });
            selectedTagsInput.value = selectedTags.join(',');
        }

        function removeTag(tag) {
            selectedTags = selectedTags.filter(t => t !== tag);
            document.querySelector(`.tag-dropdown-item input[value="${tag}"]`).checked = false;
            displaySelectedTags();
        }

        // Close dropdown if clicking outside
        document.addEventListener('click', function (event) {
            if (!event.target.closest('.tag-dropdown-container')) {
                dropdownMenu.style.display = 'none';
            }
        });
    </script>
</body>
</html>
