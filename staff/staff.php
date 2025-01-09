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

// Retrieve the profile picture from the database
$query = "SELECT profile_picture FROM staff WHERE username = '".$_SESSION['username']."'";
$result = $conn->query($query);
$staff_row = $result->fetch_assoc();

// Retrieve orders from the database
if (isset($_POST['filter_orders'])) {
    $month = $_POST['month'] ?? '';
    $year = $_POST['year'] ?? '';
    $day = $_POST['day'] ?? '';
    if ($month != "" && $year != "" && $day != "") {
        $query = "SELECT * FROM orders WHERE MONTH(Date) = '$month' AND YEAR(Date) = '$year' AND DAY(Date) = '$day'";
    } elseif ($month != "" && $year != "") {
        $query = "SELECT * FROM orders WHERE MONTH(Date) = '$month' AND YEAR(Date) = '$year'";
    } elseif ($month != "") {
        $query = "SELECT * FROM orders WHERE MONTH(Date) = '$month'";
    } elseif ($year != "") {
        $query = "SELECT * FROM orders WHERE YEAR(Date) = '$year'";
    } else {
        $query = "SELECT * FROM orders";
    }
} elseif (isset($_POST['search'])) {
    $search = $_POST['search'];
    $query = "SELECT * FROM orders WHERE customer_name LIKE '%$search%' OR name LIKE '%$search%' OR pickupadd LIKE '%$search%'";
} else {
    $query = "SELECT * FROM orders";
}
$result = $conn->query($query);
// Initialize a variable to count the number of sales had gone user selected date
$total_sales = 0;
// Initialize a variable to count the number of orders got on the selected date
$total_orders = 0;
// Initialize a variable to count the number of customers
$visitors = array();
// Initialize a variable to count the number of total profit to the user selected date orders
$total_profit = 0;
// Initialize a variable to count the number of delivered orders
$total_delivered_orders = 0;
// Initialize a variable to count the number of pickedup orders
$total_pickedup_orders = 0;

if ($result->num_rows > 0) {
    // Display orders in a table
    $orders_table = "<table>";
    $orders_table .= "<tr><th>Order ID</th><th>Customer Name</th><th>Price</th><th>Quantity</th><th>Product Name</th><th>Pickup Address</th><th>Delivery address</th><th>Order Date</th><th>Update status</th></tr>";
    while ($row = $result->fetch_assoc()) {
        $total_sales += $row['price'];
        $total_orders += $row['quantity'];
        $visitors[] = $row['customer_name'];
        
        // Calculate profit for each order
        $profit = $row['price'] * 0.15; 
        $total_profit += $profit;
        
        // Check if the order is pickedup
        if ($row['status'] == 'pickedup') {
            $total_pickedup_orders++;
        }

        // Check if the order is delivered
        if ($row['status'] == 'delivered') {
            $total_delivered_orders++;
        }
        
        $orders_table .= "<tr>";
        $orders_table .= "<td>" . $row['id'] . "</td>";
        $orders_table .= "<td>" . $row['customer_name'] . "</td>";
        $orders_table .= "<td>Rs. " . number_format($row['price'], 2) . "</td>";
        $orders_table .= "<td>" . $row['quantity'] . "</td>";
        $orders_table .= "<td>" . $row['name'] . "</td>";
        $orders_table .= "<td>" . $row['pickupadd'] . "</td>";
        $orders_table .= "<td>" . $row['deliveryadd'] . "</td>";
        $orders_table .= "<td>" . $row['Date'] . "</td>";

        $orders_table .= "<td>
        <form action='' method='post' class='update-status-form'>
            <input type='hidden' name='id' value='" . $row['id'] . "'>
            <select name='status'>
                <option value='pickedup' " . ($row['status'] == 'pickedup' ? 'selected' : '') . "><span class='status pending'>Picked Up</span></option>
                <option value='delivered' " . ($row['status'] == 'delivered' ? 'selected' : '') . "><span class='status completed'>Delivered</span></option>
            </select>
            <button type='submit' name='update_status'>Update</button>
        </form>
    </td>";
    }
    $orders_table .= "</table>";
} else {
    $orders_table = "No orders found.";
}

$unique_visitors = count(array_unique($visitors));

$unique_visitors = count(array_unique($visitors));
// Update status
if (isset($_POST['update_status'])) {
    $id = $_POST['id'];
    $status = $_POST['status'];
    $query = "UPDATE orders SET status = '$status' WHERE id = '$id'";
    $conn->query($query);
    

    
    header('Location: staff.php');
    exit;
}

// Retrieve new orders from today's date
$today = date("Y-m-d");
$query = "SELECT * FROM orders WHERE Date = '$today'";
$result = $conn->query($query);
$new_orders = $result->num_rows;



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

    <title>Staff Dashboard</title>
