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
    displayEventTime: false,    // Event appearance and interaction
    eventDidMount: function(info) {
        // Add status styling
        const status = info.event.extendedProps.status;
        const guest = info.event.extendedProps.guest || 'Guest';
        const roomType = info.event.extendedProps.roomType || 'Room';
        const roomno = info.event.extendedProps.roomno || '';
        const nodays = info.event.extendedProps.nodays || '?';
        
        // Add appropriate status class
        if (status === 'Confirm') {
            info.el.classList.add('confirmed');
        } else {
            info.el.classList.add('pending');
        }
        
        // Make it look clickable
        info.el.style.cursor = 'pointer';
        
        // Enhanced tooltip with more details and confirm button for pending bookings
        const tooltipContent = `
            <div class="tooltip-guest-info">
                <strong>${guest}</strong><br>
                ${roomType} ${roomno}<br>
                <span class="status-badge ${status.toLowerCase()}">${status}</span><br>
                <small>Stay duration: ${nodays} night(s)</small>
            </div>
            <div class="tooltip-actions mt-2">
                ${status !== 'Confirm' ? 
                    `<button class="btn btn-success btn-sm me-2 confirm-booking-btn" data-booking-id="${info.event.id}">
                        <i class="fas fa-check"></i> Confirm
                    </button>` : ''
                }
                <button class="btn btn-primary btn-sm edit-booking-btn" data-booking-id="${info.event.id}">
                    <i class="fas fa-edit"></i> Edit
                </button>
            </div>
        `;
        
        try {
            const tooltip = new bootstrap.Tooltip(info.el, {
                title: tooltipContent,
                placement: 'top',
                trigger: 'hover',
                html: true,
                container: 'body',
                delay: { show: 100, hide: 300 }
            });
            
            // Store tooltip instance for easy access and cleanup
            info.el._tooltip = tooltip;
            
            // Manual initialization to ensure it works across all browsers
            info.el.addEventListener('mouseover', () => {
                if (info.el._tooltip) {
                    info.el._tooltip.show();
                }
            });
        } catch (error) {
            console.error('Error initializing tooltip:', error);
        }
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
                            <div class="event-actions">
                                ${status !== 'Confirm' ? `
                                    <button class="btn btn-success btn-xs ms-2 confirm-booking-btn" data-booking-id="${arg.event.id}">
                                        <i class="fas fa-check"></i> Confirm
                                    </button>
                                ` : ''}
                                <button class="btn btn-primary btn-xs ms-2 edit-booking-btn" data-booking-id="${arg.event.id}">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                            </div>
                        </div>
                    </div>
                `
            };
        }
        
        return {
            html: `
                <div class="fc-event-main-content">
                    <div class="event-title">${guest}</div>
                    <div class="event-room">
                        ${roomType}
                        <div class="event-actions mt-1">
                            ${status !== 'Confirm' ? `
                                <button class="btn btn-success btn-xs me-1 confirm-booking-btn" data-booking-id="${arg.event.id}">
                                    <i class="fas fa-check"></i>
                                </button>
                            ` : ''}
                            <button class="btn btn-primary btn-xs edit-booking-btn" data-booking-id="${arg.event.id}">
                                <i class="fas fa-edit"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `
        };
    },
    
    // Add event click handler
    eventClick: function(info) {
        // Prevent default click behavior since we're using buttons
        info.jsEvent.preventDefault();
    }
};
