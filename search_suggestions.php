<?php
include "config/constants.php";
$con = new mysqli(HOST, USER, PASSWORD, DATABASE_NAME);

if (isset($_POST['query'])) {
    $query = $con->real_escape_string($_POST['query']);
    $sql = "SELECT product_title FROM products WHERE product_title LIKE '%$query%' LIMIT 5";
    $result = $con->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<a href="#" class="list-group-item list-group-item-action suggestion-item">' . $row['product_title'] . '</a>';
        }
    } else {
        echo '<a href="#" class="list-group-item list-group-item-action disabled">No results found</a>';
    }
}
?>