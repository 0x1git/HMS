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
    
    // Add form validation
    addFormValidation();
    
    // Add styling for error fields
    addErrorFieldHighlight();
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
    
    // Get unique locations
    const locationElements = document.querySelectorAll('.room-location');
    const locations = new Set();
    locationElements.forEach(el => {
        const locationText = el.textContent.replace('Location: ', '').trim();
        if (locationText && locationText !== 'Location not specified') {
            locations.add(locationText);
        }
    });
    
    // Count rooms with assigned customers
    const customerElements = document.querySelectorAll('.room-customer');
    const assignedRooms = customerElements.length;
    
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
        <div class="occupancy-info">
            <div class="occupancy-title">
                <i class="fa-solid fa-user-check"></i> Room Assignment
            </div>
            <div class="occupancy-stats">
                <div class="occupancy-item">
                    <span class="occupancy-value">${assignedRooms}</span>
                    <span class="occupancy-label">Assigned</span>
                </div>
                <div class="occupancy-item">
                    <span class="occupancy-value">${totalRooms - assignedRooms}</span>
                    <span class="occupancy-label">Available</span>
                </div>
                <div class="occupancy-item">
                    <span class="occupancy-value">${Math.round((assignedRooms/totalRooms) * 100)}%</span>
                    <span class="occupancy-label">Occupancy</span>
                </div>
            </div>
        </div>
        <div class="locations-info">
            <div class="locations-title">Locations: ${locations.size}</div>
            <div class="locations-list">${Array.from(locations).map(loc => `<span class="location-tag">${loc}</span>`).join('')}</div>
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
            e.preventDefault();
            const roomId = this.closest('.roombox').querySelector('.room-id').textContent.replace('Room ID: #', '');
            const roomType = this.closest('.roombox').querySelector('h3').textContent;
            
            // Check if room has a customer assigned
            const customerElement = this.closest('.roombox').querySelector('.room-customer');
            let confirmMessage = `Are you sure you want to delete ${roomType} (ID: ${roomId})?`;
            
            if (customerElement) {
                confirmMessage = `Warning: This room has a customer assigned. Are you sure you want to delete ${roomType} (ID: ${roomId})?`;
            }
            
            // Show styled confirmation dialog
            showConfirmDialog(confirmMessage, () => {
                // If confirmed, navigate to delete URL
                window.location.href = btn.getAttribute('href');
            });
        });
    });
}

// Function to show a styled confirmation dialog
function showConfirmDialog(message, onConfirm) {
    // Create modal backdrop
    const backdrop = document.createElement('div');
    backdrop.className = 'modal-backdrop';
    document.body.appendChild(backdrop);
    
    // Create modal dialog
    const modalDialog = document.createElement('div');
    modalDialog.className = 'modal-dialog';
    modalDialog.innerHTML = `
        <div class="modal-content">
            <div class="modal-header">
                <h4><i class="fas fa-exclamation-triangle"></i> Confirmation Required</h4>
                <button type="button" class="close-btn">&times;</button>
            </div>
            <div class="modal-body">
                <p>${message}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary cancel-btn">Cancel</button>
                <button type="button" class="btn btn-danger confirm-btn">Delete Room</button>
            </div>
        </div>
    `;
    document.body.appendChild(modalDialog);
    
    // Add CSS for the modal
    addModalStyles();
    
    // Show the modal with animation
    setTimeout(() => {
        backdrop.classList.add('show');
        modalDialog.classList.add('show');
    }, 10);
    
    // Handle close button click
    modalDialog.querySelector('.close-btn').addEventListener('click', () => {
        closeModal(backdrop, modalDialog);
    });
    
    // Handle cancel button click
    modalDialog.querySelector('.cancel-btn').addEventListener('click', () => {
        closeModal(backdrop, modalDialog);
    });
    
    // Handle confirm button click
    modalDialog.querySelector('.confirm-btn').addEventListener('click', () => {
        closeModal(backdrop, modalDialog);
        onConfirm();
    });
    
    // Close on backdrop click
    backdrop.addEventListener('click', () => {
        closeModal(backdrop, modalDialog);
    });
}

// Function to close the modal
function closeModal(backdrop, dialog) {
    backdrop.classList.remove('show');
    dialog.classList.remove('show');
    
    // Remove elements after animation
    setTimeout(() => {
        document.body.removeChild(backdrop);
        document.body.removeChild(dialog);
    }, 300);
}

