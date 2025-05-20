// Payment Counter Script for Golden Palace Hotel
document.addEventListener('DOMContentLoaded', function() {
    // Get the table and count the number of payments
    const table = document.getElementById('table-data');
    
    if (table) {
        // Count all rows minus the header row
        const totalPayments = table.rows.length - 1;
        
        // Calculate total amount from the finaltotal column (the total bill column)
        let totalAmount = 0;
        
        // Loop through the table rows (skip header)
        for (let i = 1; i < table.rows.length; i++) {
            // Total bill column is the third-to-last column
            const totalBillCell = table.rows[i].cells[table.rows[i].cells.length - 2];
            
            if (totalBillCell) {                // Parse the amount as a number, removing the ₹ symbol and any commas
                const amountText = totalBillCell.textContent.trim().replace('₹', '').replace(/,/g, '');
                const amount = parseFloat(amountText);
                if (!isNaN(amount)) {
                    totalAmount += amount;
                }
            }
        }
          // Format the total amount with commas
        const formattedTotalAmount = '₹' + totalAmount.toLocaleString('en-IN', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
        
        // If table container doesn't already have a title section, create one
        let tableTitleSection = document.querySelector('.table-title-section');
        
        if (!tableTitleSection) {
            // Create the title section
            tableTitleSection = document.createElement('div');
            tableTitleSection.className = 'table-title-section';
            
            // Create the HTML content
            tableTitleSection.innerHTML = `
                <div class="table-title">
                    <h2><i class="fas fa-credit-card"></i> Payment Records</h2>
                    <div class="table-stats">
                        <div class="stat-item">
                            <span class="stat-label">Total Payments:</span>
                            <span class="stat-value total-payments">${totalPayments}</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Total Revenue:</span>
                            <span class="stat-value total-amount">${formattedTotalAmount}</span>
                        </div>
                    </div>
                </div>
            `;
            
            // Insert the title section before the table
            const tableContainer = document.querySelector('.payment-table');
            if (tableContainer) {
                tableContainer.insertBefore(tableTitleSection, tableContainer.firstChild);
            }
        }
    }
});
