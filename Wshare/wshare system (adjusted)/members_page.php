<?php
require_once 'functions.php';
session_start();

$communityID = $_GET['community_id'];

// Fetch members
$query = "SELECT users.Username, community_members.Role FROM community_members
          JOIN users ON community_members.UserID = users.UserID
          WHERE community_members.CommunityID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $communityID);
$stmt->execute();
$membersResult = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Community Members</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Community Members</h1>
    <table>
        <tr>
            <th>Username</th>
            <th>Role</th>
            <?php if ($_SESSION['Role'] == 'admin') { ?>
                <th>Action</th>
            <?php } ?>
        </tr>
        <?php while ($row = $membersResult->fetch_assoc()) { ?>
        <tr>
            <td><?php echo htmlspecialchars($row['Username']); ?></td>
            <td><?php echo htmlspecialchars($row['Role']); ?></td>
            <?php if ($_SESSION['Role'] == 'admin') { ?>
                <td><a href="remove_member.php?community_id=<?php echo $communityID; ?>&user_id=<?php echo $row['UserID']; ?>">Remove</a></td>
            <?php } ?>
        </tr>
        <?php } ?>
    </table>
</body>
</html>
