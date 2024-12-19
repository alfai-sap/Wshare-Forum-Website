<?php
require_once 'functions.php';
session_start();

if (isset($_GET['query']) && isset($_SESSION['user_id'])) {
    $search = trim($_GET['query']);
    $sort = isset($_GET['sort']) ? $_GET['sort'] : 'followed';
    $user_id = $_SESSION['user_id'];

    $result = searchNetworkPosts($search, $sort, $user_id);
    
    if ($result->num_rows > 0) {
        echo '<div class="search_results">
                Results for "<span class="query">' . htmlspecialchars($search) . '</span>"
                <span class="count">' . $result->num_rows . ' ' . ($result->num_rows === 1 ? 'result' : 'results') . ' found</span>
              </div>';
        
        while ($post = $result->fetch_assoc()) {
            include 'network_post_template.php';
        }
    } else {
        echo '<h4 style="color: #007bff; text-align:center; padding:20px;">No results found</h4>';
    }
}
?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Reinitialize any necessary post features
    initializePostFeatures();
});
</script>
