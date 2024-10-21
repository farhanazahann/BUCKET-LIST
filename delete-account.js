"use strict";
document.addEventListener("DOMContentLoaded", function () {
    var deleteForm = document.getElementById("page-form");

    if (deleteForm) {
        deleteForm.addEventListener("submit", function (event) {
            // Display a confirmation dialog before submitting the form
            var isConfirmed = confirm("Are you sure you want to delete your account?");
            
            if (!isConfirmed) {
                // Prevent the form from being submitted if the user cancels
                event.preventDefault();
            }
        });
    }
});