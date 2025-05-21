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
    <link rel="stylesheet" href="css/luxury-payment.css">    <link rel="stylesheet" href="css/luxury-visual-enhancements.css">    <script>
        // Statistics calculation functions
        function updateStats() {
            let cards = document.getElementsByClassName('payment-card');
            let visibleCards = Array.from(cards).filter(card => card.style.display !== 'none');
            let totalBookings = visibleCards.length;
            let totalRevenue = 0;
            let totalDays = 0;

            visibleCards.forEach(card => {
                try {
                    // Calculate revenue
                    let amountStr = card.querySelector('.amount').textContent;
                    let amount = parseFloat(amountStr.replace('₹', '').replace(/,/g, ''));
                    if (!isNaN(amount)) {
                        totalRevenue += amount;
                    }

                    // Get dates
                    let checkinStr = card.querySelector('.info-item:nth-child(3) .info-value').textContent;
                    let checkoutStr = card.querySelector('.info-item:nth-child(4) .info-value').textContent;

                    let [cinDay, cinMonth, cinYear] = checkinStr.split('-');
                    let [coutDay, coutMonth, coutYear] = checkoutStr.split('-');

                    // Convert to integers
                    cinDay = parseInt(cinDay);
                    cinMonth = parseInt(cinMonth);
                    cinYear = parseInt(cinYear);
                    coutDay = parseInt(coutDay);
                    coutMonth = parseInt(coutMonth);
                    coutYear = parseInt(coutYear);

                    // Create date objects (month - 1 because JS months are 0-based)
                    let checkinDate = new Date(cinYear, cinMonth - 1, cinDay);
                    let checkoutDate = new Date(coutYear, coutMonth - 1, coutDay);
                    
                    if (!isNaN(checkinDate.getTime()) && !isNaN(checkoutDate.getTime())) {
                        let days = Math.round((checkoutDate - checkinDate) / (1000 * 60 * 60 * 24));
                        if (days >= 0) {
                            totalDays += days;
                        }
                    }
                } catch (error) {
                    console.error('Error calculating stats:', error);
                }
            });

            // Update display
            let avgStay = totalBookings > 0 ? (totalDays / totalBookings) : 0;

            document.getElementById('totalBookings').textContent = totalBookings;
            document.getElementById('totalRevenue').textContent = '₹' + totalRevenue.toLocaleString('en-IN', {maximumFractionDigits: 2});
            document.getElementById('avgStay').textContent = avgStay.toFixed(1) + ' days';
        }

        // Search function
        function searchFun() {
            let filter = document.getElementById('search_bar').value.toLowerCase();
            let cards = document.getElementsByClassName('payment-card');

            Array.from(cards).forEach(card => {
                let guestName = card.getAttribute('data-guest');
                card.style.display = guestName.includes(filter) ? '' : 'none';
            });

            // Update stats after search
            updateStats();
        }

        // Initialize when DOM is ready
        document.addEventListener('DOMContentLoaded', updateStats);
        
        // Update again when everything is loaded
        window.addEventListener('load', updateStats);
    </script>
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
    </div>    <script>
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
            // Update stats after search
            updateStats();
        }

        // Initialize stats when the page loads
        document.addEventListener('DOMContentLoaded', updateStats);
        
        // Also update after all resources are loaded
        window.addEventListener('load', updateStats);
            // Get only visible cards
            let cards = Array.from(document.getElementsByClassName('payment-card')).filter(card => card.style.display !== 'none');
            
            // Update total bookings (only visible cards)
            let totalBookings = cards.length;
            let totalRevenue = 0;
            let totalDays = 0;

            cards.forEach(card => {
                try {
                    // Calculate total revenue
                    const amountElement = card.querySelector('.amount');
                    if (amountElement) {
                        const amount = amountElement.textContent.replace('₹', '').replace(/,/g, '');
                        totalRevenue += parseFloat(amount) || 0;
                    }

                    // Get dates directly from the info items
                    const dateElements = card.querySelectorAll('.info-item .info-value');
                    const checkinStr = dateElements[2]?.textContent; // Check-in is the third info item
                    const checkoutStr = dateElements[3]?.textContent; // Check-out is the fourth info item

                    if (checkinStr && checkoutStr) {
                        // Parse the dates
                        const [cinDay, cinMonth, cinYear] = checkinStr.split('-');
                        const [coutDay, coutMonth, coutYear] = checkoutStr.split('-');

                        const checkinDate = new Date(cinYear, cinMonth - 1, cinDay);
                        const checkoutDate = new Date(coutYear, coutMonth - 1, coutDay);

                        if (!isNaN(checkinDate) && !isNaN(checkoutDate)) {
                            const days = Math.ceil((checkoutDate - checkinDate) / (1000 * 60 * 60 * 24));
                            if (days >= 0) {
                                totalDays += days;
                            }
                        }
                    }
                } catch (error) {
                    console.error('Error calculating stats:', error);
                }
            });

            // Calculate average stay
            const avgStay = totalBookings > 0 ? totalDays / totalBookings : 0;

            // Update the display
            document.getElementById('totalBookings').textContent = totalBookings;
            document.getElementById('totalRevenue').textContent = '₹' + totalRevenue.toLocaleString('en-IN', {maximumFractionDigits: 2});
            document.getElementById('avgStay').textContent = avgStay.toFixed(1) + ' days';

            // Animate the updates
            document.querySelectorAll('.stat-value').forEach(stat => {
                stat.style.animation = 'none';
                stat.offsetHeight; // Trigger reflow
                stat.style.animation = 'fadeIn 0.5s ease-out';
            });
        }

        // Initialize stats when the page loads
        document.addEventListener('DOMContentLoaded', updateStats);

        // Additional event listener for window load to ensure all resources are loaded
        window.addEventListener('load', updateStats);    </script>
</body>
</html>