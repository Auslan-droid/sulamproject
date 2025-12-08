/**
 * Double Scrollbar for Tables
 * Adds a top scrollbar to responsive tables so users can scroll horizontally
 * without reaching the bottom of the table.
 */
document.addEventListener('DOMContentLoaded', function () {
    function addTopScrollbar() {
        const tables = document.querySelectorAll('.table-responsive, .table-responsive--wide');

        tables.forEach(tableWrapper => {
            // Check if scrollbar is actually needed
            if (tableWrapper.scrollWidth <= tableWrapper.clientWidth) {
                return;
            }

            // Create top scrollbar container
            const topScroll = document.createElement('div');
            topScroll.className = 'table-top-scroll';
            topScroll.style.overflowX = 'auto';
            topScroll.style.marginBottom = '5px';
            topScroll.style.width = '100%';

            // Allow styling of the scrollbar itself to match system
            // topScroll.style.border = 'none'; 

            // Create inner spacer
            const spacer = document.createElement('div');
            spacer.style.width = tableWrapper.scrollWidth + 'px';
            spacer.style.height = '1px'; // Min height to trigger scrollbar
            spacer.style.paddingTop = '10px'; // Make it clickable

            topScroll.appendChild(spacer);

            // Insert before the table wrapper
            tableWrapper.parentNode.insertBefore(topScroll, tableWrapper);

            // Sync scroll events
            topScroll.onscroll = function () {
                tableWrapper.scrollLeft = topScroll.scrollLeft;
            };

            tableWrapper.onscroll = function () {
                topScroll.scrollLeft = tableWrapper.scrollLeft;
            };
        });
    }

    addTopScrollbar();

    // Re-check on window resize
    let resizeTimer;
    window.addEventListener('resize', function () {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function () {
            // Remove existing top scrollbars to prevent duplicates/stale widths
            document.querySelectorAll('.table-top-scroll').forEach(el => el.remove());
            addTopScrollbar();
        }, 100);
    });
});
