// Functions for calendar booking actions
function handleConfirmBooking(bookingId, event) {
    // If event is a string (from tooltip), create a dummy event
    if (typeof event === 'string') {
        event = { preventDefault: () => {}, stopPropagation: () => {} };
    }
    
    // Prevent event bubbling
    event.preventDefault();
    event.stopPropagation();
    
    // Close any open tooltips
    const tooltips = document.querySelectorAll('.tooltip');
    tooltips.forEach(tooltip => {
        const instance = bootstrap.Tooltip.getInstance(tooltip);
        if (instance) {
            instance.hide();
        }
    });
    
    // Show confirmation dialog
    if (confirm('Are you sure you want to confirm this booking?')) {
        // Navigate to the confirmation page
        window.location.href = `roomconfirm.php?id=${bookingId}`;
    }
}

function handleEditBooking(bookingId, event) {
    // If event is a string (from tooltip), create a dummy event
    if (typeof event === 'string') {
        event = { preventDefault: () => {}, stopPropagation: () => {} };
    }
    
    // Prevent event bubbling
    event.preventDefault();
    event.stopPropagation();
    
    // Close any open tooltips
    const tooltips = document.querySelectorAll('.tooltip');
    tooltips.forEach(tooltip => {
        const instance = bootstrap.Tooltip.getInstance(tooltip);
        if (instance) {
            instance.hide();
        }
    });
    
    // Navigate to the edit page
    window.location.href = `roombookedit.php?id=${bookingId}`;
}
