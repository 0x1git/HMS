/**
 * This script enhances the calendar list view in the booking calendar
 * It's loaded after calendar-toggle.js to add additional functionality
 */
document.addEventListener('DOMContentLoaded', function() {
    // Wait for the calendar to be initialized
    function enhanceCalendarListView() {
        if (window.calendar) {
            // Store the original eventDidMount function
            const originalEventDidMount = window.calendar.getOption('eventDidMount');
            
            // Define custom event content for list view
            window.calendar.setOption('eventContent', function(arg) {
                // Only customize list view
                if (arg.view.type.includes('list')) {
                    const status = arg.event.extendedProps.status === 'Confirm' ? 'Confirmed' : 'Pending';
                    const guest = arg.event.extendedProps.guest || '';
                    const roomType = arg.event.extendedProps.roomType || '';
                    const noOfDays = arg.event.extendedProps.nodays || '';
                    const statusClass = status === 'Confirmed' ? 'bg-success' : 'bg-warning text-dark';
                    
                    // Create enhanced list item with more details
                    return {
                        html: `
                            <div class="fc-event-main-frame">
                                <div class="fc-event-title-container">
                                    <div class="fc-event-title fc-sticky">
                                        ${guest} 
                                        <span class="badge ${statusClass} ms-2">
                                            ${status}
                                        </span>
                                    </div>
                                    <div class="fc-event-desc text-muted small">
                                        <i class="fas fa-bed me-1"></i>${roomType} 
                                        <i class="fas fa-calendar-day ms-3 me-1"></i>${noOfDays || '?'} night(s)
                                    </div>
                                </div>
                            </div>
                        `
                    };
                }
                return;
            });
            
            // Enhanced eventDidMount for styling list view events
            window.calendar.setOption('eventDidMount', function(info) {
                // Call original function if it exists
                if (typeof originalEventDidMount === 'function') {
                    originalEventDidMount(info);
                }
                
                // Add styling for list view events
                if (info.view.type.includes('list')) {
                    // Add status class
                    if (info.event.extendedProps.status === 'Confirm') {
                        info.el.classList.add('confirm-status');
                    } else {
                        info.el.classList.add('pending-status');
                    }
                    
                    // Add hover effect
                    info.el.classList.add('list-event-hover');
                    
                    // Add quick action buttons
                    const titleCell = info.el.querySelector('.fc-list-event-title');
                    if (titleCell) {
                        const actionsHtml = `
                            <div class="list-event-actions ms-2">
                                <a href="roombookedit.php?id=${info.event.id}" class="btn btn-sm btn-outline-primary me-1" title="Edit Booking">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button class="btn btn-sm btn-outline-info" onclick="showBookingDetails('${info.event.id}')" title="View Details">
                                    <i class="fas fa-info-circle"></i>
                                </button>
                            </div>
                        `;
                        titleCell.insertAdjacentHTML('beforeend', actionsHtml);
                    }
                }
            });
            
            // Refresh calendar to apply changes
            window.calendar.refetchEvents();
            
            console.log('Calendar list view enhancements applied');
        } else {
            // If calendar isn't ready yet, wait and try again
            setTimeout(enhanceCalendarListView, 500);
        }
    }
    
    // Start the enhancement process
    setTimeout(enhanceCalendarListView, 1000);
});
