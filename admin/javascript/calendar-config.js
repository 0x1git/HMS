/**
 * Calendar configuration options
 */
const calendarConfig = {
    initialView: 'listWeek',
    headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'listWeek,listMonth,dayGridMonth'
    },
    // Enhanced view configurations
    views: {
        listWeek: {
            titleFormat: { year: 'numeric', month: 'short', day: 'numeric' },
            duration: { weeks: 1 },
            buttonText: 'Week List',
            dayHeaderFormat: {
                weekday: 'long',
                month: 'long',
                day: 'numeric'
            }
        },
        listMonth: {
            titleFormat: { year: 'numeric', month: 'long' },
            duration: { months: 1 },
            buttonText: 'Month List',
            dayHeaderFormat: {
                weekday: 'short',
                month: 'long',
                day: 'numeric'
            }
        },
        dayGridMonth: {
            buttonText: 'Month Grid'
        }
    },
    views: {
        listWeek: {
            titleFormat: { year: 'numeric', month: 'short', day: 'numeric' },
            duration: { weeks: 1 },
            buttonText: 'Week List'
        },
        listMonth: {
            titleFormat: { year: 'numeric', month: 'long' },
            duration: { months: 1 },
            buttonText: 'Month List'
        },
        dayGridMonth: {
            buttonText: 'Month Grid'
        }
    },
    nowIndicator: true,
    slotMinTime: '06:00:00',
    slotMaxTime: '22:00:00',
    slotDuration: '01:00:00',
    businessHours: {
        daysOfWeek: [0, 1, 2, 3, 4, 5, 6],
        startTime: '06:00',
        endTime: '22:00',
    },
    // List view specific configurations
    listDayFormat: {
        weekday: 'long',
        month: 'long',
        day: 'numeric',
        year: 'numeric'
    },
    listDaySideFormat: {
        weekday: 'short'
    },
    noEventsText: 'No bookings found',
    displayEventEnd: true,
    eventTimeFormat: {
        hour: '2-digit',
        minute: '2-digit',
        hour12: true
    },
    windowResizeDelay: 200,
    stickyHeaderDates: true,
    showNonCurrentDates: false,
    expandRows: true,
};
