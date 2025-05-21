<?php
session_start();
include '../config.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Golden Palace - Staff Management</title>
    <!-- fontowesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- boot -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>    <link rel="stylesheet" href="css/luxury-staff.css">
    <link rel="stylesheet" href="css/enhanced-dropdowns.css">
</head>

<body>    <div class="addroomsection">
        <h3><i class="fas fa-user-plus me-2"></i> Add New Staff</h3>
        <form action="" method="POST">
            <label for="staffname">Name :</label>
            <input type="text" name="staffname" class="form-control" placeholder="Enter staff member's name" required>

            <label for="staffwork">Work :</label>
            <select name="staffwork" class="form-control" required>
                <option value="" selected disabled>Select position</option>
                <option value="Manager">Manager</option>
                <option value="Cook">Cook</option>
                <option value="Helper">Helper</option>
                <option value="Cleaner">Cleaner</option>
                <option value="Waiter">Waiter</option>
                <option value="Receptionist">Receptionist</option>
                <option value="Security">Security</option>
                <option value="Maintenance">Maintenance</option>
            </select>
            
            <label for="salary">Salary :</label>
            <input type="number" name="salary" class="form-control" placeholder="Enter salary amount" required min="1">
            
            <label for="join_date">Join Date :</label>
            <input type="date" name="join_date" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>

            <button type="submit" class="btn btn-success" name="addstaff">Add Staff Member</button>
        </form>        <?php
        if (isset($_POST['addstaff'])) {
            // Sanitize and validate inputs
            $staffname = mysqli_real_escape_string($conn, $_POST['staffname']);
            $staffwork = mysqli_real_escape_string($conn, $_POST['staffwork']);
            $salary = intval($_POST['salary']);
            $join_date = mysqli_real_escape_string($conn, $_POST['join_date']);
            
            // Validate inputs
            if (empty($staffname) || empty($staffwork) || $salary <= 0 || empty($join_date)) {
                echo "<div class='alert alert-danger mt-3'>Please fill in all required fields with valid values.</div>";
            } else {
                // Insert into database with all required fields
                $sql = "INSERT INTO staff(name, work, salary, join_date) VALUES ('$staffname', '$staffwork', $salary, '$join_date')";
                $result = mysqli_query($conn, $sql);

                if ($result) {
                    echo "<div class='alert alert-success mt-3'>Staff member added successfully!</div>";
                    echo "<script>setTimeout(function() { window.location.href = 'staff.php'; }, 1500);</script>";
                } else {
                    echo "<div class='alert alert-danger mt-3'>Error: " . mysqli_error($conn) . "</div>";
                }
            }
        }
        ?>
    </div>
    <!-- here room add because room.php and staff.php both css is similar -->
    <div class="room">
    <?php
        $sql = "select * from staff";
        $re = mysqli_query($conn, $sql)
        ?>
        <?php
        while ($row = mysqli_fetch_array($re)) {
            $join_date = date('M d, Y', strtotime($row['join_date']));
            $formatted_salary = number_format($row['salary']);
            
            echo "<div class='roombox staff-card'>
                    <div class='text-center no-boder'>
                        <div class='staff-icon'>
                            <i class='fa fa-user fa-4x'></i>
                        </div>
                        <h3>" . $row['name'] . "</h3>
                        <div class='staff-position'>" . $row['work'] . "</div>
                        <div class='staff-salary'>Salary: ₹" . $formatted_salary . "</div>
                        <div class='staff-joindate'>Joined: " . $join_date . "</div>
                        <div class='staff-id'>Staff ID: #" . $row['id'] . "</div>
                        <a href='staffdelete.php?id=". $row['id'] ."' class='delete-btn'>
                            <button class='btn btn-danger'><i class='fas fa-trash-alt me-2'></i>Delete</button>
                        </a>
                    </div>
                </div>";
        }
        ?>
    </div>    <!-- Custom scripts -->
    <script src="js/staff-enhancements.js"></script>
    <script src="js/dropdown-fix.js"></script>
</body>

</html>