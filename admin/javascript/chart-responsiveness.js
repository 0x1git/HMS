// Function to handle responsive chart resizing
function resizeCharts() {
    // If Morris chart exists, resize it
    if (typeof Morris !== 'undefined' && Morris.Bar && document.getElementById('profitchart')) {
        // Trigger window resize to make Morris charts responsive
        window.dispatchEvent(new Event('resize'));
    }
    
    // If Chart.js instance exists, update it
    if (typeof myChart !== 'undefined' && myChart) {
        myChart.resize();
    }
}

// Setup event listeners for resize
window.addEventListener('resize', resizeCharts);

// Initial resize
window.addEventListener('load', resizeCharts);

// MutationObserver to detect when this frame becomes visible
const observer = new MutationObserver(function(mutations) {
    mutations.forEach(function(mutation) {
        if (mutation.type === 'attributes' && 
            mutation.attributeName === 'style' && 
            window.getComputedStyle(document.body).display !== 'none') {
            setTimeout(resizeCharts, 300); // Slight delay to ensure DOM is ready
        }
    });
});

// Start observing
observer.observe(document.body, { attributes: true });
