"use strict";
document.addEventListener('DOMContentLoaded', function () {
    const deleteItemLinks = document.querySelectorAll('.delete-item-link');

    deleteItemLinks.forEach(link => {
        link.addEventListener('click', function (event) {
            event.preventDefault();
            const itemId = link.getAttribute('data-item-id');
        
            // Show a confirmation dialog
            const confirmed = confirmDelete();

            console.log('Delete link clicked for item ID:', itemId);

            if (confirmed) {
                // Redirect to delete-item.php with the item ID
                console.log('User confirmed deletion. Redirecting...');
                window.location.href = 'delete-item.php?item_id=' + itemId;
            } else {
                console.log('Deletion canceled by the user.');
            }
        });
    });

    function confirmDelete() {
        // Show a custom confirmation dialog
        console.log('Showing confirmation dialog...');
        return window.confirm('Are you sure you want to delete this item?');
    }
});