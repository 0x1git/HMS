<?php
// This script simulates a booking form submission to test if the system is working
include 'config.php';

// Check database connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

echo "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Golden Palace HMS - Test Booking</title>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css' rel='stylesheet'>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        h1 {
            color: #D4AF37;
            text-align: center;
            margin-bottom: 30px;
        }
        .result-section {
            margin-top: 30px;
            padding: 20px;
            border-radius: 5px;
        }
        .success {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }
        .error {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }
        pre {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            overflow-x: auto;
        }
    </style>
</head>
<body>
    <div class='container'>
        <h1>Golden Palace Hotel - Test Booking</h1>";

// Set up test booking data
$name = "Test User " . date('YmdHis');
$email = "testuser" . date('YmdHis') . "@example.com";
$country = "United States";
$phone = "123-456-7890";
$roomType = "Superior Room";
$bed = "Double";
$noOfRoom = "1";
$meal = "Breakfast";
$cin = date('Y-m-d'); // Today
$cout = date('Y-m-d', strtotime('+3 days')); // 3 days from today
$status = "NotConfirm";

// Calculate days difference
$date1 = new DateTime($cin);
$date2 = new DateTime($cout);
$interval = $date1->diff($date2);
$nodays = $interval->days;

echo "<h3>Test Booking Data:</h3>
<pre>
Name: $name
Email: $email
Country: $country
Phone: $phone
Room Type: $roomType
Bed: $bed
Number of Rooms: $noOfRoom
Meal: $meal
Check-in: $cin
Check-out: $cout
Number of Days: $nodays
Status: $status
</pre>";

// Sanitize input
$name = mysqli_real_escape_string($conn, $name);
$email = mysqli_real_escape_string($conn, $email);
$country = mysqli_real_escape_string($conn, $country);
$phone = mysqli_real_escape_string($conn, $phone);
$roomType = mysqli_real_escape_string($conn, $roomType);
$bed = mysqli_real_escape_string($conn, $bed);
$noOfRoom = mysqli_real_escape_string($conn, $noOfRoom);
$meal = mysqli_real_escape_string($conn, $meal);
$cin = mysqli_real_escape_string($conn, $cin);
$cout = mysqli_real_escape_string($conn, $cout);
$status = mysqli_real_escape_string($conn, $status);

// Insert booking
$sql = "INSERT INTO roombook(Name, Email, Country, Phone, RoomType, Bed, NoofRoom, Meal, cin, cout, stat, nodays) 
        VALUES ('$name', '$email', '$country', '$phone', '$roomType', '$bed', '$noOfRoom', '$meal', '$cin', '$cout', '$status', '$nodays')";

$result = mysqli_query($conn, $sql);

// Display result
if ($result) {
    $booking_id = mysqli_insert_id($conn);
    echo "<div class='result-section success'>
            <h3>✅ Test Booking Created Successfully!</h3>
            <p>Booking ID: <strong>$booking_id</strong></p>
            <p>This confirms that the booking form insert logic is working correctly.</p>
          </div>";
    
    // Verify the booking can be retrieved
    $check_sql = "SELECT * FROM roombook WHERE id = $booking_id";
    $check_result = mysqli_query($conn, $check_sql);
    
    if ($check_result && mysqli_num_rows($check_result) > 0) {
        $row = mysqli_fetch_assoc($check_result);
        echo "<h3>Database Verification:</h3>
              <p>✅ Successfully retrieved the booking from database.</p>
              <pre>";
        print_r($row);
        echo "</pre>";
    } else {
        echo "<div class='result-section error'>
                <h3>⚠️ Warning</h3>
                <p>Could not verify the booking in the database.</p>
                <p>Error: " . mysqli_error($conn) . "</p>
              </div>";
    }
} else {
    echo "<div class='result-section error'>
            <h3>❌ Test Booking Failed</h3>
            <p>Error: " . mysqli_error($conn) . "</p>
            <p>SQL Query: $sql</p>
          </div>";
}

echo "<div class='mt-4'>
        <a href='test_booking.php' class='btn btn-primary'>View All Bookings</a>
        <a href='home.php' class='btn btn-secondary ms-2'>Back to Home</a>
        <a href='admin/display_bookings.php' class='btn btn-info ms-2'>View Admin Bookings</a>
      </div>
    </div>
</body>
</html>";
?>
