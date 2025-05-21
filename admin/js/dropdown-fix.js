// Fix for select dropdown visibility
document.addEventListener('DOMContentLoaded', function() {
    // Add event listeners to detect when an option is selected
    const selectElements = document.querySelectorAll('.form-control');
    
    selectElements.forEach(select => {
        // Get placeholder text
        const placeholderOption = select.querySelector('option[value=""]');
        const placeholderText = placeholderOption ? placeholderOption.textContent : '';
        
        // Add styling for placeholder text
        if (placeholderOption && placeholderText.includes('Select')) {
            placeholderOption.style.color = 'rgba(255, 255, 255, 0.5)';
        }
        
        // Check if a value is already selected (not the placeholder)
        if (select.selectedIndex > 0) {
            select.classList.add('has-value');
        }
        
        // Add change event listener
        select.addEventListener('change', function() {
            if (this.selectedIndex > 0) {
                this.classList.add('has-value');
            } else {
                this.classList.remove('has-value');
            }
        });
    });
    
    // Add hover class to help with visibility when dropdown is open
    const addHoverClass = () => {
        document.querySelectorAll('select.form-control').forEach(select => {
            select.addEventListener('mouseenter', () => {
                select.classList.add('dropdown-hover');
            });
            
            select.addEventListener('mouseleave', () => {
                select.classList.remove('dropdown-hover');
            });
        });
    };
    
    addHoverClass();
});
