<?php
    require_once 'db_connection.php';
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Post</title>
    <link rel="stylesheet" href="./css/edit_post.css">
</head>
<body>
    <div class="container">

        <h1>Edit Your Post</h1>
        <?php
            // Check if the post ID is provided in the URL
            if (isset($_GET['post_id'])) {
                $postID = $_GET['post_id'];

                // Fetch post details from the database
                // Include your database connection script
                $query = "SELECT * FROM Posts WHERE PostID = '$postID'";
                $result = mysqli_query($conn, $query);

                if ($result && mysqli_num_rows($result) > 0) {
                    $post = mysqli_fetch_assoc($result);
        ?>

        <form action="edit_post_process.php" method="post">
            <input type="hidden" name="post_id" value="<?php echo $post['PostID']; ?>">
            <label for="title">Title:</label><br>
            <input type="text" id="title" name="title" value="<?php echo $post['Title']; ?>" readonly><br>
            <label for="content">Content:</label><br>
            <textarea id="content" name="content" rows="4" cols="50"><?php echo $post['Content']; ?></textarea><br>
            <input type="submit" value="Save Changes">
        </form>
        
        <?php
            } else {
                echo "Post not found.";
            }

            // Close database connection
            mysqli_close($conn);
            } 
            
            else 
            
            {
                echo "Post ID not provided.";
            }
        ?>

    </div>
</body>
</html>
