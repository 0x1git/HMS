// Booking Counter Script for Golden Palace Hotel
document.addEventListener('DOMContentLoaded', function() {
    // Get the table and count the number of bookings
    const table = document.getElementById('table-data');
    
    if (table) {
        // Count all rows minus the header row
        const totalBookings = table.rows.length - 1;
        
        // Count confirmed bookings
        let confirmedBookings = 0;
        
        // Loop through the table rows (skip header)
        for (let i = 1; i < table.rows.length; i++) {
            // Status column is the third-to-last column (index = table.rows[i].cells.length - 3)
            const statusCell = table.rows[i].cells[table.rows[i].cells.length - 2];
            
            if (statusCell && statusCell.textContent.trim() === 'Confirm') {
                confirmedBookings++;
            }
        }
        
        // If table container doesn't already have a title section, create one
        let tableTitleSection = document.querySelector('.table-title-section');
        
        if (!tableTitleSection) {
            // Create the title section
            tableTitleSection = document.createElement('div');
            tableTitleSection.className = 'table-title-section';
            
            // Create the HTML content
            tableTitleSection.innerHTML = `
                <div class="table-title">
                    <h2><i class="fas fa-book-open"></i> Room Bookings</h2>
                    <div class="table-stats">
                        <div class="stat-item">
                            <span class="stat-label">Total Bookings:</span>
                            <span class="stat-value total-bookings">${totalBookings}</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Confirmed:</span>
                            <span class="stat-value confirmed-bookings">${confirmedBookings}</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Pending:</span>
                            <span class="stat-value pending-bookings">${totalBookings - confirmedBookings}</span>
                        </div>
                    </div>
                </div>
            `;
            
            // Insert the title section before the table
            const tableContainer = document.querySelector('.roombooktable');
            if (tableContainer) {
                tableContainer.insertBefore(tableTitleSection, tableContainer.firstChild);
            }
        }
    }
});
