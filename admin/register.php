<?php
include "db.php";
session_start();

if(isset($_POST["f_name"])) {
    $f_name = $_POST["f_name"];
    $l_name = $_POST["l_name"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $repassword = $_POST["repassword"];
    $mobile = $_POST["mobile"];
    $address1 = $_POST["address1"];
    $address2 = $_POST["address2"];

    if($password !== $repassword){
        echo "<span style='color:red;'>Passwords do not match.</span>";
        exit();
    }

    // Check if email exists
    $stmt = $con->prepare("SELECT user_id FROM user_info WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();
    if($res->num_rows > 0){
        echo "<span style='color:red;'>Email already registered.</span>";
        exit();
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert into DB
    $stmt = $con->prepare("INSERT INTO user_info (first_name,last_name,email,password,mobile,address1,address2) VALUES (?,?,?,?,?,?,?)");
    $stmt->bind_param("sssssss",$f_name,$l_name,$email,$hashed_password,$mobile,$address1,$address2);
    if($stmt->execute()){
        echo "register_success";
    } else {
        echo "<span style='color:red;'>Registration failed. Try again.</span>";
    }
}
?>
