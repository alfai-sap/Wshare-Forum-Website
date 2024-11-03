<?php
require_once 'functions.php'; // Handles database connection and other common functions
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

// Get the filter option: 'all', 'created', or 'joined'
$filterOption = isset($_GET['filter']) ? $_GET['filter'] : 'all';

// Modify the query based on the filter option and search query
$baseQuery = "SELECT c.*, 
                     (SELECT role FROM community_members WHERE CommunityID = c.CommunityID AND UserID = ?) AS user_role,
                     (SELECT COUNT(*) FROM community_members WHERE CommunityID = c.CommunityID AND UserID = ?) AS is_member,
                     (SELECT COUNT(*) FROM community_members WHERE CommunityID = c.CommunityID) AS member_count
              FROM communities c";

// Adjust the query based on the filter option
if ($filterOption === 'created') {
    // User-created communities
    $query = $baseQuery . " WHERE c.CreatorID = ? ";
    $stmt = $conn->prepare($query . "ORDER BY c.CommunityID DESC");
    $stmt->bind_param('iii', $userID, $userID, $userID);
} elseif ($filterOption === 'joined') {
    // User-joined communities
    $query = $baseQuery . " WHERE EXISTS (SELECT 1 FROM community_members cm WHERE cm.CommunityID = c.CommunityID AND cm.UserID = ?) ";
    $stmt = $conn->prepare($query . "ORDER BY c.CommunityID DESC");
    $stmt->bind_param('iii', $userID, $userID, $userID);
} else {
    // All communities
    $query = $baseQuery . " ORDER BY c.CommunityID DESC";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ii', $userID, $userID);
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
    <title>My Communities</title>
    <link rel="stylesheet" href="./css/navbar.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="./css/left-navbar.css ?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="./css/communities.css?v=<?php echo time(); ?>">

</head>
<body>

    <ul class="navbar">
        <form class = "nav" action="" method="GET" style="display:flex;">
            <input type="hidden" name="filter" value="<?php echo htmlspecialchars($filterOption); ?>">
            <input class = "search-input" type="text" name="query" placeholder="Search communities..." value="<?php echo htmlspecialchars($searchQuery); ?>">
            <div class = "btn-search">
                <input type="submit" class="search-button" value="Search">
            </div>
        </form>
    </ul>

    <?php include 'navbar.php';?>

    

    <div class="container">
        <h1>My Communities</h1>
        
        <!-- Filter Links for Sorting -->
        <div class="filter-links">
            <!--<a href="?filter=all" class="<?php echo $filterOption === 'all' ? 'active' : ''; ?>">All</a>-->
            <a class = "create" href="Communities.php">All</a> |
            <a class = "create" href="?filter=created" class="<?php echo $filterOption === 'created' ? 'active' : ''; ?>">Created</a> |
            <a class = "create" href="?filter=joined" class="<?php echo $filterOption === 'joined' ? 'active' : ''; ?>">Joined</a>
            
        </div><br><br>

        <!-- Search Form -->
        

        <div class="communities-list">
            <?php if ($result->num_rows > 0) { ?>
                <?php while ($row = $result->fetch_assoc()) { 
                    $isAdmin = $row['user_role'] === 'admin';
                    $isMember = $row['is_member'] > 0; ?>
                    <div class="community">
                        <a href="community_page.php?community_id=<?php echo $row['CommunityID']; ?>">
                            <img src="<?php echo htmlspecialchars($row['Thumbnail']); ?>" alt="Thumbnail" class="community-thumbnail">
                        </a>
                        <div class="community-content">
                            <h2><?php echo htmlspecialchars($row['Title']); ?></h2>
                            <p><?php echo htmlspecialchars($row['Description']); ?></p>
                            <br>
                            <p><?php echo $row['member_count']; ?> Members</p>
                            <br>
                            <?php if ($isAdmin) { ?>
                                <span>You're an Admin</span>
                            <?php } elseif ($isMember) { ?>
                                <span>You're a Member</span>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <br><br><br>
                <p>No communities found.</p>
                <a class="create_" href="create_community.php" style = "color:#007bff; text-decoration:none;">I want to create one</a>
            <?php } ?>
        </div>
    </div>

</body>
</html>
