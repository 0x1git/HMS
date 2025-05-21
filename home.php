<?php

include 'config.php';
session_start();

// page redirect
$usermail="";
$usermail=$_SESSION['usermail'];
if($usermail == true){

}else{
  header("location: index.php");
}

?>


<!DOCTYPE html>
<html lang="en">
<head>    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/home.css">
    <title>Hotel Golden Palace</title>
    <!-- boot -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <!-- fontowesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <!-- sweet alert -->    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <link rel="stylesheet" href="./admin/css/roombook.css">
    <link rel="stylesheet" href="./css/sweetalert-theme.css">
</head>

<body>  <nav>
    <div class="logo">
      <img class="bluebirdlogo" src="./image/goldenpalacelogo-glow.png" alt="logo">
      <p>GOLDEN PALACE</p>
    </div>
    <ul>
      <li><a href="#firstsection">Home</a></li>
      <li><a href="#secondsection">Rooms</a></li>
      <li><a href="#thirdsection">Facilites</a></li>
      <li><a href="#contactus">Contact Us</a></li>
      <a href="./logout.php"><button class="btn btn-danger">Logout</button></a>
    </ul>
  </nav>

  <section id="firstsection" class="carousel slide carousel_section" data-bs-ride="carousel">
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img class="carousel-image" src="./image/hotel1.jpg">
        </div>
        <div class="carousel-item">
            <img class="carousel-image" src="./image/hotel2.jpg">
        </div>
        <div class="carousel-item">
            <img class="carousel-image" src="./image/hotel3.jpg">
        </div>
        <div class="carousel-item">
            <img class="carousel-image" src="./image/hotel4.jpg">
        </div>

        <div class="welcomeline">
          <h1 class="welcometag">Welcome to heaven on earth</h1>
        </div>

      <!-- bookbox -->
      <div id="guestdetailpanel">
        <form action="" method="POST" class="guestdetailpanelform">
            <div class="head">
                <h3 style="color: gold;">RESERVATION</h3>
                <i class="fa-solid fa-circle-xmark" onclick="closebox()"></i>
            </div>
            <div class="middle">
                <div class="guestinfo">
                    <h4>Guest information</h4>
                    <input type="text" name="Name" placeholder="Enter Full name">
                    <input type="email" name="Email" placeholder="Enter Email">

                    <?php
                    $countries = array("Afghanistan", "Albania", "Algeria", "American Samoa", "Andorra", "Angola", "Anguilla", "Antarctica", "Antigua and Barbuda", "Argentina", "Armenia", "Aruba", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bermuda", "Bhutan", "Bolivia", "Bosnia and Herzegowina", "Botswana", "Bouvet Island", "Brazil", "British Indian Ocean Territory", "Brunei Darussalam", "Bulgaria", "Burkina Faso", "Burundi", "Cambodia", "Cameroon", "Canada", "Cape Verde", "Cayman Islands", "Central African Republic", "Chad", "Chile", "China", "Christmas Island", "Cocos (Keeling) Islands", "Colombia", "Comoros", "Congo", "Congo, the Democratic Republic of the", "Cook Islands", "Costa Rica", "Cote d'Ivoire", "Croatia (Hrvatska)", "Cuba", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "East Timor", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Falkland Islands (Malvinas)", "Faroe Islands", "Fiji", "Finland", "France", "France Metropolitan", "French Guiana", "French Polynesia", "French Southern Territories", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Gibraltar", "Greece", "Greenland", "Grenada", "Guadeloupe", "Guam", "Guatemala", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Heard and Mc Donald Islands", "Holy See (Vatican City State)", "Honduras", "Hong Kong", "Hungary", "Iceland", "India", "Indonesia", "Iran (Islamic Republic of)", "Iraq", "Ireland", "Israel", "Italy", "Jamaica", "Japan", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Korea, Democratic People's Republic of", "Korea, Republic of", "Kuwait", "Kyrgyzstan", "Lao, People's Democratic Republic", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libyan Arab Jamahiriya", "Liechtenstein", "Lithuania", "Luxembourg", "Macau", "Macedonia, The Former Yugoslav Republic of", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Martinique", "Mauritania", "Mauritius", "Mayotte", "Mexico", "Micronesia, Federated States of", "Moldova, Republic of", "Monaco", "Mongolia", "Montserrat", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauru", "Nepal", "Netherlands", "Netherlands Antilles", "New Caledonia", "New Zealand", "Nicaragua", "Niger", "Nigeria", "Niue", "Norfolk Island", "Northern Mariana Islands", "Norway", "Oman", "Pakistan", "Palau", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Pitcairn", "Poland", "Portugal", "Puerto Rico", "Qatar", "Reunion", "Romania", "Russian Federation", "Rwanda", "Saint Kitts and Nevis", "Saint Lucia", "Saint Vincent and the Grenadines", "Samoa", "San Marino", "Sao Tome and Principe", "Saudi Arabia", "Senegal", "Seychelles", "Sierra Leone", "Singapore", "Slovakia (Slovak Republic)", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Georgia and the South Sandwich Islands", "Spain", "Sri Lanka", "St. Helena", "St. Pierre and Miquelon", "Sudan", "Suriname", "Svalbard and Jan Mayen Islands", "Swaziland", "Sweden", "Switzerland", "Syrian Arab Republic", "Taiwan, Province of China", "Tajikistan", "Tanzania, United Republic of", "Thailand", "Togo", "Tokelau", "Tonga", "Trinidad and Tobago", "Tunisia", "Turkey", "Turkmenistan", "Turks and Caicos Islands", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "United States Minor Outlying Islands", "Uruguay", "Uzbekistan", "Vanuatu", "Venezuela", "Vietnam", "Virgin Islands (British)", "Virgin Islands (U.S.)", "Wallis and Futuna Islands", "Western Sahara", "Yemen", "Yugoslavia", "Zambia", "Zimbabwe");
                    ?>

                    <select name="Country" class="selectinput">
						<option value selected >Select your country</option>
                        <?php
							foreach($countries as $key => $value):
							echo '<option value="'.$value.'">'.$value.'</option>';
                            //close your tags!!
							endforeach;
						?>
                    </select>
                    <input type="text" name="Phone" placeholder="Enter Phoneno">
                </div>

                <div class="line"></div>

                <div class="reservationinfo">
                    <h4>Reservation information</h4>
                    <select name="RoomType" class="selectinput">
						<option value selected >Type Of Room</option>
                        <option value="Superior Room">SUPERIOR ROOM</option>
                        <option value="Deluxe Room">DELUXE ROOM</option>
						<option value="Guest House">GUEST HOUSE</option>
						<option value="Single Room">SINGLE ROOM</option>
                    </select>
                    <select name="Bed" class="selectinput">
						<option value selected >Bedding Type</option>
                        <option value="Single">Single</option>
                        <option value="Double">Double</option>
						<option value="Triple">Triple</option>
                        <option value="Quad">Quad</option>
						<option value="None">None</option>
                    </select>                    <select name="NoofRoom" class="selectinput">
						<option value selected >No of Room</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                    </select>
                    <select name="Meal" class="selectinput">
						<option value selected >Meal</option>
                        <option value="Room only">Room only</option>
                        <option value="Breakfast">Breakfast</option>
						<option value="Half Board">Half Board</option>
						<option value="Full Board">Full Board</option>
					</select>                    <div class="datesection">
                        <span>
                            <label for="cin"> Check-In</label>
                            <input name="cin" type="date" id="cinField" required>
                        </span>
                        <span>
                            <label for="cout"> Check-Out</label>
                            <input name="cout" type="date" id="coutField" required>
                        </span>
                    </div>
                </div>
            </div>
            <div class="footer">
                <button class="btn btn-success" name="guestdetailsubmit">Submit</button>
            </div>
        </form>

        <!-- ==== room book php ====-->        <?php       
            if (isset($_POST['guestdetailsubmit'])) {
                // Sanitize input data to prevent SQL injection
                $Name = mysqli_real_escape_string($conn, trim($_POST['Name']));
                $Email = mysqli_real_escape_string($conn, trim($_POST['Email']));
                $Country = mysqli_real_escape_string($conn, trim($_POST['Country']));
                $Phone = mysqli_real_escape_string($conn, trim($_POST['Phone']));
                $RoomType = mysqli_real_escape_string($conn, trim($_POST['RoomType']));
                $Bed = mysqli_real_escape_string($conn, trim($_POST['Bed']));
                $NoofRoom = mysqli_real_escape_string($conn, trim($_POST['NoofRoom']));
                $Meal = mysqli_real_escape_string($conn, trim($_POST['Meal']));
                $cin = mysqli_real_escape_string($conn, trim($_POST['cin']));
                $cout = mysqli_real_escape_string($conn, trim($_POST['cout']));
                
                // Validation array to collect errors
                $errors = array();
                
                // Improved validation to check all required fields
                if(empty($Name)) $errors[] = "Name is required";
                if(empty($Email)) $errors[] = "Email is required";
                if(empty($Country) || $Country == "Select your country") $errors[] = "Country is required";
                if(empty($Phone)) $errors[] = "Phone number is required";
                if(empty($RoomType) || $RoomType == "Type Of Room") $errors[] = "Room type is required";
                if(empty($Bed) || $Bed == "Bedding Type") $errors[] = "Bed type is required";
                if(empty($NoofRoom) || $NoofRoom == "No of Room") $errors[] = "Number of rooms is required";
                if(empty($Meal) || $Meal == "Meal") $errors[] = "Meal option is required";
                if(empty($cin)) $errors[] = "Check-in date is required";
                if(empty($cout)) $errors[] = "Check-out date is required";
                
                // Validate dates
                if(!empty($cin) && !empty($cout)) {
                    $checkIn = new DateTime($cin);
                    $checkOut = new DateTime($cout);
                    $today = new DateTime();
                    
                    // Reset time part to compare just the dates
                    $today->setTime(0, 0, 0);
                    $checkIn->setTime(0, 0, 0);
                    $checkOut->setTime(0, 0, 0);
                    
                    if($checkIn < $today) {
                        $errors[] = "Check-in date cannot be in the past";
                    } elseif($checkIn >= $checkOut) {
                        $errors[] = "Check-out date must be after check-in date";
                    }
                }
                
                // Display errors if any
                if(!empty($errors)) {
                    $errorMsg = implode("<br>", $errors);
                    echo "<script>swal({
                        title: 'Form Validation Error',
                        html: true,
                        text: '$errorMsg',
                        icon: 'warning',
                    });
                    </script>";
                } else {
                    $sta = "NotConfirm";
                    
                    // Calculate the number of days between check-in and check-out
                    $date1 = new DateTime($cin);
                    $date2 = new DateTime($cout);
                    $interval = $date1->diff($date2);
                    $nodays = $interval->days;
                    
                    // Fix: Use properly sanitized data and correct day calculation
                    $sql = "INSERT INTO roombook(Name, Email, Country, Phone, RoomType, Bed, NoofRoom, Meal, cin, cout, stat, nodays) 
                            VALUES ('$Name', '$Email', '$Country', '$Phone', '$RoomType', '$Bed', '$NoofRoom', '$Meal', '$cin', '$cout', '$sta', '$nodays')";
                    $result = mysqli_query($conn, $sql);
                    
                    if ($result) {
                        // Clear form data to prevent resubmission
                        $_POST = array();
                        
                        echo "<script>
                            swal({
                                title: 'Reservation Successful!',
                                text: 'Thank you for booking with Golden Palace Hotel. Your reservation has been recorded.',
                                icon: 'success',
                                buttons: {
                                    confirm: {
                                        text: 'OK',
                                        value: true,
                                        visible: true,
                                        className: 'btn btn-success',
                                        closeModal: true
                                    }
                                }
                            }).then((value) => {
                                if (value) {
                                    // Redirect with a clean URL to avoid form resubmission
                                    window.location.href = 'home.php';
                                }
                            });
                        </script>";
                        
                        // Ensure modal is visible
                        echo "<script>
                            setTimeout(function() {
                                var modals = document.querySelectorAll('.swal-modal');
                                if (modals.length > 0) {
                                    modals.forEach(function(modal) {
                                        modal.style.zIndex = '9999';
                                        modal.style.display = 'flex';
                                    });
                                }
                            }, 100);
                        </script>";
                    } else {
                        echo "<script>
                            swal({
                                title: 'Database Error',
                                text: 'Sorry, we could not process your reservation. Error: " . mysqli_error($conn) . "',
                                icon: 'error'
                            });
                            console.error('SQL Error: " . mysqli_error($conn) . "');
                        </script>";
                    }
                }
            }
            ?>
          </div>

    </div>
  </section>
    
  <section id="secondsection"> 
    <div class="ourroom">
    <h1 class="head" style="color: gold; font-family: 'Cinizel', serif;">≼ Our Rooms≽</h1>

      <div class="roomselect">
        <div class="roombox">
          <div class="hotelphoto h1"></div>
          <div class="roomdata">
            <h2>Superior Room</h2>
            <div class="services">
              <i class="fa-solid fa-wifi"></i>
              <i class="fa-solid fa-burger"></i>
              <i class="fa-solid fa-spa"></i>
              <i class="fa-solid fa-dumbbell"></i>
              <i class="fa-solid fa-person-swimming"></i>
            </div>
            <button class="btn btn-primary bookbtn" onclick="openbookbox()">Book</button>
          </div>
        </div>
        <div class="roombox">
          <div class="hotelphoto h2"></div>
          <div class="roomdata">
            <h2>Deluxe Room</h2>
            <div class="services">
              <i class="fa-solid fa-wifi"></i>
              <i class="fa-solid fa-burger"></i>
              <i class="fa-solid fa-spa"></i>
              <i class="fa-solid fa-dumbbell"></i>
            </div>
            <button class="btn btn-primary bookbtn" onclick="openbookbox()">Book</button>
          </div>
        </div>
        <div class="roombox">
          <div class="hotelphoto h3"></div>
          <div class="roomdata">
            <h2>Guest Room</h2>
            <div class="services">
              <i class="fa-solid fa-wifi"></i>
              <i class="fa-solid fa-burger"></i>
              <i class="fa-solid fa-spa"></i>
            </div>
            <button class="btn btn-primary bookbtn" onclick="openbookbox()">Book</button>
          </div>
        </div>
        <div class="roombox">
          <div class="hotelphoto h4"></div>
          <div class="roomdata">
            <h2>Single Room</h2>
            <div class="services">
              <i class="fa-solid fa-wifi"></i>
              <i class="fa-solid fa-burger"></i>
            </div>
            <button class="btn btn-primary bookbtn" onclick="openbookbox()">Book</button>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section id="thirdsection">
    <h1 class="head" style="color: gold; font-family: 'Cinizel', serif;"> ≼ Facilities ≽ </h1>
    <div class="facility">
      <div class="box">
        <h2>Swiming pool</h2>
      </div>
      <div class="box">
        <h2>Spa</h2>
      </div>
      <div class="box">
        <h2>24*7 Restaurants</h2>
      </div>
      <div class="box">
        <h2>24*7 Gym</h2>
      </div>
    </div>
  </section>

  <section id="contactus">
    <div class="social">
      <i class="fa-brands fa-instagram"></i>
      <i class="fa-brands fa-facebook"></i>
      <i class="fa-solid fa-envelope"></i>
    </div>
    <div class="createdby">
      <h5>Created by @TeamRocket</h5>
    </div>
  </section>
