
<!-- Image Modal -->
<div id="imageModal" class="image-modal">
    <span class="close-modal">&times;</span>
    <div class="modal-content">
        <button class="nav-btn prev-btn">&lt;</button>
        <img id="modalImage" src="" draggable="false">
        <button class="nav-btn next-btn">&gt;</button>
        <div class="image-counter"></div>
    </div>
    <div class="zoom-controls">
        <button class="zoom-in">+</button>
        <button class="zoom-out">-</button>
        <button class="zoom-reset">Reset</button>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    initializeImageSlideshow();
});
</script>