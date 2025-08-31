<?php
session_start();
include "db.php";

if (isset($_POST["f_name"])) {

    $f_name = trim($_POST["f_name"]);
    $l_name = trim($_POST["l_name"]);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $repassword = $_POST['repassword'];
    $mobile = trim($_POST['mobile']);
    $address1 = trim($_POST['address1']);
    $address2 = trim($_POST['address2']);

    // Validation regex
    $namePattern = "/^[a-zA-Z ]+$/";
    $emailPattern = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9]+(\.[a-z]{2,4})$/";
    $numberPattern = "/^[0-9]+$/";

    // Check for empty fields
    if (
        empty($f_name) || empty($l_name) || empty($email) || empty($password) || empty($repassword) ||
        empty($mobile) || empty($address1) || empty($address2)
    ) {

        echo "<div class='alert alert-warning'>
                <b>Please fill all fields!</b>
              </div>";
        exit();
    }

    // Validate first name
    if (!preg_match($namePattern, $f_name)) {
        echo "<div class='alert alert-warning'><b>First name is not valid!</b></div>";
        exit();
    }

    // Validate last name
    if (!preg_match($namePattern, $l_name)) {
        echo "<div class='alert alert-warning'><b>Last name is not valid!</b></div>";
        exit();
    }

    // Validate email
    if (!preg_match($emailPattern, $email)) {
        echo "<div class='alert alert-warning'><b>Email is not valid!</b></div>";
        exit();
    }

    // Validate password
    if (strlen($password) < 9) {
        echo "<div class='alert alert-warning'><b>Password is too short!</b></div>";
        exit();
    }

    if ($password !== $repassword) {
        echo "<div class='alert alert-warning'><b>Passwords do not match!</b></div>";
        exit();
    }

    // Validate mobile
    if (!preg_match($numberPattern, $mobile) || strlen($mobile) != 10) {
        echo "<div class='alert alert-warning'><b>Mobile number must be 10 digits!</b></div>";
        exit();
    }

    // Check if email already exists
    $sql = "SELECT user_id FROM user_info WHERE email = '$email' LIMIT 1";
    $check_query = mysqli_query($con, $sql);
    if (mysqli_num_rows($check_query) > 0) {
        echo "<div class='alert alert-danger'><b>Email already exists. Try another email address.</b></div>";
        exit();
    }

    // All validations passed, insert user
    $password_hash = md5($password);
    $sql = "INSERT INTO `user_info` (`first_name`, `last_name`, `email`, `password`, `mobile`, `address1`, `address2`) 
            VALUES ('$f_name', '$l_name', '$email', '$password_hash', '$mobile', '$address1', '$address2')";
    $run_query = mysqli_query($con, $sql);

    if ($run_query) {
        $_SESSION["uid"] = mysqli_insert_id($con);
        $_SESSION["name"] = $f_name;

        // Assign cart items for guest user to logged in user
        $ip_add = getenv("REMOTE_ADDR");
        $sql = "UPDATE cart SET user_id = '$_SESSION[uid]' WHERE ip_add = '$ip_add' AND user_id = -1";
        mysqli_query($con, $sql);

        echo "register_success";
        exit();
    } else {
        echo "<div class='alert alert-danger'><b>Registration failed. Please try again!</b></div>";
        exit();
    }
}
?>