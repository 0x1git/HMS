// Calendar toggle and initialization script
console.log('Calendar toggle script loading...');

document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing calendar toggle...');
    
    // Get required elements
    const calendarViewBtn = document.getElementById('calendar-view-btn');
    const tableContainer = document.querySelector('.roombooktable');
    const searchSection = document.querySelector('.searchsection');
    let calendar = null;

    console.log('Elements found:', {
        calendarViewBtn: !!calendarViewBtn,
        tableContainer: !!tableContainer,
        searchSection: !!searchSection
    });

    // Initialize view state
    let isCalendarView = false;

    // Add click event listener to the calendar view button
    if (calendarViewBtn) {
        calendarViewBtn.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Calendar view button clicked');

            if (!isCalendarView) {
                // Switch to calendar view
                showCalendarView();
                calendarViewBtn.textContent = 'Table View';
            } else {
                // Switch back to table view
                showTableView();
                calendarViewBtn.textContent = 'Calendar View';
            }
            
            isCalendarView = !isCalendarView;
        });
    }

    function showCalendarView() {
        console.log('Switching to calendar view');
        
        // Hide the table with fade effect
        if (tableContainer) {
            tableContainer.style.opacity = '0';
            setTimeout(() => {
                tableContainer.style.display = 'none';
                // Show calendar
                initializeCalendar();
            }, 300);
        }
    }

    function showTableView() {
        console.log('Switching to table view');
        
        // Hide calendar
        const calendarContainer = document.getElementById('calendar-container');
        if (calendarContainer) {
            calendarContainer.style.opacity = '0';
            setTimeout(() => {
                if (calendar) {
                    calendar.destroy();
                    calendar = null;
                }
                calendarContainer.remove();
                
                // Show table with fade effect
                if (tableContainer) {
                    tableContainer.style.display = '';
                    setTimeout(() => {
                        tableContainer.style.opacity = '1';
                    }, 50);
                }
            }, 300);
        }
    }

    function initializeCalendar() {
        console.log('Initializing calendar');
        
        // Get or create calendar container
        let calendarContainer = document.getElementById('calendar-container');
        if (!calendarContainer) {
            calendarContainer = document.createElement('div');
            calendarContainer.id = 'calendar-container';
            if (tableContainer && tableContainer.parentNode) {
                tableContainer.parentNode.insertBefore(calendarContainer, tableContainer);
            }
        }

        // Initialize FullCalendar
        calendar = new FullCalendar.Calendar(calendarContainer, {
            ...calendarConfig,
            events: function(fetchInfo, successCallback, failureCallback) {
                console.log('Fetching events...', fetchInfo);
                // Fetch events from the server
                fetch('get_booking.php')
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Received event data:', data);
                        if (!Array.isArray(data)) {
                            console.error('Expected array of events but got:', typeof data);
                            data = [];
                        }
                        
                        const events = data.map(booking => {
                            console.log('Processing booking:', booking);
                            return {
                                id: booking.id,
                                title: `${booking.title || booking.Name}`,
                                start: booking.start || booking.cin,
                                end: booking.end || booking.cout,
                                allDay: true,
                                color: booking.color,
                                textColor: booking.textColor,
                                extendedProps: booking.extendedProps || {
                                    guest: booking.Name,
                                    roomType: booking.RoomType || booking.TRoom,
                                    roomno: booking.roomno,
                                    status: booking.stat,
                                    bedType: booking.Bed,
                                    meal: booking.Meal,
                                    nodays: booking.nodays
                                },
                                className: (booking.stat || booking.status || '').toLowerCase()
                            };
                        });
                        
                        console.log('Processed events:', events);
                        successCallback(events);
                    })
                    .catch(error => {
                        console.error('Error fetching events:', error);
                        failureCallback(error);
                        // Show error message to user
                        const errorDiv = document.createElement('div');
                        errorDiv.className = 'calendar-error';
                        errorDiv.textContent = 'Error loading events. Please try refreshing the page.';
                        calendarContainer.prepend(errorDiv);
                    });
            }
        });

        // Render calendar with fade in effect
        calendar.render();
        setTimeout(() => {
            calendarContainer.style.opacity = '1';
        }, 50);
    }
});
