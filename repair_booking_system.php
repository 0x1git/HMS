<?php
// This script is a diagnostic and repair tool for the booking system
include 'config.php';

// Check if the repair action is requested
$action = isset($_GET['action']) ? $_GET['action'] : '';

// Initialize success message
$success_message = '';

echo "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Golden Palace HMS - Repair Tool</title>
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
        .card {
            margin-bottom: 20px;
            border-color: #eaeaea;
        }
        .card-header {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .btn-gold {
            background-color: #D4AF37;
            color: white;
            border: none;
        }
        .btn-gold:hover {
            background-color: #C5A028;
            color: white;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border-color: #c3e6cb;
            margin-bottom: 20px;
            padding: 15px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class='container'>
        <h1>Golden Palace Hotel - System Repair Tool</h1>";

// Display success message if any
if (!empty($success_message)) {
    echo "<div class='alert alert-success'>{$success_message}</div>";
}

// Database connection check
echo "<div class='card'>
        <div class='card-header'>Database Connection Check</div>
        <div class='card-body'>";

if ($conn) {
    echo "<p class='text-success'><strong>✓ Database connection established successfully.</strong></p>";
    echo "<p>Connected to MySQL server: " . mysqli_get_host_info($conn) . "</p>";
    echo "<p>Database: {$database}</p>";
} else {
    echo "<p class='text-danger'><strong>✗ Database connection failed!</strong></p>";
    echo "<p>Error: " . mysqli_connect_error() . "</p>";
    echo "<p>Please check your database settings in config.php.</p>";
}

echo "</div></div>";

// Database tables check
echo "<div class='card'>
        <div class='card-header'>Database Tables Check</div>
        <div class='card-body'>";

if ($conn) {
    $tables = array('roombook', 'room', 'payment', 'login');
    $missing_tables = array();
    
    foreach ($tables as $table) {
        $result = mysqli_query($conn, "SHOW TABLES LIKE '$table'");
        if (mysqli_num_rows($result) == 0) {
            $missing_tables[] = $table;
        }
    }
    
    if (empty($missing_tables)) {
        echo "<p class='text-success'><strong>✓ All required tables exist.</strong></p>";
    } else {
        echo "<p class='text-danger'><strong>✗ Missing tables: " . implode(', ', $missing_tables) . "</strong></p>";
        echo "<p><a href='?action=create_tables' class='btn btn-warning'>Create Missing Tables</a></p>";
    }
    
    // Check roombook table structure
    $result = mysqli_query($conn, "DESCRIBE roombook");
    if ($result) {
        echo "<h5>Roombook table structure:</h5>";
        echo "<div class='table-responsive'><table class='table table-sm table-striped'>
                <thead>
                    <tr>
                        <th>Field</th>
                        <th>Type</th>
                        <th>Null</th>
                        <th>Key</th>
                        <th>Default</th>
                        <th>Extra</th>
                    </tr>
                </thead>
                <tbody>";
                
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row['Field'] . "</td>";
            echo "<td>" . $row['Type'] . "</td>";
            echo "<td>" . $row['Null'] . "</td>";
            echo "<td>" . $row['Key'] . "</td>";
            echo "<td>" . (isset($row['Default']) ? $row['Default'] : 'NULL') . "</td>";
            echo "<td>" . $row['Extra'] . "</td>";
            echo "</tr>";
        }
        
        echo "</tbody></table></div>";
    } else {
        echo "<p class='text-danger'>Could not get roombook table structure: " . mysqli_error($conn) . "</p>";
    }
} else {
    echo "<p class='text-danger'>Cannot check tables without a database connection.</p>";
}

echo "</div></div>";

// Data check
echo "<div class='card'>
        <div class='card-header'>Data Check</div>
        <div class='card-body'>";

if ($conn) {
    $query = "SELECT COUNT(*) as count FROM roombook";
    $result = mysqli_query($conn, $query);
    
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $count = $row['count'];
        
        echo "<p><strong>Total room bookings: {$count}</strong></p>";
        
        if ($count == 0) {
            echo "<p class='text-warning'>No bookings found! This could mean either no one has booked yet, or there's an issue with form submissions.</p>";
            echo "<p><a href='?action=test_insert' class='btn btn-warning'>Test Insert Booking</a></p>";
        } else {
            echo "<p class='text-success'>✓ Bookings found in the database.</p>";
            
            // Show the most recent booking
            $query = "SELECT * FROM roombook ORDER BY id DESC LIMIT 1";
            $result = mysqli_query($conn, $query);
            
            if ($result && mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                
                echo "<h5>Most recent booking:</h5>";
                echo "<div class='table-responsive'><table class='table table-sm'>";
                foreach ($row as $key => $value) {
                    echo "<tr><th>{$key}</th><td>{$value}</td></tr>";
                }
                echo "</table></div>";
            }
        }
    } else {
        echo "<p class='text-danger'>Error checking data: " . mysqli_error($conn) . "</p>";
    }
} else {
    echo "<p class='text-danger'>Cannot check data without a database connection.</p>";
}

