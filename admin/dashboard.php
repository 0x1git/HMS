<?php
    session_start();
    include '../config.php';

    // roombook
    $roombooksql ="Select * from roombook";
    $roombookre = mysqli_query($conn, $roombooksql);
    $roombookrow = mysqli_num_rows($roombookre);

    // staff
    $staffsql ="Select * from staff";
    $staffre = mysqli_query($conn, $staffsql);
    $staffrow = mysqli_num_rows($staffre);

    // room
    $roomsql ="Select * from room";
    $roomre = mysqli_query($conn, $roomsql);
    $roomrow = mysqli_num_rows($roomre);

    //roombook roomtype
    $chartroom1 = "SELECT * FROM roombook WHERE RoomType='Superior Room'";
    $chartroom1re = mysqli_query($conn, $chartroom1);
    $chartroom1row = mysqli_num_rows($chartroom1re);

    $chartroom2 = "SELECT * FROM roombook WHERE RoomType='Deluxe Room'";
    $chartroom2re = mysqli_query($conn, $chartroom2);
    $chartroom2row = mysqli_num_rows($chartroom2re);

    $chartroom3 = "SELECT * FROM roombook WHERE RoomType='Guest House'";
    $chartroom3re = mysqli_query($conn, $chartroom3);
    $chartroom3row = mysqli_num_rows($chartroom3re);

    $chartroom4 = "SELECT * FROM roombook WHERE RoomType='Single Room'";
    $chartroom4re = mysqli_query($conn, $chartroom4);
    $chartroom4row = mysqli_num_rows($chartroom4re);
?>
<!-- moriss profit -->
<?php 	
					$query = "SELECT * FROM payment";
					$result = mysqli_query($conn, $query);
					$chart_data = '';
					$tot = 0;
					while($row = mysqli_fetch_array($result))
					{              $chart_data .= "{ date:'".$row["cout"]."', profit:".$row["finaltotal"]."}, ";
              $tot = $tot + $row["finaltotal"];
					}

					$chart_data = substr($chart_data, 0, -2);
				
?>

<!DOCTYPE html>
<html lang="en">
<head>    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/dashboard.css">
    <link rel="stylesheet" href="./css/chart-enhancements.css">
    <!-- chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- morish bar -->
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>    <script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>

    <title>Golden Palace - Dashboard</title>

</head>
<body>
   <div class="dashboard-container">
     <div class="databox">
          <div class="box roombookbox">
            <h2>Room Occupancy</h2>  
            <h1><?php echo $roombookrow ?> / <?php echo $roomrow ?></h1>
          </div>
          <div class="box guestbox">
            <h2>Staff Members</h2>  
            <h1><?php echo $staffrow ?></h1>
          </div>          <div class="box profitbox">
            <h2>Revenue</h2>  
            <h1><?php echo $tot?> <span>₹</span></h1>
          </div>
      </div>
      <div class="chartbox">
          <div class="bookroomchart">
              <canvas id="bookroomchart"></canvas>
              <h3>Room Bookings by Category</h3>
          </div>
          <div class="profitchart" >
              <div id="profitchart"></div>
              <h3>Revenue Trends</h3>
          </div>
      </div>
   </div>
</body>



<script>
        const labels = [
          'Superior Room',
          'Deluxe Room',
          'Guest House',
          'Single Room',
        ];
        const data = {
          labels: labels,
          datasets: [{
            label: 'Room Bookings',
            backgroundColor: [
                'rgba(201, 165, 92, 0.8)',   // Gold
                'rgba(139, 163, 201, 0.8)',  // Blue
                'rgba(108, 168, 130, 0.8)',  // Green
                'rgba(147, 126, 159, 0.8)',  // Purple
            ],
            borderColor: 'rgba(0, 0, 0, 0.1)',
            data: [<?php echo $chartroom1row ?>,<?php echo $chartroom2row ?>,<?php echo $chartroom3row ?>,<?php echo $chartroom4row ?>],
          }]
        };
  
        const doughnutchart = {
          type: 'doughnut',
          data: data,
          options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
              legend: {
                position: 'bottom',
                labels: {
                  color: 'rgba(233, 234, 239, 0.8)',
                  font: {
                    family: 'Montserrat',
                    size: 12
                  }
                }
              }
            }
          }
        };
        
      const myChart = new Chart(
      document.getElementById('bookroomchart'),
      doughnutchart);
</script>

<script>
Morris.Bar({
 element : 'profitchart',
 data:[<?php echo $chart_data;?>],
 xkey:'date',
 ykeys:['profit'],
 labels:['Revenue'],
 hideHover:'auto',
 stacked:true,
 barColors:[
  'rgba(201, 165, 92, 0.8)', // Gold
 ],
 resize: true,
 padding: 10,
 gridTextColor: 'rgba(233, 234, 239, 0.8)',
 gridTextFamily: 'Montserrat',
 gridTextSize: 12
});
</script>

<script src="./javascript/chart-responsiveness.js"></script>

</html>