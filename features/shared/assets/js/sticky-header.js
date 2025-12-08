/**
 * Sticky Header Enhancements
 * Adds visual cues (shadows) when tables are scrolled.
 */
document.addEventListener('DOMContentLoaded', () => {
    // Select all table scroll containers
    const tableContainers = document.querySelectorAll('.table-responsive');

    tableContainers.forEach(container => {
        // Add scroll event listener
        container.addEventListener('scroll', () => {
            if (container.scrollTop > 5) {
                container.classList.add('is-scrolling');
            } else {
                container.classList.remove('is-scrolling');
            }
        });

        // Initial check
        if (container.scrollTop > 5) {
            container.classList.add('is-scrolling');
        }
    });

    console.log('Sticky headers initialized.');
});
