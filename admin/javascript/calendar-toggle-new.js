// This script handles toggling between table view and calendar view
console.log('Calendar toggle script loading...');

document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing calendar toggle...');
    
    // Get required elements
    const calendarViewBtn = document.getElementById('calendar-view-btn');
    const tableContainer = document.querySelector('.roombooktable');
    const searchSection = document.querySelector('.searchsection');

    console.log('Elements found:', {
        calendarViewBtn: !!calendarViewBtn,
        tableContainer: !!tableContainer,
        searchSection: !!searchSection
    });

    // Add click event listener to the calendar view button
    if (calendarViewBtn) {
        calendarViewBtn.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Calendar view button clicked');

            // Hide the table
            if (tableContainer) {
                tableContainer.style.display = 'none';
            }

            // Get or create calendar container
            let calendarContainer = document.getElementById('calendar-container');
            
            if (!calendarContainer) {
                console.log('Creating new calendar container');
                // Create calendar container
                calendarContainer = document.createElement('div');
                calendarContainer.id = 'calendar-container';
                calendarContainer.className = 'calendar-container';
                
                // Create calendar structure
                calendarContainer.innerHTML = `
                    <div class="card calendar-card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <h3 class="calendar-title">
                                    <i class="fas fa-calendar-alt me-2"></i>Booking Calendar
                                </h3>
                                <div class="calendar-controls">
                                    <button id="table-view-btn" class="btn btn-outline-light btn-sm">
                                        <i class="fas fa-table me-1"></i>Table View
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="calendar"></div>
                        </div>
                    </div>
                `;
                
                // Insert calendar after search section
                if (searchSection) {
                    searchSection.insertAdjacentElement('afterend', calendarContainer);
                } else {
                    document.body.appendChild(calendarContainer);
                }

                // Initialize calendar
                const calendarEl = calendarContainer.querySelector('#calendar');
                if (calendarEl && typeof FullCalendar !== 'undefined') {
                    console.log('Initializing FullCalendar...');
                    
                    // Calculate height
                    const windowHeight = window.innerHeight;
                    const searchSectionHeight = searchSection ? searchSection.offsetHeight : 60;
                    const navbarHeight = 60;
                    const padding = 40;
                    const calendarHeight = windowHeight - searchSectionHeight - navbarHeight - padding;
                    
                    // Initialize calendar with configuration
                    const calendar = new FullCalendar.Calendar(calendarEl, {
                        ...calendarConfig,
                        themeSystem: 'bootstrap5',
                        height: calendarHeight,
                        events: 'get_booking.php',
                        eventDidMount: function(info) {
                            // Add tooltip
                            new bootstrap.Tooltip(info.el, {
                                title: `${info.event.extendedProps.guest} - ${info.event.extendedProps.roomType}`,
                                placement: 'top',
                                trigger: 'hover',
                                container: 'body'
                            });
                        },
                        eventClick: function(info) {
                            showBookingDetails(info.event.id);
                        }
                    });

                    console.log('Rendering calendar...');
                    calendar.render();
                    
                    // Handle table view button click
                    const tableViewBtn = calendarContainer.querySelector('#table-view-btn');
                    if (tableViewBtn) {
                        tableViewBtn.addEventListener('click', function() {
                            calendarContainer.style.display = 'none';
                            if (tableContainer) {
                                tableContainer.style.display = 'block';
                            }
                        });
                    }
                } else {
                    console.error('FullCalendar library not loaded or calendar element not found');
                }
            } else {
                // Show existing calendar
                console.log('Showing existing calendar');
                calendarContainer.style.display = 'block';
            }
        });
    } else {
        console.error('Calendar view button not found');
    }

    // Function to show booking details
    window.showBookingDetails = function(bookingId) {
        fetch(`get_booking.php?id=${bookingId}`)
            .then(response => response.json())
            .then(data => {
                if (data) {
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
                    
                    const modalElement = document.createElement('div');
                    modalElement.className = 'modal fade';
                    modalElement.id = 'bookingDetailsModal';
                    modalElement.tabIndex = '-1';
                    modalElement.innerHTML = modalHtml;
                    
                    const existingModal = document.getElementById('bookingDetailsModal');
                    if (existingModal) {
                        existingModal.remove();
                    }
                    
                    document.body.appendChild(modalElement);
                    const bookingModal = new bootstrap.Modal(modalElement);
                    bookingModal.show();
                }
            })
            .catch(error => console.error('Error fetching booking details:', error));
    };
});