// Function to add modal styles
function addModalStyles() {
    // Only add styles once
    if (document.getElementById('modal-styles')) return;
    
    const style = document.createElement('style');
    style.id = 'modal-styles';
    style.textContent = `
        .modal-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0);
            z-index: 1050;
            transition: background-color 0.3s ease;
        }
        
        .modal-backdrop.show {
            background-color: rgba(0, 0, 0, 0.5);
        }
        
        .modal-dialog {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -40%);
            z-index: 1051;
            opacity: 0;
            transition: all 0.3s ease;
        }
        
        .modal-dialog.show {
            opacity: 1;
            transform: translate(-50%, -50%);
        }
        
        .modal-content {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 8px;
            border: 1px solid rgba(201, 165, 92, 0.3);
            box-shadow: 0 5px 30px rgba(0, 0, 0, 0.3);
            width: 400px;
            max-width: 90vw;
            overflow: hidden;
        }
        
        .modal-header {
            padding: 15px 20px;
            border-bottom: 1px solid rgba(201, 165, 92, 0.2);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .modal-header h4 {
            margin: 0;
            color: var(--light-text);
            font-size: 18px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .modal-header h4 i {
            color: var(--error-color);
        }
        
        .close-btn {
            background: none;
            border: none;
            color: var(--light-text);
            font-size: 24px;
            cursor: pointer;
            opacity: 0.7;
            transition: opacity 0.2s;
        }
        
        .close-btn:hover {
            opacity: 1;
        }
        
        .modal-body {
            padding: 20px;
            color: var(--light-text);
        }
        
        .modal-footer {
            padding: 15px 20px;
            border-top: 1px solid rgba(201, 165, 92, 0.2);
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }
        
        .modal-footer .btn {
            padding: 8px 16px;
            border-radius: 4px;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .btn-secondary {
            background-color: rgba(255, 255, 255, 0.1);
            color: var(--light-text);
            border: none;
        }
        
        .btn-secondary:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }
        
        .btn-danger {
            background: linear-gradient(135deg, rgba(193, 73, 73, 0.8), rgba(169, 57, 57, 0.9));
            color: white;
            border: none;
        }
        
        .btn-danger:hover {
            background: linear-gradient(135deg, rgba(203, 83, 83, 0.9), rgba(193, 73, 73, 1));
            transform: translateY(-2px);
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.15);
        }
    `;
    document.head.appendChild(style);
}

// Add form validation for required fields
function addFormValidation() {
    const addRoomForm = document.querySelector('.addroomsection form');
    
    if (addRoomForm) {
        addRoomForm.addEventListener('submit', function(e) {
            const roomType = this.querySelector('select[name="troom"]');
            const bedType = this.querySelector('select[name="bed"]');
            const location = this.querySelector('input[name="place"]');
            let isValid = true;
            let errorMessage = '';
            
            // Remove any existing error messages
            const existingError = document.querySelector('.form-error');
            if (existingError) {
                existingError.remove();
            }
            
            // Validate room type
            if (roomType.selectedIndex <= 0) {
                isValid = false;
                errorMessage += 'Please select a room type. ';
                roomType.classList.add('error-field');
            } else {
                roomType.classList.remove('error-field');
            }
            
            // Validate bed type
            if (bedType.selectedIndex <= 0) {
                isValid = false;
                errorMessage += 'Please select a bed type. ';
                bedType.classList.add('error-field');
            } else {
                bedType.classList.remove('error-field');
            }
            
            // Validate location
            if (!location.value.trim()) {
                isValid = false;
                errorMessage += 'Please enter a room location. ';
                location.classList.add('error-field');
            } else {
                location.classList.remove('error-field');
            }
            
            // If validation fails, prevent form submission and show error
            if (!isValid) {
                e.preventDefault();
                
                // Create and show error message
                const errorDiv = document.createElement('div');
                errorDiv.className = 'form-error';
                errorDiv.textContent = errorMessage;
                
                addRoomForm.insertBefore(errorDiv, addRoomForm.querySelector('button'));
                
                // Auto-scroll to error
                errorDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        });
    }
}

// Add error field highlight style
function addErrorFieldHighlight() {
    // Add CSS rules for error fields
    const style = document.createElement('style');
    style.textContent = `
        .error-field {
            border: 1px solid var(--error-color) !important;
            box-shadow: 0 0 0 2px rgba(193, 73, 73, 0.2) !important;
        }
        
        .form-error {
            background-color: rgba(193, 73, 73, 0.1);
            border-left: 3px solid var(--error-color);
            color: #f8d7da;
            padding: 10px 15px;
            margin: 15px 0;
            border-radius: 4px;
            font-size: 14px;
        }
    `;
    document.head.appendChild(style);
}
