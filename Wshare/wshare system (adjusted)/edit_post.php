<?php
require_once 'functions.php';
session_start();

// Ensure the user is logged in
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
    <title>Edit Post</title>
    <link rel="stylesheet" href="./css/left-navbar.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="./css/homepage.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="./css/edit_post.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="./css/navbar.css?v=<?php echo time(); ?>">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="container">
        <a class="backButton" id="backButton">
            <div class="back"><p class="back-label">Back</p></div>
        </a>
        <div class="post-form">
            <h1 class="create-label">Edit Your Post</h1>
            <?php
            // Check if the post ID is provided in the URL
            if (isset($_GET['post_id'])) {
                $postID = $_GET['post_id'];
                // Fetch post details from the database
                $post = getPostById($postID);
                if ($post) {
                    // Check if the logged-in user is the author of the post
                    if ($post['Username'] == $_SESSION['username']) {
                        // Fetch current tags for the post
                        $currentTags = getTagsByPostId($postID);
            ?>
            <form action="edit_post_process.php" method="post" enctype="multipart/form-data">
                
                <input type="hidden" name="post_id" value="<?php echo $post['PostID']; ?>">

                <input type="hidden" name="current_photo" value="<?php echo htmlspecialchars($post['PhotoPath'], ENT_QUOTES, 'UTF-8'); ?>">

                <label for="title">Title:</label>
                <input type="hidden" id="title" name="title" value="<?php echo htmlspecialchars($post['Title'], ENT_QUOTES, 'UTF-8'); ?>">
                <input type="text" name="title" class="post-title-in" value="<?php echo htmlspecialchars($post['Title'], ENT_QUOTES, 'UTF-8'); ?>" disabled>

                <label for="title">Content:</label>
                <textarea id="content" name="content" class="post-content-in" rows="4" cols="50" required><?php echo htmlspecialchars($post['Content'], ENT_QUOTES, 'UTF-8'); ?></textarea>
                
                <label for="title">Photo:</label>
                <?php if ($post['PhotoPath']): ?>
                    <div class="current-photo">
                        <img src="<?php echo htmlspecialchars($post['PhotoPath'], ENT_QUOTES, 'UTF-8'); ?>" alt="Current Photo" style="max-width: 100%; height: auto;">
                    </div>
                <?php else:?>
                    <div class="current-photo"><br>
                        <p style="margin-left: 10px; color:#0056b3">No photo uploaded</p><br>
                    </div>
                <?php endif; ?>
                
                <label for="title">Replace Photo:</label>
                <input type="file" id="photo" name="photo" class="post-image-in" accept="image/*">
                
                <!-- Display existing images -->
                <div class="current-images" id="current-images">
                    <?php
                    $imagesQuery = "SELECT * FROM post_images WHERE PostID = ? ORDER BY DisplayOrder";
                    $imagesStmt = $conn->prepare($imagesQuery);
                    $imagesStmt->bind_param('i', $postID);
                    $imagesStmt->execute();
                    $images = $imagesStmt->get_result()->fetch_all(MYSQLI_ASSOC);
                    
                    foreach($images as $image): ?>
                        <div class="image-container" data-image-id="<?php echo $image['ImageID']; ?>">
                            <img src="<?php echo htmlspecialchars($image['ImagePath']); ?>" alt="Post Image">
                            <input type="checkbox" name="remove_images[]" value="<?php echo $image['ImageID']; ?>">
                            <label>Remove</label>
                            <input type="hidden" name="image_order[]" value="<?php echo $image['ImageID']; ?>">
                        </div>
                    <?php endforeach; ?>
                </div>

                <style>
                .current-images {
                    display: grid;
                    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
                    gap: 10px;
                    margin: 10px 0;
                }

                .image-container {
                    position: relative;
                    border: 1px solid #ddd;
                    padding: 5px;
                    cursor: move;
                }

                .image-container img {
                    width: 100%;
                    height: 150px;
                    object-fit: cover;
                }
                </style>

                <script>
                // Initialize Sortable for existing images
                new Sortable(document.getElementById('current-images'), {
                    animation: 150,
                    onEnd: function() {
                        updateImageOrder();
                    }
                });

                function updateImageOrder() {
                    const containers = document.querySelectorAll('.image-container');
                    const orderInput = document.createElement('input');
                    orderInput.type = 'hidden';
                    orderInput.name = 'image_order';
                    orderInput.value = Array.from(containers).map(c => c.getAttribute('data-image-id')).join(',');
                    
                    // Remove existing order input if any
                    const existingOrder = document.querySelector('input[name="image_order"]');
                    if (existingOrder) existingOrder.remove();
                    
                    document.querySelector('form').appendChild(orderInput);
                }
                </script>

                <label for="new_photos">Add More Photos:</label>
                <input type="file" name="new_photos[]" multiple accept="image/*">
                
                <!-- Tag Selection Dropdown -->
                
                <label for="title">Tags:</label>
                <div class="tag-dropdown-container">
                
                    <input type="text" class="tag-dropdown" placeholder="Select Tags..." readonly onclick="toggleDropdown()">
                    
                    <div class="tag-dropdown-menu">
                        <input type="text" class="tag-search" placeholder="Search Tags..." onkeyup="filterTags()">
                        <div id="tag-list">
                            <?php foreach ($tags as $tag): ?>
                                <div class="tag-dropdown-item">
                                    <input type="checkbox" value="<?php echo htmlspecialchars($tag, ENT_QUOTES, 'UTF-8'); ?>" <?php echo in_array($tag, $currentTags) ? 'checked' : ''; ?> onchange="toggleTag(this)">
                                    <?php echo htmlspecialchars($tag, ENT_QUOTES, 'UTF-8'); ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                
                </div>
                
                <!-- Hidden input to store selected tags -->
                <input type="hidden" name="selected_tags" id="selected-tags" value="<?php echo htmlspecialchars(implode(',', $currentTags), ENT_QUOTES, 'UTF-8'); ?>">
                
                <!-- Display selected tags -->
                <div class="selected-tags" id="selected-tags-display">
                    <?php foreach ($currentTags as $tag): ?>
                        <div class="selected-tag">
                            <?php echo htmlspecialchars($tag, ENT_QUOTES, 'UTF-8'); ?> <span class="selected-tag-remove" onclick="removeTag('<?php echo htmlspecialchars($tag, ENT_QUOTES, 'UTF-8'); ?>')">&times;</span>
                        </div>
                    <?php endforeach; ?>
                </div>
                <input type="submit" class="post-postbtn-in" value="Save Changes">
            </form>
            <?php
                    } else {
                        echo "Error: You are not authorized to edit this post.";
                    }
                } else {
                    echo "Error: Post not found.";
                }
            } else {
                echo "Error: Post ID not provided.";
            };
            ?>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        document.getElementById('logo-nav').addEventListener('click', function() {
            var element = document.getElementById('left-navbar');
            element.style.display = (element.style.display === 'none') ? 'block' : 'none';
        });

        document.getElementById('logo-left-nav').addEventListener('click', function() {
            var element = document.getElementById('left-navbar');
            element.style.display = (element.style.display === 'none') ? 'block' : 'none';
        });

        document.getElementById('comm_label').addEventListener('click', function() {
            var element = document.getElementById('comments');
            element.style.display = (element.style.display === 'none') ? 'block' : 'none';
        });

        document.addEventListener('click', function(e) {
            if (e.target && (e.target.matches('.image-container img') || e.target.matches('.current-photo img'))) {
                showImagePreview(e.target);
            }
        });
    </script>
    <script>
        // JavaScript for handling the tag dropdown functionality
        const dropdownMenu = document.querySelector('.tag-dropdown-menu');
        const tagDropdown = document.querySelector('.tag-dropdown');
        const selectedTagsInput = document.getElementById('selected-tags');
        const selectedTagsDisplay = document.getElementById('selected-tags-display');
        let selectedTags = selectedTagsInput.value.split(',').filter(tag => tag);

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


        // Add this JavaScript to update the image order before form submission
        function updateImageOrder() {
            const containers = document.querySelectorAll('.image-container');
            const imageIds = Array.from(containers).map(c => c.dataset.imageId).join(',');
            const input = document.querySelector('input[name="image_order"]');
            if (input) {
                input.value = imageIds;
            } else {
                const newInput = document.createElement('input');
                newInput.type = 'hidden';
                newInput.name = 'image_order';
                newInput.value = imageIds;
                document.querySelector('form').appendChild(newInput);
            }
        }
    </script>
</body>
</html> 
