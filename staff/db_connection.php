<?php
// Configuration
$db_host = 'bngohgshca7ox8tkhqsb-mysql.services.clever-cloud.com';
$db_username = 'ufoppiogo8gnqlkh';
$db_password = 'lVvFHvaxTHJk8aTbdncB';
$db_name = 'bngohgshca7ox8tkhqsb';

// Create connection
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to close the connection
function close_connection() {
    global $conn;
    $conn->close();
}

// Include this file in your PHP scripts to establish a connection
?>