
function validatePassword(password) {
    const passwordPattern = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/;
    return passwordPattern.test(password);
}

function validateForm(formId) {
    const form = document.getElementById(formId);
    let isValid = true;
    let errorMessages = [];

    form.querySelectorAll('input').forEach((input) => {
        const value = input.value.trim();
        
        if (input.hasAttribute('required') && value === "") {
            isValid = false;
            errorMessages.push(`${input.name} is required.`);
            input.style.borderColor = "red";
        } else {
            input.style.borderColor = "";
        }

        if (input.type === 'password' && value !== "" && !validatePassword(value)) {
            isValid = false;
            errorMessages.push("Password must be at least 8 characters long and contain at least one letter and one number.");
            input.style.borderColor = "red";
        }
    });

    form.querySelectorAll('textarea').forEach((textarea) => {
        const value = textarea.value.trim();

        if (textarea.hasAttribute('required') && value === "") {
            isValid = false;
            errorMessages.push(`${textarea.name} is required.`);
            textarea.style.borderColor = "red";
        } else {
            textarea.style.borderColor = "";
        }
    });

    const errorContainer = document.getElementById('error-messages');
    if (errorMessages.length > 0) {
        errorContainer.innerHTML = errorMessages.join('<br>');
        errorContainer.style.display = 'block';
    } else {
        errorContainer.style.display = 'none';
    }

    return isValid;
}

document.addEventListener('DOMContentLoaded', function () {
    const registerForm = document.getElementById('register-form');
    const createBlogForm = document.getElementById('create-blog-form');

    if (registerForm) {
        registerForm.addEventListener('submit', function (e) {
            if (!validateForm('register-form')) {
                e.preventDefault();
            }
        });
    }

    if (createBlogForm) {
        createBlogForm.addEventListener('submit', function (e) {
            if (!validateForm('create-blog-form')) {
                e.preventDefault();
            }
        });
    }
});
