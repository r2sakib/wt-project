<?php

$host = "localhost";
$db_name = "portal";
$username = "root";
$password = ""; 

try {
    $conn = new mysqli($host, $username, $password, $db_name);
    $conn->set_charset("utf8mb4");

} catch (mysqli_sql_exception $e) {
    die("Database Connection Failed: " . $e->getMessage());
}

?>