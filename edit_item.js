"use strict";
document.addEventListener('DOMContentLoaded', function () {
    const editForm = document.getElementById('edit-form');
    editForm.addEventListener('submit', function (event) {
        if (!validateForm()) {
            event.preventDefault(); // Prevent form submission if validation fails
        }
    });

    function validateForm() {
        resetErrorMessages();

        const titleInput = document.getElementById('title');
        const descriptionInput = document.getElementById('description');
        const startDateInput = document.getElementById('starting_date');
        const completionDateInput = document.getElementById('completion_date');
        const stateInput = document.querySelector('input[name="state"]:checked');
        const proofInput = document.getElementById('proof');

        let isValid = true;

        if (titleInput.value.trim() === '') {
            displayError(titleInput, 'Please enter a title.');
            isValid = false;
        }

        if (descriptionInput.value.trim() === '') {
            displayError(descriptionInput, 'Please enter a description.');
            isValid = false;
        }

        if (!stateInput) {
            const stateRadioGroup = document.querySelector('input[name="state"]');
            displayError(stateRadioGroup, 'Please select a state.');
            isValid = false;
        }

        if (stateInput && stateInput.value === 'In Progress' && startDateInput.value.trim() === '') {
            displayError(startDateInput, 'Please enter a starting date.');
            isValid = false;
        }

        if (stateInput && stateInput.value === 'Completed' && (startDateInput.value.trim() === '' || completionDateInput.value.trim() === '')) {
            displayError(startDateInput, 'Please enter a starting date.');
            displayError(completionDateInput, 'Please enter a completion date.');
            isValid = false;
        }

        // Additional date validation
        const today = new Date().toISOString().split('T')[0];

        if (startDateInput.value.trim() !== '' && !isValidDateFormat(startDateInput.value)) {
            displayError(startDateInput, 'Invalid date format. Please use YYYY-MM-DD.');
            isValid = false;
        }

        if (completionDateInput.value.trim() !== '' && !isValidDateFormat(completionDateInput.value)) {
            displayError(completionDateInput, 'Invalid date format. Please use YYYY-MM-DD.');
            isValid = false;
        }

        
            const todayDate = new Date(today);

            if (startDateInput.value.trim() !== '') {
                const startDate = new Date(startDateInput.value);
            
                if (startDate > todayDate) {
                    displayError(startDateInput, 'Starting date can\'t be after today.');
                    isValid = false;
                }
            }
            
            if (completionDateInput.value.trim() !== '') {
                const completionDate = new Date(completionDateInput.value);
            
                if (completionDate > todayDate) {
                    displayError(completionDateInput, 'Completion date can\'t be after today.');
                    isValid = false;
                }
            }
            
            if (startDateInput.value.trim() !== '' && completionDateInput.value.trim() !== '') {
                const startDate = new Date(startDateInput.value);
                const completionDate = new Date(completionDateInput.value);
            
                if (startDate > completionDate) {
                    displayError(startDateInput, 'Starting date can\'t be after the completion date.');
                    displayError(completionDateInput, 'Completion date can\'t be before the starting date.');
                    isValid = false;
                }
            }
        else if (stateInput.value === 'Completed') {
            // If state is 'Completed' and no completion date is provided, show an error
            displayError(completionDateInput, 'Completion date is required for Completed state.');
            isValid = false;
        } else if (stateInput.value === 'In Progress' && startDateInput.value.trim() === '') {
            // If state is 'In Progress' and no starting date is provided, show an error
            displayError(startDateInput, 'Starting date is required for In Progress state.');
            isValid = false;
        }

        // Add more validation for proofInput if needed (e.g., file type, size)

        return isValid;
    }

    function resetErrorMessages() {
        const errorMessages = document.querySelectorAll('.error');
        errorMessages.forEach(function (error) {
            error.classList.add('hidden');
        });
    }

    function displayError(inputElement, errorMessage) {
        const errorSpan = inputElement.nextElementSibling;
        errorSpan.innerText = errorMessage;
        errorSpan.classList.remove('hidden');
    }

    function isValidDateFormat(dateString) {
        // Check if the date string is in the format YYYY-MM-DD
        const dateRegex = /^\d{4}-\d{2}-\d{2}$/;
        return dateRegex.test(dateString);
    }
});