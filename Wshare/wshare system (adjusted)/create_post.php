<?php
require_once 'functions.php';
require_once 'changes.php';
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

if (checkUserBan()) {
    echo "<div style='text-align: center; margin-top: 50px; color: red;'>";
    echo checkUserBan(true);
    echo "<br><a href='homepage.php'>Return to Homepage</a>";
    echo "</div>";
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>
    <link rel="stylesheet" href="./css/modal.css?v=<?php echo time(); ?>">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container">
        
        <?php if (isset($_SESSION['username'])): ?>

            <div class="post-form">
                <label class="create-label">Create A Post</label>

                <form id="post-form" action="create_post_process.php" method="POST" enctype="multipart/form-data">
                    <label for="title">Title:</label>
                    <input class="post-title-in" type="text" id="title" name="title" placeholder="Title..." required>

                    <!-- Move Tags section here -->
                    <label for="tag">Tags:</label>
                    <div class="tag-dropdown-container">
                        <input type="text" class="tag-dropdown" placeholder="Select Tags..." readonly onclick="toggleDropdown()">
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
                    <input type="hidden" name="selected_tags" id="selected-tags">
                    <div class="selected-tags" id="selected-tags-display"></div>

                    <label for="content">Content:</label>
                    <textarea class="post-content-in" id="content" name="content" placeholder="What am I thinking?..." required></textarea>

                    <label for="photo">Main Photo:</label>
                    <input class="post-image-in" type="file" id="photo" name="photo" accept="image/*">
                    <div id="image-preview" class="image-preview-container"></div>

                    <!-- Attachment Toggle Buttons -->
                    <div class="attachment-toggles">
                        <button type="button" class="toggle-btn" onclick="toggleAttachment('videos')">
                            <i class="fas fa-video"></i> Add Videos
                        </button>
                        <button type="button" class="toggle-btn" onclick="toggleAttachment('images')">
                            <i class="fas fa-images"></i> Add More Images
                        </button>
                        <button type="button" class="toggle-btn" onclick="toggleAttachment('documents')">
                            <i class="fas fa-file"></i> Add Documents
                        </button>
                    </div>

                    <!-- Hidden attachment sections -->
                    <div id="videos-section" class="attachment-section" style="display: none;">
                        <label for="videos">Videos (Max 100MB each):</label>
                        <input class="post-video-in" type="file" id="videos" name="videos[]" accept="video/*" multiple>
                        <div id="video-preview" class="video-preview-container"></div>
                    </div>

                    <div id="images-section" class="attachment-section" style="display: none;">
                        <label for="additional_photos">Additional Photos:</label>
                        <input class="post-image-in" type="file" id="additional_photos" name="additional_photos[]" accept="image/*" multiple>
                        <div id="additional-image-preview" class="image-preview-container"></div>
                    </div>

                    <div id="documents-section" class="attachment-section" style="display: none;">
                        <label for="documents">Documents:</label>
                        <input class="post-document-in" type="file" id="documents" name="documents[]" accept=".pdf,.doc,.docx,.txt" multiple>
                        <div id="document-preview" class="document-preview-container"></div>
                    </div>

                    <input type="submit" class="post-postbtn-in" value="Post">

                    <a class="backButton" id="backButton">
                        <div class="back"><p class="back-label">Back</p></div>
                    </a>
                </form>

                
            </div>

        <?php else: ?>
            <p>Please log in to create a post.</p>
        <?php endif; ?>
    </div>

<style>
.image-preview-container {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 10px;
    margin: 10px 0;
}

.image-preview {
    position: relative;
    border: 1px solid #ddd;
    padding: 5px;
    cursor: move;
}

.image-preview img {
    width: 100%;
    height: 150px;
    object-fit: cover;
}

.remove-image {
    position: absolute;
    top: 5px;
    right: 5px;
    background: rgba(0,0,0,0.5);
    color: white;
    border: none;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    cursor: pointer;
}

.image-modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0,0,0,0.9);
}

.image-modal .modal-content {
    position: relative;
    margin: auto;
    padding: 0;
    width: 80%;
    max-width: 700px;
    text-align: center;
}

.image-modal img {
    width: 100%;
    max-width: 700px;
    transition: transform 0.25s ease;
    cursor: grab;
}

