<?php
require_once 'functions.php';
require_once 'changes.php'; // Add this line if it's not already there
require_once 'chat_functions.php';
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

// Fetch chat messages
$messages = getMessages($communityID);
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
    <link rel="stylesheet" href="./css/right-chat.css?v=<?php echo time(); ?>">
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
            <button class="top-menu-btn" id="floating-chat-btn" onclick="toggleChatWindow()">
                Community Chat
            </button>
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
                                <span style="color: #007bff;">(You)</span><br>
                                <form method="POST" action="community_manage_member.php" class="admin-actions">
                                    <input type="hidden" name="community_id" value="<?php echo $communityID; ?>">
                                    <input type="hidden" name="member_id" value="<?php echo $userID; ?>">
                                    <button class="top-menu-btn" style="margin-top: 10px;" type="submit" name="action" value="leave_admin_role" onclick="return confirm('Are you sure you want to leave the admin role?');">Leave Role</button>
                                </form>
                            <?php } ?>
                        </div>
                    </li>
                <?php } ?>
            </ul>
        </div>
        
        

        <!-- Only show the post form if the user is a member -->
        <?php if ($isMember) { ?>
            <?php if (checkUserBan()): ?>
                <div class="ban-message" style="color: red; margin: 10px 0;">
                    <?php echo checkUserBan(true); ?>
                </div>
            <?php else: ?>
                <div class="community-form" id="create_post_form" style="margin: 10px; display:none;">
                    <p class="message">Welcome <?php echo $username ?>! Share your story with the community.</p>
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
            <?php endif; ?>
            <br><br>
        <?php } else { ?>
            <p class="message">You must be a member of this community to create posts.</p>
        <?php } ?>

        <?php if ($isMember): ?>
            <button class="leave-button"><a href="leave_community.php?community_id=<?php echo $communityID ?>" >Leave</a></button>
        <?php else: ?>
            <?php
            // Check if user has a pending request
            $pendingRequestCheck = "SELECT status FROM community_join_requests 
                                  WHERE CommunityID = ? AND UserID = ? AND status = 'pending'";
            $stmt = $conn->prepare($pendingRequestCheck);
            $stmt->bind_param('ii', $communityID, $userID);
            $stmt->execute();
            $pendingResult = $stmt->get_result();
            $hasPendingRequest = $pendingResult->num_rows > 0;
            ?>
            
            <?php if ($hasPendingRequest): ?>
                <div class="pending-message" style="color: #007bff; padding: 10px; text-align: center;">Your request is pending for approval</div>
            <?php else: ?>
                <button class="join-button"><a href="join_community.php?community_id=<?php echo $communityID?>" >Join</a></button>
            <?php endif; ?>
        <?php endif; ?><br><br>

        <div class="search-bar">
            <form action="community_page.php" method="GET" class="search-bar" style="width: 100;display:flex;">
                <input type="hidden" name="community_id" value="<?php echo $communityID; ?>">
                <input type="text" name="search" placeholder="Search posts..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" class = "search-input">

                <select name="tag" id="tag" style="height: 40px;">
                    <option value="">Select a tag</option>
                    <?php foreach ($tags as $tag): ?>
                        <option value="<?php echo htmlspecialchars($tag); ?>" <?php echo (isset($_GET['tag']) && $_GET['tag'] == $tag) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($tag); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <button class = "search-button" type="submit" style="height: 40px;">Search</button>
            </form>
        </div>
        <?php 
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $tag = isset($_GET['tag']) ? trim($_GET['tag']) : '';

        $searchQuery = "SELECT p.*, up.ProfilePic, u.Username 
                        FROM community_posts p
                        JOIN users u ON p.UserID = u.UserID
                        JOIN userprofiles up ON up.UserID = u.UserID
                        WHERE p.CommunityID = ?";

        $params = [$communityID];
        $types = "i";

        if (!empty($search)) {
            $searchQuery .= " AND (p.Title LIKE ? OR p.Content LIKE ?)";
            $params[] = '%' . $search . '%';
            $params[] = '%' . $search . '%';
            $types .= "ss";
        }

        if (!empty($tag)) {
            $searchQuery .= " AND EXISTS (
                                SELECT 1 FROM community_post_tags pt
                                JOIN tags t ON pt.TagID = t.TagID
                                WHERE pt.PostID = p.PostID AND t.TagName = ?
                            )";
            $params[] = $tag;
            $types .= "s";
        }

        $searchQuery .= " ORDER BY p.CreatedAt DESC";
        $stmt = $conn->prepare($searchQuery);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $postsResult = $stmt->get_result();
        ?>
        <br>
        <h2>Posts</h2><br><br>
        <?php while ($post = $postsResult->fetch_assoc()) { ?>
            <div class="post-container">
                
                <div class="post">
                <?php if (hasUserLikedPost($post['PostID'], $_SESSION['user_id'])): ?>
                        <div class="liked-message" style="background-color: #e0f7fa; padding: 10px; border-radius: 5px; margin-bottom: 10px;">
                            <p style="margin: 0; color: #00796b;">You liked this post on <?php echo date('F j, Y', strtotime(getLikeDate($post['PostID'], $_SESSION['user_id']))); ?></p>
                        </div><br>
                    <?php endif; ?>
                    <div class="pic_user">
                        <?php if (!empty($post['ProfilePic'])): ?>
                            <img class="author_pic" src="<?php echo $post['ProfilePic']; ?>" alt="Profile Picture">
                        <?php else: ?>
                            <img class="author_pic" src="default_pic.svg" alt="Profile Picture">
                        <?php endif; ?>
                        <div class="user_post_info" style="display: flex;">
                            <p class="post_username">
                                <a class="post_uname" href="view_user.php?username=<?php echo urlencode($post['Username']); ?>">
                                    <?php echo $post['Username']; ?>
                                </a>
                                <?php
                                // Check if post author is an admin of this community
                                $adminCheck = "SELECT Role FROM community_members WHERE CommunityID = ? AND UserID = ? AND Role = 'admin'";
                                $stmt = $conn->prepare($adminCheck);
                                $stmt->bind_param('ii', $communityID, $post['UserID']);
                                $stmt->execute();
                                $adminResult = $stmt->get_result();
                                if ($adminResult->num_rows > 0): ?>
                                    <span style="color: #ff4b4b; margin-left: 5px; font-size: 0.8em;padding: 2px 6px; border: 1px solid #ff4b4b; border-radius: 4px;">Admin</span>
                                <?php endif; ?>
                            </p>
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
                        <form class="like" action="like_community_post.php" method="POST">
                            <input type="hidden" name="postID" value="<?php echo $post['PostID']; ?>">
                            <button type="submit" class="like-btn" name="like" style="background-color:transparent; border:none; padding: 10px;">
                                <?php if (hasUserLikedPost($post['PostID'], $_SESSION['user_id'])): ?>
                                    <img class="bulb" src="bulb_active.svg" style="height:20px; width:20px;">
                                <?php else: ?>
                                    <img class="bulb" src="bulb.svg" style="height:20px; width:20px;">
                                <?php endif; ?>
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

    </div><!-- Close main container -->

    <?php if ($isMember): ?>
    <div class="chat-container" id="chat-container">
        <div class="chat-header">
            <h3>Community Chat</h3><br>
            <button class="top-menu-btn" onclick="toggleChatWindow()">Close</button>
        </div>
        
        <?php if (isset($_SESSION['chat_error'])): ?>
            <div class="error-message" style="color: red; padding: 10px; background-color: #ffe6e6; margin: 10px; border-radius: 5px;">
                <?php 
                    echo htmlspecialchars($_SESSION['chat_error']); 
                    unset($_SESSION['chat_error']);
                ?>
            </div>
        <?php endif; ?>

        <div class="chat-messages" id="chat-messages">
            <?php foreach ($messages as $message): 
                $isOwnMessage = $message['UserID'] == $userID;
                $messageClass = $isOwnMessage ? 'message-self' : '';
            ?>
                <div class="message <?php echo $messageClass; ?>" data-message-id="<?php echo $message['MessageID']; ?>" onclick="toggleReplyButton(this)">
                    <?php if (!$isOwnMessage): ?>
                        <div class="message-header">
                            <img src="<?php echo htmlspecialchars($message['ProfilePic']); ?>" 
                                 alt="Profile" class="message-avatar" width="24" height="24">
                            <span class="message-username"><?php echo htmlspecialchars($message['Username']); ?></span>
                        </div>
                    <?php endif; ?>
                    
                    <div class="message-content">
                        <?php if ($message['ParentMessageID']): 
                            $parentMessage = getMessageByID($message['ParentMessageID']);
                        ?>
                            <div class="reply-indicator">
                                <strong><?php echo htmlspecialchars($parentMessage['Username']); ?>:</strong>
                                <span><?php echo htmlspecialchars(substr($parentMessage['Content'], 0, 50)); ?>...</span>
                            </div>
                        <?php endif; ?>
                        <?php echo htmlspecialchars($message['Content']); ?>
                        
                        <?php
                        // Fetch message attachments
                        $attachments = getMessageAttachments($message['MessageID']);
                        
                        if (!empty($attachments)): ?>
                            <div class="message-attachments">
                            <?php foreach ($attachments as $attachment): ?>
                                <?php if (strpos($attachment['FileType'], 'image') === 0): ?>
                                    <img src="<?php echo htmlspecialchars($attachment['FilePath']); ?>" alt="Attachment">
                                <?php else: ?>
                                    <a href="<?php echo htmlspecialchars($attachment['FilePath']); ?>" target="_blank">
                                        <?php echo htmlspecialchars($attachment['FileName']); ?>
                                    </a>
                                <?php endif; ?>
                            <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                        <div class="message-reactions">
                            <div class="reaction-buttons">
                                <button onclick="toggleReaction(<?php echo $message['MessageID']; ?>, '👍')" class="reaction-btn <?php echo hasUserReacted($message['MessageID'], $userID, '👍') ? 'active' : ''; ?>">
                                    👍
                                </button>
                                <button onclick="toggleReaction(<?php echo $message['MessageID']; ?>, '❤️')" class="reaction-btn <?php echo hasUserReacted($message['MessageID'], $userID, '❤️') ? 'active' : ''; ?>">
                                    ❤️
                                </button>
                                <button onclick="toggleReaction(<?php echo $message['MessageID']; ?>, '😄')" class="reaction-btn <?php echo hasUserReacted($message['MessageID'], $userID, '😄') ? 'active' : ''; ?>">
                                    😄
                                </button>
                                <button onclick="toggleReaction(<?php echo $message['MessageID']; ?>, '😮')" class="reaction-btn <?php echo hasUserReacted($message['MessageID'], $userID, '😮') ? 'active' : ''; ?>">
                                    😮
                                </button>
                            </div>
                            <?php 
                            $reactions = getMessageReactions($message['MessageID']);
                            foreach ($reactions as $reaction): ?>
                                <div class="reaction-count" title="<?php echo htmlspecialchars($reaction['users']); ?>">
                                    <?php echo $reaction['ReactionType']; ?> 
                                    <span class="count"><?php echo $reaction['count']; ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <div class="message-info">
                            <?php echo timeAgo($message['CreatedAt']); ?>
                        </div>
                    </div>
                    
                    <div class="message-actions" style="display: none;">
                        <button class="reply-btn" onclick="setReplyTo(<?php echo $message['MessageID']; ?>, '<?php echo htmlspecialchars($message['Username']); ?>')">
                            <img src="reply.svg" alt="Reply" width="16" height="16"> Reply
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="chat-input">
            <form method="POST" action="send_message.php" enctype="multipart/form-data" class="chat-form" onsubmit="return validateForm()">
                <input type="hidden" name="community_id" value="<?php echo $communityID; ?>">
                <input type="hidden" name="reply_to" id="reply-to" value="">
                <div id="reply-indicator" style="display: none;">
                    Replying to <span id="reply-username"></span>
                    <button type="button" onclick="clearReply()">Cancel</button>
                </div>
                <textarea name="message" placeholder="Type a message..." required></textarea>
                <input type="file" name="attachments[]" id="attachments" multiple style="display: none">
                <button type="button" onclick="document.getElementById('attachments').click()">
                    <img src="attach.svg" alt="Attach" width="20" height="20">
                </button>
                <button type="submit">
                    <img src="sendm.svg" alt="Send" width="20" height="20">
                </button>
            </form>
            <div id="attachment-preview" class="attachment-preview"></div>
            <div id="file-error" class="error-message" style="display: none; color: red;"></div>
        </div>
    </div>
    
<?php endif; ?>

<script>
    function toggleReplyButton(messageElement) {
        const actions = messageElement.querySelector('.message-actions');
        actions.style.display = actions.style.display === 'block' ? 'none' : 'block';
    }

    function setReplyTo(messageID, username) {
        document.getElementById('reply-to').value = messageID;
        document.getElementById('reply-username').textContent = username;
        document.getElementById('reply-indicator').style.display = 'block';
    }

    function clearReply() {
        document.getElementById('reply-to').value = '';
        document.getElementById('reply-indicator').style.display = 'none';
    }

    function toggleChatWindow() {
        const chatContainer = document.getElementById('chat-container');
        const floatingChatBtn = document.getElementById('floating-chat-btn');
        if (chatContainer.style.display === 'none' || chatContainer.style.display === '') {
            chatContainer.style.display = 'flex';
        } else {
            chatContainer.style.display = 'none';
        }
    }

    // Simple attachment preview
    document.getElementById('attachments').addEventListener('change', function(e) {
        const preview = document.getElementById('attachment-preview');
        preview.innerHTML = '';
        
        [...e.target.files].forEach(file => {
            const div = document.createElement('div');
            div.className = 'preview-item';
            
            if (file.type.startsWith('image/')) {
                const img = document.createElement('img');
                img.src = URL.createObjectURL(file);
                div.appendChild(img);
            } else {
                div.textContent = file.name;
            }
            
            const remove = document.createElement('button');
            remove.className = 'remove-preview';
            remove.textContent = '×';
            remove.onclick = () => div.remove();
            div.appendChild(remove);
            
            preview.appendChild(div);
        });
    });
</script>

<script>
// Add this to your existing JavaScript
function toggleReaction(messageId, reactionType) {
    fetch('toggle_reaction.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `message_id=${messageId}&reaction_type=${encodeURIComponent(reactionType)}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Refresh the chat messages
            location.reload(); // You might want to implement a more elegant solution
        }
    })
    .catch(error => console.error('Error:', error));
}
</script>

