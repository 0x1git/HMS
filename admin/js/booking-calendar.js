// Booking Calendar JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Global handlers for booking actions
    window.handleConfirmBooking = function(bookingId, event) {
        if (event) {
            event.preventDefault();
            event.stopPropagation();
        }
        window.location.href = `roomconfirm.php?id=${bookingId}`;
    };

    window.handleEditBooking = function(bookingId, event) {
        if (event) {
            event.preventDefault();
            event.stopPropagation();
        }
        window.location.href = `roombookedit.php?id=${bookingId}`;
    };

    // Add event delegation for all booking buttons
    document.addEventListener('click', function(event) {
        // Handle confirm booking buttons
        if (event.target.closest('.confirm-booking-btn')) {
            const button = event.target.closest('.confirm-booking-btn');
            const bookingId = button.getAttribute('data-booking-id');
            handleConfirmBooking(bookingId, event);
        }
        
        // Handle edit booking buttons
        if (event.target.closest('.edit-booking-btn')) {
            const button = event.target.closest('.edit-booking-btn');
            const bookingId = button.getAttribute('data-booking-id');
            handleEditBooking(bookingId, event);
        }
    });

    // Initialize FullCalendar
    const calendarEl = document.getElementById('calendar');
    
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,listWeek'
        },
        themeSystem: 'bootstrap5',
        events: bookingsData,
        eventClick: function(info) {
            // Prevent default behavior only if the click is not on a button
            if (!info.jsEvent.target.closest('button')) {
                info.jsEvent.preventDefault();
                showBookingDetails(info.event);
            }
        },
        eventTimeFormat: {
            hour: '2-digit',
            minute: '2-digit',
            meridiem: 'short'
        },
        eventContent: function(arg) {
            const event = arg.event;
            const bookingId = event.id;
            
            return {
                html: `
                    <div class="fc-event-main-content">
                        <div class="fc-event-title">${event.title}</div>
                        <div class="fc-event-buttons">
                            <button type="button" class="btn btn-success btn-sm confirm-booking-btn" 
                                data-booking-id="${bookingId}">
                                <i class="fas fa-check"></i>
                            </button>
                            <button type="button" class="btn btn-primary btn-sm edit-booking-btn"
                                data-booking-id="${bookingId}">
                                <i class="fas fa-edit"></i>
                            </button>
                        </div>
                    </div>
                `
            };
        },
        eventDidMount: function(info) {
            const event = info.event;
            const bookingId = event.id;
            
            // Get event status for styling
            const status = event.extendedProps.status === 'Confirm' ? 'confirmed' : 'pending';
            const statusLabel = event.extendedProps.status === 'Confirm' ? 'Confirmed' : 'Pending';
            
            // Initialize tooltip
            const tooltip = new bootstrap.Tooltip(info.el, {
                title: `
                    <div class="tooltip-content">
                        <h6>${event.title}</h6>
                        <div class="tooltip-details">
                            <p>${event.extendedProps.description || ''}</p>
                            <span class="status-badge ${status}">${statusLabel}</span>
                        </div>
                        <div class="tooltip-buttons">
                            <button type="button" class="btn btn-success btn-sm confirm-booking-btn" 
                                data-booking-id="${bookingId}">
                                <i class="fas fa-check"></i> Confirm
                            </button>
                            <button type="button" class="btn btn-primary btn-sm edit-booking-btn"
                                data-booking-id="${bookingId}">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                        </div>
                    </div>
                `,
                html: true,
                placement: 'top',
                trigger: 'hover',
                container: 'body',
                animation: true
            });

            // Clean up tooltip on event unmount
            info.el._tooltip = tooltip;
        },

        dayMaxEvents: true // allow "more" link when too many events
    });
    
    calendar.render();

    // Filter toggle button
    document.getElementById('filterToggle').addEventListener('click', function() {
        const filterPanel = document.getElementById('filterPanel');
        if (filterPanel.style.display === 'none') {
            filterPanel.style.display = 'block';
        } else {
            filterPanel.style.display = 'none';
        }
    });

    // Apply filters
    document.getElementById('applyFilters').addEventListener('click', function() {
        const roomTypeFilter = document.getElementById('roomTypeFilter').value;
        const statusFilter = document.getElementById('statusFilter').value;
        const nameFilter = document.getElementById('nameFilter').value.toLowerCase();

        calendar.getEvents().forEach(event => {
            let visible = true;
            
            if (roomTypeFilter && event.extendedProps.roomType !== roomTypeFilter) {
                visible = false;
            }
            
            if (statusFilter && event.extendedProps.status !== statusFilter) {
                visible = false;
            }
            
            if (nameFilter && !event.extendedProps.name.toLowerCase().includes(nameFilter)) {
                visible = false;
            }
            
            if (visible) {
                event.setProp('display', 'auto');
            } else {
                event.setProp('display', 'none');
            }
        });
    });

    // Reset filters
    document.getElementById('resetFilters').addEventListener('click', function() {
        document.getElementById('roomTypeFilter').value = '';
        document.getElementById('statusFilter').value = '';
        document.getElementById('nameFilter').value = '';
        
        calendar.getEvents().forEach(event => {
            event.setProp('display', 'auto');
        });
    });

    // Show booking details in modal
    function showBookingDetails(event) {
        const bookingId = event.id;
        const bookingProps = event.extendedProps;
        const modal = new bootstrap.Modal(document.getElementById('bookingDetailsModal'));
        
        // Format dates for display
        const startDate = new Date(event.start);
        const endDate = new Date(event.end);
        const formattedStartDate = startDate.toLocaleDateString('en-US', { 
            weekday: 'short', 
            year: 'numeric', 
            month: 'short', 
            day: 'numeric' 
        });
        const formattedEndDate = endDate.toLocaleDateString('en-US', { 
            weekday: 'short', 
            year: 'numeric', 
            month: 'short', 
            day: 'numeric' 
        });
        
        // Calculate stay duration
        const days = Math.round((endDate - startDate) / (1000 * 60 * 60 * 24));
        
        // Set modal content
        const modalContent = document.getElementById('bookingModalContent');
        modalContent.innerHTML = `
            <div class="booking-details p-2">
                <div class="row booking-detail-row">
                    <div class="col-md-6">
                        <span class="detail-label">Guest Name:</span>
                        <span class="detail-value">${bookingProps.name}</span>
                    </div>
                    <div class="col-md-6">
                        <span class="detail-label">Status:</span>
                        <span class="status-badge ${bookingProps.status === 'Confirm' ? 'status-confirmed' : 'status-pending'}">
                            ${bookingProps.status === 'Confirm' ? 'Confirmed' : 'Pending'}
                        </span>
                    </div>
                </div>
                <div class="row booking-detail-row">
                    <div class="col-md-6">
                        <span class="detail-label">Room Type:</span>
                        <span class="detail-value">${bookingProps.roomType}</span>
                    </div>
                    <div class="col-md-6">
                        <span class="detail-label">Bed Type:</span>
                        <span class="detail-value">${bookingProps.bedType}</span>
                    </div>
                </div>
                <div class="row booking-detail-row">
                    <div class="col-md-6">
                        <span class="detail-label">Check-In:</span>
                        <span class="detail-value">${formattedStartDate}</span>
                    </div>
                    <div class="col-md-6">
                        <span class="detail-label">Check-Out:</span>
                        <span class="detail-value">${formattedEndDate}</span>
                    </div>
                </div>
                <div class="row booking-detail-row">
                    <div class="col-md-12">
                        <span class="detail-label">Stay Duration:</span>
                        <span class="detail-value">${days} day${days !== 1 ? 's' : ''}</span>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-12 text-center">
                        <div class="booking-id-badge">
                            Booking ID: #${bookingId}
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        // Set up action buttons
        const editBtn = document.getElementById('editBookingBtn');
        editBtn.href = `roombookedit.php?id=${bookingId}`;
        
        const confirmBtn = document.getElementById('confirmBookingBtn');
        if (bookingProps.status === 'Confirm') {
            confirmBtn.style.display = 'none';
        } else {
            confirmBtn.style.display = 'inline-block';
            confirmBtn.href = `roomconfirm.php?id=${bookingId}`;
        }
        
        modal.show();
    }
});