</body>

<script>
    var bookbox = document.getElementById("guestdetailpanel");    function openbookbox() {
        // Clear any old form values first
        var form = bookbox.querySelector('form');
        if (form) {
            form.reset();
        }
        
        // Set default dates (today for check-in, tomorrow for check-out)
        var today = new Date();
        var tomorrow = new Date();
        tomorrow.setDate(today.getDate() + 1);
        
        // Format dates as YYYY-MM-DD
        var cinInput = document.getElementById('cinField');
        var coutInput = document.getElementById('coutField');
        
        if (cinInput && coutInput) {
            var todayFormatted = today.toISOString().split('T')[0];
            var tomorrowFormatted = tomorrow.toISOString().split('T')[0];
            
            cinInput.value = todayFormatted;
            coutInput.value = tomorrowFormatted;
            
            // Set min dates to prevent past bookings
            cinInput.min = todayFormatted;
            coutInput.min = tomorrowFormatted;
            
            // Store original values in case we need to reset
            cinInput.dataset.defaultValue = todayFormatted;
            coutInput.dataset.defaultValue = tomorrowFormatted;
        }
        
        // Display the booking box
        bookbox.style.display = "flex";
        document.body.style.overflow = "hidden"; // Prevent background scrolling
    }

    function closebox() {
        bookbox.style.display = "none";
        document.body.style.overflow = "auto"; // Restore background scrolling
    }

    // Close panel when clicking outside
    bookbox.addEventListener('click', function(e) {
        if (e.target === bookbox) {
            closebox();
        }
    });

    // Close panel when pressing Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === "Escape" && bookbox.style.display === "flex") {
            closebox();
        }
    });

    // Prevent form resubmission on page refresh
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
      // Update minimum check-out date when check-in date changes
    document.addEventListener('DOMContentLoaded', function() {
        var cinInput = document.getElementById('cinField');
        if (cinInput) {
            cinInput.addEventListener('change', function() {
                var cinDate = new Date(this.value);
                var coutInput = document.getElementById('coutField');
                
                if (coutInput) {
                    // Set minimum check-out date to the day after check-in
                    var minCheckOut = new Date(cinDate);
                    minCheckOut.setDate(cinDate.getDate() + 1);
                    var minCheckOutFormatted = minCheckOut.toISOString().split('T')[0];
                    coutInput.min = minCheckOutFormatted;
                    
                    // If current check-out is before new check-in, update it
                    if (new Date(coutInput.value) <= cinDate) {
                        coutInput.value = minCheckOutFormatted;
                    }
                }
            });
        }
    });
</script>
</html>