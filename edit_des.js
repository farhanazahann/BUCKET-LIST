"use strict";
document.addEventListener("DOMContentLoaded", function () {
    const descInput = document.getElementById("description");
    const charCountSpan = document.getElementById("charCount");
    const maxChars = 2500;

    descInput.addEventListener("input", function () {
        const remainingChars = maxChars - descInput.value.length;
        charCountSpan.innerText = `Characters left: ${remainingChars}`;

        if (remainingChars < 50) {
            charCountSpan.style.color = "red";
        } else {
            charCountSpan.style.color = "blue";
        }

        // Validate word limit
        const words = descInput.value.trim().split(/\s+/).filter(word => word.length > 0);
        const wordCount = words.length;
        const wordLimit = 200; // You can adjust the word limit

        if (wordCount > wordLimit) {
            showError("description-error", "Description exceeds the word limit");
        } else {
            resetError("description-error");
        }
    });

    function showError(spanId, errorMessage) {
        const errorSpan = document.getElementById(spanId);
        errorSpan.innerText = errorMessage;
        errorSpan.style.color = "red";
    }

    function resetError(spanId) {
        const errorSpan = document.getElementById(spanId);
        errorSpan.innerText = "";
    }
});
