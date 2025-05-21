<?php
session_start();
include '../config.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Golden Palace - Booking Calendar</title>
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>    <!-- FullCalendar -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    <!-- Custom styles -->
    <link rel="stylesheet" href="css/booking-calendar.css">
    <!-- Calendar scripts -->
    <script src="javascript/calendar-actions.js"></script>
    <script src="javascript/calendar-config.js"></script>
</head>

<body>
    <div class="container-fluid px-4 mt-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card calendar-card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="calendar-title"><i class="fas fa-calendar-alt me-2"></i>Booking Calendar</h3>
                            <div class="calendar-controls">
                                <a href="roombook.php" class="btn btn-outline-light btn-sm me-2"><i class="fas fa-table me-1"></i>Table View</a>
                                <button id="filterToggle" class="btn btn-primary btn-sm"><i class="fas fa-filter me-1"></i>Filters</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="filterPanel" class="mb-4" style="display: none;">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label">Room Type</label>
                                    <select id="roomTypeFilter" class="form-select form-select-sm">
                                        <option value="">All Room Types</option>
                                        <option value="Superior Room">Superior Room</option>
                                        <option value="Deluxe Room">Deluxe Room</option>
                                        <option value="Guest House">Guest House</option>
                                        <option value="Single Room">Single Room</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Status</label>
                                    <select id="statusFilter" class="form-select form-select-sm">
                                        <option value="">All Statuses</option>
                                        <option value="Confirm">Confirmed</option>
                                        <option value="NotConfirm">Pending</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Guest Name</label>
                                    <input type="text" id="nameFilter" class="form-control form-control-sm" placeholder="Search by name">
                                </div>
                                <div class="col-md-3 d-flex align-items-end">
                                    <button id="applyFilters" class="btn btn-success btn-sm me-2"><i class="fas fa-check me-1"></i>Apply</button>
                                    <button id="resetFilters" class="btn btn-secondary btn-sm"><i class="fas fa-undo me-1"></i>Reset</button>
                                </div>
                            </div>
                        </div>
                        
                        <div id="calendar"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Booking Details Modal -->
        <div class="modal fade" id="bookingDetailsModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Booking Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="bookingModalContent">
                        <!-- Content will be dynamically loaded here -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <a href="#" id="editBookingBtn" class="btn btn-primary"><i class="fas fa-edit me-1"></i>Edit</a>
                        <a href="#" id="confirmBookingBtn" class="btn btn-success"><i class="fas fa-check-circle me-1"></i>Confirm</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Get booking data from PHP -->
    <script>
        const bookingsData = <?php
            $query = "SELECT id, Name, RoomType, Bed, cin, cout, stat FROM roombook";
            $result = mysqli_query($conn, $query);
            $bookings = array();
            
            while ($row = mysqli_fetch_assoc($result)) {
                // Format the data for FullCalendar
                $bookings[] = array(
                    'id' => $row['id'],
                    'title' => $row['Name'] . ' - ' . $row['RoomType'],
                    'start' => $row['cin'],
                    'end' => $row['cout'],
                    'backgroundColor' => $row['stat'] == 'Confirm' ? 'var(--confirmed-color)' : 'var(--pending-color)',
                    'borderColor' => $row['stat'] == 'Confirm' ? 'var(--confirmed-border)' : 'var(--pending-border)',
                    'textColor' => '#fff',
                    'extendedProps' => array(
                        'name' => $row['Name'],
                        'roomType' => $row['RoomType'],
                        'bedType' => $row['Bed'],
                        'status' => $row['stat']
                    )
                );
            }
            
            echo json_encode($bookings);
        ?>;
    </script>
    
    <script src="js/booking-calendar.js"></script>
</body>

</html>
