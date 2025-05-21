// Luxury Room Animations and Enhancements
document.addEventListener('DOMContentLoaded', function() {
    // Add animation to room boxes on page load
    const roomBoxes = document.querySelectorAll('.roombox');
    
    // Animate room boxes with staggered delay for a cascade effect
    roomBoxes.forEach((box, index) => {
        setTimeout(() => {
            box.classList.add('animated');
        }, 100 * index);
    });
    
    // Add room counter to the top of the page
    addRoomCounter();
    
    // Add hover effect for room boxes
    addRoomHoverEffects();
    
    // Add subtle hover animations to form elements
    enhanceFormElements();
    
    // Add confirm dialog before room deletion
    addDeleteConfirmation();
});

// Function to add room counter
function addRoomCounter() {
    const roomContainer = document.querySelector('.room');
    if (!roomContainer) return;
    
    // Count rooms by type
    const superiorRooms = document.querySelectorAll('.roomboxsuperior').length;
    const deluxeRooms = document.querySelectorAll('.roomboxdelux').length;
    const guestRooms = document.querySelectorAll('.roomboguest').length;
    const singleRooms = document.querySelectorAll('.roomboxsingle').length;
    const totalRooms = superiorRooms + deluxeRooms + guestRooms + singleRooms;
    
    // Create counter element
    const counterDiv = document.createElement('div');
    counterDiv.className = 'room-counter';
    counterDiv.innerHTML = `
        <div class="counter-title">
            <i class="fa-solid fa-hotel"></i>
            <h2>Room Inventory</h2>
        </div>
        <div class="counter-stats">
            <div class="stat-box superior">
                <span class="stat-count">${superiorRooms}</span>
                <span class="stat-label">Superior</span>
            </div>
            <div class="stat-box deluxe">
                <span class="stat-count">${deluxeRooms}</span>
                <span class="stat-label">Deluxe</span>
            </div>
            <div class="stat-box guest">
                <span class="stat-count">${guestRooms}</span>
                <span class="stat-label">Guest House</span>
            </div>
            <div class="stat-box single">
                <span class="stat-count">${singleRooms}</span>
                <span class="stat-label">Single</span>
            </div>
            <div class="stat-box total">
                <span class="stat-count">${totalRooms}</span>
                <span class="stat-label">Total Rooms</span>
            </div>
        </div>
    `;
    
    // Insert counter at the beginning of the room container's parent
    roomContainer.parentNode.insertBefore(counterDiv, roomContainer);
    
    // Add animation to the counter
    setTimeout(() => {
        counterDiv.classList.add('fade-in');
        
        // Animate count numbers
        const countElements = counterDiv.querySelectorAll('.stat-count');
        countElements.forEach(element => {
            const finalValue = parseInt(element.textContent);
            animateValue(element, 0, finalValue, 1500);
        });
    }, 100);
}

// Function to animate count numbers
function animateValue(element, start, end, duration) {
    let startTimestamp = null;
    const step = (timestamp) => {
        if (!startTimestamp) startTimestamp = timestamp;
        const progress = Math.min((timestamp - startTimestamp) / duration, 1);
        element.textContent = Math.floor(progress * (end - start) + start);
        if (progress < 1) {
            window.requestAnimationFrame(step);
        }
    };
    window.requestAnimationFrame(step);
}

// Function to add hover effects to room boxes
function addRoomHoverEffects() {
    const roomBoxes = document.querySelectorAll('.roombox');
    
    roomBoxes.forEach(box => {
        box.addEventListener('mouseenter', function() {
            // Add a subtle glow effect to icon
            const icon = this.querySelector('.room-icon');
            if (icon) {
                icon.style.boxShadow = '0 0 20px rgba(201, 165, 92, 0.3)';
            }
        });
        
        box.addEventListener('mouseleave', function() {
            // Remove the glow effect
            const icon = this.querySelector('.room-icon');
            if (icon) {
                icon.style.boxShadow = '0 5px 15px rgba(0, 0, 0, 0.1)';
            }
        });
    });
}

// Function to enhance form elements
function enhanceFormElements() {
    const formControls = document.querySelectorAll('.form-control');
    
    formControls.forEach(control => {
        // Add focus and blur event listeners for visual enhancement
        control.addEventListener('focus', function() {
            this.parentElement.classList.add('focused');
        });
        
        control.addEventListener('blur', function() {
            this.parentElement.classList.remove('focused');
        });
    });
    
    // Add animation to the submit button
    const submitBtn = document.querySelector('.btn-success');
    if (submitBtn) {
        submitBtn.addEventListener('mouseenter', function() {
            this.classList.add('pulse');
        });
        
        submitBtn.addEventListener('mouseleave', function() {
            this.classList.remove('pulse');
        });
    }
}

// Function to add delete confirmation
function addDeleteConfirmation() {
    const deleteButtons = document.querySelectorAll('.delete-btn');
    
    deleteButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            if (!confirm('Are you sure you want to delete this room?')) {
                e.preventDefault();
            }
        });
    });
}
