<?php
include 'dashFunctions.php'; // Include your database functions

// Handle CRUD operations
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_user'])) {
        addUser($_POST['username'], $_POST['email'], $_POST['password']);
    } elseif (isset($_POST['update_user'])) {
        updateUser($_POST['user_id'], $_POST['username'], $_POST['email']);
    } elseif (isset($_POST['delete_user'])) {
        deleteUser($_POST['user_id']);
    }
}

// Fetch all users for display
$users = getAllUsers();

// Handle search query
$searchTerm = '';
if (isset($_POST['search'])) {
    $searchTerm = $_POST['search'];
}

// Fetch all users for display or filter based on search term
$users = getUsersBySearch($searchTerm);


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link rel="stylesheet" href="dashboard.css?v=<?php echo time(); ?>">
</head>
<body>

<?php include 'sidebar.php'; ?>

<h1>Manage Users</h1>

<div class="container">

    <!-- Add User Form -->
    <div class="form-box">
        <h2>Add New User</h2>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="add_user">Add User</button>
        </form>
    </div>

    <!-- Search Form -->
    <div class="search-box">
        <form method="POST">
            <input type="text" name="search" placeholder="Search users by username or email" value="<?php echo $searchTerm; ?>">
            <button type="submit">Search</button>
        </form>
    </div>


    <!-- Users Table -->
    <div class="table-container">
        <h2>All Users</h2>
        <table>
            <tr>
                <th>UserID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Date Joined</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($users as $user) { ?>
            <tr>
                <td><?php echo $user['UserID']; ?></td>
                <td><?php echo $user['Username']; ?></td>
                <td><?php echo $user['Email']; ?></td>
                <td><?php echo $user['dateJoined']; ?></td>
                <td>
                    <!-- Update Form -->
                    <form method="POST" class="inline-form">
                        <input type="hidden" name="user_id" value="<?php echo $user['UserID']; ?>">
                        <input type="text" name="username" value="<?php echo $user['Username']; ?>" required>
                        <input type="email" name="email" value="<?php echo $user['Email']; ?>" required>
                        <button type="submit" name="update_user">Update</button>
                    </form>

                    <!-- Delete Form -->
                    <form method="POST" class="inline-form">
                        <input type="hidden" name="user_id" value="<?php echo $user['UserID']; ?>">
                        <button type="submit" name="delete_user" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </td>
            </tr>
            <?php } ?>
        </table>
    </div>
</div>

</body>
</html>
