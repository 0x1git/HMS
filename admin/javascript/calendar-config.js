/**
 * Calendar configuration options
 */
const calendarConfig = {
    initialView: 'listWeek', // Set list view as default
    headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'listWeek,listMonth,dayGridMonth'
    },
    views: {
        listWeek: {
            titleFormat: { year: 'numeric', month: 'short', day: 'numeric' },
            duration: { weeks: 1 },
            buttonText: 'Week List',
            dayHeaderFormat: {
                weekday: 'long',
                month: 'long',
                day: 'numeric'
            },
            listDayFormat: { weekday: 'long', month: 'short', day: 'numeric' },
            listDaySideFormat: false // Cleaner list view
        },
        listMonth: {
            titleFormat: { year: 'numeric', month: 'long' },
            duration: { months: 1 },
            buttonText: 'Month List',
            dayHeaderFormat: {
                weekday: 'short',
                month: 'long',
                day: 'numeric'
            },
            listDayFormat: { weekday: 'short', month: 'short', day: 'numeric' },
            listDaySideFormat: false
        },
        dayGridMonth: {
            buttonText: 'Month Grid'
        }
    },
    // Event appearance
    eventDidMount: function(info) {
        // Add status styling
        const status = info.event.extendedProps.status;
        const guest = info.event.extendedProps.guest || 'Guest';
        const roomType = info.event.extendedProps.roomType || 'Room';
        const roomno = info.event.extendedProps.roomno || '';
        
        if (status === 'Confirm') {
            info.el.classList.add('confirmed');
        } else {
            info.el.classList.add('pending');
        }
        
        // Enhanced tooltip with more details
        const tooltipContent = `
            <strong>${guest}</strong><br>
            ${roomType} ${roomno}<br>
            <span class="status-badge ${status.toLowerCase()}">${status}</span>
        `;
        
        new bootstrap.Tooltip(info.el, {
            title: tooltipContent,
            placement: 'top',
            trigger: 'hover',
            html: true,
            container: 'body'
        });
    },
    
    // Event display
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
    
    // Calendar behavior
    nowIndicator: true,
    navLinks: true,
    businessHours: true,
    editable: false,
    dayMaxEvents: true,
    displayEventTime: false
};
