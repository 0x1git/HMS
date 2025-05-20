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
    <title>Golden Palace - Payment Management</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>    
    <!-- Font Awesome -->    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css"/>
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/luxury-theme.css">    <link rel="stylesheet" href="css/luxury-payment-cards.css">
    <link rel="stylesheet" href="css/luxury-payment.css">
    <link rel="stylesheet" href="css/luxury-visual-enhancements.css">
</head>
<body>
    <div class="payment-container payment-section">
        <div class="luxury-card gradient-card">
            <div class="luxury-card-header">
                <h3><i class="fas fa-search me-2"></i> Search Payments</h3>
            </div>
            <div class="luxury-card-body">
                <input type="text" name="search_bar" id="search_bar" class="luxury-input" placeholder="Search by guest name..." onkeyup="searchFun()">
            </div>
        </div>

        <div class="stats-container mt-4">
            <div class="luxury-card gradient-card">
                <div class="luxury-card-header">
                    <h3><i class="fas fa-chart-bar me-2"></i> Statistics</h3>
                </div>
                <div class="luxury-card-body">
                    <div class="stats-grid">
                        <div class="stat-box total-bookings">
                            <div class="stat-label">Total Bookings</div>
                            <div class="stat-value" id="totalBookings">0</div>
                        </div>
                        <div class="stat-box total-revenue">
                            <div class="stat-label">Total Revenue</div>
                            <div class="stat-value" id="totalRevenue">₹0</div>
                        </div>
                        <div class="stat-box avg-stay">
                            <div class="stat-label">Average Stay</div>
                            <div class="stat-value" id="avgStay">0 days</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="payment-grid mt-4" id="payment-cards"><?php
            $paymanttablesql = "SELECT * FROM payment ORDER BY id DESC";
            $paymantresult = mysqli_query($conn, $paymanttablesql);
            while ($res = mysqli_fetch_array($paymantresult)) {
            ?>            <div class="luxury-card gradient-card payment-card" data-guest="<?php echo strtolower($res['Name']); ?>">
                <div class="luxury-card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="guest-name mb-0"><?php echo $res['Name']; ?></h3>
                        <span class="booking-id luxury-status">#<?php echo $res['id']; ?></span>
                    </div>
                </div>
                <div class="luxury-card-body">
                    <div class="payment-info-grid">
                        <div class="info-item">
                            <div class="info-label text-muted">Room Type</div>
                            <div class="info-value"><?php echo $res['RoomType']; ?></div>                        </div>
                        <div class="info-item">
                            <div class="info-label text-muted">Bed Type</div>
                            <div class="info-value"><?php echo $res['Bed']; ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label text-muted">Check In</div>
                            <div class="info-value"><?php echo $res['cin']; ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label text-muted">Check Out</div>
                            <div class="info-value"><?php echo $res['cout']; ?></div>
                        </div>
                    </div>
                </div>
                <div class="luxury-card-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="total-amount">
                            <span class="text-muted">Total Amount:</span>
                            <span class="amount">₹<?php echo number_format($res['finaltotal'], 2); ?></span>
                        </div>
                        <div class="card-actions">
                            <a href="invoiceprint.php?id=<?php echo $res['id']?>" class="luxury-button me-2">
                                <i class="fa-solid fa-print me-1"></i> Print
                            </a>
                            <a href="paymantdelete.php?id=<?php echo $res['id']?>" class="luxury-button" style="background: var(--error-color); border-color: var(--error-color); color: white;">
                                <i class="fa-solid fa-trash me-1"></i> Delete
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
        </div>
    </div>

    <script>
        function searchFun() {
            let filter = document.getElementById('search_bar').value.toLowerCase();
            let cards = document.getElementById('payment-cards').getElementsByClassName('payment-card');

            for(let i = 0; i < cards.length; i++) {
                let guestName = cards[i].getAttribute('data-guest');
                if(guestName.includes(filter)) {
                    cards[i].style.display = '';
                } else {
                    cards[i].style.display = 'none';
                }
            }
        }

        // Update statistics
        function updateStats() {
            let cards = document.getElementById('payment-cards').getElementsByClassName('payment-card');
            let totalBookings = cards.length;
            let totalRevenue = 0;
            let totalDays = 0;

            for(let i = 0; i < cards.length; i++) {
                let amount = cards[i].querySelector('.amount').textContent;
                totalRevenue += parseFloat(amount.replace('₹', '').replace(/,/g, ''));

                // Calculate days between check-in and check-out
                let checkin = new Date(cards[i].querySelector('.info-value:nth-child(3)').textContent);
                let checkout = new Date(cards[i].querySelector('.info-value:nth-child(4)').textContent);
                let days = (checkout - checkin) / (1000 * 60 * 60 * 24);
                totalDays += days;
            }

            document.getElementById('totalBookings').textContent = totalBookings;
            document.getElementById('totalRevenue').textContent = '₹' + totalRevenue.toLocaleString('en-IN', {maximumFractionDigits: 2});
            document.getElementById('avgStay').textContent = (totalDays / totalBookings).toFixed(1) + ' days';
        }

        // Call updateStats when page loads
        window.onload = updateStats;
    </script>
</body>
</html>