.image-modal .close-modal {
    position: absolute;
    top: 15px;
    right: 35px;
    color: #fff;
    font-size: 40px;
    font-weight: bold;
    transition: 0.3s;
    cursor: pointer;
}

.image-modal .close-modal:hover,
.image-modal .close-modal:focus {
    color: #bbb;
    text-decoration: none;
    cursor: pointer;
}

.image-modal .nav-btn {
    position: absolute;
    top: 50%;
    color: white;
    font-size: 30px;
    font-weight: bold;
    transition: 0.3s;
    cursor: pointer;
    user-select: none;
}

.image-modal .prev-btn {
    left: 0;
}

.image-modal .next-btn {
    right: 0;
}

.image-modal .nav-btn:hover {
    color: #bbb;
}

.image-modal .zoom-controls {
    position: absolute;
    bottom: 15px;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    gap: 10px;
}

.image-modal .zoom-controls button {
    background: rgba(0,0,0,0.5);
    color: white;
    border: none;
    padding: 10px;
    cursor: pointer;
}

.image-modal .zoom-controls button:hover {
    background: rgba(0,0,0,0.7);
}

.image-counter {
    position: absolute;
    bottom: 15px;
    right: 15px;
    color: white;
    font-size: 16px;
}

.video-preview-container {
    display: flex;
    flex-direction: column;
    gap: 15px;
    margin: 15px 0;
}

.video-preview {
    position: relative;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 8px;
    background: #f8f9fa;
}

.video-preview video {
    width: 100%;
    max-width: 500px;
    border-radius: 4px;
}

.remove-video {
    position: absolute;
    top: 10px;
    right: 10px;
    background: rgba(0,0,0,0.7);
    color: white;
    border: none;
    border-radius: 50%;
    width: 24px;
    height: 24px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
}

