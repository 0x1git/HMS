/**
 * Enhanced Calendar Button Handlers
 * This script ensures buttons in the calendar work consistently across all browsers
 */
document.addEventListener('DOMContentLoaded', function() {
    console.log('Enhanced calendar buttons initialized');

    // Global event delegation for all calendar buttons
    document.addEventListener('click', function(event) {
        // If event happened inside a tooltip, handle buttons specially
        const isInTooltip = event.target.closest('.tooltip');
        
        // Handle edit buttons
        const editBtn = event.target.closest('.edit-booking-btn');
        if (editBtn) {
            event.preventDefault();
            event.stopPropagation();
            console.log('Edit button clicked (enhanced handler)');
            
            const bookingId = editBtn.getAttribute('data-booking-id');
            if (bookingId) {
                // Close all tooltips first
                closeAllTooltips();
                // Navigate to edit page
                setTimeout(() => {
                    window.location.href = `roombookedit.php?id=${bookingId}`;
                }, 50);
            }
        }
        
        // Handle confirm buttons
        const confirmBtn = event.target.closest('.confirm-booking-btn');
        if (confirmBtn) {
            event.preventDefault();
            event.stopPropagation();
            console.log('Confirm button clicked (enhanced handler)');
            
            const bookingId = confirmBtn.getAttribute('data-booking-id');
            if (bookingId) {
                // Close all tooltips first
                closeAllTooltips();
                
                // Show confirmation dialog
                setTimeout(() => {
                    if (confirm('Are you sure you want to confirm this booking?')) {
                        window.location.href = `roomconfirm.php?id=${bookingId}`;
                    }
                }, 50); 
            }
        }
    }, true); // Use capturing for more reliable event handling
    
    // Helper function to close all open tooltips
    function closeAllTooltips() {
        const tooltips = document.querySelectorAll('.tooltip');
        tooltips.forEach(tooltip => {
            try {
                const tooltipInstance = bootstrap.Tooltip.getInstance(tooltip);
                if (tooltipInstance) {
                    tooltipInstance.hide();
                }
            } catch (error) {
                console.warn('Error closing tooltip:', error);
            }
        });
    }
    
    // Make tooltips close when clicking on buttons inside them
    document.addEventListener('shown.bs.tooltip', function (event) {
        const tooltip = event.target;
        if (tooltip) {
            const tooltipElement = document.querySelector('.tooltip');
            if (tooltipElement) {
                const buttons = tooltipElement.querySelectorAll('button');
                buttons.forEach(button => {
                    button.addEventListener('click', function() {
                        const tooltipInstance = bootstrap.Tooltip.getInstance(tooltip);
                        if (tooltipInstance) {
                            tooltipInstance.hide();
                        }
                    });
                });
            }
        }
    });
});
