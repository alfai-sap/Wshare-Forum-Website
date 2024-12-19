
document.addEventListener('DOMContentLoaded', function() {
    const navbar = document.getElementById('left-navbar');
    const toggleButtons = document.querySelectorAll('#logo-nav, #logo-left-nav');
    const overlay = document.createElement('div');
    
    // Create overlay
    overlay.className = 'navbar-overlay';
    document.body.appendChild(overlay);
    
    // Toggle handlers
    function toggleNavbar() {
        navbar.classList.toggle('active');
        overlay.style.display = navbar.classList.contains('active') ? 'block' : 'none';
        document.body.style.overflow = navbar.classList.contains('active') ? 'hidden' : '';
    }
    
    // Event listeners
    toggleButtons.forEach(button => {
        button.addEventListener('click', toggleNavbar);
    });
    
    // Close navbar when clicking overlay
    overlay.addEventListener('click', toggleNavbar);
    
    // Close navbar on window resize if screen becomes larger than mobile breakpoint
    window.addEventListener('resize', () => {
        if (window.innerWidth > 768 && navbar.classList.contains('active')) {
            toggleNavbar();
        }
    });
});