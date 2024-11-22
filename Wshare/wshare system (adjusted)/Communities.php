<?php
require_once 'functions.php'; // Assuming this file handles the database connection
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php'); // Redirect to login page if not logged in
    exit;
}

// Fetch user ID
$username = $_SESSION['username'];
$userID = getUserIdByUsername($username); // You should define this function in `functions.php`

// Check if there's a search query
$searchQuery = isset($_GET['query']) ? trim($_GET['query']) : '';

$filterOption = isset($_GET['filter']) ? $_GET['filter'] : 'all';

// Modify the query based on the filter option
if (!empty($searchQuery)) {
    $baseQuery = "SELECT c.*, 
                         (SELECT role FROM community_members WHERE CommunityID = c.CommunityID AND UserID = ?) AS user_role,
                         (SELECT COUNT(*) FROM community_members WHERE CommunityID = c.CommunityID AND UserID = ?) AS is_member,
                         (SELECT COUNT(*) FROM community_members WHERE CommunityID = c.CommunityID) AS member_count
                  FROM communities c
                  WHERE c.Title LIKE ?";
    $searchParam = '%' . $searchQuery . '%';
} else {
    $baseQuery = "SELECT c.*, 
                         (SELECT role FROM community_members WHERE CommunityID = c.CommunityID AND UserID = ?) AS user_role,
                         (SELECT COUNT(*) FROM community_members WHERE CommunityID = c.CommunityID AND UserID = ?) AS is_member,
                         (SELECT COUNT(*) FROM community_members WHERE CommunityID = c.CommunityID) AS member_count
                  FROM communities c";
}

// Adjust the query based on the selected filter
if ($filterOption === 'created') {
    // User created communities
    $query = $baseQuery . " AND c.CreatorID = ? ORDER BY c.CommunityID DESC";
    $stmt = $conn->prepare($query);
    if (!empty($searchQuery)) {
        $stmt->bind_param('iiis', $userID, $userID, $userID, $searchParam);
    } else {
        $stmt->bind_param('iii', $userID, $userID, $userID);
    }
} elseif ($filterOption === 'joined') {
    // User joined communities
    $query = $baseQuery . " AND EXISTS (SELECT 1 FROM community_members cm WHERE cm.CommunityID = c.CommunityID AND cm.UserID = ?) ORDER BY c.CommunityID DESC";
    $stmt = $conn->prepare($query);
    if (!empty($searchQuery)) {
        $stmt->bind_param('iiis', $userID, $userID, $userID, $searchParam);
    } else {
        $stmt->bind_param('iii', $userID, $userID, $userID);
    }
} else {
    // All communities
    $query = $baseQuery . " ORDER BY c.CommunityID DESC";
    $stmt = $conn->prepare($query);
    if (!empty($searchQuery)) {
        $stmt->bind_param('iis', $userID, $userID, $searchParam);
    } else {
        $stmt->bind_param('ii', $userID, $userID);
    }
}

// Execute the query
$stmt->execute();
$result = $stmt->get_result();


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Communities</title>
    <link rel="stylesheet" href="./css/navbar.css ?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="./css/left-navbar.css ?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="./css/communities.css ?v=<?php echo time(); ?>">
    <style>
        
    </style>
</head>
<body>
    <ul class="navbar">
        <form class="nav" action="" method="GET" style="display: flex;">
            <input class="search-input" type="text" id="search" name="query" placeholder="I'm looking for..." value="<?php echo htmlspecialchars($searchQuery); ?>">
            <div class="btn-search">
                <input type="submit" class="search-button" value="Search">
            </div>
        </form>
    </ul>
    
    <?php include 'navbar.php';?>

    <div class="container">
        <h1>Join a Community</h1>
        <div class="sort-options">
        <a class="create" href="create_community.php">Create Hub</a> |
        <a class="create" href="my_communities.php">My Hubs</a>
        </div>
        
        <br><br>
        <div class="communities-list">
            <?php if ($result->num_rows > 0) { ?>
                <?php while ($row = $result->fetch_assoc()) { 

                    $isAdmin = $row['user_role'] === 'admin';
                    $isMember = $row['is_member'] > 0; 
                    
                    // Check if the user has a pending request for this community
                    $communityID = $row['CommunityID'];
                    $pendingQuery = "SELECT COUNT(*) AS request_count FROM community_join_requests 
                                    WHERE CommunityID = $communityID 
                                    AND UserID = $userID 
                                    AND status = 'pending'";

                    $pendingResult = $conn->query($pendingQuery);
                    $pendingRequest = $pendingResult->fetch_assoc();
                    $hasPendingRequest = $pendingRequest['request_count'] > 0;

                    // Check if the user's join request was rejected for this community
                    $rejectedQuery = "SELECT COUNT(*) AS request_count FROM community_join_requests 
                                    WHERE CommunityID = $communityID 
                                    AND UserID = $userID 
                                    AND status = 'rejected'";

                    $rejectedResult = $conn->query($rejectedQuery);
                    $rejectedRequest = $rejectedResult->fetch_assoc();
                    $hasRejectedRequest = $rejectedRequest['request_count'] > 0;

                    ?>

                    <div class="community">
                        <a href="community_page.php?community_id=<?php echo $row['CommunityID']; ?>">
                            <img src="<?php echo htmlspecialchars($row['Thumbnail']); ?>" alt="Thumbnail" class="community-thumbnail">
                            <div class="community-content">
                                <h2 class="community-title">
                                    <?php echo htmlspecialchars($row['Title']); ?>
                                    <span class="community-visibility"><?php echo htmlspecialchars($row['Visibility']); ?></span> <!-- Display visibility -->
                                </h2>
                                <p class="community-description"><?php echo htmlspecialchars($row['Description']); ?></p>
                                <p class="community-members"><?php echo $row['member_count']; ?> Members</p>
                                <?php if ($isAdmin) { ?>
                                    <span class="admin-text">You're an Admin</span>
                                <?php } elseif ($isMember) { ?>
                                    <span class="member-text">You're a Member</span>
                                <?php }elseif ($hasPendingRequest) { ?>
                                    <span class="member-text">Join request pending</span>
                                <?php } elseif ($hasRejectedRequest) { ?>
                                    <span class="member-text">Your join request was rejected</span>
                                <?php }else { ?>
                                    <a href="join_community.php?community_id=<?php echo $row['CommunityID']; ?>" class="join-button">Join</a>
                                <?php } ?>
                            </div>
                        </a>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <p>No communities found matching your search.</p>
            <?php } ?>
        </div>
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


</body>
</html>
