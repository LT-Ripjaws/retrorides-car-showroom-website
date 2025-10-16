
 //Login Page - Password Visibility Toggle


document.addEventListener('DOMContentLoaded', function() {
    
   
    // Password visibility toggle
    const toggleButtons = document.querySelectorAll('.toggle-password');
    toggleButtons.forEach(button =>{
        button.addEventListener('click', function() {
            const input = document.getElementById('loginPassword');
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
});