<?php
include 'dashFunctions.php'; // Include your database functions

// Get community ID from query parameter
$communityId = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch community details
$community = getCommunityDetails($communityId);

if (!$community) {
    die('Community not found.');
}

// Fetch community members
$members = getCommunityMembers($communityId);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Community</title>
    <link rel="stylesheet" href="dashboard.css?v=<?php echo time(); ?>">
    <style>
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .community-details, .community-members {
            margin-bottom: 20px;
        }
        .community-details h2, .community-members h2 {
            margin-bottom: 10px;
            color: #333;
        }
        .community-details p, .community-members ul {
            margin: 5px 0;
            color: #555;
        }
        .community-members ul {
            list-style-type: none;
            padding: 0;
        }
        .community-members li {
            background-color: #fff;
            margin: 5px 0;
            padding: 10px;
            border-radius: 4px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }
        button {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <?php include 'sidebar.php'; ?>
    <div class="container">
        <h1>Community Details</h1>
        <div class="community-details">
            <h2><?php echo htmlspecialchars($community['Title']); ?></h2>
            <p><strong>Description:</strong> <?php echo htmlspecialchars($community['Description']); ?></p>
            <p><strong>Visibility:</strong> <?php echo htmlspecialchars($community['Visibility']); ?></p>
            <p><strong>Members:</strong> <?php echo $community['member_count']; ?></p>
            <p><strong>Admins:</strong> <?php echo implode(', ', $community['admins']); ?></p>
            <p><strong>Status:</strong> <?php echo $community['is_active'] ? 'Active' : 'Inactive'; ?></p>
        </div>
        <div class="community-members">
            <h2>Members</h2>
            <ul>
                <?php foreach ($members as $member): ?>
                    <li><?php echo htmlspecialchars($member['Username']); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <button onclick="window.history.back()">Back</button>
    </div>
</body>
</html>