.video-size-error {
    color: red;
    font-size: 12px;
    margin-top: 5px;
}
</style>

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

    // JavaScript to handle the back button functionality
    document.getElementById('backButton').addEventListener('click', function() {
        const previousPage = localStorage.getItem('previousPage');

        if (previousPage) {
            // Redirect to the manually stored previous page
            window.location.href = previousPage;

            // Optional: Clear the stored previous page after redirecting
            localStorage.removeItem('previousPage');
        } else {
            // Fallback if no previous page is found
            window.location.href = 'http://localhost/php-parctice/wshare%20admin%20latest/Wshare/wshare%20system%20(adjusted)/homepage.php'; // Replace with your fallback URL
        }
    });

    document.getElementById('photo').addEventListener('change', function(e) {
        const previewDiv = document.getElementById('image-preview');
        previewDiv.innerHTML = ''; // Clear previous previews
        const file = e.target.files[0];
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.createElement('div');
                preview.className = 'image-preview';
                preview.innerHTML = `
                    <img src="${e.target.result}" alt="Preview">
                    <button type="button" class="remove-image" onclick="removeImage(this)">×</button>
                `;
                previewDiv.appendChild(preview);
                document.getElementById('add-more-photos-btn').style.display = 'block';
            }
            reader.readAsDataURL(file);
        }
    });

    function showAdditionalPhotosSection() {
        document.getElementById('additional-photos-section').style.display = 'block';
        document.getElementById('add-more-photos-btn').style.display = 'none';
    }

    document.getElementById('additional_photos').addEventListener('change', function(e) {
        const previewDiv = document.getElementById('additional-image-preview');
        previewDiv.innerHTML = ''; // Clear previous previews
        const files = Array.from(e.target.files);
        
        files.forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.createElement('div');
                preview.className = 'image-preview';
                preview.setAttribute('data-file-name', file.name);
                preview.innerHTML = `
                    <img src="${e.target.result}" alt="Preview">
                    <button type="button" class="remove-image" onclick="removeImage(this)">×</button>
                    <input type="hidden" name="image_order[]" value="${file.name}">
                `;
                previewDiv.appendChild(preview);
            }
            reader.readAsDataURL(file);
        });
    });

    function removeImage(button) {
        const preview = button.closest('.image-preview');
        preview.remove();
    }

    // Initialize Sortable
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('additional-image-preview');
        new Sortable(container, {
            animation: 150,
            onEnd: function() {
                updateImageOrder();
            }
        });
    });

    function updateImageOrder() {
        const previews = document.querySelectorAll('.image-preview');
        const orderInput = document.createElement('input');
        orderInput.type = 'hidden';
        orderInput.name = 'image_order';
        orderInput.value = Array.from(previews).map(p => p.getAttribute('data-file-name')).join(',');
        
        // Remove existing order input if any
        const existingOrder = document.querySelector('input[name="image_order"]');
        if (existingOrder) existingOrder.remove();
        
        document.getElementById('post-form').appendChild(orderInput);
    }

    // JavaScript for handling the image modal functionality
    let currentZoom = 1;
    let isDragging = false;
    let startX, startY, translateX = 0, translateY = 0;
    let currentImageIndex = 0;
    let previewImages = [];

    function showImagePreview(img) {
        const modal = document.getElementById('imageModal');
        const modalImg = document.getElementById('modalImage');
        const previewContainer = document.getElementById('image-preview');
        
        previewImages = Array.from(previewContainer.getElementsByTagName('img'));
        currentImageIndex = previewImages.indexOf(img);
        
        modal.style.display = "block";
        modalImg.src = img.src;
        updateImageCounter();
        resetZoom();
    }

    function closeModal() {
        document.getElementById('imageModal').style.display = "none";
        resetZoom();
    }

    function zoom(factor) {
        currentZoom *= factor;
        currentZoom = Math.min(Math.max(0.5, currentZoom), 3);
        updateTransform();
    }

    function resetZoom() {
        currentZoom = 1;
        translateX = 0;
        translateY = 0;
        updateTransform();
    }

    function updateTransform() {
        const img = document.getElementById('modalImage');
        img.style.transform = `scale(${currentZoom}) translate(${translateX}px, ${translateY}px)`;
    }

    function navigateImage(direction) {
        currentImageIndex = (currentImageIndex + direction + previewImages.length) % previewImages.length;
        const modalImg = document.getElementById('modalImage');
        modalImg.src = previewImages[currentImageIndex].src;
        updateImageCounter();
        resetZoom();
    }

    function updateImageCounter() {
        const counter = document.querySelector('.image-counter');
        counter.textContent = `${currentImageIndex + 1} / ${previewImages.length}`;
    }

    // Add click event to preview images
    document.addEventListener('click', function(e) {
        if (e.target && e.target.matches('.image-preview img')) {
            showImagePreview(e.target);
        }
    });

    // Add drag functionality
    const modalImage = document.getElementById('modalImage');

    modalImage.addEventListener('mousedown', startDrag);
    modalImage.addEventListener('touchstart', startDrag);

    document.addEventListener('mousemove', drag);
    document.addEventListener('touchmove', drag);

    document.addEventListener('mouseup', stopDrag);
    document.addEventListener('touchend', stopDrag);

    function startDrag(e) {
        if (currentZoom <= 1) return;
        isDragging = true;
        if (e.type === "mousedown") {
            startX = e.clientX - translateX;
            startY = e.clientY - translateY;
        } else {
            startX = e.touches[0].clientX - translateX;
            startY = e.touches[0].clientY - translateY;
        }
        modalImage.style.cursor = 'grabbing';
    }

    function drag(e) {
        if (!isDragging || currentZoom <= 1) return;
        e.preventDefault();
        
        let clientX, clientY;
        if (e.type === "mousemove") {
            clientX = e.clientX;
            clientY = e.clientY;
        } else {
            clientX = e.touches[0].clientX;
            clientY = e.touches[0].clientY;
        }
        
        translateX = clientX - startX;
        translateY = clientY - startY;
        updateTransform();
    }

    function stopDrag() {
        isDragging = false;
        modalImage.style.cursor = 'grab';
    }

    // Add keyboard controls
    document.addEventListener('keydown', function(e) {
        if (document.getElementById('imageModal').style.display === "block") {
            switch(e.key) {
                case "Escape": closeModal(); break;
                case "ArrowLeft": navigateImage(-1); break;
                case "ArrowRight": navigateImage(1); break;
                case "+": zoom(1.2); break;
                case "-": zoom(0.8); break;
                case "0": resetZoom(); break;
            }
        }
    });

    document.getElementById('videos').addEventListener('change', function(e) {
        const previewDiv = document.getElementById('video-preview');
        const maxSize = 100 * 1024 * 1024; // 100MB in bytes
        const files = Array.from(e.target.files);
        
        files.forEach((file, index) => {
            if (file.size > maxSize) {
                const errorDiv = document.createElement('div');
                errorDiv.className = 'video-size-error';
                errorDiv.textContent = `${file.name} exceeds 100MB limit and will not be uploaded`;
                previewDiv.appendChild(errorDiv);
                return;
            }

            const preview = document.createElement('div');
            preview.className = 'video-preview';
            
            // Create video URL
            const videoURL = URL.createObjectURL(file);
            
            // Format file size
            const fileSize = file.size < 1024 * 1024 
                ? `${(file.size / 1024).toFixed(2)} KB`
                : `${(file.size / (1024 * 1024)).toFixed(2)} MB`;

            preview.innerHTML = `
                <div class="video-wrapper">
                    <video controls preload="metadata">
                        <source src="${videoURL}" type="${file.type}">
                        Your browser does not support the video tag.
                    </video>
                    <div class="video-overlay">
                        <div class="video-duration"></div>
                    </div>
                </div>
                <div class="video-info">
                    <div class="video-name">${file.name}</div>
                    <div class="video-size">${fileSize}</div>
                </div>
                <button type="button" class="remove-video" onclick="removeVideo(this, '${videoURL}')">×</button>
                <input type="hidden" name="video_order[]" value="${file.name}">
            `;
            previewDiv.appendChild(preview);

            // Get video duration
            const video = preview.querySelector('video');
            video.addEventListener('loadedmetadata', function() {
                const duration = Math.round(video.duration);
                const minutes = Math.floor(duration / 60);
                const seconds = duration % 60;
                const durationText = `${minutes}:${seconds.toString().padStart(2, '0')}`;
                preview.querySelector('.video-duration').textContent = durationText;
            });
        });
    });

    function removeVideo(button, videoURL) {
        const preview = button.closest('.video-preview');
        // Revoke the video URL to free up memory
        URL.revokeObjectURL(videoURL);
        preview.remove();
    }

    document.getElementById('post-form').addEventListener('submit', function(e) {
        // Prevent form from submitting
        e.preventDefault();

        // Check required fields
        const title = document.getElementById('title').value.trim();
        const content = document.getElementById('content').value.trim();
        
        if (!title || !content) {
            alert('Please fill in both title and content fields');
            return false;
        }

        // Calculate total file size
        let totalSize = 0;
        const fileInputs = ['photo', 'additional_photos', 'documents', 'videos'];
        
        fileInputs.forEach(inputId => {
            const input = document.getElementById(inputId);
            if (input && input.files) {
                Array.from(input.files).forEach(file => {
                    totalSize += file.size;
                });
            }
        });

        const maxSize = 150 * 1024 * 1024; // 150MB
        if (totalSize > maxSize) {
            alert('Total file size cannot exceed 150MB');
            return false;
        }

        // Submit the form if all validations pass
        this.submit();
    });

    function toggleAttachment(type) {
        const section = document.getElementById(`${type}-section`);
        const button = document.querySelector(`[onclick="toggleAttachment('${type}')"]`);
        
        if (section.style.display === 'none') {
            // Hide all sections first
            document.querySelectorAll('.attachment-section').forEach(s => s.style.display = 'none');
            document.querySelectorAll('.toggle-btn').forEach(b => b.classList.remove('active'));
            
            // Show selected section
            section.style.display = 'block';
            button.classList.add('active');
            
            // Smooth scroll to section
            section.scrollIntoView({ behavior: 'smooth', block: 'start' });
        } else {
            section.style.display = 'none';
            button.classList.remove('active');
        }
    }
</script>

<!-- Image Modal -->
<div id="imageModal" class="image-modal">
    <span class="close-modal" onclick="closeModal()">&times;</span>
    <button class="nav-btn prev-btn" onclick="navigateImage(-1)">&lt;</button>
    <button class="nav-btn next-btn" onclick="navigateImage(1)">&gt;</button>
    <div class="modal-content">
        <img id="modalImage" src="" draggable="false">
        <div class="image-counter"></div>
    </div>
    <div class="zoom-controls">
        <button onclick="zoom(1.2)">+</button>
        <button onclick="zoom(0.8)">-</button>
        <button onclick="resetZoom()">Reset</button>
    </div>
</div>


</body>
</html>
