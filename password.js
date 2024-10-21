
"use strict";
function updatePasswordStrengthIndicator(password) {
    const strengthTextElement = document.getElementById("password-strength-indicator-text");

    if (password.length === 0) {
        strengthTextElement.textContent = '';
    } else {
        const strength = checkPasswordStrength(password);

        if (strength.length === 0) {
            strengthTextElement.textContent = 'Strong';
            strengthTextElement.style.color = 'green';
        } else if (password.length < 6) {
            strengthTextElement.textContent = 'Weak';
            strengthTextElement.style.color = 'red';
        } else if (password.length < 8) {
            strengthTextElement.textContent = 'Moderate';
            strengthTextElement.style.color = 'orange';
        } else {
            strengthTextElement.textContent = 'Strong';
            strengthTextElement.style.color = 'green';
        }
    }
}

function checkPasswordStrength(password) {
    const requirements = [
        {
            condition: password.length >= 8,
            message: 'Password must be at least 8 characters long.'
        },
        {
            condition: /[a-z]/.test(password),
            message: 'Password must contain at least one lowercase letter.'
        },
        {
            condition: /[A-Z]/.test(password),
            message: 'Password must contain at least one uppercase letter.'
        },
        {
            condition: /\d/.test(password),
            message: 'Password must contain at least one digit.'
        },
        {
            condition: /[@$!%*?&]/.test(password),
            message: 'Password must contain at least one special character (@$!%*?&).'
        }
    ];

    return requirements.filter(req => !req.condition).map(req => req.message);
}

document.addEventListener("DOMContentLoaded", function () {
    const passwordInput = document.getElementById("password");

    passwordInput.addEventListener('input', function () {
        updatePasswordStrengthIndicator(this.value);
    });
});
