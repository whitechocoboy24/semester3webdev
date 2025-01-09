<?php
// Include the database connection file
require_once 'db_connection.php';

// Function to validate the form data
function validateForm() {
    $userName = $_POST["userName"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $verifyPassword = $_POST["verifyPassword"];

    if (strlen($password) < 8) {
        echo "Your password must have at least 8 characters";
        header("Refresh:2; url=".$_SERVER["PHP_SELF"]);
        exit;
    } else if ($password !== $verifyPassword) {
        echo "Passwords do not match";
        header("Refresh:2; url=".$_SERVER["PHP_SELF"]);
        exit;
    }
}
// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the user wants to edit their profile picture
    if (isset($_POST['upload_picture'])) {
        // ... (rest of the code remains the same)

        // if everything is ok, try to upload file
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                echo "The file " . basename($_FILES["fileToUpload"]["name"]) . " has been uploaded.";

                // Update the database to store the filename
                $filename = basename($_FILES["fileToUpload"]["name"]);
                $query = "UPDATE staff SET profile_picture = '$filename' WHERE username = '".$_SESSION['username']."'";
                $conn->query($query);
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    }
}
// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    validateForm();

    // Check if the email is already registered
    $sql = "SELECT * FROM staff WHERE gmail = '".$_POST["email"]."'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "Email already registered. Please try again.";
        header("Refresh:2; url=".$_SERVER["PHP_SELF"]);
        exit;
    }

    // Check if the username is already taken
    $sql = "SELECT * FROM staff WHERE username = '".$_POST["userName"]."'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "Username already taken";
        header("Refresh:2; url=".$_SERVER["PHP_SELF"]);
        exit;
    }

// Upload the profile picture
if (!empty($_FILES['profile_picture']['name'])) {
    $profile_picture = $_FILES['profile_picture'];
    $upload_path = 'logo/';
    $profile_picture_name = $profile_picture['name'];
    move_uploaded_file($profile_picture['tmp_name'], $upload_path . $profile_picture_name);
} else {
    $profile_picture_name = 'default.jpg'; // or any other default image
}
    // Insert the data into the table
    $sql = "INSERT INTO staff (username, gmail, password, profile_picture) VALUES ('".$_POST["userName"]."', '".$_POST["email"]."', '".$_POST["password"]."', '".$profile_picture_name."')";

    // Check if the query was successful
    if ($conn->query($sql) === TRUE) {
        header("Refresh:1; url=reganima.php");
        echo "Registration successful";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Close the connection
    close_connection();

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=2, user-scalable=yes">
    <title>Register</title>
    <link rel="icon" type="image/png" href="webpics/web-bgs/roundlogo.png">
    <link rel="stylesheet" href="nav.css">
    <link rel="stylesheet" href="joinuscss.css">
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


        <script>
            document.getElementById('profile_picture').addEventListener('change', function() {
                const file = this.files[0];
                const reader = new FileReader();
                reader.onload = function(event) {
                    document.getElementById('profile_picture_preview').src = event.target.result;
                };
                reader.readAsDataURL(file);
            });
        </script>

<form id="registerForm" class="p-3 mt-3" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
<div class="profile-pic-container">
    <img src="roundlogo.png" alt="Profile Picture" id="profile_picture_preview">
    <input type="file" name="profile_picture" id="profile_picture" accept="image/*">
    <div class="overlay-text">upload profile pic</div>
</div>

<script>
    // Trigger the file input when the profile picture is clicked
    document.querySelector('.profile-pic-container').addEventListener('click', function() {
        document.getElementById('profile_picture').click();
    });

    // Preview the selected profile picture
    document.getElementById('profile_picture').addEventListener('change', function() {
        const file = this.files[0];
        const reader = new FileReader();
        reader.onload = function(event) {
            document.getElementById('profile_picture_preview').src = event.target.result;
        };
        reader.readAsDataURL(file);
    });
</script>
    <div class="form-field d-flex align-items-center">
        <span class="far fa-user"></span>
        <input type="text" name="userName" id="userName" placeholder="User name">
        <span id="userNameError" class="error"></span>
    </div>

    <div class="form-field d-flex align-items-center">
        <span class="fas fa-key"></span>
        <input type="email" name="email" id="email" placeholder="Email">
        <span id="emailError" class="error"></span>
    </div>
    <div class="form-field d-flex align-items-center">
        <span class="fas fa-key"></span>
        <input type="password" name="password" id="pwd" placeholder="Password">
        <span id="passwordError" class="error"></span>
    </div>
    <div class="form-field d-flex align-items-center">
        <span class="fas fa-key"></span>
        <input type="password" name="verifyPassword" id="verifyPwd" placeholder="Verify Password">
        <span id="verifyPasswordError" class="error"></span>
    </div>
    <button type="submit" class="btn mt-3">Register</button>
</form>
        <div class="text-center fs-6">
            <a href="login.php">Already have an account? Login</a>
        </div>
    </div>

    <script>
        function validateForm() {
            // Clear previous errors
            clearErrors();

            // Check if fields are empty
            let isValid = true;

            if (document.getElementById('userName').value.trim() === '') {
                document.getElementById('userNameError').textContent = "Username is required";
                isValid = false;
            }
            if (document.getElementById('email').value.trim() === '') {
                document.getElementById('emailError').textContent = "Email is required";
                isValid = false;
            }
            if (document.getElementById('pwd').value.trim() === '') {
                document.getElementById('passwordError').textContent = "Password is required";
                isValid = false;
            }
            if (document.getElementById('verifyPwd').value.trim() === '') {
                document.getElementById('verifyPasswordError').textContent = "Verify Password is required";
                isValid = false;
            }

            // If any fields are invalid, prevent form submission
            return isValid;
        }

        function clearErrors() {
            const errorFields = ['userNameError', 'emailError', 'passwordError', 'verifyPasswordError'];
            errorFields.forEach(function(field) {
                document.getElementById(field).textContent = '';
            });
        }
    </script>
</body>
</html>
