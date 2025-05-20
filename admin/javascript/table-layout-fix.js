// Ensure search bar and table are properly displayed
document.addEventListener('DOMContentLoaded', function() {
    // Function to handle table appearance
    function adjustTableLayout() {
        const searchBar = document.querySelector('.searchsection');
        const tableContainer = document.querySelector('.roombooktable');
        
        if (searchBar && tableContainer) {
            // Add a small delay to ensure all styles are applied
            setTimeout(() => {
                // Make sure the table is below the search bar
                const searchBarHeight = searchBar.offsetHeight;
                const searchBarPosition = searchBar.getBoundingClientRect().top;
                
                // Adjust table position if needed
                if (window.getComputedStyle(tableContainer).position !== 'absolute') {
                    tableContainer.style.marginTop = searchBarHeight + 'px';
                }
                
                // Ensure table container has proper height
                const windowHeight = window.innerHeight;
                const availableHeight = windowHeight - (searchBarPosition + searchBarHeight);
                tableContainer.style.maxHeight = (availableHeight - 40) + 'px'; // Subtract extra padding
            }, 100);
        }
    }
    
    // Run on load
    adjustTableLayout();
    
    // Run when window is resized
    window.addEventListener('resize', adjustTableLayout);
    
    // Add horizontal scroll hint if table is wider than container
    const tableContainer = document.querySelector('.roombooktable');
    const table = document.querySelector('.table');
    
    if (tableContainer && table) {
        if (table.offsetWidth > tableContainer.offsetWidth) {
            // Check if scroll hint already exists
            if (!document.querySelector('.scroll-hint')) {
                // Create scroll hint
                const scrollHint = document.createElement('div');
                scrollHint.className = 'scroll-hint';
                scrollHint.innerHTML = '<i class="fas fa-arrows-alt-h"></i> Scroll horizontally to see more';
                
                // Insert after table
                tableContainer.parentNode.insertBefore(scrollHint, tableContainer.nextSibling);
            }
        }
    }
    
    // Make sure that any table title section we've added remains visible
    const tableTitleSection = document.querySelector('.table-title-section');
    if (tableTitleSection) {
        tableTitleSection.style.position = 'sticky';
        tableTitleSection.style.top = '0';
        tableTitleSection.style.backgroundColor = 'rgba(13, 26, 66, 0.95)';
        tableTitleSection.style.zIndex = '2';
        tableTitleSection.style.padding = '15px 0 5px 0';
        tableTitleSection.style.marginBottom = '10px';
    }
});