</head>
<body>

    <!-- SIDEBAR -->
    <section id="sidebar">
        <a href="#" class="brand">
            
            <span class="text"><h4>SpinCycle</h4></span>
        </a>
        <ul class="side-menu top">
        <li>
                <a href="profanima.php">
                <i class="bx bxs-truck"></i>
                    <span class="text">Manage Profile</span>
                </a >
            </li>
            <li class="active"> <!--this is the page mover-->
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
            <button type="submit" class="search-btn"><i class='bx bx-search' ></i></button>
        </div>
    </form>

            <a href="#" class="notification" data-toggle="tooltip" data-placement="bottom" title="
                <?php
                if ($new_orders > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "Order ID: " . $row['id'] . ", Customer Name: " . $row['customer_name'] . ", Order Date: " . $row['Date'] . "<br>";
                    }
                } else {
                    echo "No new orders today.";
                }
                ?>
            ">
                <i class='bx bxs-bell' ></i>
                <span class="num"><?php echo $new_orders; ?></span>
            </a>
            <span class="username">Welcome back, <?php echo $_SESSION['username']; ?></span>
         <a href="profanima.php" class="profile">
         <img src="logo/<?php echo $staff_row['profile_picture']; ?>" alt="Profile Picture">
            </a>
            </a>
        </nav>
        <!-- NAVBAR -->

        <!-- MAIN -->
        <main>
            <div class="head-title">
                <div class="left">
                    <h1>Orders</h1>
                    <ul class="breadcrumb">

                        <li><i class ='bx bx-chevron-right' ></i></li>
                        <li>
                            <a class="active" href="staff.php">All Orders</a>
                        </li>
                    </ul>
                </div>

            </div>
            <ul class="box-infod">
    <li>
        <i class='bx bx-calendar-week'></i>
        <div class="form-filter">
            <form action="" method="post">
                <select name="month" id="month">
                    <option value="">Select Month</option>
                    <option value="01">January</option>
                    <option value="02">February</option>
                    <option value="03">March</option>
                    <option value="04">April</option>
                    <option value="05">May</option>
                    <option value="06">June</option>
                    <option value="07">July</option>
                    <option value="08">August</option>
                    <option value="09">September</option>
                    <option value="10">October</option>
                    <option value="11">November</option>
                    <option value="12">December</option>
                </select>
                <select name="year" id="year">
                    <option value="">Select Year</option>
                    <option value="2021">2021</option>
                    <option value="2022">2022</option>
                    <option value="2023">2023</option>
                    <option value="2024">2024</option>
                    <option value="2025">2025</option>
                    <option value="2026">2026</option>
                    <option value="2027">2027</option>
                    <option value="2028">2028</option>
                    <option value="2029">2029</option>
                    <option value="2030">2030</option>
                    </select>
                <select name="day" id="day">
                    <option value="">Select Day</option>
                    <?php for ($i = 1; $i <= 31; $i++) { ?>
                        <option value="<?php echo str_pad($i, 2, '0', STR_PAD_LEFT); ?>"><?php echo $i; ?></option>
                    <?php } ?>
                </select>
                <button type="submit" name="filter_orders">filter</button>
            </form>
        </div>
    </li>
</ul>
<script>
window.addEventListener('load', function() {
  var countElement = document.querySelector('.countUp');
  var count = 0;
  var interval = setInterval(function() {
    if (count < <?php echo $total_pickedup_orders; ?>) {
      count++;
      countElement.textContent = count;
    } else {
      clearInterval(interval);
    }
  }, 180);
})
</script>
            <ul class="box-info">
    <li>
        <i class='bx bxs-calendar-check' ></i>
        <span class="text">
        <h3><?php echo $total_delivered_orders; ?></h3>
        <div class="stats">
        Delivered
        </div>
        </span>
    </li>

    <li>
    <i class='bx bxs-calendar-check' ></i>
        <span class="text">
        <h3 class="countUp"><?php echo $total_pickedup_orders; ?></h3>
        <div class="stats">pickedup</p>
        </span>
    </li>

    <li>
        <i class='bx bxs-group' ></i>
        <span class="text">
            <h3><?php echo $unique_visitors; ?></h3>
            <div class="stats">Customers</p>
        </span>
    </li>

    <li>
        <i class='bx bxs-dollar-circle' ></i>
        <span class="text">
            <h3>Rs. <?php echo number_format($total_sales, 2); ?></h3>
            <div class="stats">Total Sales</p>
        </span>
    </li>

    <li>
    <i class='bx bx-trending-up' ></i>
        <span class="text">
            <h3>Rs. <?php echo number_format($total_profit, 2); ?></h3>
            <div class="stats">Total Profit</p>
        </span>
    </li>
    <!--
    <li>
    <i class='bx bx-trending-down'></i>
        <span class="text">
            <h3>Rs. <?php echo number_format($total_profit, 2); ?></h3>
            <p>Total Loss</p>
        </span>
    </li>p-->

</ul>

            <div class="table-data">
                <div class="order">
                    <div class="head">
                        <h3>Recent Orders</h3>
                       <!-- <i class='bx bx-search' ></i>-->
                       <i class='bx bx-filter' id="filterButton"></i> 
            </div>
            <script>
                $("#filterButton").click(function() {
    var clickedColumn = $(this).data('field'); // Get the column being clicked

    if (sortColumns[clickedColumn]) { 
        var sortOrder = sortColumns[clickedColumn].order;
        sortTable(clickedColumn, sortOrder);

        // Toggle sort order
        sortColumns[clickedColumn].order = (sortOrder === 'asc') ? 'desc' : 'asc';
    }
});

function sortTable(column, order) {
    var table = $('#ordersTable');
    var tbody = table.find('tbody');
    var rows = tbody.find('tr');

    rows.sort(function(a, b) {
        var aVal = $(a).find('td:eq(' + $(a).find('th:contains(' + column + ')').index() + ')').text();
        var bVal = $(b).find('td:eq(' + $(b).find('th:contains(' + column + ')').index() + ')').text();

        if (column === 'Order Date') { // Assuming date format is YYYY-MM-DD
            aVal = new Date(aVal); 
            bVal = new Date(bVal);
        }

        if (order === 'asc') {
            return aVal > bVal ? 1 : -1;
        } else {
            return aVal < bVal ? 1 : -1;
        }
    });

    tbody.empty().append(rows);
}
            </script>
            <table id="ordersTable">

                    </div>
                    <?php echo $orders_table; ?>
                </div>
               
            </div>
        </main>
        <!-- MAIN -->
    </section>
    <!-- CONTENT -->


    <script src="staff.js"></script>
</body>
</html>