<?php
include 'dashFunctions.php';

// Handle comment deletion
if (isset($_POST['delete_comment'])) {
    deleteComment($_POST['comment_id']);
}

// Handle search
$comments = isset($_GET['search']) ? searchCommentsDash($_GET['search']) : getAllComments(20);

// Get comment statistics
$commentStats = getCommentStats();

// Handle keyword filtering
if (isset($_POST['filter_keyword'])) {
    $comments = filterCommentsByKeyword($_POST['keyword']);
}

$settings = getAllAdminSettings();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Comments</title>
    <link rel="stylesheet" href="dashboard.css?v=<?php echo time(); ?>">
    <style>
        .btn-delete, .btn-view {
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            margin: 0 4px;
            transition: all 0.3s ease;
            display: inline-block;
            min-width: 80px;
        }

        .btn-delete {
            background-color: #dc3545;
            color: white;
        }

        .btn-delete:hover {
            background-color: #c82333;
        }

        .btn-view {
            background-color: #33b5e5;
            color: white;
        }

        .btn-view:hover {
            background-color: #0099cc;
        }

        .inline-form {
            display: inline-block;
            margin-right: 5px;
        }

        td {
            vertical-align: middle;
        }

        td:last-child {
            white-space: nowrap;
            text-align: center;
            min-width: 180px;
        }
    </style>
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

        <!-- Comments Table -->
        <div class="table-container">
            <table>
                <tr>
                    <th>ID</th>
                    <th>Post</th>
                    <th>Comment</th>
                    <th>Author</th>
                    <th>Created At</th>
                    <th>Post Comments</th>
                    <th>Actions</th>
                </tr>
                <?php foreach ($comments as $comment): ?>
                <tr>
                    <td><?php echo htmlspecialchars($comment['CommentID']); ?></td>
                    <td><?php echo htmlspecialchars($comment['PostTitle']); ?></td>
                    <td><?php echo htmlspecialchars(substr($comment['Content'], 0, 100)) . '...'; ?></td>
                    <td><?php echo htmlspecialchars($comment['Username']); ?></td>
                    <td><?php echo calculateTimeAgo($comment['CreatedAt']); ?></td>
                    <td><?php echo countPostComments($comment['PostID']); ?></td>
                    <td>
                        <form method="POST" class="inline-form" onsubmit="return confirm('Are you sure you want to delete this comment?');">
                            <input type="hidden" name="comment_id" value="<?php echo $comment['CommentID']; ?>">
                            <button type="submit" name="delete_comment" class="btn-delete">Delete</button>
                        </form>
                        <button onclick="viewComment(<?php echo $comment['CommentID']; ?>)" class="btn-view">View</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>

    <script>
    function viewComment(commentId) {
        // Get the post ID for this comment
        fetch(`get_post_id.php?comment_id=${commentId}`)
            .then(response => response.json())
            .then(data => {
                if (data.postId) {
                    window.location.href = `admin_view_post.php?id=${data.postId}&highlight=${commentId}`;
                }
            });
    }
    </script>
</body>
</html>