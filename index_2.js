"use strict";

document.addEventListener("DOMContentLoaded", function () {
  // Select all anchor tags for viewing completed items
  const viewItemLinks = document.querySelectorAll(".view-item-link");

  // Add event listener to each link
  viewItemLinks.forEach(function (link) {
    link.addEventListener("click", function (event) {
      event.preventDefault();

      // Fetch the URL from the link's href attribute
      const url = this.getAttribute("href");

      // Fetch the item details and display in the modal window
      fetch(url)
        .then(function (response) {
          return response.text();
        })
        .then(function (data) {
          // Display the content in the modal
          document.getElementById("modal-content").innerHTML = data;

          // Show the modal
          document.getElementById("viewItemModal").style.display = "block";

          // Hide the header
          const header = document.getElementById("main-header");
          if (header) {
            header.style.display = "none";
          }

          // Hide the footer
          const footer = document.querySelector("footer");
          if (footer) {
            footer.style.display = "none";
          }
        })
        .catch(function (error) {
          console.error("Error fetching item details:", error);
        });
    });
  });

  // Function to close the modal
  window.closeModal = function () {
    document.getElementById("viewItemModal").style.display = "none";

    // Show the header
    const header = document.getElementById("main-header");
    if (header) {
      header.style.display = "block";
    }

    // Show the footer
    const footer = document.querySelector("footer");
    if (footer) {
      footer.style.display = "block";
    }
  };
});
