<?php

define('HOST', 'localhost');
define('USER', 'root');
define('PASSWORD', '');
define('DATABASE_NAME', 'ecommerce_site');

define('CURRENCY', 'Rs.');

// Create database connection
$con = new mysqli(HOST, USER, PASSWORD, DATABASE_NAME);

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}
?>