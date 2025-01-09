<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // If they are not logged in, redirect them to the login page
    header('Location: login.php');
    exit;
}

// Include the database connection file
require_once 'db_connection.php';

// Retrieve the staff member's profile information from the database
$query = "SELECT * FROM staff WHERE username = '".$_SESSION['username']."'";
$result = $conn->query($query);
$staff_row = $result->fetch_assoc();

// Retrieve new orders from today's date
$today = date("Y-m-d");
$query = "SELECT * FROM orders WHERE Date = '$today'";
$result = $conn->query($query);
$new_orders = $result->num_rows;

// Retrieve the orders for the current date
$current_date_orders = array();
$query = "SELECT * FROM orders WHERE Date = '$today'";
$result = $conn->query($query);
while ($order_row = $result->fetch_assoc()) {
    $current_date_orders[] = $order_row;
}

// Check if the form has been submitted
$error_message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the user wants to edit their username
    if (isset($_POST['save'])) {
        $old_username = $_SESSION['username'];
        $new_username = $_POST['userName'];
        $new_gmail = $_POST['gmail'];

        // Check if the new username already exists in the database
        $query = "SELECT * FROM staff WHERE username = '$new_username'";
        $result = $conn->query($query);
        if ($result->num_rows > 0) {
            // If the new username already exists, display an error message
            $error_message = "Username already exists. Please choose a different username.";
        } else {
            // If the new username does not exist, update the username in the database
            $query = "UPDATE staff SET username = '$new_username', gmail = '$new_gmail' WHERE username = '$old_username'";
            $conn->query($query);

            // Update the session variable with the new username
            $_SESSION['username'] = $new_username;

            // Retrieve the updated profile information from the database
            $query = "SELECT * FROM staff WHERE username = '".$_SESSION['username']."'";
            $result = $conn->query($query);
            $staff_row = $result->fetch_assoc();
        }
    }
}

// Close the connection
close_connection();
?>

<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <!-- My CSS -->
    <link rel="stylesheet" href="staff.css">
    <link rel="stylesheet" href="profile.css">   
     <style>
        .error {
            color: red;
            font-size: 12px;
        }
    </style>

    <title>Profile</title>
</head>
<body>

     <!-- SIDEBAR -->
     <section id="sidebar">
        <a href="#" class="brand">
            
            <span class="text"><h4>SpinCycle</h4></span>
        </a>
        <ul class="side-menu top">
        <li class="active">
                <a href="profanima.php">
                <i class="bx bxs-truck"></i>
                    <span class="text">Manage Profile</span>
                </a >
            </li>
            <li> <!--this is the page mover-->
                <a href="loadingstaff.php">
                    <i class='bx bxs-dashboard' ></i>
                    <span class="text">Orders</span>
                </a>
            </li>
            <li>
                <a href="loading deliv.php">
                <i class="bx bxs-box"></i>
                    <span class="text">Delivered Orders</span>
               
                </a>
            </li>
            <li>
                <a href="loadingpick.php">
                <i class="bx bxs-truck"></i>
                    <span class="text">Picked-Up Orders</span>
                </a >
            </li>

        </ul>
        <ul class="side-menu">

            <li>
                <a href="logoutanimation.php" class="logout">
                    <i class='bx bxs-log-out-circle' ></i>
                    <span class="text">Logout</span>
                </a>
            </li>
        </ul>
    </section>
    <!-- SIDEBAR -->


    <!-- CONTENT -->
    <section id="content">
        <!-- NAVBAR -->
        <nav>
            <i class='bx bx-menu' ></i>
            <a href="#" class="nav-link">Categories</a>
            <form action="" method="post">
        <div class="form-input">
            <input type="search" name="search" placeholder="Search...">
            <button type="submit" class="search-btn"><i class='bx bx-search'></i></button>
        </div>
    </form>

            <span class="username">Welcome back, <?php echo $_SESSION['username']; ?></span>
            <a href="#" class="profile">
                <img src="roundlogo.png">
            </a>
        </nav>
        <div class="wrapper">
    <div class="profilepic">
    <a href="#" class="profile">
                <img src="roundlogo.png">
            </a>
    </div>
    <h2>Staff ID</h2>
<div class="form-field d-flex align-items-center">
    <div class="profiledata"><?php echo $staff_row['id']; ?></div>
</div>
<h2>Username</h2>
<div class="form-field d-flex align-items-center">
  
    <div class="profiledata"><?php echo $staff_row['username']; ?></div>
</div>
<h2>Gmail Id</h2>
<div class="form-field d-flex align-items-center">
  
    <div class="profiledata"><?php echo $staff_row['gmail']; ?></div>
</div>
<h2>Joined On</h2>
<div class="form-field d-flex align-items-center">

    <div class="profiledata"><?php echo $staff_row['date']; ?></div>
</div>
</div>
</section>
</body>
</html>