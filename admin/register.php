<?php
session_start();
include ".../db.php"; // Correct relative path

header('Content-Type: application/json'); // Return JSON

if(isset($_POST["admin_register"])) {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);
    $cpassword = trim($_POST["cpassword"]);

    // Validate fields
    if(empty($name) || empty($email) || empty($password) || empty($cpassword)){
        echo json_encode(['status'=>303, 'message'=>'All fields are required']);
        exit();
    }

    if($password !== $cpassword){
        echo json_encode(['status'=>303, 'message'=>'Passwords do not match']);
        exit();
    }

    // Check if email exists
    $stmt = $con->prepare("SELECT id FROM admin WHERE email=? LIMIT 1");
    $stmt->bind_param("s",$email);
    $stmt->execute();
    $res = $stmt->get_result();
    if($res->num_rows > 0){
        echo json_encode(['status'=>303, 'message'=>'Email already registered']);
        exit();
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Insert admin
    $stmt = $con->prepare("INSERT INTO admin (name,email,password,is_active) VALUES (?,?,?,1)");
    $stmt->bind_param("sss",$name,$email,$hashed_password);

    if($stmt->execute()){
        echo json_encode(['status'=>202, 'message'=>'Admin registered successfully']);
    } else {
        echo json_encode(['status'=>303, 'message'=>'Registration failed. Try again.']);
    }
    exit();
}
?>
