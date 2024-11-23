<?php
require_once 'functions.php'; // This includes your database connection
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Fetch tags from the database
$tags = [];
$sql = "SELECT TagName FROM tags ORDER BY TagName ASC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $tags[] = $row['TagName'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Post - Wshare</title>
    <link rel="stylesheet" href="./css/left-navbar.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="./css/homepage.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="./css/create_post.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="./css/navbar.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="./css/right-sidebar.css?v=<?php echo time(); ?>">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container">
        <a class="backButton" id="backButton">
            <div class="back"><p class="back-label">Back</p></div>
        </a>
        <?php if (isset($_SESSION['username'])): ?>

            <div class="post-form">
                <label class="create-label">Create A Post</label>

                <form id="post-form" action="create_post_process.php" method="POST" enctype="multipart/form-data">
                    <label for="title">Title:</label>
                    <input class="post-title-in" type="text" id="title" name="title" placeholder="Title..." required>

                    <label for="content">Content:</label>
                    <textarea class="post-content-in" id="content" name="content" placeholder="What am I thinking?..." required></textarea>

                    <label for="photo">Photo:</label>
                    <input class="post-image-in" type="file" id="photo" name="photo" accept="image/*">

                    <label for="tag">Tags:</label>
                    <!-- Tag Selection Dropdown -->
                    <div class="tag-dropdown-container">
                        <input type="text" class="tag-dropdown" placeholder="Tags" readonly onclick="toggleDropdown()">
                        <div class="tag-dropdown-menu">
                            <input type="text" class="tag-search" placeholder="Search Tags..." onkeyup="filterTags()">
                            <div id="tag-list">
                                <?php foreach ($tags as $tag): ?>
                                    <div class="tag-dropdown-item">
                                        <input type="checkbox" value="<?php echo $tag; ?>" onchange="toggleTag(this)">
                                        <?php echo htmlspecialchars($tag); ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Hidden input to store selected tags -->
                    <input type="hidden" name="selected_tags" id="selected-tags">

                    <!-- Display selected tags -->
                    <div class="selected-tags" id="selected-tags-display"></div>

                    <input type="submit" class="post-postbtn-in" value="Post">
                </form>
            </div>

        <?php else: ?>
            <p>Please log in to create a post.</p>
        <?php endif; ?>
    </div>

<script>
    // JavaScript for handling the tag dropdown functionality
    const dropdownMenu = document.querySelector('.tag-dropdown-menu');
    const tagDropdown = document.querySelector('.tag-dropdown');
    const selectedTagsInput = document.getElementById('selected-tags');
    const selectedTagsDisplay = document.getElementById('selected-tags-display');
    let selectedTags = [];

    function toggleDropdown() {
        dropdownMenu.style.display = dropdownMenu.style.display === 'block' ? 'none' : 'block';
    }

    function filterTags() {
        const searchValue = document.querySelector('.tag-search').value.toLowerCase();
        const items = document.querySelectorAll('.tag-dropdown-item');
        items.forEach(item => {
            const tagName = item.textContent.toLowerCase();
            item.style.display = tagName.includes(searchValue) ? 'flex' : 'none';
        });
    }

    function toggleTag(checkbox) {
        const tagValue = checkbox.value;
        if (checkbox.checked) {
            if (!selectedTags.includes(tagValue)) {
                selectedTags.push(tagValue);
                displaySelectedTags();
            }
        } else {
            selectedTags = selectedTags.filter(tag => tag !== tagValue);
            displaySelectedTags();
        }
    }

    function displaySelectedTags() {
        selectedTagsDisplay.innerHTML = '';
        selectedTags.forEach(tag => {
            const tagElement = document.createElement('div');
            tagElement.className = 'selected-tag';
            tagElement.innerHTML = `${tag} <span class="selected-tag-remove" onclick="removeTag('${tag}')">&times;</span>`;
            selectedTagsDisplay.appendChild(tagElement);
        });
        selectedTagsInput.value = selectedTags.join(',');
    }

    function removeTag(tag) {
        selectedTags = selectedTags.filter(t => t !== tag);
        document.querySelector(`.tag-dropdown-item input[value="${tag}"]`).checked = false;
        displaySelectedTags();
    }

    // Close dropdown if clicking outside
    document.addEventListener('click', function (event) {
        if (!event.target.closest('.tag-dropdown-container')) {
            dropdownMenu.style.display = 'none';
        }
    });
</script>

</body>
</html>