echo "</div></div>";

// Repair actions
if ($action == 'test_insert') {
    // Insert test data
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
    
    echo "<div class='card'>
            <div class='card-header'>Test Insertion</div>
            <div class='card-body'>";
    
    $result = mysqli_query($conn, $sql);
    
    if ($result) {
        $new_id = mysqli_insert_id($conn);
        echo "<div class='alert alert-success'>
                <strong>Success!</strong> Test booking was inserted with ID: {$new_id}.<br>
                You should now be able to see this booking in the admin panel.
              </div>";
              
        echo "<p><a href='admin/roombook.php' target='_blank' class='btn btn-gold'>Check Admin Panel</a></p>";
    } else {
        echo "<div class='alert alert-danger'>
                <strong>Error!</strong> Could not insert test booking: " . mysqli_error($conn) . "
              </div>";
    }
    
    echo "</div></div>";
} elseif ($action == 'create_tables') {
    // Create missing tables if needed
    echo "<div class='card'>
            <div class='card-header'>Creating Missing Tables</div>
            <div class='card-body'>";
    
    // Create roombook table if missing
    $result = mysqli_query($conn, "SHOW TABLES LIKE 'roombook'");
    if (mysqli_num_rows($result) == 0) {
        $sql = "CREATE TABLE roombook (
            id int(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            Name varchar(50) NOT NULL,
            Email varchar(50) NOT NULL,
            Country varchar(30) NOT NULL,
            Phone varchar(15) NOT NULL,
            RoomType varchar(30) NOT NULL,
            Bed varchar(30) NOT NULL,
            NoofRoom int(10) NOT NULL,
            Meal varchar(30) NOT NULL,
            cin date NOT NULL,
            cout date NOT NULL,
            nodays int(10) NOT NULL,
            stat varchar(30) NOT NULL
        )";
        
        if (mysqli_query($conn, $sql)) {
            echo "<p class='text-success'>Roombook table created successfully.</p>";
        } else {
            echo "<p class='text-danger'>Error creating roombook table: " . mysqli_error($conn) . "</p>";
        }
    }
    
    // Create other tables as needed
    // ...
    
    echo "</div></div>";
}

// Form handling issues check
echo "<div class='card'>
        <div class='card-header'>Form Submission Troubleshooting</div>
        <div class='card-body'>";

echo "<h5>Common issues with form submissions:</h5>
      <ol>
        <li>Form validation errors not showing messages to users</li>
        <li>Database insertion fails silently</li>
        <li>SweetAlert messages not appearing correctly</li>
        <li>Success messages showing but data not saved</li>
      </ol>

      <h5>Recommended fixes:</h5>
      <ol>
        <li>Ensure form fields have proper validation</li>
        <li>Add error handling with detailed messages</li>
        <li>Fix SweetAlert z-index issues</li>
        <li>Ensure database has proper permissions</li>
        <li>Test a booking submission manually using the form</li>
      </ol>";

echo "<a href='home.php' class='btn btn-gold'>Test Booking Form</a>";

echo "</div></div>";

// Fix recommendations
echo "<div class='card'>
        <div class='card-header'>Recommended Fixes</div>
        <div class='card-body'>";
echo "<p><a href='?action=apply_fixes' class='btn btn-primary'>Auto-Apply All Fixes</a></p>";

echo "<ul>
        <li><a href='?action=fix_form'>Fix Form Submission</a> - Fix SweetAlert z-index and form handling</li>
        <li><a href='?action=fix_admin'>Fix Admin Panel Display</a> - Ensure admin panel shows all bookings</li>
        <li><a href='?action=fix_database'>Fix Database Issues</a> - Repair any database structure problems</li>
      </ul>";

echo "</div></div>";

echo "</div>"; // Close container
echo "</body></html>";

// Close connection
mysqli_close($conn);
?>
