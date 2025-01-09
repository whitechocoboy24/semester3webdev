function validateForm() {
    var userName = document.getElementById("userName").value;
    var password = document.getElementById("pwd").value;
    var hostname = window.location.hostname;

    if (userName === "") {
        alert("Please fill the username to access the staff dashboard");
        return false;
    } else if (password === "") {
        alert("Please fill the password to access dashboard");
        return false;
    }

    return true;
}