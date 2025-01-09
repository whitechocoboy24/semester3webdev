<?php
// Include the database connection file
require_once 'db_connection.php';

// Get the search query from the URL
$query = $_GET['query'];

// Retrieve orders from the database that match the search query
$search_query = "SELECT * FROM orders WHERE customer_name LIKE '%$query%' OR name LIKE '%$query%' OR pickupadd LIKE '%$query%' OR deliveryadd LIKE '%$query%' OR Date LIKE '%$query%'";
$search_result = $conn->query($search_query);

// Display the search results in a table
$search_table = "<table>";
$search_table .= "<tr><th>Order ID</th><th>Customer Name</th><th>Price</th><th>Quantity</th><th>Product Name</th><th>Pickup Address</th><th>Delivery address</th><th>Order Date</th></tr>";
while ($row = $search_result->fetch_assoc()) {
    $search_table .= "<tr>";
    $search_table .= "<td>" . $row['id'] . "</td>";
    $search_table .= "<td>" . $row['customer_name'] . "</td>";
    $search_table .= "<td>Rs. " . number_format($row['price'], 2) . "</td>";
    $search_table .= "<td>" . $row['quantity'] . "</td>";
    $search_table .= "<td>" . $row['name'] . "</td>";
    $search_table .= "<td>" . $row['pickupadd'] . "</td>";
    $search_table .= "<td>" . $row['deliveryadd'] . "</td>";
    $search_table .= "<td>" . $row['Date'] . "</td>";
    $search_table .= "</tr>";
}
$search_table .= "</table>";

// Close the database connection
close_connection();
?>

<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.0.9/css/box icons.min.css' rel='stylesheet'>
    <!-- My CSS -->
    <link rel="stylesheet" href="staff.css">
    <link rel="stylesheet" href="statuscss.css">

    <title>SpinCycle</title>
</head>
<body>

    <!-- SIDEBAR -->
    <section id="sidebar">
        <a href="#" class="brand">
            <i class='bx bxs-smile'></i>
            <span class="text">SpinCycle</span>
        </a>
        <ul class="side-menu top">
            <li > 
                <a href="staff.php">
                    <i class='bx bxs-dashboard' ></i>
                    <span class="text">Orders</span>
                </a>
            </li>
            <li class="active">
                <a href="#">
                    <i class='bx bxs-shopping-bag-alt' ></i>
                    <span class="text">Delivered Orders</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <i class='bx bxs-doughnut-chart' ></i>
                    <span class="text">Picked-Up Orders</span>
                </a>
            </li>
            <li>
                <a href="adminfeedbacks .html">
                    <i class='bx bxs-message-dots' ></i>
                    <span class="text">Feedbacks</span>
                </a>
            </li>
            <li>
                <a href="dashboardteam.html">
                    <i class='bx bxs-group' ></i>
                    <span class="text">Team</span>
                </a>
            </li>
        </ul>
        <ul class="side-menu">
            <li>
                <a href="#">
                    <i class='bx bxs-cog' ></i>
                    <span class="text">Settings</span>
                </a>
            </li>
            <li>
                <a href="login.html" class="logout">
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
            <i class='bx bx-menu' ></ i>
            <a href="#" class="nav-link">Categories</a>
            <form action="search.php" method="get">
                <div class="form-input">
                    <input type="search" name="query" placeholder="Search...">
                    <button type="submit" class="search-btn"><i class='bx bx-search' ></i></button>
                </div>
            </form>
            <input type="checkbox" id="switch-mode" hidden>
            <label for="switch-mode" class="switch-mode"></label>
            <a href="#" class="notification">
                <i class='bx bxs-bell' ></i>
                <span class="num">8</span>
            </a>
            <a href="#" class="profile">
                <img src="img/people.png">
            </a>
        </nav>
        <!-- NAVBAR -->

        <!-- MAIN -->
        <main>
            <div class="head-title">
                <div class="left">
                    <h1>Search Results</h1>
                    <ul class="breadcrumb">
                        <li>
                            <a href="#">Orders</a>
                        </li>
                        <li><i class ='bx bx-chevron-right' ></i></li>
                        <li>
                            <a class="active" href="#">Home</a>
                        </li>
                    </ul>
                </div>
                <a href="#" class="btn-download">
                    <i class='bx bx-plus'></i>
                    <span class="text">Add New Staff</span>
                </a>
            </div>

            <div class="table-data">
                <div class="order">
                    <div class="head">
                        <h3>Search Results</h3>
                        <i class='bx bx-search' ></i>
                        <i class='bx bx-filter' ></i>
                    </div>
                    <?php echo $search_table; ?>
                </div>
               
            </div>
        </main>
        <!-- MAIN -->
    </section>
    <!-- CONTENT -->


    <script src="adminhome.js"></script>
</body>
</html>