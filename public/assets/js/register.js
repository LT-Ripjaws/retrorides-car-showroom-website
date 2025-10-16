
 //Registration Page - Password Toggle


document.addEventListener('DOMContentLoaded', function() {
    
   
    // Password visibility toggle
    const toggleButtons = document.querySelectorAll('.toggle-password');
    toggleButtons.forEach(button =>{
        button.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const input = document.getElementById(targetId);
            const icon = this.querySelector('.material-symbols-rounded');

            if (input.type === 'password') {
                input.type = 'text';
                icon.textContent = 'visibility_off';
            }
            else {
                input.type = 'password';
                icon.textContent = 'visibility';
            }
        });
    });


    /**
     * =================================================================
     * REAL-TIME PASSWORD MATCHING VALIDATION
     * =================================================================
     */
    
    const passwordInput = document.querySelector('input[name="password"]');
    const confirmPasswordInput = document.querySelector('input[name="confirm_password"]');
    
    if (passwordInput && confirmPasswordInput) {
        
       
         // Check for password mismatch when user types
       
        confirmPasswordInput.addEventListener('input', function() {
            const password = passwordInput.value;
            const confirmPassword = this.value;
            
            // If both fields have content, compare them
            if (password.length > 0 && confirmPassword.length > 0) {
                if (password !== confirmPassword) {
                    // Passwords don't match
                    this.style.borderColor = '#e74c3c';
                    this.style.background = 'rgba(231, 76, 60, 0.1)';
                } else {
                    // Passwords match
                    this.style.borderColor = '#2ecc71';
                    this.style.background = 'rgba(46, 204, 113, 0.1)';
                }
            } else {
                // Fields are empty, reset styling
                this.style.borderColor = 'rgba(255, 255, 255, 0.2)';
                this.style.background = 'rgba(255, 255, 255, 0.05)';
            }
        });
        
       
         // Also check when main password field changes
         
        passwordInput.addEventListener('input', function() {
            const confirm = confirmPasswordInput.value;
            
            // Only validate if confirm field has content
            if (confirm.length > 0) {
                if (this.value !== confirm) {
                    confirmPasswordInput.style.borderColor = '#e74c3c';
                    confirmPasswordInput.style.background = 'rgba(231, 76, 60, 0.1)';
                } else {
                    confirmPasswordInput.style.borderColor = '#2ecc71';
                    confirmPasswordInput.style.background = 'rgba(46, 204, 113, 0.1)';
                }
            }
        });
    }
});