/**
 * Calendar configuration options
 */
const calendarConfig = {
    // Initial view and toolbar settings
    initialView: 'listWeek',
    headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'listWeek,listMonth,dayGridMonth'
    },
    
    // Calendar behavior
    nowIndicator: true,
    navLinks: true,
    businessHours: true,
    editable: false,
    dayMaxEvents: true,
    displayEventTime: false,

    // Event appearance and interaction
    eventDidMount: function(info) {
        // Add status styling
        const status = info.event.extendedProps.status;
        const guest = info.event.extendedProps.guest || 'Guest';
        const roomType = info.event.extendedProps.roomType || 'Room';
        const roomno = info.event.extendedProps.roomno || '';
        
        // Add appropriate status class
        if (status === 'Confirm') {
            info.el.classList.add('confirmed');
        } else {
            info.el.classList.add('pending');
        }
        
        // Make it look clickable
        info.el.style.cursor = 'pointer';
        
        // Enhanced tooltip with more details
        const tooltipContent = `
            <strong>${guest}</strong><br>
            ${roomType} ${roomno}<br>
            <span class="status-badge ${status.toLowerCase()}">${status}</span><br>
            <em>Click to edit booking</em>
        `;
        
        new bootstrap.Tooltip(info.el, {
            title: tooltipContent,
            placement: 'top',
            trigger: 'hover',
            html: true,
            container: 'body'
        });
    },
    
    // Event content customization
    eventContent: function(arg) {
        const guest = arg.event.extendedProps.guest || 'Guest';
        const roomType = arg.event.extendedProps.roomType || 'Room';
        const status = arg.event.extendedProps.status || 'Pending';
        
        if (arg.view.type.startsWith('list')) {
            return {
                html: `
                    <div class="fc-event-main-content">
                        <div class="event-title">${guest}</div>
                        <div class="event-details">
                            <span class="room-type">${roomType}</span>
                            <span class="status-badge ${status.toLowerCase()}">${status}</span>
                        </div>
                    </div>
                `
            };
        }
        
        return {
            html: `
                <div class="fc-event-main-content">
                    <div class="event-title">${guest}</div>
                    <div class="event-room">${roomType}</div>
                </div>
            `
        };
    },
    
    // Add event click handler
    eventClick: function(info) {
        // Get the booking ID from the event
        const bookingId = info.event.id;
        
        // Redirect to the room booking edit page with the ID
        window.location.href = `roombookedit.php?id=${bookingId}`;
    }
};
