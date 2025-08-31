<?php
session_start();

class Credentials
{
    private $con;

    function __construct()
    {
        include_once("Database.php");
        $db = new Database();
        $this->con = $db->connect();
    }

    public function createAdminAccount($name, $email, $password)
    {
        $q = $this->con->query("SELECT email FROM admin WHERE email = '$email'");
        if ($q->num_rows > 0) {
            return ['status' => 303, 'message' => 'Email already exists'];
        } else {
            $password = password_hash($password, PASSWORD_BCRYPT, ["cost" => 8]);
            $q = $this->con->query("INSERT INTO `admin`(`name`, `email`, `password`, `is_active`) VALUES ('$name','$email','$password','0')");
            if ($q) {
                return ['status' => 202, 'message' => 'Admin Created Successfully'];
            }
        }
        return ['status' => 303, 'message' => 'Error creating admin'];
    }

    public function loginAdmin($email, $password)
    {
        $q = $this->con->query("SELECT * FROM admin WHERE email = '$email' LIMIT 1");
        if ($q->num_rows > 0) {
            $row = $q->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                $_SESSION['admin_name'] = $row['name'];
                $_SESSION['admin_id'] = $row['id'];
                return ['status' => 202, 'message' => 'Login Successful'];
            } else {
                return ['status' => 303, 'message' => 'Incorrect Password'];
            }
        } else {
            return ['status' => 303, 'message' => 'No account found with this email'];
        }
    }
}

// Handle AJAX
if (isset($_POST['admin_register'])) {
    extract($_POST);
    if (!empty($name) && !empty($email) && !empty($password) && !empty($cpassword)) {
        if ($password === $cpassword) {
            $c = new Credentials();
            echo json_encode($c->createAdminAccount($name, $email, $password));
        } else {
            echo json_encode(['status' => 303, 'message' => 'Passwords do not match']);
        }
    } else {
        echo json_encode(['status' => 303, 'message' => 'All fields are required']);
    }
    exit();
}

if (isset($_POST['admin_login'])) {
    extract($_POST);
    if (!empty($email) && !empty($password)) {
        $c = new Credentials();
        echo json_encode($c->loginAdmin($email, $password));
    } else {
        echo json_encode(['status' => 303, 'message' => 'Email and Password required']);
    }
    exit();
}
?>