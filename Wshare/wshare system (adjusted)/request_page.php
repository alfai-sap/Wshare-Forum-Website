<?php
require_once 'functions.php';
session_start();

$communityID = $_GET['community_id']; // Community ID to manage requests

// Fetch join requests
$query = "SELECT * FROM community_join_requests WHERE CommunityID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $communityID);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Join Requests</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Join Requests</h1>
    <table>
        <tr>
            <th>User</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) {
            $userID = $row['UserID'];
            $userQuery = "SELECT Username FROM users WHERE UserID = ?";
            $userStmt = $conn->prepare($userQuery);
            $userStmt->bind_param('i', $userID);
            $userStmt->execute();
            $userResult = $userStmt->get_result();
            $user = $userResult->fetch_assoc();
        ?>
        <tr>
            <td><?php echo htmlspecialchars($user['Username']); ?></td>
            <td>
                <a href="approve_request.php?request_id=<?php echo $row['RequestID']; ?>&action=approve">Approve</a>
                <a href="approve_request.php?request_id=<?php echo $row['RequestID']; ?>&action=decline">Decline</a>
            </td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>
