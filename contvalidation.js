
function validateForm(event) {
    event.preventDefault(); 

    
    const name = document.querySelector('input[name="name"]').value;
    const email = document.querySelector('input[name="email"]').value;
    const phone = document.querySelector('input[name="phone"]').value;
    const message = document.querySelector('textarea[name="message"]').value;

    
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    
    if (!name || !/^[a-zA-Z\s]+$/.test(name)) {
        alert("Please enter a valid name (letters and spaces only).");
        return false;
    }

    if (!email || !emailRegex.test(email)) {
        alert("Please enter a valid email address.");
        return false;
    }

    
    if (!phone || !/^\d+$/.test(phone)) {
        alert("Please enter a valid phone number (numbers only).");
        return false;
    }
    if (message.length > 500) {
        alert("Message must not exceed 500 characters.");
        return false;
    }
    document.querySelector('form').submit();
}