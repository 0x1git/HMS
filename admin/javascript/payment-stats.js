function updateStats() {
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
                // Debug log to check date formats
                console.log('Check-in:', checkinStr, 'Check-out:', checkoutStr);

                // Parse dates (assuming format is DD-MM-YYYY)
                const [cinDay, cinMonth, cinYear] = checkinStr.split('-').map(num => parseInt(num, 10));
                const [coutDay, coutMonth, coutYear] = checkoutStr.split('-').map(num => parseInt(num, 10));

                // Debug log parsed components
                console.log('Parsed dates:', { cinDay, cinMonth, cinYear }, { coutDay, coutMonth, coutYear });

                // Create dates (months are 0-based in JavaScript)
                const checkinDate = new Date(cinYear, cinMonth - 1, cinDay);
                const checkoutDate = new Date(coutYear, coutMonth - 1, coutDay);

                // Debug log created Date objects
                console.log('Date objects:', checkinDate, checkoutDate);

                if (!isNaN(checkinDate) && !isNaN(checkoutDate)) {
                    const days = Math.ceil((checkoutDate - checkinDate) / (1000 * 60 * 60 * 24));
                    // Debug log calculated days
                    console.log('Calculated days:', days);
                    if (days >= 0 && days < 365 * 5) { // Sanity check: stay less than 5 years
                        totalDays += days;
                    } else {
                        console.warn('Invalid stay duration:', days, 'days for dates:', checkinStr, checkoutStr);
                    }
                }
            }
        } catch (error) {
            console.error('Error calculating stats:', error);
        }
    });

    // Calculate average stay (avoid division by zero)
    let avgStay = totalBookings > 0 ? totalDays / totalBookings : 0;

    // Debug log final calculations
    console.log('Final calculations:', {
        totalBookings,
        totalRevenue,
        totalDays,
        avgStay
    });

    // Update display values
    document.getElementById('totalBookings').textContent = totalBookings;
    document.getElementById('totalRevenue').textContent = '₹' + totalRevenue.toLocaleString('en-IN', {maximumFractionDigits: 2});
    document.getElementById('avgStay').textContent = avgStay.toFixed(1) + ' days';

    // Add some visual feedback
    document.querySelectorAll('.stat-value').forEach(stat => {
        stat.style.animation = 'none';
        stat.offsetHeight; // Trigger reflow
        stat.style.animation = 'fadeIn 0.5s ease-out';
    });
}
