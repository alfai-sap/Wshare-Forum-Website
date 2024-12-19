<?php
require_once 'functions.php';
require_once 'changes.php'; // Add this line if it's not already there
require_once 'chat_functions.php';
require_once "bookmark_functions.php"; // Add this line to include bookmark functions
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
$query = "SELECT u.UserID, u.Username, u.Email, up.ProfilePic, cm.Role, 
                 (SELECT COUNT(*) FROM user_bans WHERE UserID = u.UserID AND CommunityID = ? AND IsActive = 1) AS IsBanned,
                 (SELECT COUNT(*) FROM user_bans WHERE UserID = u.UserID AND CommunityID = ? AND IsActive = 1 AND BannedBy = 0) AS IsBannedBySuperAdmin
          FROM community_members cm 
          JOIN users u ON cm.UserID = u.UserID 
          JOIN userprofiles up ON u.UserID = up.UserID
          WHERE cm.CommunityID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('iii', $communityID, $communityID, $communityID);
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

// Fetch reports if the user is an admin
$reports = $currentUserRole === 'admin' ? getCommunityReports($communityID) : [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($community['Title']); ?> - Community</title>
    <link rel="stylesheet" href="./css/navbar.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="./css/left-navbar.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="./css/community_page.css?v=<?php echo time(); ?>">
    <style>
        .table-container {
            overflow-x: auto;
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table th, table td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }

        table th {
            background-color: #f4f4f4;
            font-weight: bold;
        }

        .inline-form {
            display: inline-block;
            margin-right: 10px;
        }

        .inline-form select, .inline-form button {
            margin-right: 5px;
        }

        .inline-form button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
        }

        .inline-form button:hover {
            background-color: #0056b3;
        }

        .inline-form button:active {
            transform: scale(0.98);
        }

        .ban-message {
            color: red;
            background-color: #ffe6e6;
            padding: 10px;
            border: 1px solid red;
            border-radius: 5px;
            margin: 10px 0;
        }
        
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4);
            padding-top: 60px;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            border-radius: 8px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
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
                <button class="top-menu-btn" onclick="toggleReports()">Manage Reports</button>
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
                                
                                <!-- Ban/Unban Member button -->
                                <?php if ($member['IsBannedBySuperAdmin'] > 0) { ?>
                                    <span class="admin-text">Banned</span>
                                <?php } elseif ($member['IsBanned'] > 0) { ?>
                                    <button type="submit" name="action" value="unban_member" class="top-menu-btn" onclick="return confirm('Are you sure you want to unban this member?');">Unban</button>
                                <?php } else { ?>
                                    <button type="button" class="top-menu-btn" onclick="openBanModal(<?php echo $member['UserID']; ?>)">Ban</button>
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
        
        <!-- Admin Section for Managing Reports -->
        <div id="reportsList" style="display:none;">
            <h2>Manage Reports</h2>
            <div class="table-container">
                <table>
                    <tr>
                        <th>ID</th>
                        <th>Type</th>
                        <th>Reporter</th>
                        <th>Violation</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                    <?php foreach ($reports as $report): ?>
                    <tr>
                        <td><?php echo $report['ReportID']; ?></td>
                        <td><?php echo ucfirst($report['ReportType']); ?></td>
                        <td><?php echo htmlspecialchars($report['Username']); ?></td>
                        <td><?php echo htmlspecialchars($report['Violation']); ?></td>
                        <td><?php echo ucfirst($report['Status']); ?></td>
                        <td>
                            <form method="POST" class="inline-form" action="update_report_status.php">
                                <input type="hidden" name="report_id" value="<?php echo $report['ReportID']; ?>">
                                <select name="status">
                                    <option value="pending" <?php echo $report['Status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                    <option value="reviewed" <?php echo $report['Status'] == 'reviewed' ? 'selected' : ''; ?>>Reviewed</option>
                                    <option value="resolved" <?php echo $report['Status'] == 'resolved' ? 'selected' : ''; ?>>Resolved</option>
                                </select>
                            </form>
                            <a href="view_community_report.php?id=<?php echo $report['ReportID']; ?>">View</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>

        <!-- Only show the post form if the user is a member -->
        <?php if ($isMember) { ?>
            <?php if (checkUserBan(true, $communityID)): ?>
                <div class="ban-message">
                    <?php echo checkUserBan(true, $communityID); ?>
                </div>
            <?php else: ?>
                <div class="community-form" id="create_post_form" style="margin: 10px; display:none;">
                    <p class="message">Got an Interesting topic ? share it to the community.</p>
                    <form id="post-form" action="community_create_post.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="community_id" value="<?php echo $communityID; ?>">

                        <label for="title">Title:</label>
                        <input class="post-title-in" type="text" id="title" name="title" placeholder="Title..." required>

                        <label for="content">Content:</label>
                        <textarea class="post-content-in" id="content" name="content" placeholder="I'm thinking about..." required></textarea>

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
                    
                    <div class="post-buttons-separator"></div>
                    
                    <div class="lik">
                        <form class="like" action="like_community_post.php" method="POST">
                            <input type="hidden" name="postID" value="<?php echo $post['PostID']; ?>">
                            <button type="submit" class="like-btn" name="like">
                                <?php if (hasUserLikedPost($post['PostID'], $_SESSION['user_id'])): ?>
                                    <img class="bulb liked-icon" src="bulb_active.svg">
                                <?php else: ?>
                                    <img class="bulb" src="bulb.svg">
                                <?php endif; ?>
                            </button>
                        </form>
                        <span class="like-count">
                            <?php echo getCommunityLikeCount($post['PostID']); ?> Brilliant Points
                        </span>
                        <button class="comment-btn">
                            <img class="bulb" src="comment.svg">
                        </button>
                        <span class="comment-count">
                            <?php echo getCommunityCountComment($post['PostID']); ?> Comments
                        </span>
                        <button class="view-btn">
                            <a href="view_community_post.php?id=<?php echo $post['PostID']; ?>" class="view-link">
                                <img class="bulb" src="view.svg">
                                
                            </a>
                            <span class="comment-count">See discussion</span>
                        </button>
                    </div>
                </div>
            </div>
        <?php } ?>

    </div><!-- Close main container -->

    <?php if ($isMember): ?>
    <?php if (checkUserBan(true, $communityID)): ?>
        <div class="ban-message">
            <?php echo checkUserBan(true, $communityID); ?>
        </div>
    <?php else: ?>
    <div class="chat-container" id="chat-container">
        <div class="chat-header">
            <div class="chat-header-top">
                <h3>Community Chat</h3>
                <div class="chat-header-buttons">
                    <button class="refresh-btn" onclick="refreshMessages()" title="Refresh messages">
                        <img src="refresh.svg" alt="Refresh" width="16" height="16">
                    </button>
                    <button class="top-menu-btn" onclick="toggleChatWindow()">Close</button>
                </div>
            </div>
            <div class="chat-filters">
                <div class="filter-dropdown">
                    <button class="filter-btn" id="current-filter">Chat</button>
                    <div class="filter-options">
                        <button onclick="filterMessages('all')" class="filter-option">Chat</button>
                        <button onclick="filterMessages('documents')" class="filter-option">Documents</button>
                        <button onclick="filterMessages('pictures')" class="filter-option">Pictures</button>
                        <button onclick="filterMessages('videos')" class="filter-option">Videos</button>
                    </div>
                </div>
            </div>
            <div class="chat-search">
                <input type="text" id="chat-search" placeholder="Search messages...">
                <button id="clear-search" style="display: none;">Clear</button>
            </div>
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
                <div class="message <?php echo $messageClass; ?>" data-message-id="<?php echo $message['MessageID']; ?>">
                    <?php if (!$isOwnMessage): ?>
                        <div class="message-header">
                            <img src="<?php echo htmlspecialchars($message['ProfilePic']); ?>" 
                                 alt="Profile" class="message-avatar" width="24" height="24">
                            <span class="message-username"><?php echo htmlspecialchars($message['Username']); ?></span>
                            <span class="message-timestamp"><?php echo timeAgo($message['CreatedAt']); ?></span>
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
                        
                        
                    </div>
                    
                    <?php if ($isOwnMessage): ?>
                        <div class="message-header">
                            <span class="message-timestamp"><?php echo timeAgo($message['CreatedAt']); ?></span>
                        </div>
                    <?php endif; ?>
                    
                    <div class="message-actions">
                        <button class="reply-btn" onclick="setReplyTo(<?php echo $message['MessageID']; ?>, '<?php echo htmlspecialchars($message['Username']); ?>')">
                            reply
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
<?php endif; ?>

<!-- Ban Member Modal -->
<div id="banMemberModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeBanModal()">&times;</span>
        <h2>Ban Member</h2>
        <form id="banMemberForm" method="POST" action="community_manage_member.php">
            <input type="hidden" name="community_id" value="<?php echo $communityID; ?>">
            <input type="hidden" name="member_id" id="banMemberId">
            <input type="hidden" name="action" value="ban_member">
            
            <div class="form-group">
                <label for="ban_duration">Ban Duration:</label>
                <select name="ban_duration" id="ban_duration" required class="form-control">
                    <option value="1">1 day</option>
                    <option value="3">3 days</option>
                    <option value="7">1 week</option>
                    <option value="30">1 month</option>
                    <option value="90">3 months</option>
                    <option value="365">1 year</option>
                    <option value="36500">Permanent</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="ban_reason">Reason:</label>
                <textarea name="ban_reason" id="ban_reason" required class="form-control"></textarea>
            </div>
            
            <button type="submit" class="btn btn-danger">Ban User</button>
            <button type="button" onclick="closeBanModal()" class="btn btn-secondary">Cancel</button>
        </form>
    </div>
</div>

<script>

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
    const maxSize = 25 * 1024 * 1024; // 25MB in bytes
    const errorDiv = document.getElementById('file-error');
    
    if (attachments.length > 0) {
        let totalSize = 0;
        for (let i = 0; i < attachments.length; i++) {
            totalSize += attachments[i].size;
            
            if (attachments[i].size > maxSize) {
                errorDiv.textContent = `File "${attachments[i].name}" exceeds 25MB limit`;
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
    const maxSize = 25 * 1024 * 1024; // 25MB in bytes
    const errorDiv = document.getElementById('file-error');
    const preview = document.getElementById('attachment-preview');
    preview.innerHTML = '';
    errorDiv.style.display = 'none';
    
    let totalSize = 0;
    for (const file of e.target.files) {
        totalSize += file.size;
        if (file.size > maxSize) {
            errorDiv.textContent = `File "${file.name}" exceeds 25MB limit`;
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

    function toggleReports() {
        var reportsList = document.getElementById('reportsList');

        reportsList.style.display = reportsList.style.display === 'none' ? 'block' : 'none';
        document.getElementById('adminsList').style.display = 'none';
        document.getElementById('membersList').style.display = 'none';
        document.getElementById('pendingRequestsList').style.display = 'none';
        document.getElementById('create_post_form').style.display = 'none';
        document.getElementById('edit-community').style.display = 'none';
    }
    
    function openBanModal(memberId) {
        document.getElementById('banMemberId').value = memberId;
        document.getElementById('banMemberModal').style.display = 'block';
    }

    function closeBanModal() {
        document.getElementById('banMemberModal').style.display = 'none';
    }
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

<script>
document.querySelectorAll('.post_title').forEach(title => {
    title.addEventListener('click', function(e) {
        e.preventDefault(); // Prevent any default behavior
        const content = this.closest('.post').querySelector('.post_content');
        
        // Toggle content visibility
        content.classList.toggle('show');
        this.classList.toggle('active');
    });
});
</script>

<script>
// Add this near your other chat-related JavaScript
document.getElementById('chat-search').addEventListener('input', function(e) {
    const searchText = e.target.value.toLowerCase();
    const clearButton = document.getElementById('clear-search');
    const messages = document.querySelectorAll('#chat-messages .message');
    
    clearButton.style.display = searchText ? 'block' : 'none';
    
    messages.forEach(message => {
        const content = message.querySelector('.message-content').textContent.toLowerCase();
        const username = message.querySelector('.message-username');
        const usernameText = username ? username.textContent.toLowerCase() : '';
        
        // Remove any existing highlight spans
        message.querySelectorAll('.highlight').forEach(span => {
            const text = span.textContent;
            span.replaceWith(text);
        });
        
        if (searchText === '') {
            message.style.display = 'flex';
            return;
        }
        
        if (content.includes(searchText) || usernameText.includes(searchText)) {
            message.style.display = 'flex';
            
            // Highlight matching text in content
            const contentElement = message.querySelector('.message-content');
            const originalText = contentElement.textContent;
            const highlightedText = originalText.replace(
                new RegExp(searchText, 'gi'),
                match => `<span class="highlight">${match}</span>`
            );
            contentElement.innerHTML = highlightedText;
            
            // Scroll the message into view
            message.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        } else {
            message.style.display = 'none';
        }
    });
});

document.getElementById('clear-search').addEventListener('click', function() {
    const searchInput = document.getElementById('chat-search');
    searchInput.value = '';
    searchInput.dispatchEvent(new Event('input'));
    this.style.display = 'none';
});
</script>

<script>
function filterMessages(type) {
    const messages = document.querySelectorAll('#chat-messages .message');
    const currentFilter = document.getElementById('current-filter');
    currentFilter.textContent = type.charAt(0).toUpperCase() + type.slice(1);
    
    messages.forEach(message => {
        const hasAttachment = message.querySelector('.message-attachments');
        
        switch(type) {
            case 'documents':
                message.style.display = hasAttachment && 
                    message.querySelector('a[href$=".pdf"], a[href$=".doc"], a[href$=".docx"], a[href$=".txt"]') ? 
                    'flex' : 'none';
                break;
            case 'pictures':
                message.style.display = hasAttachment && 
                    message.querySelector('img:not(.message-avatar)') ? 
                    'flex' : 'none';
                break;
            case 'videos':
                message.style.display = hasAttachment && 
                    message.querySelector('video, a[href$=".mp4"], a[href$=".mov"]') ? 
                    'flex' : 'none';
                break;
            default:
                message.style.display = 'flex';
        }
    });
}

// Update existing search function to include attachments
document.getElementById('chat-search').addEventListener('input', function(e) {
    const searchText = e.target.value.toLowerCase();
    const clearButton = document.getElementById('clear-search');
    const messages = document.querySelectorAll('#chat-messages .message');
    
    clearButton.style.display = searchText ? 'block' : 'none';
    
    messages.forEach(message => {
        const content = message.querySelector('.message-content').textContent.toLowerCase();
        const username = message.querySelector('.message-username');
        const usernameText = username ? username.textContent.toLowerCase() : '';
        const attachments = message.querySelectorAll('.message-attachments a');
        let attachmentNames = '';
        attachments.forEach(a => attachmentNames += a.textContent.toLowerCase() + ' ');
        
        if (searchText === '') {
            message.style.display = 'flex';
            return;
        }
        
        if (content.includes(searchText) || 
            usernameText.includes(searchText) || 
            attachmentNames.includes(searchText)) {
            // ... existing highlight code ...
        } else {
            message.style.display = 'none';
        }
    });
});
</script>

<script>
function refreshMessages() {
    const refreshBtn = document.querySelector('.refresh-btn img');
    refreshBtn.classList.add('rotating');
    
    fetch(`get_messages.php?community_id=<?php echo $communityID; ?>`)
        .then(response => response.json())
        .then(messages => {
            const chatMessages = document.getElementById('chat-messages');
            chatMessages.innerHTML = ''; // Clear existing messages
            
            messages.forEach(message => {
                // Recreate message elements
                const messageDiv = createMessageElement(message);
                chatMessages.appendChild(messageDiv);
            });
        })
        .catch(error => console.error('Error:', error))
        .finally(() => {
            setTimeout(() => {
                refreshBtn.classList.remove('rotating');
            }, 500);
        });
}

function createMessageElement(message) {
    // This is a simplified version - you'll need to match your existing message structure
    const div = document.createElement('div');
    div.className = `message ${message.UserID == <?php echo $userID; ?> ? 'message-self' : ''}`;
    // Add your message content structure here
    return div;
}
</script>

</body>
</html>

