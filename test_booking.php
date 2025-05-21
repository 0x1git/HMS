<?php
// This script is used to diagnose booking form issues
include 'config.php';

// Check database connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

echo "<h1>Golden Palace HMS - Booking System Diagnostic</h1>";

// Check roombook table structure
echo "<h2>1. Roombook Table Structure</h2>";
$result = mysqli_query($conn, "DESCRIBE roombook");
if ($result) {
    echo "<table border='1'><tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    while($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>".$row['Field']."</td>";
        echo "<td>".$row['Type']."</td>";
        echo "<td>".$row['Null']."</td>";
        echo "<td>".$row['Key']."</td>";
        echo "<td>".$row['Default']."</td>";
        echo "<td>".$row['Extra']."</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "Error getting table structure: " . mysqli_error($conn);
}

// Check for booking data
echo "<h2>2. Recent Bookings (Last 10)</h2>";
$result = mysqli_query($conn, "SELECT * FROM roombook ORDER BY id DESC LIMIT 10");
if ($result) {
    if (mysqli_num_rows($result) > 0) {
        echo "<table border='1'>";
        
        // Print table headers
        $fields = mysqli_fetch_fields($result);
        echo "<tr>";
        foreach ($fields as $field) {
            echo "<th>".$field->name."</th>";
        }
        echo "</tr>";
        
        // Print data rows
        mysqli_data_seek($result, 0);
        while($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            foreach($row as $key => $value) {
                echo "<td>".$value."</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<div style='color: red; font-weight: bold;'>No bookings found in the database! This indicates that form submissions are not being saved.</div>";
    }
} else {
    echo "Error querying recent bookings: " . mysqli_error($conn);
}

// Test booking submission function
echo "<h2>3. Test Booking Form Submission</h2>";
echo "<form method='POST'>";
echo "<button type='submit' name='test_submission' style='background-color: #4CAF50; color: white; padding: 10px 15px; border: none; cursor: pointer;'>Run Test Booking Submission</button>";
echo "</form>";

if (isset($_POST['test_submission'])) {
    // Test data for a booking
    $testName = "Test User " . rand(1000, 9999);
    $testEmail = "test" . rand(100, 999) . "@example.com";
    $testCountry = "United States";
    $testPhone = "123-456-" . rand(1000, 9999);
    $testRoomType = "Deluxe Room";
    $testBed = "Double";
    $testNoofRoom = "1";
    $testMeal = "Breakfast";
    $testCin = date('Y-m-d');
    $testCout = date('Y-m-d', strtotime('+3 days'));
    $testSta = "NotConfirm";
    
    // Calculate days between dates
    $date1 = new DateTime($testCin);
    $date2 = new DateTime($testCout);
    $interval = $date1->diff($date2);
    $testNodays = $interval->days;
    
    // Create and execute test query
    $testSql = "INSERT INTO roombook(Name, Email, Country, Phone, RoomType, Bed, NoofRoom, Meal, cin, cout, stat, nodays) 
                VALUES ('$testName', '$testEmail', '$testCountry', '$testPhone', '$testRoomType', '$testBed', '$testNoofRoom', 
                        '$testMeal', '$testCin', '$testCout', '$testSta', '$testNodays')";
    
    echo "<div style='background-color: #f0f0f0; padding: 10px; margin-top: 10px;'>";
    echo "<p><strong>Test SQL Query:</strong> " . htmlspecialchars($testSql) . "</p>";
    
    $testResult = mysqli_query($conn, $testSql);
    
    if ($testResult) {
        $newId = mysqli_insert_id($conn);
        echo "<p style='color: green;'><strong>Test submission successful!</strong> New record ID: $newId</p>";
        
        // Verify the record was saved
        $verifyQuery = "SELECT * FROM roombook WHERE id = $newId";
        $verifyResult = mysqli_query($conn, $verifyQuery);
        
        if ($verifyResult && mysqli_num_rows($verifyResult) > 0) {
            $row = mysqli_fetch_assoc($verifyResult);
            echo "<p><strong>Verification - New record data:</strong></p>";
            echo "<table border='1'>";
            foreach ($row as $key => $value) {
                echo "<tr><td><strong>$key</strong></td><td>$value</td></tr>";
            }
            echo "</table>";
        } else {
            echo "<p style='color: red;'><strong>Error:</strong> Could not verify the new record!</p>";
        }
    } else {
        echo "<p style='color: red;'><strong>Error submitting test booking:</strong> " . mysqli_error($conn) . "</p>";
    }
    echo "</div>";
}

// Check for data consistency
echo "<h2>4. Data Consistency Check</h2>";
$countQuery = "SELECT COUNT(*) as total FROM roombook";
$countResult = mysqli_query($conn, $countQuery);

if ($countResult) {
    $countRow = mysqli_fetch_assoc($countResult);
    $totalBookings = $countRow['total'];
    echo "<p>Total bookings in database: <strong>$totalBookings</strong></p>";
    
    if ($totalBookings == 0) {
        echo "<p style='color: red;'><strong>Warning:</strong> No bookings found in the database.</p>";
        echo "<p>Possible issues:</p>";
        echo "<ul>";
        echo "<li>Form submissions are not being processed</li>";
        echo "<li>SQL queries have syntax errors</li>";
        echo "<li>Database permissions issue</li>";
        echo "</ul>";
    } else {
        echo "<p style='color: green;'>Data exists in the bookings table.</p>";
    }
} else {
    echo "<p style='color: red;'><strong>Error:</strong> " . mysqli_error($conn) . "</p>";
}

// Additional checks
echo "<h2>5. Connection and Environment Info</h2>";
echo "<p><strong>PHP Version:</strong> " . phpversion() . "</p>";
echo "<p><strong>MySQL Version:</strong> " . mysqli_get_server_info($conn) . "</p>";
echo "<p><strong>Database Name:</strong> " . $database . "</p>";

// Check booking visibility in admin panel
echo "<h2>6. Admin Panel Visibility Check</h2>";
echo "<p>To check if bookings are correctly displayed in the admin panel:</p>";
echo "<ol>";
echo "<li>Run this test to add a sample booking</li>";
echo "<li>Navigate to <a href='admin/roombook.php' target='_blank'>admin/roombook.php</a></li>";
echo "<li>Verify the test booking appears in the table</li>";
echo "</ol>";
echo "<p>If the test booking shows in this diagnostic but not in the admin panel, there may be an issue with the query in the admin panel.</p>";

// Close the connection
mysqli_close($conn);
?>