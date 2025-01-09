
function validateForm() {
    var userName = document.getElementById("userName").value;
    var email = document.getElementById("email").value;
    var password = document.getElementById("pwd").value;
    var verifyPassword = document.getElementById("verifyPwd").value;
    var errorMessage = document.getElementById("error-message");

    if (userName === "") {
        errorMessage.innerHTML = "Please fill the username";
        return false;
    } else if (email === "") {
        errorMessage.innerHTML = "Please fill the email";
        return false;
    } else if (password === "") {
        errorMessage.innerHTML = "Please fill the password";
        return false;
    } else if (verifyPassword === "") {
        errorMessage.innerHTML = "Please fill the verify password";
        return false;
    } else if (password !== verifyPassword) {
        errorMessage.innerHTML = "Passwords do not match";
        return false;
    } else {
        errorMessage.innerHTML = "";
        return true;
    }
}