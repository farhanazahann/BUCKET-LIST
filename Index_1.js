"use strict";
document.addEventListener('DOMContentLoaded', function () {

    const newListNameLabel = document.getElementById('newListNameLabel');
    const newListNameInput = document.getElementById('newListName');
    const newListDescriptionLabel = document.getElementById('newListDescriptionLabel');
    const newListDescriptionInput = document.getElementById('newListDescription');
    const publicVisibilityLabel = document.getElementById('publicVisibilityLabel');
    const publicVisibilityCheckbox = document.getElementById('publicVisibility');

    const selectedListDropdown = document.getElementById('selectedList');
    selectedListDropdown.addEventListener('change', function () {
        const selectedValue = selectedListDropdown.value;

        // Check if the selected value is 'new_list'
        if (selectedValue === 'new_list') {
            // Show the input fields for creating a new list
            newListNameLabel.style.display = 'block';
            newListNameInput.style.display = 'block';
            newListDescriptionLabel.style.display = 'block';
            newListDescriptionInput.style.display = 'block';
            publicVisibilityLabel.style.display = 'block';
            publicVisibilityCheckbox.style.display = 'block';
        } else {
            // Hide the input fields for creating a new list
            newListNameLabel.style.display = 'none';
            newListNameInput.style.display = 'none';
            newListDescriptionLabel.style.display = 'none';
            newListDescriptionInput.style.display = 'none';
            publicVisibilityLabel.style.display = 'none';
            publicVisibilityCheckbox.style.display = 'none';
        }
    });
    });