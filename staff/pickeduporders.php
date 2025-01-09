<?php
// Include the database connection file
require_once 'db_connection.php';
session_start();
if (isset($_GET['logout']) && $_GET['logout'] == 'true') {
    session_start();
    session_destroy();
    header('Location: login.php');
    exit;
}

// Retrieve the profile picture from the database
$query = "SELECT profile_picture FROM staff WHERE username = '".$_SESSION['username']."'";
$result = $conn->query($query);
$staff_row = $result->fetch_assoc();

// Retrieve orders from the database
$query = "SELECT * FROM orders WHERE status = 'pickedup'";
$result = $conn->query($query);

$total_sales = 0;
$total_orders = 0;
$visitors = array();

if ($result->num_rows > 0) {
    // Display orders in a table
    $orders_table = "<table>";
    $orders_table .= "<tr><th>Order ID</th><th>Customer Name</th><th>Price</th><th>Quantity</th><th>Product Name</th><th>Pickup Address</th><th>Delivery address</th><th>Order Date</th></tr>";
    while ($row = $result->fetch_assoc()) {
        $total_sales += $row['price'];
        $total_orders += $row['quantity'];
        $visitors[] = $row['customer_name'];
        $orders_table .= "<tr>";
        $orders_table .= "<td>" . $row['id'] . "</td>";
        $orders_table .= "<td>" . $row['customer_name'] . "</td>";
        $orders_table .= "<td>Rs. " . number_format($row['price'], 2) . "</td>";
        $orders_table .= "<td>" . $row['quantity'] . "</td>";
        $orders_table .= "<td>" . $row['name'] . "</td>";
        $orders_table .= "<td>" . $row['pickupadd'] . "</td>";
        $orders_table .= "<td>" . $row['deliveryadd'] . "</td>";
        $orders_table .= "<td>" . $row['Date'] . "</td>";
        $orders_table .= "</tr>";
    }
    $orders_table .= "</table>";
} else {
    $orders_table = "No orders found.";
}

$unique_visitors = count(array_unique($visitors));

// Close the database connection
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
    <link rel="stylesheet" href="statuscss.css">

    <title>pickedup</title>
</head>
<body>

    <!-- SIDEBAR -->
    <section id="sidebar">
        <a href="#" class="brand">
           
            <span class="text">SpinCycle</span>
        </a>
        <ul class="side-menu top">
        <li>
                <a href="profanima.php">
                <i class="bx bxs-truck"></i>
                    <span class="text">Manage Profile</span>
                </a >
</li> 
            <li > 
                <a href="loadingstaff.php">
                    <i class='bx bxs-dashboard' ></i>
                    <span class="text">Orders</span>
                </a>
            </li>
            <li>
                <a href="loadingdeliv.php">
                <i class="bx bxs-box"></i>
                    <span class="text">Delivered Orders</span>
                </a>
            </li>
            <li class="active">
                <a href="loadingpick.php">
                <i class="bx bxs-truck"></i>
                    <span class="text">Picked-Up Orders</span>
                </a>
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
            <form action="#">
                <div class="form-input">

                </div>
            </form>

            <a href="#" class="notification">
                <i class='bx bxs-bell' ></i>
                <span class="num">8</span>
            </a>
            <span class="username">Welcome back, <?php echo $_SESSION['username']; ?></span>
         <a href="profanima.php" class="profile">
         <img src="logo/<?php echo $staff_row['profile_picture']; ?>" alt="Profile Picture">
            </a>

        </nav>
        <!-- NAVBAR -->

        <!-- MAIN -->
        <main>
            <div class="head-title">
                <div class="left">
                    <h1>Picked-Up Orders</h1>
                    <ul class="breadcrumb">

                        <li><i class ='bx bx-chevron-right' ></i></li>
                        <li>
                            <a class="active" href="pickeduporders.php">Picked-Up</a>
                        </li>
                    </ul>
                </div>

            </div>

          

            <div class="table-data">
                <div class="order">
                    <?php echo $orders_table; ?>
                </div>
               
            </div>
        </main>
        <!-- MAIN -->
    </section>
    <!-- CONTENT -->


    <script src="adminhome.js"></script>
</body>
</html>