<?php
/** 
 * Get booking details via AJAX 
 * Used by the booking calendar to fetch complete booking details
 */
session_start();
include '../config.php';

// Check if booking ID is provided
if (isset($_GET['id'])) {
    $bookingId = intval($_GET['id']);
    
    // Get booking details for a specific booking
    $query = "SELECT * FROM roombook WHERE id = $bookingId";
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $booking = mysqli_fetch_assoc($result);
        echo json_encode($booking);
    } else {
        // Booking not found
        echo json_encode(['error' => 'Booking not found']);
    }
} else {
    // No booking ID provided, so return all bookings for calendar
    $query = "SELECT * FROM roombook ORDER BY cin ASC";
    $result = mysqli_query($conn, $query);
    
    $events = array();
    
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            // Format the dates properly for FullCalendar (needs YYYY-MM-DD format)
            $startDate = date('Y-m-d', strtotime($row['cin']));
            
            // Handle end date - add 1 day for proper display as FullCalendar uses exclusive end dates
            $endDate = date('Y-m-d', strtotime($row['cout'] . ' +1 day'));
              // Format the event for FullCalendar with proper coloring
            $color = ($row['stat'] == 'Confirm') ? '#2ecc71' : '#f39c12';
            $textColor = ($row['stat'] == 'Confirm') ? '#fff' : '#333';
            
            $event = array(
                'id' => $row['id'],
                'title' => $row['Name'] . ' - ' . $row['RoomType'],
                'start' => $startDate,
                'end' => $endDate, 
                'allDay' => true,
                'color' => $color,
                'textColor' => $textColor,
                'extendedProps' => array(
                    'roomType' => $row['RoomType'],
                    'bedType' => $row['Bed'],
                    'status' => $row['stat'],
                    'guest' => $row['Name'],
                    'email' => $row['Email'],
                    'country' => $row['Country'],
                    'phone' => $row['Phone'],
                    'nodays' => $row['nodays'], // Add number of days for display
                    'meal' => $row['Meal']
                )
            );
            
            $events[] = $event;
        }
    }
    
    echo json_encode($events);
}
?>
