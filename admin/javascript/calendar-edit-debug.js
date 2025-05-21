// Debug script to ensure edit button functionality in calendar view
document.addEventListener('DOMContentLoaded', function() {
    console.log('Calendar edit debug script loaded');
    
    // Add an observer to watch for dynamically added edit buttons
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.addedNodes && mutation.addedNodes.length > 0) {
                for (let i = 0; i < mutation.addedNodes.length; i++) {
                    const node = mutation.addedNodes[i];
                    if (node.nodeType === Node.ELEMENT_NODE) {
                        // Check for edit buttons in the added element
                        const editButtons = node.querySelectorAll('.edit-booking-btn');
                        if (editButtons.length > 0) {
                            console.log('Edit buttons found:', editButtons.length);
                            editButtons.forEach(btn => {
                                console.log('Edit button data-booking-id:', btn.getAttribute('data-booking-id'));
                                
                                // Add direct click handler to ensure it works
                                btn.addEventListener('click', function(e) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                    const bookingId = this.getAttribute('data-booking-id');
                                    console.log('Edit button clicked directly for booking ID:', bookingId);
                                    window.location.href = `roombookedit.php?id=${bookingId}`;
                                });
                            });
                        }
                    }
                }
            }
        });
    });
    
    // Start observing the document body for changes
    observer.observe(document.body, {
        childList: true,
        subtree: true
    });
    
    // Additional global click handler for edit buttons
    document.addEventListener('click', function(e) {
        const target = e.target.closest('.edit-booking-btn');
        if (target) {
            console.log('Edit button clicked through global handler');
            console.log('Button:', target);
            console.log('Booking ID:', target.getAttribute('data-booking-id'));
        }
    });
});
