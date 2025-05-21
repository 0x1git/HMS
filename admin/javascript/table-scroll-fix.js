// Room Booking Table - Vertical and Horizontal Scroll Fix
document.addEventListener('DOMContentLoaded', function() {
    // Function to adjust table column widths based on content and container size
    function adjustTableLayout() {
        const tableContainer = document.querySelector('.roombooktable');
        const table = document.querySelector('.roombooktable .table');
        
        if (!tableContainer || !table) return;
        
        // Calculate available width
        const containerWidth = tableContainer.clientWidth;
        
        // Set min-width to ensure horizontal scrolling capability
        table.style.minWidth = '120%';
        table.style.width = '120%';
        table.style.tableLayout = 'fixed';
          // Enable both vertical and horizontal scrolling
        tableContainer.style.overflowX = 'auto';
        tableContainer.style.overflowY = 'auto';
        
        // Get all table headings
        const headings = table.querySelectorAll('th');
          // Adjust column widths for better fit if there are columns
        if (headings.length > 0) {
            // Calculate relative widths based on content importance
            // Prioritize columns with critical information
            const idWidth = '4%';       // Small ID column
            const nameWidth = '9%';      // Name needs decent space
            const emailWidth = '11%';    // Emails can be long
            const countryWidth = '6%';   // Country names
            const phoneWidth = '8%';     // Phone numbers
            const roomTypeWidth = '9%';  // Room type names
            const bedTypeWidth = '7%';   // Bed type
            const noOfRoomWidth = '4%';  // Simple number
            const mealWidth = '7%';      // Meal types
            const checkinWidth = '8%';   // Dates
            const checkoutWidth = '8%';  // Dates
            const noOfDayWidth = '4%';   // Simple number
            const statusWidth = '6%';    // Status text
            const actionWidth = '9%';    // Action buttons
            
            // Apply column widths
            if (headings.length >= 14) {
                headings[0].style.width = idWidth;
                headings[1].style.width = nameWidth;
                headings[2].style.width = emailWidth;
                headings[3].style.width = countryWidth;
                headings[4].style.width = phoneWidth;
                headings[5].style.width = roomTypeWidth;
                headings[6].style.width = bedTypeWidth;
                headings[7].style.width = noOfRoomWidth;
                headings[8].style.width = mealWidth;
                headings[9].style.width = checkinWidth;
                headings[10].style.width = checkoutWidth;
                headings[11].style.width = noOfDayWidth;
                headings[12].style.width = statusWidth;
                headings[13].style.width = actionWidth;
            }
            
            // Ensure all cells have same width as headers
            const rows = table.querySelectorAll('tbody tr');
            rows.forEach(row => {
                const cells = row.querySelectorAll('td');
                for (let i = 0; i < cells.length && i < headings.length; i++) {
                    cells[i].style.width = headings[i].style.width;
                    // Add ellipsis for text overflow
                    cells[i].style.overflow = 'hidden';
                    cells[i].style.textOverflow = 'ellipsis';
                    cells[i].style.whiteSpace = 'nowrap';
                }
            });
        }        // Enable horizontal scrollbar and ensure proper styling
        tableContainer.style.overflowX = 'auto';
        tableContainer.style.position = 'relative';
        
        // If there are many rows, ensure the table has enough height to scroll
        if (rows.length > 10) {
            tableContainer.style.maxHeight = '75vh';
            tableContainer.style.overflowY = 'auto';
        }
    }
    
    // Run on page load
    adjustTableLayout();
    
    // Run when window is resized
    window.addEventListener('resize', adjustTableLayout);
});