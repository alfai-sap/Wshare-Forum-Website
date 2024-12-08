
<?php
include 'dashFunctions.php';

// Handle post deletion
if (isset($_POST['delete_post'])) {
    deletePost($_POST['post_id']);
}

// Handle search
$posts = isset($_GET['search']) ? searchPosts($_GET['search']) : getAllPosts(20);

// Get post statistics
$postStats = getPostStats();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Posts</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
    <?php include 'sidebar.php'; ?>
    
    <div class="container">
        <h1>Manage Posts</h1>

        <!-- Statistics Cards -->
        <div class="dashboard">
            <div class="box">
                <h2>Total Posts</h2>
                <p><?php echo $postStats['total_posts']; ?></p>
            </div>
            <div class="box">
                <h2>Posts (24h)</h2>
                <p><?php echo $postStats['posts_24h']; ?></p>
            </div>
            <div class="box">
                <h2>Posts (7d)</h2>
                <p><?php echo $postStats['posts_7d']; ?></p>
            </div>
        </div>

        <!-- Search Form -->
        <div class="search-box">
            <form method="GET">
                <input type="text" name="search" placeholder="Search posts..." value="<?php echo $_GET['search'] ?? ''; ?>">
                <button type="submit">Search</button>
            </form>
        </div>

        <!-- Posts Table -->
        <div class="table-container">
            <table>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
                <?php foreach ($posts as $post): ?>
                <tr>
                    <td><?php echo $post['PostID']; ?></td>
                    <td><?php echo htmlspecialchars($post['Title']); ?></td>
                    <td><?php echo htmlspecialchars($post['Username']); ?></td>
                    <td><?php echo $post['CreatedAt']; ?></td>
                    <td>
                        <form method="POST" class="inline-form" onsubmit="return confirm('Are you sure you want to delete this post?');">
                            <input type="hidden" name="post_id" value="<?php echo $post['PostID']; ?>">
                            <button type="submit" name="delete_post">Delete</button>
                        </form>
                        <button onclick="viewPost(<?php echo $post['PostID']; ?>)">View</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>

    <script>
    function viewPost(postId) {
        // Add your view post logic here
        alert('View post ' + postId);
    }
    </script>
</body>
</html>