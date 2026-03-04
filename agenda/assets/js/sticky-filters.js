/**
 * Sticky Filters for Mobile
 * Handles the behavior of filter sections on mobile devices:
 * When the user scrolls past the filter section (it goes off-screen top),
 * the filter section becomes fixed at the bottom of the screen.
 */
document.addEventListener('DOMContentLoaded', function () {

    // Function to check if we are on mobile
    const checkMobile = () => window.innerWidth <= 768;

    // Select all potential filter sections
    const sections = document.querySelectorAll('.noticias-filtros-section, .blog-filtros-section, .eventos-filtros-section, .artistas-filtros-section, .talleres-filtros-section');

    if (sections.length === 0) return;

    sections.forEach(section => {
        // Create a placeholder element to prevent layout jump
        const placeholder = document.createElement('div');
        placeholder.className = 'sticky-filter-placeholder';
        placeholder.style.display = 'none';
        placeholder.style.height = section.offsetHeight + 'px';
        placeholder.style.width = '100%';

        // Insert placeholder before section in the DOM
        if (section.parentNode) {
            section.parentNode.insertBefore(placeholder, section);
        }

        const handleScroll = () => {
            // Update placeholder height in case content changed
            if (!section.classList.contains('sticky-bottom-mobile') && section.offsetHeight > 0) {
                placeholder.style.height = section.offsetHeight + 'px';
            }

            if (!checkMobile()) {
                // Reset desktop state if screen resized
                if (section.classList.contains('sticky-bottom-mobile')) {
                    section.classList.remove('sticky-bottom-mobile');
                    placeholder.style.display = 'none';
                }
                return;
            }

            const rect = section.getBoundingClientRect();
            const placeholderRect = placeholder.getBoundingClientRect();
            const isSticky = section.classList.contains('sticky-bottom-mobile');

            // Logic:
            // 1. If NOT sticky, check if element has scrolled off screen to the top
            // rect.bottom < 0 implies it is fully above the viewport
            if (!isSticky) {
                if (rect.bottom < 0) {
                    // Make sticky at bottom
                    section.classList.add('sticky-bottom-mobile');
                    placeholder.style.display = 'block';
                }
            } else {
                // 2. If Sticky, check if placeholder is entering view
                // placeholderRect.bottom > 0 means the original spot is visible (or partially)
                if (placeholderRect.bottom > 0) {
                    // Revert to normal position
                    section.classList.remove('sticky-bottom-mobile');
                    placeholder.style.display = 'none';
                }
            }
        };

        // Listen to scroll and resize events
        window.addEventListener('scroll', handleScroll, { passive: true });
        window.addEventListener('resize', handleScroll);

        // Initial check on load
        handleScroll();
    });
});
