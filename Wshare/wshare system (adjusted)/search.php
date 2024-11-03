<?php
// Include your database connection and any necessary functions
require_once 'functions.php';

// Check if the search query is set in the GET parameters
if(isset($_GET['search']) && !empty($_GET['search'])) {
    // Sanitize the search query to prevent SQL injection
    $search = $_GET['search'];
    $search = mysqli_real_escape_string($conn, $search);

    // Perform the database query to search for posts
    $posts = searchPosts($search);

    // Display search results
    if($posts) {
        // Loop through the search results and display them
        foreach($posts as $post) {
            echo '<div>';
            echo '<h2>'.$post['Title'].'</h2>';
            echo '<p>'.$post['Content'].'</p>';
            // Display other post details as needed
            echo '</div>';
        }
    } else {
        echo '<p>No results found for "'.$search.'"</p>';
    }
} else {
    // Handle case where no search query is provided
    echo '<p>Please enter a search query.</p>';
}
?>
