<?php
// This is a diagnostic tool to check the roombook table and query from the admin side
include '../config.php';

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

echo "<h1>Room Booking Admin Diagnostic</h1>";

// Check if the roombook table exists
$tableCheck = mysqli_query($conn, "SHOW TABLES LIKE 'roombook'");
$tableExists = mysqli_num_rows($tableCheck) > 0;

echo "<h2>1. Table Check</h2>";
echo $tableExists ? 
    "<p style='color:green'>The 'roombook' table exists.</p>" : 
    "<p style='color:red'>Error: The 'roombook' table does not exist!</p>";

if ($tableExists) {
    // Show sample query
    $query = "SELECT * FROM roombook ORDER BY id DESC LIMIT 10";
    echo "<h2>2. Query Test</h2>";
    echo "<p>Testing query: <code>" . $query . "</code></p>";
    
    $result = mysqli_query($conn, $query);
    if ($result) {
        $numRows = mysqli_num_rows($result);
        echo "<p>Query executed successfully. Found <strong>" . $numRows . "</strong> rows.</p>";
        
        if ($numRows > 0) {
            echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
            echo "<tr>";
            $fields = mysqli_fetch_fields($result);
            foreach ($fields as $field) {
                echo "<th style='padding: 8px; background-color: #f2f2f2;'>" . $field->name . "</th>";
            }
            echo "</tr>";
            
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                foreach ($row as $value) {
                    echo "<td style='padding: 8px; border: 1px solid #ddd;'>" . $value . "</td>";
                }
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p style='color:orange'>No records found in the roombook table. This could be normal if no bookings have been made.</p>";
        }
    } else {
        echo "<p style='color:red'>Error executing query: " . mysqli_error($conn) . "</p>";
    }
    
    // Check for potential issues
    echo "<h2>3. Troubleshooting</h2>";
    
    // Check if the connection is being cached/reused
    echo "<p><strong>Connection Status:</strong> " . (mysqli_ping($conn) ? "Active" : "Inactive") . "</p>";
    
    // Check permissions
    $userInfo = mysqli_query($conn, "SELECT CURRENT_USER()");
    $user = mysqli_fetch_array($userInfo)[0];
    echo "<p><strong>Current MySQL User:</strong> " . $user . "</p>";
    
    // Check for column mismatches
    echo "<p><strong>Column Structure:</strong></p>";
    $columns = mysqli_query($conn, "DESCRIBE roombook");
    if ($columns) {
        echo "<table border='1' style='border-collapse: collapse;'>";
        echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
        while($col = mysqli_fetch_assoc($columns)) {
            echo "<tr>";
            echo "<td>".$col['Field']."</td>";
            echo "<td>".$col['Type']."</td>";
            echo "<td>".$col['Null']."</td>";
            echo "<td>".$col['Key']."</td>";
            echo "<td>".$col['Default']."</td>";
            echo "<td>".$col['Extra']."</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p style='color:red'>Error retrieving column information: " . mysqli_error($conn) . "</p>";
    }
}

// Provide some recommendations
echo "<h2>4. Recommendations</h2>";
echo "<ol>";
echo "<li>Ensure database connection parameters are the same in both home.php and admin files</li>";
echo "<li>Verify that the roombook table exists and has the correct structure</li>";
echo "<li>Check for any custom filters in the admin page that might be hiding some entries</li>";
echo "<li>Verify that form submissions are working correctly by checking recent entries</li>";
echo "<li>Ensure that database credentials have sufficient privileges</li>";
echo "</ol>";

// Add a test insert form
echo "<h2>5. Test Insert from Admin</h2>";
echo "<form method='POST'>";
echo "<button type='submit' name='admin_test_insert' style='padding: 10px; background-color: #4CAF50; color: white; border: none; cursor: pointer;'>Run Test Insert</button>";
echo "</form>";

if (isset($_POST['admin_test_insert'])) {
    // Test data for a booking
    $testName = "Admin Test " . rand(1000, 9999);
    $testEmail = "admin" . rand(100, 999) . "@test.com";
    $testCountry = "Admin Country";
    $testPhone = "555-123-" . rand(1000, 9999);
    $testRoomType = "Superior Room";
    $testBed = "Single";
    $testNoofRoom = "1";
    $testMeal = "Full Board";
    $testCin = date('Y-m-d');
    $testCout = date('Y-m-d', strtotime('+5 days'));
    $testSta = "NotConfirm";
    
    // Calculate days
    $date1 = new DateTime($testCin);
    $date2 = new DateTime($testCout);
    $interval = $date1->diff($date2);
    $testNodays = $interval->days;
    
    $sql = "INSERT INTO roombook(Name,Email,Country,Phone,RoomType,Bed,NoofRoom,Meal,cin,cout,stat,nodays) 
            VALUES ('$testName','$testEmail','$testCountry','$testPhone','$testRoomType','$testBed',
                    '$testNoofRoom','$testMeal','$testCin','$testCout','$testSta','$testNodays')";
    
    $result = mysqli_query($conn, $sql);
    
    if ($result) {
        $newId = mysqli_insert_id($conn);
        echo "<div style='background-color: #dff0d8; border: 1px solid #d6e9c6; padding: 15px; margin-top: 15px;'>";
        echo "<p style='color: #3c763d;'><strong>Test insert successful!</strong> New record created with ID: $newId</p>";
        echo "<p>Now check if this record appears in the <a href='roombook.php' target='_blank'>roombook.php</a> admin page.</p>";
        echo "</div>";
    } else {
        echo "<div style='background-color: #f2dede; border: 1px solid #ebccd1; padding: 15px; margin-top: 15px;'>";
        echo "<p style='color: #a94442;'><strong>Error:</strong> " . mysqli_error($conn) . "</p>";
        echo "</div>";
    }
}

mysqli_close($conn);
?>

<div style="margin-top: 20px; border-top: 1px solid #ccc; padding-top: 10px;">
    <p><a href="roombook.php">&laquo; Back to Room Booking Admin</a></p>
</div>