<script>
// Add this to your existing JavaScript
function validateForm() {
    const attachments = document.getElementById('attachments').files;
    const maxSize = 5 * 1024 * 1024; // 5MB in bytes
    const errorDiv = document.getElementById('file-error');
    
    if (attachments.length > 0) {
        let totalSize = 0;
        for (let i = 0; i < attachments.length; i++) {
            totalSize += attachments[i].size;
            
            if (attachments[i].size > maxSize) {
                errorDiv.textContent = `File "${attachments[i].name}" exceeds 5MB limit`;
                errorDiv.style.display = 'block';
                return false;
            }
        }
        
        if (totalSize > maxSize * attachments.length) {
            errorDiv.textContent = 'Total file size exceeds the limit';
            errorDiv.style.display = 'block';
            return false;
        }
    }
    
    errorDiv.style.display = 'none';
    return true;
}

document.getElementById('attachments').addEventListener('change', function(e) {
    const maxSize = 5 * 1024 * 1024; // 5MB in bytes
    const errorDiv = document.getElementById('file-error');
    const preview = document.getElementById('attachment-preview');
    preview.innerHTML = '';
    errorDiv.style.display = 'none';
    
    let totalSize = 0;
    for (const file of e.target.files) {
        totalSize += file.size;
        if (file.size > maxSize) {
            errorDiv.textContent = `File "${file.name}" exceeds 5MB limit`;
            errorDiv.style.display = 'block';
            e.target.value = ''; // Clear the file input
            return;
        }
    }
    
    if (totalSize > maxSize * e.target.files.length) {
        errorDiv.textContent = 'Total file size exceeds the limit';
        errorDiv.style.display = 'block';
        e.target.value = ''; // Clear the file input
        return;
    }
    
    // If validation passes, show preview
    [...e.target.files].forEach(file => {
        const div = document.createElement('div');
        div.className = 'preview-item';
        
        if (file.type.startsWith('image/')) {
            const img = document.createElement('img');
            img.src = URL.createObjectURL(file);
            div.appendChild(img);
        } else {
            div.textContent = `${file.name} (${(file.size / 1024 / 1024).toFixed(2)}MB)`;
        }
        
        const remove = document.createElement('button');
        remove.className = 'remove-preview';
        remove.textContent = '×';
        remove.onclick = () => {
            div.remove();
            if (preview.children.length === 0) {
                e.target.value = '';
            }
        };
        div.appendChild(remove);
        
        preview.appendChild(div);
    });
});
</script>

<script>
    function filterTags() {
        const searchInput = document.querySelector('.tag-search');
        const filter = searchInput.value.toLowerCase();
        const tags = document.querySelectorAll('.tag-dropdown-item');

        tags.forEach(tag => {
            if (tag.textContent.toLowerCase().includes(filter)) {
                tag.style.display = 'block';
            } else {
                tag.style.display = 'none';
            }
        });
    }

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

<style>
.message-reactions {
    margin-top: 5px;
    display: flex;
    flex-wrap: wrap;
    gap: 5px;
}

.reaction-buttons {
    display: flex;
    gap: 2px;
}

.reaction-btn {
    background: none;
    border: none;
    padding: 2px 5px;
    cursor: pointer;
    border-radius: 12px;
    transition: background-color 0.2s;
}

.reaction-btn:hover {
    background-color: #e0e0e0;
}

.reaction-btn.active {
    background-color: #e3f2fd;
}

.reaction-count {
    background-color: #f0f2f5;
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 0.9em;
    display: flex;
    align-items: center;
    gap: 4px;
    cursor: default;
}

.reaction-count .count {
    font-size: 0.9em;
    color: #65676b;
}
</style>

</body>
</html>

