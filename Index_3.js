"use strict";
document.addEventListener('DOMContentLoaded', function () {
    const mainForm = document.getElementById('addlist_form');
    mainForm.addEventListener('submit', function (event) {
        if (!validateForm()) {
            event.preventDefault(); // Prevent form submission if validation fails
        }
    });

    function validateForm() {
        resetErrorMessages();

        // Replace these IDs with your actual form field IDs
        const newItemTitle = document.getElementById('newItemTitle');
        const options = document.querySelectorAll('input[name="options"]');
        const state = document.querySelectorAll('input[name="state"]:checked');
        const startingDate = document.getElementById('starting_date');
        const completionDate = document.getElementById('completion_date');
        const newItemDescription = document.getElementById('newItemDescription');
        const selectedList = document.getElementById('selectedList');
        const newListName = document.getElementById('newListName');
        const newListDescription = document.getElementById('newListDescription');

        let isValid = true;

        if (newItemTitle.value.trim() === '') {
            displayError(newItemTitle, 'Please enter a title.');
            isValid = false;
        }

        let selectedCategory = false;
        options.forEach(function (option) {
            if (option.checked) {
                selectedCategory = true;
            }
        });
        if (!selectedCategory) {
            displayError(options[0], 'Please select a category.');
            isValid = false;
        }

        if (!state || state.length === 0) {
            displayError(document.querySelector('input[name="state"]'), 'Please select a state.');
            isValid = false;
        } else {
            const selectedState = state[0].value;
            if (selectedState === 'In Progress' && startingDate.value.trim() === '') {
                displayError(startingDate, 'Please enter a starting date.');
                isValid = false;
            } else if (selectedState === 'Completed' && (startingDate.value.trim() === '' || completionDate.value.trim() === '')) {
                displayError(startingDate, 'Please enter a starting date.');
                displayError(completionDate, 'Please enter a completion date.');
                isValid = false;
            }
        }

        if (startingDate.value.trim() !== '' && !isValidDateFormat(startingDate.value)) {
            displayError(startingDate, 'Invalid date format. Please use YYYY-MM-DD.');
            isValid = false;
        }

        if (completionDate.value.trim() !== '' && !isValidDateFormat(completionDate.value)) {
            displayError(completionDate, 'Invalid date format. Please use YYYY-MM-DD.');
            isValid = false;
        }

        // Additional date validation
        const today = new Date().toISOString().split('T')[0];

        const todayDate = new Date(today);

        if (startingDate.value.trim() !== '') {
            const startDate = new Date(startingDate.value);

            if (startDate > todayDate) {
                displayError(startingDate, 'Starting date can\'t be after today.');
                isValid = false;
            }
        }

        if (completionDate.value.trim() !== '') {
            const completionDateValue = new Date(completionDate.value);

            if (completionDateValue > todayDate) {
                displayError(completionDate, 'Completion date can\'t be after today.');
                isValid = false;
            }
        }

        if (startingDate.value.trim() !== '' && completionDate.value.trim() !== '') {
            const startDate = new Date(startingDate.value);
            const completionDateValue = new Date(completionDate.value);

            if (startDate > completionDateValue) {
                displayError(startingDate, 'Starting date can\'t be after the completion date.');
                displayError(completionDate, 'Completion date can\'t be before the starting date.');
                isValid = false;
            }
        }

        // If a new list is being created, validate the new list fields
        if (selectedList.value === 'new_list') {
            if (newListName.value.trim() === '') {
                displayError(newListName, 'Please enter a new list name.');
                isValid = false;
            }

            if (newListDescription.value.trim() === '') {
                displayError(newListDescription, 'Please enter a new list description.');
                isValid = false;
            }
        }

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
