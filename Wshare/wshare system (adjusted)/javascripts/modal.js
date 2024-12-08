let currentImageIndex = 0;
let currentImages = [];
let currentZoom = 1;
let isDragging = false;
let startPos = { x: 0, y: 0 };
let currentPos = { x: 0, y: 0 };

function initializeImageSlideshow() {
    document.querySelectorAll('.post-images-container').forEach(container => {
        const slides = container.querySelectorAll('.post-image-slide');
        const prevBtn = container.querySelector('.prev');
        const nextBtn = container.querySelector('.next');
        const counter = container.querySelector('.image-counter');

        if (slides.length > 1) {
            let currentSlide = 0;

            function updateSlides() {
                slides.forEach(slide => slide.classList.remove('active'));
                slides[currentSlide].classList.add('active');
                if (counter) {
                    counter.textContent = `${currentSlide + 1}/${slides.length}`;
                }
            }

            if (prevBtn) {
                prevBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    currentSlide = (currentSlide - 1 + slides.length) % slides.length;
                    updateSlides();
                });
            }

            if (nextBtn) {
                nextBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    currentSlide = (currentSlide + 1) % slides.length;
                    updateSlides();
                });
            }
        }
    });
}

function openModal(images, startIndex = 0) {
    currentImages = Array.isArray(images) ? images : [images];
    currentImageIndex = startIndex;
    
    const modal = document.getElementById('imageModal');
    updateModalImage();
    modal.style.display = 'block';
    
    // Reset zoom and position
    resetZoom();
}

// ... rest of your modal.js code for zoom, drag, navigation, etc ...

function updateModalImage() {
    const modalImg = document.getElementById('modalImage');
    modalImg.src = currentImages[currentImageIndex];
    
    const counter = document.querySelector('#imageModal .image-counter');
    counter.textContent = `${currentImageIndex + 1}/${currentImages.length}`;
}

function nextImage() {
    if (currentImages.length <= 1) return;
    currentImageIndex = (currentImageIndex + 1) % currentImages.length;
    updateModalImage();
    resetZoom();
}

function prevImage() {
    if (currentImages.length <= 1) return;
    currentImageIndex = (currentImageIndex - 1 + currentImages.length) % currentImages.length;
    updateModalImage();
    resetZoom();
}

function closeModal() {
    const modal = document.getElementById('imageModal');
    modal.classList.remove('active');
    // Restore body scrolling when modal is closed
    document.body.style.overflow = 'auto';
    
    setTimeout(() => modal.style.display = 'none', 300);
    resetZoom();
}

function zoom(factor) {
    currentZoom = Math.min(Math.max(0.5, currentZoom * factor), 5);
    updateTransform();
}

function updateTransform() {
    const img = document.getElementById('modalImage');
    img.style.transform = `translate(${currentPos.x}px, ${currentPos.y}px) scale(${currentZoom})`;
}

function startDrag(e) {
    if (currentZoom > 1) {
        isDragging = true;
        startPos = {
            x: e.clientX - currentPos.x,
            y: e.clientY - currentPos.y
        };
    }
}

function drag(e) {
    if (isDragging) {
        currentPos = {
            x: e.clientX - startPos.x,
            y: e.clientY - startPos.y
        };
        updateTransform();
    }
}

function endDrag() {
    isDragging = false;
}

function handleZoom(e) {
    if (e.target.id === 'modalImage') {
        e.preventDefault();
        const factor = e.deltaY > 0 ? 0.9 : 1.1;
        zoom(factor);
    }
}

function resetZoom() {
    currentZoom = 1;
    currentPos = { x: 0, y: 0 };
    updateTransform();
}

// Initialize when document is ready 
document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('imageModal');
    const modalImg = document.getElementById('modalImage');
    const closeBtn = modal.querySelector('.close-modal');
    const prevBtn = modal.querySelector('.prev-btn');
    const nextBtn = modal.querySelector('.next-btn');
    const counter = modal.querySelector('.image-counter');

    // Initialize slideshow
    initializeImageSlideshow();

    // Modal image handlers
    if (modalImg) {
        modalImg.addEventListener('mousedown', startDrag);
        modalImg.addEventListener('dragstart', e => e.preventDefault());
        modalImg.addEventListener('wheel', handleZoom);
    }

    // Navigation handlers
    if (prevBtn) prevBtn.addEventListener('click', e => {
        e.stopPropagation();
        prevImage();
    });
    
    if (nextBtn) nextBtn.addEventListener('click', e => {
        e.stopPropagation();
        nextImage();
    });

    // Close handlers
    if (closeBtn) {
        closeBtn.addEventListener('click', e => {
            e.preventDefault();
            e.stopPropagation();
            closeModal();
        });
    }

    // Modal background handlers
    if (modal) {
        modal.addEventListener('wheel', e => e.stopPropagation(), { passive: false });
        modal.addEventListener('click', e => {
            if (e.target === modal || e.target.classList.contains('modal-content')) {
                closeModal();
            }
        });
    }

    // Document-level handlers
    document.addEventListener('mousemove', drag);
    document.addEventListener('mouseup', endDrag);
    document.addEventListener('keydown', e => {
        if (modal.style.display === 'block') {
            if (e.key === 'Escape') closeModal();
            if (e.key === 'ArrowLeft') prevImage();
            if (e.key === 'ArrowRight') nextImage();
        }
    });

    // Initialize click handlers for all post images
    document.querySelectorAll('.post-images-container').forEach(container => {
        const slides = container.querySelectorAll('.post-image-slide img');
        const overlay = container.querySelector('.gallery-overlay');
        
        if (overlay && slides.length > 0) {
            overlay.addEventListener('click', () => {
                const images = Array.from(slides).map(img => img.src).join(',');
                openModal(images);
            });
        }
    });
});