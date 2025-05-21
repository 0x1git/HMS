<?php
// This script automatically fixes common issues with the booking system
include 'config.php';

// Initialize variables
$fixes_applied = [];
$errors = [];

echo "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Golden Palace HMS - Auto Fix Tool</title>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css' rel='stylesheet'>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .container {
            max-width: 1000px;
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
        .fixed-item {
            padding: 10px;
            margin-bottom: 10px;
            border-left: 4px solid #28a745;
            background-color: #f8f9fa;
        }
        .error-item {
            padding: 10px;
            margin-bottom: 10px;
            border-left: 4px solid #dc3545;
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>
    <div class='container'>
        <h1>Golden Palace Hotel - Auto Fix Tool</h1>";

// 1. Fix: Ensure the SweetAlert theme CSS file exists
$sweetalert_css_path = "css/sweetalert-theme.css";
if (!file_exists($sweetalert_css_path)) {
    $sweetalert_css_content = "/* Enhanced SweetAlert Styling for Hotel Golden Palace */
.swal-overlay {
    z-index: 9999 !important; /* Ensure it's above all other elements */
    background-color: rgba(0, 0, 0, 0.7); /* Darken the background for better visibility */
}

.swal-modal {
    z-index: 10000 !important; /* Higher than the overlay */
    animation: swal-zoom 0.3s ease-in-out; /* Add animation */
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3); /* Enhanced shadow for depth */
    border-radius: 8px; /* Rounded corners */
    background: linear-gradient(135deg, #2e2e2e, #1a1a1a);
    border: 1px solid #D4AF37;
}

.swal-title {
    font-family: 'Playfair Display', serif;
    color: #D4AF37 !important;
    font-weight: 600;
    margin-bottom: 20px !important;
}

.swal-text {
    font-family: 'Poppins', sans-serif;
    color: #fff !important;
    font-size: 16px;
}

.swal-button {
    font-family: 'Poppins', sans-serif;
    font-weight: 500;
    border-radius: 5px;
    padding: 10px 25px;
    transition: all 0.3s ease;
}

.swal-button--confirm {
    background: linear-gradient(to right, #C5A028, #D4AF37) !important;
    color: #333 !important;
}

.swal-button--confirm:hover {
    background: linear-gradient(to right, #D4AF37, #F4E4BC) !important;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(212, 175, 55, 0.3);
}

.swal-icon--success {
    border-color: #D4AF37 !important;
}

.swal-icon--success__line {
    background-color: #D4AF37 !important;
}

.swal-icon--success__ring {
    border-color: #D4AF37 !important;
}

.swal-icon--warning {
    border-color: #F8BB86;
}

.swal-icon--error {
    border-color: #F27474;
}

.swal-icon--error__line {
    background-color: #F27474;
}

/* Animation for sweet alerts */
@keyframes swal-zoom {
    0% {
        transform: scale(0.7);
        opacity: 0;
    }
    100% {
        transform: scale(1);
        opacity: 1;
    }
}";

    if (file_put_contents($sweetalert_css_path, $sweetalert_css_content)) {
        $fixes_applied[] = "Created SweetAlert theme CSS file: $sweetalert_css_path";
    } else {
        $errors[] = "Failed to create SweetAlert theme CSS file";
    }
} else {
    $fixes_applied[] = "SweetAlert theme CSS file already exists";
}

// 2. Fix: Update home.php to include the SweetAlert theme CSS
$home_php_path = "home.php";
if (file_exists($home_php_path)) {
    $home_content = file_get_contents($home_php_path);
    
    // Check if the SweetAlert theme is already included
    if (strpos($home_content, 'sweetalert-theme.css') === false) {
        $pattern = '/<script src="https:\/\/unpkg.com\/sweetalert\/dist\/sweetalert.min.js"><\/script>(\s*)<link rel="stylesheet" href="\.\/admin\/css\/roombook.css">/';
        $replacement = '<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>$1<link rel="stylesheet" href="./admin/css/roombook.css">$1<link rel="stylesheet" href="./css/sweetalert-theme.css">';
        
        $home_content = preg_replace($pattern, $replacement, $home_content);
        
        if (file_put_contents($home_php_path, $home_content)) {
            $fixes_applied[] = "Added SweetAlert theme CSS to home.php";
        } else {
            $errors[] = "Failed to update home.php with SweetAlert theme CSS";
        }
    } else {
        $fixes_applied[] = "SweetAlert theme CSS is already included in home.php";
    }
} else {
    $errors[] = "Could not find home.php";
}

// 3. Fix: Update the form submission script to ensure SweetAlert appears correctly
if (file_exists($home_php_path)) {
    $home_content = file_get_contents($home_php_path);
    
    // Look for the existing success message code
    $success_pattern = '/if\s*\(\$result\)\s*\{\s*\/\/\s*Clear\s*form\s*data[^}]*}\s*else\s*\{/s';
    
    // Prepare the replacement success message code
    $success_replacement = 'if ($result) {
        // Get the new booking ID
        $newBookingId = mysqli_insert_id($conn);
        
        // Clear form data to prevent resubmission
        $_POST = array();
        
        // Close booking panel first to avoid layers
        echo "<script>
            // First hide the booking panel
            document.getElementById(\'guestdetailpanel\').style.display = \'none\';
            document.body.style.overflow = \'auto\';
            
            // Then show the success message
            setTimeout(function() {
                swal({
                    title: \'Reservation Successful!\',
                    text: \'Thank you for booking with Golden Palace Hotel. Your booking ID is: \' + \'' . $newBookingId . '\',
                    icon: \'success\',
                    buttons: {
                        confirm: {
                            text: \'OK\',
                            value: true,
                            visible: true,
                            className: \'btn btn-success\',
                            closeModal: true
                        }
                    }
                }).then((value) => {
                    if (value) {
                        // Refresh page with clean URL to avoid form resubmission
                        window.location.href = \'home.php#secondsection\';
                    }
                });
                
                // Ensure SweetAlert appears on top
                var swalOverlay = document.querySelector(\'.swal-overlay\');
                var swalModal = document.querySelector(\'.swal-modal\');
                if (swalOverlay) swalOverlay.style.zIndex = \'9999\';
                if (swalModal) swalModal.style.zIndex = \'10000\';
            }, 300);
        </script>";
    } else {';
    
    // Only replace if we can find the pattern
    if (preg_match($success_pattern, $home_content)) {
        $home_content = preg_replace($success_pattern, $success_replacement, $home_content);
        
        if (file_put_contents($home_php_path, $home_content)) {
            $fixes_applied[] = "Updated success message handling in home.php";
        } else {
            $errors[] = "Failed to update success message handling in home.php";
        }
    } else {
        $errors[] = "Could not locate success message pattern in home.php";
    }
}

// 4. Fix: Create a direct SQL display for bookings in admin panel
$admin_display_path = "admin/direct_bookings.php";
$admin_display_content = '<?php
// Direct SQL query to display all bookings regardless of filters
include "../config.php";

// Force a new database connection
if (isset($conn)) mysqli_close($conn);
$conn = mysqli_connect($server, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get all bookings, newest first
$sql = "SELECT * FROM roombook ORDER BY id DESC";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Bookings - Direct Query</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { padding: 20px; font-family: sans-serif; }
        h1 { margin-bottom: 20px; color: #D4AF37; }
    </style>
</head>
<body>
    <div class="container">
        <h1>All Room Bookings (Direct Query)</h1>
        <?php if ($result && mysqli_num_rows($result) > 0): ?>
            <p class="alert alert-success">Found <?php echo mysqli_num_rows($result); ?> bookings</p>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Room Type</th>
                            <th>Check-in</th>
                            <th>Check-out</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo $row["id"]; ?></td>
                            <td><?php echo $row["Name"]; ?></td>
                            <td><?php echo $row["Email"]; ?></td>
                            <td><?php echo $row["RoomType"]; ?></td>
                            <td><?php echo $row["cin"]; ?></td>
                            <td><?php echo $row["cout"]; ?></td>
                            <td><?php echo $row["stat"]; ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="alert alert-danger">
                No bookings found! <?php if (!$result) echo "Error: " . mysqli_error($conn); ?>
            </p>
        <?php endif; ?>
        
        <p><a href="roombook.php" class="btn btn-primary">Back to Admin Panel</a></p>
        
        <div class="card mt-4">
            <div class="card-header">Debug Info</div>
            <div class="card-body">
                <p><strong>SQL Query:</strong> <?php echo $sql; ?></p>
                <p><strong>Connection Info:</strong> <?php echo mysqli_get_host_info($conn); ?></p>
                <p><strong>Database:</strong> <?php echo $database; ?></p>
            </div>
        </div>
    </div>
</body>
</html>';

if (file_put_contents($admin_display_path, $admin_display_content)) {
    $fixes_applied[] = "Created direct SQL display page: admin/direct_bookings.php";
} else {
    $errors[] = "Failed to create direct SQL display page";
}

// 5. Fix: Create a test booking if the table is empty
$check_sql = "SELECT COUNT(*) FROM roombook";
$check_result = mysqli_query($conn, $check_sql);

if ($check_result) {
    $row = mysqli_fetch_array($check_result);
    $count = $row[0];
    
    if ($count == 0) {
        // Add a test booking
        $testName = "Test User " . rand(1000, 9999);
        $testEmail = "test" . rand(100, 999) . "@example.com";
        $testCountry = "United States";
        $testPhone = "555-" . rand(100, 999) . "-" . rand(1000, 9999);
        $testRoomType = "Deluxe Room";
        $testBed = "Double";
        $testNoofRoom = "1";
        $testMeal = "Breakfast";
        $testCin = date('Y-m-d');
        $testCout = date('Y-m-d', strtotime('+3 days'));
        $testSta = "NotConfirm";
        
        // Calculate days
        $date1 = new DateTime($testCin);
        $date2 = new DateTime($testCout);
        $interval = $date1->diff($date2);
        $testNodays = $interval->days;
        
        $sql = "INSERT INTO roombook(Name, Email, Country, Phone, RoomType, Bed, NoofRoom, Meal, cin, cout, stat, nodays) 
                VALUES ('$testName', '$testEmail', '$testCountry', '$testPhone', '$testRoomType', '$testBed', '$testNoofRoom', 
                        '$testMeal', '$testCin', '$testCout', '$testSta', '$testNodays')";
        
        if (mysqli_query($conn, $sql)) {
            $new_id = mysqli_insert_id($conn);
            $fixes_applied[] = "Added test booking with ID: $new_id";
        } else {
            $errors[] = "Failed to add test booking: " . mysqli_error($conn);
        }
    } else {
        $fixes_applied[] = "Bookings already exist in database ($count found)";
    }
} else {
    $errors[] = "Failed to check for existing bookings: " . mysqli_error($conn);
}

// 6. Fix: Update the roombook.php file to ensure proper data loading
$roombook_php_path = "admin/roombook.php";
if (file_exists($roombook_php_path)) {
    $roombook_content = file_get_contents($roombook_php_path);
    
    // Add a debugging link in the admin interface
    $nav_pattern = '/<div class="button-group">/';
    $nav_replacement = '<div class="button-group">
            <a href="direct_bookings.php" class="btn btn-info btn-sm me-2"><i class="fas fa-database me-1"></i>View All Bookings</a>';
    
    if (preg_match($nav_pattern, $roombook_content)) {
        $roombook_content = preg_replace($nav_pattern, $nav_replacement, $roombook_content);
        
        if (file_put_contents($roombook_php_path, $roombook_content)) {
            $fixes_applied[] = "Added direct bookings view link to admin panel";
        } else {
            $errors[] = "Failed to update admin panel with direct bookings link";
        }
    } else {
        $errors[] = "Could not locate navigation pattern in roombook.php";
    }
}

// Display results
echo "<h2>Fixes Applied:</h2>";
if (!empty($fixes_applied)) {
    foreach ($fixes_applied as $fix) {
        echo "<div class='fixed-item'>✓ $fix</div>";
    }
} else {
    echo "<p>No fixes were applied.</p>";
}

echo "<h2>Errors:</h2>";
if (!empty($errors)) {
    foreach ($errors as $error) {
        echo "<div class='error-item'>✗ $error</div>";
    }
} else {
    echo "<p>No errors encountered.</p>";
}

echo "<h2>Next Steps:</h2>
<ol>
    <li>Test the booking form on <a href='home.php'>home page</a> to verify success messages appear</li>
    <li>Check if bookings appear in <a href='admin/roombook.php'>admin panel</a></li>
    <li>Use the <a href='admin/direct_bookings.php'>direct bookings view</a> to verify all bookings</li>
    <li>If issues persist, check the <a href='repair_booking_system.php'>repair tool</a> for further diagnostics</li>
</ol>";

echo "<a href='index.php' class='btn btn-primary'>Back to Login</a>";

echo "</div>"; // Close container
echo "</body></html>";

mysqli_close($conn);
?>
