"use strict";
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('myModal');
    const openModalBtn = document.getElementById('openModalBtn');
    const closeModalBtn = document.querySelector('.close');
    const modalContent = document.getElementById('modalContent');

    openModalBtn.addEventListener('click', function () {
        // Use AJAX to load content into the modal
        const xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (this.readyState === 4 && this.status === 200) {
                modalContent.innerHTML = this.responseText;
                modal.style.display = 'block';
            }
        };
        xhr.open("GET", "modal-content.php", true);
        xhr.send();
    });

    closeModalBtn.addEventListener('click', function () {
        modal.style.display = 'none';
    });

    // Close the modal if the user clicks outside the modal content
    window.addEventListener('click', function (event) {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });
});