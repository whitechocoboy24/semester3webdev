<?php
// Include the database connection file
require_once 'db_connection.php';

// Function to validate the form data
function validateForm() {
    $userName = $_POST["userName"];
    $password = $_POST["password"];

    
}

// Start the session
session_start();

// ...

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate the form data
    validateForm();

    // Check if the username and password are correct
    $sql = "SELECT * FROM staff WHERE username = '".$_POST["userName"]."'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($_POST["password"] === $row["password"]) {
            // Login successful
            $_SESSION['username'] = $_POST["userName"];
            header('Location: loginanimation.php');
            exit;
        } else {
            // Login failed
            header('Location: incorrectpass.php');
            exit;
        }
    } else {
        // Username not found
        header('Location: incorrectpass.php');
        exit;
    }
}

// Close the connection
close_connection();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=2, user-scalable=yes">
    <title>Login</title>
    <link rel="icon" type="image/png" href="webpics/web-bgs/roundlogo.png" >
    <link rel="stylesheet" href="nav.css">
    <link rel="stylesheet" href="logincss.css">
    <style>
        .error {
            color: red;
            font-size: 12px;
        }
    </style>
</head>
<body>

            <div class="wrapper">
                <h6>Welcome to spincycle...</h6>

                <div class="logo">
                    <img src="roundlogo.png" alt="">
                </div>

                <form id="loginForm" class="p-3 mt-3" action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" onsubmit="return validateForm()">

                <div class="form-field d-flex align-items-center">
                <span class="far fa-user"></span>
                <input type="text" name="userName" id="userName" placeholder="Username">
                <span id="userNameError" class="error"></span>
            </div>

                    <div class="form-field d-flex align-items-center">
                        <span class="fas fa-key"></span>
                        <input type="password" name="password" id="pwd" placeholder="Password">
                        <span id="passwordError" class="error"></span>
                    </div>
                    <button class="btn mt-3">Login</button>
                    <div id="error-message"></div>
                </form>
                <div class="text-center fs-6">
                    <a href="joinus.php">Don't have an account? Register</a>
                </div>
            </div>
            <script>
        function validateForm() {
            // Clear previous errors
            clearErrors();

            // Check if fields are empty
            let isValid = true;

            if (document.getElementById('userName').value.trim() === '') {
                document.getElementById('userNameError').textContent = "*Username is required";
                isValid = false;
            }
            if (document.getElementById('pwd').value.trim() === '') {
                document.getElementById('passwordError').textContent = "*Password is required";
                isValid = false;
            }


            // If any fields are invalid, prevent form submission
            return isValid;
        }

        function clearErrors() {
            const errorFields = ['userNameError', 'passwordError'];
            errorFields.forEach(function(field) {
                document.getElementById(field).textContent = '';
            });
        }
    </script>
    
     
</body>
    </html>