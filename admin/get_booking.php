/** 
 * Get booking details via AJAX 
 * Used by the booking calendar to fetch complete booking details
 */
<?php
session_start();
include '../config.php';

// Check if booking ID is provided
if (isset($_GET['id'])) {
    $bookingId = intval($_GET['id']);
    
    // Get booking details
    $query = "SELECT * FROM roombook WHERE id = $bookingId";
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $booking = mysqli_fetch_assoc($result);
        
        // Format the response
        $response = array(
            'success' => true,
            'booking' => $booking
        );
    } else {
        // Booking not found
        $response = array(
            'success' => false,
            'message' => 'Booking not found'
        );
    }
} else {
    // No booking ID provided
    $response = array(
        'success' => false,
        'message' => 'No booking ID provided'
    );
}

// Send JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
