// Status Column Styling Script
document.addEventListener('DOMContentLoaded', function() {
    // Apply status styling to the status column
    function applyStatusStyling() {
        const table = document.getElementById('table-data');
        
        if (table) {
            // Get all rows (skip header)
            const rows = table.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                // Status column is the third-to-last column
                const statusCell = row.cells[row.cells.length - 2];
                
                if (statusCell) {
                    const status = statusCell.textContent.trim();
                    const oldContent = statusCell.textContent;
                    
                    // Create status label element
                    const statusLabel = document.createElement('span');
                    statusLabel.textContent = status;
                    statusLabel.className = 'status-label';
                    
                    // Add specific class based on status
                    if (status === 'Confirm') {
                        statusLabel.classList.add('status-confirmed');
                    } else if (status === 'NotConfirm') {
                        statusLabel.classList.add('status-notconfirm');
                        statusLabel.textContent = 'Pending';
                    }
                    
                    // Clear the cell and append the new element
                    statusCell.textContent = '';
                    statusCell.appendChild(statusLabel);
                    
                    // Highlight bookings for today or tomorrow
                    const checkInCell = row.cells[row.cells.length - 4]; // Check-in date column
                    if (checkInCell) {
                        const checkInDate = new Date(checkInCell.textContent.trim());
                        const today = new Date();
                        
                        // Reset time components for accurate date comparison
                        today.setHours(0, 0, 0, 0);
                        checkInDate.setHours(0, 0, 0, 0);
                        
                        // Check if check-in date is today
                        if (checkInDate.getTime() === today.getTime()) {
                            row.classList.add('today');
                        }
                    }
                }
            });
        }
    }
    
    // Run the function
    applyStatusStyling();
});
