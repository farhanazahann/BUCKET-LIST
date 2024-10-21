
"use strict";
function openModal(itemId) {
    // Use AJAX to load content into the modal
    const xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            document.getElementById('modal-content').innerHTML = this.responseText;
            document.getElementById('viewItemModal').style.display = 'block';
        }
    };
    xhr.open("GET", "view-item.php?id=" + itemId, true);
    xhr.send();
}
