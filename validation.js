document.getElementById("registrationForm").addEventListener("submit", function (e) {
    if (!validateForm()) {
        e.preventDefault();
    }
});

function validateForm() {
    const nameInput = document.getElementById("name").value.trim();
    const emailInput = document.getElementById("email").value.trim();
    const phoneInput = document.getElementById("phone").value.trim();
    const dobInput = document.getElementById("dob").value.trim();
    const passwordInput = document.getElementById("password").value.trim();
    const confirmPasswordInput = document.getElementById("confirm-password").value.trim();

    const nameError = document.getElementById("nameError");
    const emailError = document.getElementById("emailError");
    const phoneError = document.getElementById("phoneError");
    const dobError = document.getElementById("dobError");
    const passwordError = document.getElementById("passwordError");
    const confirmPasswordError = document.getElementById("confirmPasswordError");

    nameError.textContent = "";
    emailError.textContent = "";
    phoneError.textContent = "";
    dobError.textContent = "";
    passwordError.textContent = "";
    confirmPasswordError.textContent = "";

    let isValid = true;
    
    const nameRegex = /^[A-Za-z\s]+$/;
    if (!nameRegex.test(nameInput)) {
        nameError.textContent = "Name should only contain letters and spaces.";
        isValid = false;
    }

    
    const emailRegex = /^[A-Za-z][A-Za-z0-9._%+-]*@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/;
    if (!emailRegex.test(emailInput)) {
        emailError.textContent = "Invalid email format.";
        isValid = false;
    }

    
    const phoneRegex = /^(97|98)\d{8}$/;
    if (!phoneRegex.test(phoneInput)) {
        phoneError.textContent = "Phone number must start with 97 or 98 and be 10 digits.";
        isValid = false;
    }


    const today = new Date();
    const dob = new Date(dobInput);
    if (dobInput === "" || dob > today) {
        dobError.textContent = "Date of birth cannot be in the future.";
        isValid = false;
    }

    
    const passwordRegex = /^(?=.*[A-Z])(?=.*[!@#$%^&*])(?=.*\d).{8,}$/;
    if (!passwordRegex.test(passwordInput)) {
        passwordError.textContent =
            "Password must be at least 8 characters long, include a capital letter, a special character, and a number.";
        isValid = false;
    }

    
    if (passwordInput !== confirmPasswordInput) {
        confirmPasswordError.textContent = "Passwords do not match.";
        isValid = false;
    }

    return isValid;
}
