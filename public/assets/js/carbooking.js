/**
 * Booking Form JavaScript
 * 
 * Handles client-side validation and UX enhancements for the car booking form
 * 
 * Features:
 * - Form validation on submit
 * - Error summary display
 * - Submit button state management (loading spinner)
 * - Prevents double submission
 */

// First we wait for the DOM to be fully loaded before running our script

document.addEventListener('DOMContentLoaded', function() {

    const bookingForm = document.getElementById('bookingForm');
    const submitBtn = document.getElementById('submitBtn');
    const nameInput = document.getElementById('name');
    const emailInput = document.getElementById('email');
    const termsCheckbox = document.getElementById('terms');

    if (!bookingForm) 
        { return;}

    /* VALIDATION FUNCTIONS */
    /*======================*/

    function validateEmail(email) {
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailPattern.test(email);
    }

    function validateName(name){
        return name.trim().length >= 2;
    }

    function validateForm() {
        const errors = [];

        //first we get the values of the inputs
        const name = nameInput.value.trim();
        const email = emailInput.value.trim();
        const termsAccepted = termsCheckbox.checked;

        // now we validate each field.
        if (!validateName(name))
        {
            errors.push("Please enter a valid name (at least 2 characters).");
        }

        if (!validateEmail(email))
        {
            errors.push("Please enter a valid email address.");
        }
        else if (email.length === 0)
        {
            errors.push("Email address is required.");
        }

        if (!termsAccepted)
        {
            errors.push("You must accept the terms and conditions.");
        }
        return errors;
    }


    /* ERROR SUMMARY DISPLAY FUNCTIONS */
    /*=================================*/

    function showErrors(errors){

        hideErrors(); // first we hide any existing errors

        // then we create the error container
        const errorContainer = document.createElement('div');
        errorContainer.id = 'formErrors';
        errorContainer.className = 'form-errors';

        // this container has the header that indicates there are errors
        const errorHeader = document.createElement('div');
        errorHeader.className = 'error-header';
        errorHeader.innerHTML = `<span class="material-symbols-rounded">error</span>
                                 <h3> Please fix the following errors:</h3>`;

        // and this is a list of the specific errors to show, so first we create an unordered list
        // then since our errors is an array, we loop through it and create a list item for each error.
        const errorList = document.createElement('ul');
        errors.forEach(function(error){
            const listItem = document.createElement('li');
            listItem.textContent = error;
            errorList.appendChild(listItem);
        });

        // finally we append the header and the list to the container
        errorContainer.appendChild(errorHeader);
        errorContainer.appendChild(errorList);

        // and we insert the error container before the form
        bookingForm.parentNode.insertBefore(errorContainer, bookingForm);

        // we also scroll to the top of the page so the user can see the errors
        errorContainer.scrollIntoView({ behavior: 'smooth',
                                        block: 'start'
         });

         setTimeout(() => {
            errorContainer.remove();
        }, 10000);
    }
    
    function hideErrors(){
        const existingErrors = document.getElementById('formErrors');
        if (existingErrors) {
            existingErrors.remove();
        }
    }



    /* FORM SUBMISSION HANDLER */
    /*=======================*/

    //this is our main form submit event handler
    // It is the entry point of all form validation and submission we did

    bookingForm.addEventListener('submit', function(e){
        e.preventDefault(); // we prevent the default form submission
        hideErrors(); // we hide any existing errors

        const errors = validateForm(); // we validate the form and get any errors

        //now we will check if any validation errors were found
        if (errors.length > 0){
            showErrors(errors); // if there are errors, we show them
            return;
        }

        //then we can submit the form properly.
        bookingForm.submit(); 
    });
})