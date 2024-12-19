<?php
require_once "functions.php";
require_once "bookmark_functions.php";
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$userID = $_SESSION['user_id'];
$selectedLabel = $_GET['label'] ?? null;
$filterType = $_GET['type'] ?? 'all'; // Add filter type
$searchQuery = $_GET['search'] ?? '';
$searchFilter = $_GET['filter'] ?? 'all'; // new filter parameter
$bookmarks = [];
$labels = [];

if ($filterType === 'general' || $filterType === 'all') {
    $bookmarks = array_merge($bookmarks, getUserBookmarks($userID, $selectedLabel, $searchQuery, $searchFilter));
    $labels = array_merge($labels, getBookmarkLabels($userID));
}

if ($filterType === 'community' || $filterType === 'all') {
    $bookmarks = array_merge($bookmarks, getUserCommunityBookmarks($userID, $selectedLabel, $searchQuery, $searchFilter));
    $labels = array_merge($labels, getCommunityBookmarkLabels($userID));
}

$successMessage = $_SESSION['success_message'] ?? null;
$errorMessage = $_SESSION['error_message'] ?? null;
unset($_SESSION['success_message'], $_SESSION['error_message']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookmarks</title>
    <link rel="stylesheet" href="./css/navbar.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="./css/left-navbar.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="./css/bookmarks.css?v=<?php echo time(); ?>">
    <!--<link rel="stylesheet" href="./css/notifications.css?v=<?php echo time(); ?>">-->
</head>
<body>
    <?php include 'navbar.php'; ?>
    
    <div class="container">
        <?php if ($successMessage): ?>
            <div class="alert success"><?php echo htmlspecialchars($successMessage); ?></div>
        <?php endif; ?>
        <?php if ($errorMessage): ?>
            <div class="alert error"><?php echo htmlspecialchars($errorMessage); ?></div>
        <?php endif; ?>

        <div class="bookmarks-header">
            <h1>My Bookmarks</h1>
            <div class="search-container">
                <form action="" method="GET" class="search-form">
                    <?php if ($selectedLabel): ?>
                        <input type="hidden" name="label" value="<?php echo htmlspecialchars($selectedLabel); ?>">
                    <?php endif; ?>
                    <input type="text" name="search" placeholder="Search bookmarks..." 
                           value="<?php echo htmlspecialchars($searchQuery); ?>">
                    <select name="filter">
                        <option value="all" <?php echo $searchFilter === 'all' ? 'selected' : ''; ?>>All Fields</option>
                        <option value="title" <?php echo $searchFilter === 'title' ? 'selected' : ''; ?>>Title</option>
                        <option value="label" <?php echo $searchFilter === 'label' ? 'selected' : ''; ?>>Label</option>
                        <option value="notes" <?php echo $searchFilter === 'notes' ? 'selected' : ''; ?>>Notes</option>
                    </select>
                    <button type="submit">Search</button>
                </form>
            </div>
        </div>
        
        <div class="labels-container">
            <a href="bookmarks.php" class="label <?php echo !$selectedLabel ? 'active' : ''; ?>">All</a>
            <?php foreach ($labels as $label): ?>
                <a href="?label=<?php echo urlencode($label['Label']); ?>" 
                   class="label <?php echo $selectedLabel === $label['Label'] ? 'active' : ''; ?>">
                    <?php echo htmlspecialchars($label['Label']); ?>
                </a>
            <?php endforeach; ?>
        </div>
        
        <div class="bookmarks-grid">
            <?php foreach ($bookmarks as $bookmark): ?>
                <?php $postType = isset($bookmark['PostType']) ? $bookmark['PostType'] : (isset($bookmark['CommunityID']) ? 'community' : 'general'); ?>
                <div class="bookmark-card">
                    <div class="bookmark-header">
                        <h3><a href="<?php echo $postType === 'community' ? 'view_community_post.php' : 'view_post.php'; ?>?id=<?php echo $bookmark['PostID']; ?>"><?php echo htmlspecialchars($bookmark['Title']); ?></a></h3>
                        <div class="labels">
                            <span class="bookmark-label"><?php echo ucfirst($postType); ?></span>
                            <?php if (!empty($bookmark['Label'])): ?>
                                <span class="bookmark-label"><?php echo htmlspecialchars($bookmark['Label']); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <p class="bookmark-content"><?php echo substr(htmlspecialchars($bookmark['Content']), 0, 150) . '...'; ?></p>
                    <div class="bookmark-meta">
                        <span>By <?php echo htmlspecialchars($bookmark['Username']); ?></span>
                        <span><?php echo timeAgo($bookmark['CreatedAt']); ?></span>
                    </div>
                    <div class="bookmark-actions">
                        <button onclick="openRemoveBookmarkModal(<?php echo $bookmark['PostID']; ?>, '<?php echo $postType; ?>')" class="remove-bookmark">
                            Remove
                        </button>
                        <button class="edit-label" onclick="openEditLabelModal(<?php echo $bookmark['BookmarkID']; ?>, '<?php echo htmlspecialchars($bookmark['Label'] ?? ''); ?>', '<?php echo $postType; ?>')">
                            Edit Label
                        </button>
                        <button class="edit-notes" onclick="openEditNotesModal(<?php echo $bookmark['BookmarkID']; ?>, '<?php echo htmlspecialchars($bookmark['Notes'] ?? ''); ?>', '<?php echo $postType; ?>')">
                            Add Notes
                        </button>
                        <button class="view-notes" onclick="openViewNotesModal('<?php echo htmlspecialchars($bookmark['Notes'] ?? ''); ?>')">
                            View Notes
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Create Label Modal -->
    <div id="createLabelModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="document.getElementById('createLabelModal').style.display='none'">&times;</span>
            <form action="bookmark_handler.php" method="POST">
                <input type="hidden" name="action" value="create_label">
                <input type="hidden" name="post_type" value="general"> <!-- Default to general -->
                <label for="newLabel">New Label:</label>
                <input type="text" id="newLabel" name="label" required>
                <button type="submit">Create</button>
            </form>
        </div>
    </div>

    <!-- Edit Label Modal -->
    <div id="editLabelModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="document.getElementById('editLabelModal').style.display='none'">&times;</span>
            <form action="bookmark_handler.php" method="POST">
                <input type="hidden" name="action" value="update_label">
                <input type="hidden" id="editLabelBookmarkID" name="bookmark_id">
                <input type="hidden" id="editLabelPostType" name="post_type">
                <label for="editLabel">Edit Label:</label>
                <input type="text" id="editLabel" name="label" required>
                <button type="submit">Update</button>
            </form>
        </div>
    </div>

    <!-- Edit Notes Modal -->
    <div id="editNotesModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="document.getElementById('editNotesModal').style.display='none'">&times;</span>
            <form action="bookmark_handler.php" method="POST">
                <input type="hidden" name="action" value="update_notes">
                <input type="hidden" id="editNotesBookmarkID" name="bookmark_id">
                <input type="hidden" id="editNotesPostType" name="post_type">
                <label for="editNotes">Edit Notes:</label>
                <textarea id="editNotes" name="notes" required></textarea>
                <button type="submit">Update</button>
            </form>
        </div>
    </div>

    <!-- View Notes Modal -->
    <div id="viewNotesModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="document.getElementById('viewNotesModal').style.display='none'">&times;</span>
            <h2>Notes</h2>
            <p id="viewNotesContent"></p>
        </div>
    </div>

    <!-- Remove Bookmark Modal -->
    <div id="removeBookmarkModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeRemoveModal()">&times;</span>
            <h2>Confirm Removal</h2>
            <p>Are you sure you want to remove this bookmark?</p>
            <form id="removeBookmarkForm" action="bookmark_handler.php" method="POST">
                <input type="hidden" name="action" value="remove">
                <input type="hidden" id="removeBookmarkPostID" name="post_id">
                <input type="hidden" id="removeBookmarkPostType" name="post_type">
                <div class="modal-buttons">
                    <button type="submit">Yes, Remove</button>
                    <button type="button" onclick="closeRemoveModal()">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    function openEditLabelModal(bookmarkID, currentLabel, postType) {
        document.getElementById('editLabelBookmarkID').value = bookmarkID;
        document.getElementById('editLabel').value = currentLabel;
        document.getElementById('editLabelPostType').value = postType;
        document.getElementById('editLabelModal').style.display = 'block';
    }

    function openEditNotesModal(bookmarkID, currentNotes, postType) {
        document.getElementById('editNotesBookmarkID').value = bookmarkID;
        document.getElementById('editNotes').value = currentNotes;
        document.getElementById('editNotesPostType').value = postType;
        document.getElementById('editNotesModal').style.display = 'block';
    }

    function openViewNotesModal(notes) {
        document.getElementById('viewNotesContent').innerText = notes;
        document.getElementById('viewNotesModal').style.display = 'block';
    }

    function openRemoveBookmarkModal(postId, postType) {
        document.getElementById('removeBookmarkPostID').value = postId;
        document.getElementById('removeBookmarkPostType').value = postType;
        document.getElementById('removeBookmarkModal').style.display = 'block';
    }

    function closeRemoveModal() {
        document.getElementById('removeBookmarkModal').style.display = 'none';
    }

    // Close modals when clicking outside
    window.onclick = function(event) {
        if (event.target.classList.contains('modal')) {
            event.target.style.display = 'none';
        }
    }
    </script>

</body>
</html>