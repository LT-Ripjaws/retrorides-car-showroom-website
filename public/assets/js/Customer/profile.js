/**
 * Customer Profile JavaScript
 * 
 * Features:
 * - Password visibility toggle
 * - Real-time password validation
 * - Password match checking
 */

document.addEventListener('DOMContentLoaded', function(){

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

    // Password Validation

    const newPasswordInput = document.getElementById('new_password');
    const confirmPasswordInput = document.getElementById('confirm_password');
    const lengthRequirement = document.getElementById('req-length');
    const matchRequirement = document.getElementById('req-match');

    if (newPasswordInput && confirmPasswordInput) {

        newPasswordInput.addEventListener('input', function() {
            const password = this.value;

            if(password.length >= 8) {
                lengthRequirement.classList.add('valid');
                lengthRequirement.classList.remove('invalid');
                lengthRequirement.querySelector('.material-symbols-rounded').textContent = 'check_circle';
            }
            else if (password.length > 0 )
            {
                lengthRequirement.classList.add('invalid');
                lengthRequirement.classList.remove('valid');
                lengthRequirement.querySelector('.material-symbols-rounded').textContent = 'cancel';
            } else {
                lengthRequirement.classList.remove('valid', 'invalid');
                lengthRequirement.querySelector('.material-symbols-rounded').textContent = 'circle';
            }

            checkPasswordMatch();
        });


        confirmPasswordInput.addEventListener('input', checkPasswordMatch);

        function checkPasswordMatch() {

            const password = newPasswordInput.value;
            const confirmPassword = confirmPasswordInput.value;

            if (confirmPassword.length === 0) {
                matchRequirement.classList.remove('valid', 'invalid');
                matchRequirement.querySelector('.material-symbols-rounded').textContent = 'circle';
                return;
            }
            if (password === confirmPassword) {
                matchRequirement.classList.add('valid');
                matchRequirement.classList.remove('invalid');
                matchRequirement.querySelector('.material-symbols-rounded').textContent = 'check_circle';
            }
            else {
                matchRequirement.classList.add('invalid');
                matchRequirement.classList.remove('valid');
                matchRequirement.querySelector('.material-symbols-rounded').textContent = 'cancel';
            }
        };
    };

});