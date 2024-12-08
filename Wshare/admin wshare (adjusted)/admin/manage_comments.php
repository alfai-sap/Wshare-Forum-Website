<?php
include 'dashFunctions.php';

// Handle comment deletion
if (isset($_POST['delete_comment'])) {
    deleteComment($_POST['comment_id']);
}

// Handle search
$comments = isset($_GET['search']) ? searchComments($_GET['search']) : getAllComments(20);

// Get comment statistics
$commentStats = getCommentStats();

// Handle keyword filtering
if (isset($_POST['filter_keyword'])) {
    $comments = filterCommentsByKeyword($_POST['keyword']);
}

// Handle AI-based content analysis
if (isset($_POST['analyze_content'])) {
    $analysisResults = analyzeCommentContent($_POST['comment_id']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Comments</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
    <?php include 'sidebar.php'; ?>
    
    <div class="container">
        <h1>Manage Comments</h1>

        <!-- Statistics Cards -->
        <div class="dashboard">
            <div class="box">
                <h2>Total Comments</h2>
                <p><?php echo $commentStats['total_comments']; ?></p>
            </div>
            <div class="box">
                <h2>Comments (24h)</h2>
                <p><?php echo $commentStats['comments_24h']; ?></p>
            </div>
            <div class="box">
                <h2>Comments (7d)</h2>
                <p><?php echo $commentStats['comments_7d']; ?></p>
            </div>
        </div>

        <!-- Search Form -->
        <div class="search-box">
            <form method="GET">
                <input type="text" name="search" placeholder="Search comments..." value="<?php echo $_GET['search'] ?? ''; ?>">
                <button type="submit">Search</button>
            </form>
        </div>

        <!-- Keyword Filtering Form -->
        <div class="filter-box">
            <form method="POST">
                <input type="text" name="keyword" placeholder="Enter keyword to filter comments">
                <button type="submit" name="filter_keyword">Filter</button>
            </form>
        </div>

        <!-- Comments Table -->
        <div class="table-container">
            <table>
                <tr>
                    <th>ID</th>
                    <th>Post</th>
                    <th>Comment</th>
                    <th>Author</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
                <?php foreach ($comments as $comment): ?>
                <tr>
                    <td><?php echo htmlspecialchars($comment['CommentID']); ?></td>
                    <td><?php echo htmlspecialchars($comment['PostTitle']); ?></td>
                    <td><?php echo htmlspecialchars(substr($comment['Content'], 0, 100)) . '...'; ?></td>
                    <td><?php echo htmlspecialchars($comment['Username']); ?></td>
                    <td><?php echo $comment['CreatedAt']; ?></td>
                    <td>
                        <form method="POST" class="inline-form" onsubmit="return confirm('Are you sure you want to delete this comment?');">
                            <input type="hidden" name="comment_id" value="<?php echo $comment['CommentID']; ?>">
                            <button type="submit" name="delete_comment">Delete</button>
                        </form>
                        <form method="POST" class="inline-form">
                            <input type="hidden" name="comment_id" value="<?php echo $comment['CommentID']; ?>">
                            <button type="submit" name="analyze_content">Analyze</button>
                        </form>
                        <button onclick="viewComment(<?php echo $comment['CommentID']; ?>)">View</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>

    <script>
    function viewComment(commentId) {
        // Add your view comment logic here
        window.location.href = `view_comment.php?id=${commentId}`;
    }
    </script>
</body>
</html>