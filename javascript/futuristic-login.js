// Get DOM elements
const login = document.getElementById('Log_in');
const signup = document.getElementById('sign_up');
const btns = document.querySelectorAll('.btns');
const authsection = document.querySelectorAll('.authsection');
const formInputs = document.querySelectorAll('.form-control');

// Login/Signup page toggle functions with animations
function signuppage() {
  login.style.opacity = '0';
  login.style.transform = 'translateY(-20px)';
  
  setTimeout(() => {
    login.style.display = 'none';
    signup.style.display = 'flex';
    
    setTimeout(() => {
      signup.style.opacity = '1';
      signup.style.transform = 'translateY(0)';
    }, 50);
  }, 300);
}

function loginpage() {
  signup.style.opacity = '0';
  signup.style.transform = 'translateY(-20px)';
  
  setTimeout(() => {
    signup.style.display = 'none';
    login.style.display = 'flex';
    
    setTimeout(() => {
      login.style.opacity = '1';
      login.style.transform = 'translateY(0)';
    }, 50);
  }, 300);
}

// Initialize styles for smooth animations
login.style.opacity = '1';
login.style.transform = 'translateY(0)';
login.style.transition = 'opacity 0.3s ease, transform 0.3s ease';

signup.style.opacity = '0';
signup.style.transform = 'translateY(-20px)';
signup.style.transition = 'opacity 0.3s ease, transform 0.3s ease';

// User/Staff role toggle with slide effect
const slideNav = function(manual) {
  // Remove active class from all buttons and sections
  btns.forEach((btn) => {
    btn.classList.remove('active');
  });
  
  authsection.forEach((slide) => {
    slide.classList.remove('active');
  });

  // Add active class to selected button and section
  btns[manual].classList.add('active');
  authsection[manual].classList.add('active');
};

// Add click event listeners to role buttons
btns.forEach((btn, i) => {
  btn.addEventListener('click', () => {
    slideNav(i);
  });
});

// Add subtle animations to form inputs
formInputs.forEach(input => {
  // Add focus effects
  input.addEventListener('focus', function() {
    this.parentElement.classList.add('focused');
  });
  
  input.addEventListener('blur', function() {
    this.parentElement.classList.remove('focused');
  });
});

// Form validation functions
function validateEmail(email) {
  const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  return re.test(String(email).toLowerCase());
}

function validatePassword(password) {
  return password.length >= 6; // Simple validation for minimum length
}

// Add these functions to the global scope for HTML onclick attributes
window.signuppage = signuppage;
window.loginpage = loginpage;