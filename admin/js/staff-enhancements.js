// Staff Management Enhancements
document.addEventListener('DOMContentLoaded', function() {
    // Add delete confirmation
    addDeleteConfirmation();
    
    // Add form validation
    addFormValidation();
    
    // Add hover effects to staff cards
    addStaffCardEffects();
});

// Function to add delete confirmation
function addDeleteConfirmation() {
    const deleteButtons = document.querySelectorAll('.delete-btn');
    
    deleteButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const staffId = this.closest('.staff-card').querySelector('.staff-id').textContent.replace('Staff ID: #', '');
            const staffName = this.closest('.staff-card').querySelector('h3').textContent;
            const staffPosition = this.closest('.staff-card').querySelector('.staff-position').textContent;
            
            // Create confirmation message
            const confirmMessage = `Are you sure you want to delete staff member ${staffName} (${staffPosition}) with ID #${staffId}?`;
              // Show confirm dialog
            if (confirm(confirmMessage)) {
                // If confirmed, navigate to delete URL
                window.location.href = btn.getAttribute('href');
            }
        });
    });
}

// Function to add form validation
function addFormValidation() {
    const staffForm = document.querySelector('.addroomsection form');
    
    if (staffForm) {
        staffForm.addEventListener('submit', function(e) {
            let isValid = true;
            let errorMessage = '';
            
            // Get form fields
            const nameField = this.querySelector('input[name="staffname"]');
            const workField = this.querySelector('select[name="staffwork"]');
            const salaryField = this.querySelector('input[name="salary"]');
            const joinDateField = this.querySelector('input[name="join_date"]');
            
            // Validate name
            if (!nameField.value.trim()) {
                isValid = false;
                errorMessage += 'Name is required. ';
                nameField.classList.add('error-field');
            } else {
                nameField.classList.remove('error-field');
            }
            
            // Validate work position
            if (!workField.value) {
                isValid = false;
                errorMessage += 'Position is required. ';
                workField.classList.add('error-field');
            } else {
                workField.classList.remove('error-field');
            }
            
            // Validate salary
            if (!salaryField.value || parseInt(salaryField.value) <= 0) {
                isValid = false;
                errorMessage += 'Please enter a valid salary amount. ';
                salaryField.classList.add('error-field');
            } else {
                salaryField.classList.remove('error-field');
            }
            
            // Validate join date
            if (!joinDateField.value) {
                isValid = false;
                errorMessage += 'Join date is required. ';
                joinDateField.classList.add('error-field');
            } else {
                joinDateField.classList.remove('error-field');
            }
            
            // If not valid, prevent form submission and show error
            if (!isValid) {
                e.preventDefault();
                alert('Please fix the following errors:\n\n' + errorMessage);
            }
        });
    }
}

// Function to add hover effects to staff cards
function addStaffCardEffects() {
    const staffCards = document.querySelectorAll('.staff-card');
    
    staffCards.forEach((card, index) => {
        // Add staggered animation delay
        card.style.animationDelay = (index * 0.1) + 's';
        
        // Add hover effect
        card.addEventListener('mouseenter', function() {
            const icon = this.querySelector('.staff-icon');
            if (icon) {
                icon.style.transform = 'scale(1.1) rotate(5deg)';
                icon.style.color = '#e5d3a3';
            }
        });
        
        card.addEventListener('mouseleave', function() {
            const icon = this.querySelector('.staff-icon');
            if (icon) {
                icon.style.transform = 'scale(1)';
                icon.style.color = '#c9a55c';
            }
        });
    });
}
