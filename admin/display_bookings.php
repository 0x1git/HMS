<?php
// This fixes table display issues in the roombook.php admin page

// Force a new database connection each time to avoid stale connections
if (isset($conn)) {
    mysqli_close($conn);
}

// Load connection parameters
include '../config.php';

// Ensure connection is active
if (!$conn || !mysqli_ping($conn)) {
    $conn = mysqli_connect($server, $username, $password, $database);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
}

// Query for all room bookings, ordered by most recent first
$sql = "SELECT * FROM roombook ORDER BY id DESC";
$result = mysqli_query($conn, $sql);

if (!$result) {
    echo "Error loading bookings: " . mysqli_error($conn);
    exit;
}

$totalBookings = mysqli_num_rows($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Booking Data</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding: 20px;
            font-family: 'Poppins', sans-serif;
        }
        h1 {
            margin-bottom: 20px;
        }
        .alert {
            margin-bottom: 20px;
        }
        .table-responsive {
            margin-bottom: 30px;
        }
        .actions {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mb-4">Room Booking Data (Direct Query)</h1>
        
        <div class="alert alert-info">
            <strong>Total bookings found:</strong> <?php echo $totalBookings; ?>
        </div>
        
        <?php if ($totalBookings == 0): ?>
            <div class="alert alert-warning">
                <strong>No bookings found!</strong> This suggests there may be an issue with the booking form submission or database setup.
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Country</th>
                            <th>Phone</th>
                            <th>Room Type</th>
                            <th>Bed</th>
                            <th>Rooms</th>
                            <th>Meal</th>
                            <th>Check-in</th>
                            <th>Check-out</th>
                            <th>Days</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo $row['Name']; ?></td>
                                <td><?php echo $row['Email']; ?></td>
                                <td><?php echo $row['Country']; ?></td>
                                <td><?php echo $row['Phone']; ?></td>
                                <td><?php echo $row['RoomType']; ?></td>
                                <td><?php echo $row['Bed']; ?></td>
                                <td><?php echo $row['NoofRoom']; ?></td>
                                <td><?php echo $row['Meal']; ?></td>
                                <td><?php echo $row['cin']; ?></td>
                                <td><?php echo $row['cout']; ?></td>
                                <td><?php echo $row['nodays']; ?></td>
                                <td><?php echo $row['stat']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
        
        <div class="actions">
            <h3>Actions:</h3>
            <a href="../test_booking.php" class="btn btn-success">Run Test Booking Insertion</a>
            <a href="../home.php" class="btn btn-primary">Go to Booking Form</a>
            <a href="./roombook.php" class="btn btn-secondary">Back to Admin Panel</a>
        </div>
        
        <div class="mt-5">
            <h3>Troubleshooting Tips:</h3>
            <ul class="list-group">
                <li class="list-group-item">If bookings appear here but not in the admin panel, there may be a filter applied in the admin view.</li>
                <li class="list-group-item">If no bookings appear even after adding test data, check your database connection and permissions.</li>
                <li class="list-group-item">Ensure the form in home.php is correctly submitting data to the database.</li>
                <li class="list-group-item">If you see bookings here but not in roombook.php, the issue may be with the table display in the admin UI.</li>
                <li class="list-group-item">Make sure all required fields are being filled properly when submitting bookings.</li>
            </ul>
        </div>
    </div>
</body>
</html>
