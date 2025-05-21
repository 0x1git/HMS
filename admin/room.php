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
    <title>Golden Palace - Room Management</title>
    <!-- fontowesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- boot -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>    <!-- Custom styles -->
    <link rel="stylesheet" href="css/luxury-room.css">
    <link rel="stylesheet" href="css/luxury-room-enhancements.css">
    <link rel="stylesheet" href="css/room-animations.css">
    <link rel="stylesheet" href="css/room-visual-effects.css">
    <link rel="stylesheet" href="css/room-spacing-fix.css">
</head>

<body>
    <div class="addroomsection">
        <h3><i class="fas fa-plus-circle me-2"></i> Add New Room</h3>
        <form action="" method="POST">            <label for="troom">Type of Room :</label>
            <select name="troom" class="form-control">
                <option value selected>Select Room Type</option>
                <option value="Superior Room">SUPERIOR ROOM</option>
                <option value="Deluxe Room">DELUXE ROOM</option>
                <option value="Guest House">GUEST HOUSE</option>
                <option value="Single Room">SINGLE ROOM</option>
            </select>            <label for="bed">Type of Bed :</label>
            <select name="bed" class="form-control">
                <option value selected>Select Bed Type</option>
                <option value="Single">Single</option>
                <option value="Double">Double</option>
                <option value="Triple">Triple</option>
                <option value="Quad">Quad</option>
                <option value="None">None</option>
            </select>
              <label for="place">Location/Place :</label>
            <input type="text" name="place" class="form-control" placeholder="Enter room location (e.g., Main Building, West Wing)" required>
            
            <!-- Hidden field for customer ID (default 0 = no customer assigned) -->
            <input type="hidden" name="cusid" value="0">

            <button type="submit" class="btn btn-success" name="addroom">Add Room</button>
        </form>        <?php
        if (isset($_POST['addroom'])) {
            // Validate required fields
            if (empty($_POST['troom']) || empty($_POST['bed']) || empty($_POST['place'])) {
                echo "<div class='alert alert-danger mt-3'>Error: All fields are required. Please fill in all the fields.</div>";
            } else {
                $typeofroom = mysqli_real_escape_string($conn, $_POST['troom']);
                $typeofbed = mysqli_real_escape_string($conn, $_POST['bed']); 
                $place = mysqli_real_escape_string($conn, $_POST['place']);
                // Get customer ID from the form or set default to 0 (no customer assigned)
                $cusid = isset($_POST['cusid']) ? intval($_POST['cusid']) : 0;

                $sql = "INSERT INTO room(type, bedding, place, cusid) VALUES ('$typeofroom', '$typeofbed', '$place', $cusid)";
                $result = mysqli_query($conn, $sql);

                if ($result) {
                    // Success message and redirect with animation effect
                    echo "<div class='alert alert-success mt-3'>
                            <i class='fas fa-check-circle me-2'></i> Room added successfully!
                          </div>";
                    echo "<script>
                            setTimeout(function() {
                                window.location.href = 'room.php';
                            }, 1500);
                          </script>";
                } else {
                    echo "<div class='alert alert-danger mt-3'>
                            <i class='fas fa-exclamation-triangle me-2'></i> Error: " . mysqli_error($conn) . "
                          </div>";
                }
            }
        }
        ?>
    </div>    <div class="room">
        <?php
        $sql = "select * from room";
        $re = mysqli_query($conn, $sql)
        ?>
        <?php
        while ($row = mysqli_fetch_array($re)) {            $id = $row['type'];
            $roomId = $row['id'];
            $bedding = $row['bedding'];
            $place = isset($row['place']) ? $row['place'] : "Location not specified";
            $customerId = isset($row['cusid']) ? $row['cusid'] : 0;
            
            // Define icons based on bed type
            $bedIcon = "fa-bed";
            switch($bedding) {
                case "Double":
                    $bedIcon = "fa-bed-pulse";
                    break;
                case "Triple":
                    $bedIcon = "fa-house";
                    break;
                case "Quad":
                    $bedIcon = "fa-house-user";
                    break;
                default:
                    $bedIcon = "fa-bed";
            }            if ($id == "Superior Room") {
                echo "<div class='roombox roomboxsuperior'>
                        <div class='text-center no-boder'>
                            <div class='room-icon'>
                                <i class='fa-solid $bedIcon fa-4x mb-2'></i>
                            </div>
                            <h3>" . $row['type'] . "</h3>
                            <div class='mb-1'>Bed Type: <span class='bed-type'>" . $row['bedding'] . "</span></div>
                            <div class='room-location'>Location: " . $place . "</div>
                            " . ($customerId > 0 ? "<div class='room-customer'>Customer ID: " . $customerId . "</div>" : "") . "
                            <div class='room-id'>Room ID: #" . $roomId . "</div>
                            <a href='roomdelete.php?id=". $roomId ."' class='delete-btn'>
                                <button class='btn btn-danger'><i class='fas fa-trash-alt me-2'></i>Delete</button>
                            </a>
                        </div>
                    </div>";} else if ($id == "Deluxe Room") {                echo "<div class='roombox roomboxdelux'>
                        <div class='text-center no-boder'>
                            <div class='room-icon'>
                                <i class='fa-solid $bedIcon fa-4x mb-2'></i>
                            </div>
                            <h3>" . $row['type'] . "</h3>
                            <div class='mb-1'>Bed Type: <span class='bed-type'>" . $row['bedding'] . "</span></div>
                            <div class='room-location'>Location: " . $place . "</div>
                            " . ($customerId > 0 ? "<div class='room-customer'>Customer ID: " . $customerId . "</div>" : "") . "
                            <div class='room-id'>Room ID: #" . $roomId . "</div>
                            <a href='roomdelete.php?id=". $roomId ."' class='delete-btn'>
                                <button class='btn btn-danger'><i class='fas fa-trash-alt me-2'></i>Delete</button>
                            </a>
                        </div>
                    </div>";} else if ($id == "Guest House") {                echo "<div class='roombox roomboguest'>
                        <div class='text-center no-boder'>
                            <div class='room-icon'>
                                <i class='fa-solid $bedIcon fa-4x mb-2'></i>
                            </div>
                            <h3>" . $row['type'] . "</h3>
                            <div class='mb-1'>Bed Type: <span class='bed-type'>" . $row['bedding'] . "</span></div>
                            <div class='room-location'>Location: " . $place . "</div>
                            " . ($customerId > 0 ? "<div class='room-customer'>Customer ID: " . $customerId . "</div>" : "") . "
                            <div class='room-id'>Room ID: #" . $roomId . "</div>
                            <a href='roomdelete.php?id=". $roomId ."' class='delete-btn'>
                                <button class='btn btn-danger'><i class='fas fa-trash-alt me-2'></i>Delete</button>
                            </a>
                        </div>
                    </div>";} else if ($id == "Single Room") {                echo "<div class='roombox roomboxsingle'>
                        <div class='text-center no-boder'>
                            <div class='room-icon'>
                                <i class='fa-solid $bedIcon fa-4x mb-2'></i>
                            </div>
                            <h3>" . $row['type'] . "</h3>
                            <div class='mb-1'>Bed Type: <span class='bed-type'>" . $row['bedding'] . "</span></div>
                            <div class='room-location'>Location: " . $place . "</div>
                            " . ($customerId > 0 ? "<div class='room-customer'>Customer ID: " . $customerId . "</div>" : "") . "
                            <div class='room-id'>Room ID: #" . $roomId . "</div>
                            <a href='roomdelete.php?id=". $roomId ."' class='delete-btn'>
                                <button class='btn btn-danger'><i class='fas fa-trash-alt me-2'></i>Delete</button>
                            </a>
                        </div>
                    </div>";
            }
        }
        ?></div>    <!-- Custom Scripts -->
    <script src="js/room-enhancements.js"></script>
    <script src="js/dropdown-fix.js"></script>
</body>

</html>