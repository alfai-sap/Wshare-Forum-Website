document.addEventListener('DOMContentLoaded', function() {
    function initializeImageSlideshow() {
        document.querySelectorAll('.post-images-container').forEach(container => {
            const slides = container.querySelectorAll('.post-image-slide');
            const dots = container.querySelectorAll('.progress-dot');
            const counter = container.querySelector('.image-counter');
            let currentIndex = 0;

            function updateSlides(newIndex) {
                currentIndex = newIndex;
                slides.forEach((slide, index) => {
                    slide.classList.toggle('active', index === currentIndex);
                });
                dots.forEach((dot, index) => {
                    dot.classList.toggle('active', index === currentIndex);
                });
                if (counter) {
                    counter.textContent = `${currentIndex + 1}/${slides.length}`;
                }
            }

            // Navigation handling
            container.addEventListener('click', (e) => {
                const target = e.target;
                
                if (target.closest('.nav-left')) {
                    e.stopPropagation();
                    updateSlides((currentIndex - 1 + slides.length) % slides.length);
                } else if (target.closest('.nav-right')) {
                    e.stopPropagation();
                    updateSlides((currentIndex + 1) % slides.length);
                } else if (target.classList.contains('progress-dot')) {
                    e.stopPropagation();
                    updateSlides(parseInt(target.dataset.index));
                }
            });

            // Initialize first slide
            updateSlides(0);
        });
    }

    // Initialize slideshow
    initializeImageSlideshow();
});