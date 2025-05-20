// This script handles toggling between table view and calendar view
console.log('Calendar toggle script loaded');

document.addEventListener('DOMContentLoaded', function() {
    // Get elements
    const calendarViewBtn = document.getElementById('calendar-view-btn');
    const tableViewBtn = document.getElementById('table-view-btn');
    const tableContainer = document.querySelector('.roombooktable');
    const calendarContainer = document.getElementById('calendar-container');
    const searchSection = document.querySelector('.searchsection');
    
    // Debug log
    console.log('Calendar view button:', calendarViewBtn);
    console.log('Table container:', tableContainer);
    
    if (calendarViewBtn && tableContainer) {
        // Initialize FullCalendar if not already loaded
        function initCalendar() {
            if (!window.calendarInitialized && document.getElementById('calendar')) {
                const calendarEl = document.getElementById('calendar');
                  // Calculate available height - better calculation logic
                const windowHeight = window.innerHeight;
                const searchSectionHeight = searchSection ? searchSection.offsetHeight : 60; // Default if not found
                const navbarHeight = 60; // Estimated navbar height
                const padding = 80; // Additional padding to ensure space at bottom
                const calendarHeight = windowHeight - searchSectionHeight - navbarHeight - padding;
                
                // Set height on calendar container to fit the screen
                const calendarContainer = document.getElementById('calendar-container');
                if (calendarContainer) {
                    calendarContainer.style.height = `${calendarHeight}px`;
                }                  const calendar = new FullCalendar.Calendar(calendarEl, {
                    ...calendarConfig,  // Use configuration from calendar-config.js
                    themeSystem: 'bootstrap5',
                    height: calendarHeight - 50, // Fixed height based on calculation                    events: {
                        url: 'get_booking.php',
                        method: 'GET',
                        failure: function() {
                            console.error('There was an error while fetching events!');
                        },
                    },
                    // Customize the rendering of events in list view
                    eventContent: function(arg) {
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
                    },
                    eventClick: function(info) {
                        // Display booking details when an event is clicked
                        showBookingDetails(info.event.id);
                    },                    eventDidMount: function(info) {
                        // Add tooltip with guest details
                        const tooltip = new bootstrap.Tooltip(info.el, {
                            title: `${info.event.extendedProps.guest} - ${info.event.extendedProps.roomType}`,
                            placement: 'top',
                            trigger: 'hover',
                            container: 'body'
                        });
                        
                        // Enhance list view with status indicators
                        if (info.view.type === 'listWeek') {
                            // Add status class to the event
                            if (info.event.extendedProps.status === 'Confirm') {
                                info.el.classList.add('confirm-status');
                                
                                // Add status badge to title
                                const titleEl = info.el.querySelector('.fc-list-event-title');
                                if (titleEl) {
                                    titleEl.setAttribute('data-status', 'Confirmed');
                                }
                            } else {
                                info.el.classList.add('pending-status');
                                
                                // Add status badge to title
                                const titleEl = info.el.querySelector('.fc-list-event-title');
                                if (titleEl) {
                                    titleEl.setAttribute('data-status', 'Pending');
                                }
                            }
                        }
                    },windowResize: function(view) {
                        // Recalculate height on window resize with improved calculation
                        const newWindowHeight = window.innerHeight;
                        const newSearchSectionHeight = searchSection ? searchSection.offsetHeight : 60;
                        const navbarHeight = 60;
                        const newPadding = 80;
                        const newCalendarHeight = newWindowHeight - newSearchSectionHeight - navbarHeight - newPadding;
                        
                        const container = document.getElementById('calendar-container');
                        if (container) {
                            container.style.height = `${newCalendarHeight}px`;
                        }
                        calendar.setOption('height', newCalendarHeight - 50);
                    }
                });
                  calendar.render();
                window.calendarInitialized = true;
                window.calendar = calendar;
                
                // Force data refresh and resize after rendering
                setTimeout(() => {
                    // Refresh all events from the server
                    calendar.refetchEvents();
                    
                    // Trigger resize to ensure proper dimensions
                    window.dispatchEvent(new Event('resize'));
                    
                    // Log confirmation that calendar is ready
                    console.log('Calendar fully initialized and events loaded');
                }, 300);
                
            } else if (window.calendar) {
                // If calendar exists, refresh it
                window.calendar.refetchEvents();
                
                // Trigger resize to adjust height
                window.dispatchEvent(new Event('resize'));
            }
        }
        
        // Show calendar view
        calendarViewBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Hide table, show calendar
            tableContainer.style.display = 'none';
            
            // If calendar container doesn't exist, create it
            if (!calendarContainer) {
                const container = document.createElement('div');
                container.id = 'calendar-container';
                container.className = 'calendar-container';
                container.innerHTML = `
                    <div class="card calendar-card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <h3 class="calendar-title"><i class="fas fa-calendar-alt me-2"></i>Booking Calendar</h3>
                                <div class="calendar-controls">
                                    <button id="table-view-btn" class="btn btn-outline-light btn-sm me-2">
                                        <i class="fas fa-table me-1"></i>Table View
                                    </button>
                                    <button id="filterToggle" class="btn btn-primary btn-sm">
                                        <i class="fas fa-filter me-1"></i>Filters
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="filterPanel" class="mb-4" style="display: none;">
                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <label class="form-label">Room Type</label>
                                        <select id="roomTypeFilter" class="form-select form-select-sm">
                                            <option value="">All Room Types</option>
                                            <option value="Superior Room">Superior Room</option>
                                            <option value="Deluxe Room">Deluxe Room</option>
                                            <option value="Guest House">Guest House</option>
                                            <option value="Single Room">Single Room</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Status</label>
                                        <select id="statusFilter" class="form-select form-select-sm">
                                            <option value="">All Statuses</option>
                                            <option value="Confirm">Confirmed</option>
                                            <option value="NotConfirm">Pending</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Guest Name</label>
                                        <input type="text" id="guestNameFilter" class="form-control form-control-sm" placeholder="Search by name...">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">&nbsp;</label>
                                        <button id="applyFilters" class="btn btn-primary btn-sm d-block">Apply Filters</button>
                                    </div>
                                </div>
                            </div>
                            <div id="calendar"></div>
                        </div>
                    </div>
                `;
                
                // Insert after search section
                searchSection.insertAdjacentElement('afterend', container);
                
                // Update reference to the new container and button
                window.calendarContainer = container;
                window.tableViewBtn = container.querySelector('#table-view-btn');
                
                // Add event listener for table view button
                window.tableViewBtn.addEventListener('click', function() {
                    container.style.display = 'none';
                    tableContainer.style.display = 'block';
                });
                
                // Add event listener for filter toggle
                container.querySelector('#filterToggle').addEventListener('click', function() {
                    const filterPanel = container.querySelector('#filterPanel');
                    filterPanel.style.display = filterPanel.style.display === 'none' ? 'block' : 'none';
                });
                
                // Add event listener for applying filters
                container.querySelector('#applyFilters').addEventListener('click', function() {
                    applyCalendarFilters();
                });
                  // Check if the required libraries are already loaded
                const fullCalendarLoaded = typeof FullCalendar !== 'undefined';
                const bootstrapLoaded = typeof bootstrap !== 'undefined';
                
                // Load required scripts for the calendar if needed
                if (!document.querySelector('link[href*="fullcalendar"]')) {
                    const link = document.createElement('link');
                    link.rel = 'stylesheet';
                    link.href = 'https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css';
                    document.head.appendChild(link);
                }
                
                // Check if FullCalendar is loaded, if not, load it
                if (!fullCalendarLoaded) {
                    const script = document.createElement('script');
                    script.src = 'https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js';
                    script.onload = function() {
                        // After FullCalendar loads, check if Bootstrap is loaded
                        if (!bootstrapLoaded) {
                            const bootstrapScript = document.createElement('script');
                            bootstrapScript.src = 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js';
                            bootstrapScript.onload = function() {
                                // Initialize calendar after both libraries are loaded
                                initCalendar();
                            };
                            document.body.appendChild(bootstrapScript);
                        } else {
                            initCalendar();
                        }
                    };
                    document.body.appendChild(script);
                } else if (!bootstrapLoaded) {
                    // If FullCalendar is loaded but Bootstrap isn't
                    const bootstrapScript = document.createElement('script');
                    bootstrapScript.src = 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js';
                    bootstrapScript.onload = function() {
                        initCalendar();
                    };
                    document.body.appendChild(bootstrapScript);
                } else {
                    // Both libraries are loaded, initialize calendar
                    initCalendar();
                }} else {
                // Just show the existing calendar container
                calendarContainer.style.display = 'block';
                initCalendar();
                
                // Ensure table view button works in existing container
                const existingTableViewBtn = calendarContainer.querySelector('#table-view-btn');
                if (existingTableViewBtn) {
                    existingTableViewBtn.addEventListener('click', function() {
                        calendarContainer.style.display = 'none';
                        tableContainer.style.display = 'block';
                    });
                }
            }
        });
    }
    
    // Function to apply filters to the calendar
    function applyCalendarFilters() {
        const roomTypeFilter = document.getElementById('roomTypeFilter').value;
        const statusFilter = document.getElementById('statusFilter').value;
        const guestNameFilter = document.getElementById('guestNameFilter').value.toLowerCase();
        
        if (window.calendar) {
            window.calendar.getEvents().forEach(event => {
                let visible = true;
                
                if (roomTypeFilter && event.extendedProps.roomType !== roomTypeFilter) {
                    visible = false;
                }
                
                if (statusFilter && event.extendedProps.status !== statusFilter) {
                    visible = false;
                }
                
                if (guestNameFilter && !event.title.toLowerCase().includes(guestNameFilter)) {
                    visible = false;
                }
                
                if (visible) {
                    event.setProp('display', 'auto');
                } else {
                    event.setProp('display', 'none');
                }
            });
        }
    }
    
    // Function to show booking details
    function showBookingDetails(bookingId) {
        fetch(`get_booking.php?id=${bookingId}`)
            .then(response => response.json())
            .then(data => {
                if (data) {
                    // Create and show a modal with booking details
                    const modalHtml = `
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header bg-dark text-white">
                                    <h5 class="modal-title">Booking Details</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="booking-details">
                                        <h4>${data.Name}</h4>
                                        <p><i class="fas fa-envelope me-2"></i>${data.Email}</p>
                                        <p><i class="fas fa-globe me-2"></i>${data.Country}</p>
                                        <p><i class="fas fa-phone me-2"></i>${data.Phone}</p>
                                        
                                        <hr>
                                        
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p><strong>Room Type:</strong> ${data.RoomType}</p>
                                                <p><strong>Bed Type:</strong> ${data.Bed}</p>
                                                <p><strong>No. of Rooms:</strong> ${data.NoofRoom}</p>
                                                <p><strong>Meal Plan:</strong> ${data.Meal}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <p><strong>Check-In:</strong> ${data.cin}</p>
                                                <p><strong>Check-Out:</strong> ${data.cout}</p>
                                                <p><strong>Days:</strong> ${data.nodays}</p>
                                                <p><strong>Status:</strong> 
                                                    <span class="badge ${data.stat === 'Confirm' ? 'bg-success' : 'bg-warning text-dark'}">
                                                        ${data.stat === 'Confirm' ? 'Confirmed' : 'Pending'}
                                                    </span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <a href="roombookedit.php?id=${data.id}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-edit me-1"></i>Edit
                                    </a>
                                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    `;
                    
                    // Create the modal element
                    const modalElement = document.createElement('div');
                    modalElement.className = 'modal fade';
                    modalElement.id = 'bookingDetailsModal';
                    modalElement.tabIndex = '-1';
                    modalElement.innerHTML = modalHtml;
                    
                    // Remove any existing modal
                    const existingModal = document.getElementById('bookingDetailsModal');
                    if (existingModal) {
                        existingModal.remove();
                    }
                    
                    // Add the modal to the DOM
                    document.body.appendChild(modalElement);
                    
                    // Initialize and show the modal
                    const bookingModal = new bootstrap.Modal(modalElement);
                    bookingModal.show();
                }
            })
            .catch(error => console.error('Error fetching booking details:', error));
    }
});
