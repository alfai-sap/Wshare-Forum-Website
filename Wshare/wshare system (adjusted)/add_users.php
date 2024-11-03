<?php
require_once 'functions.php';
session_start();

$communityID = $_GET['community_id']; // Community ID to add users to

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userID = $_POST['user_id'];
    $role = $_POST['role'];

    $query = "INSERT INTO community_members (CommunityID, UserID, Role) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('iis', $communityID, $userID, $role);

    if ($stmt->execute()) {
        echo "User added successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }
}

// Fetch users
$query = "SELECT * FROM users";
$result = $conn->query($query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Users</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Add Users to Community</h1>
    <form action="" method="POST">
        <label for="user_id">Select User:</label>
        <select id="user_id" name="user_id" required>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <option value="<?php echo $row['UserID']; ?>"><?php echo htmlspecialchars($row['Username']); ?></option>
            <?php } ?>
        </select>
        
        <label for="role">Role:</label>
        <select id="role" name="role">
            <option value="member">Member</option>
            <option value="admin">Admin</option>
        </select>

        <input type="hidden" name="community_id" value="<?php echo $communityID; ?>">
        <input type="submit" value="Add User">
    </form>
</body>
</html>
