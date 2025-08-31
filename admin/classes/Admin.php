<?php
session_start();

class Admin
{
    private $con;

    function __construct()
    {
        include_once("Database.php");
        $db = new Database();
        $this->con = $db->connect();
    }

    // Fetch all admins
    public function getAdminList()
    {
        $query = $this->con->query("SELECT `id` as admin_id, `name`, `email`, `is_active` FROM `admin`");
        $ar = [];
        if ($query->num_rows > 0) {
            while ($row = $query->fetch_assoc()) {
                $ar[] = $row;
            }
            return ['status' => 202, 'message' => $ar];
        }
        return ['status' => 303, 'message' => 'No Admin'];
    }

    // Handle login
    public function login($email, $password)
    {
        $stmt = $this->con->prepare("SELECT * FROM admin WHERE email=? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();

            // If you used password_hash for storing passwords
            if (password_verify($password, $row['password'])) {
                $_SESSION['admin_id'] = $row['id'];
                $_SESSION['admin_email'] = $row['email'];
                return ['status' => 202, 'message' => 'Login success'];
            } else {
                return ['status' => 303, 'message' => 'Invalid password'];
            }
        }
        return ['status' => 303, 'message' => 'Admin not found'];
    }

    // Delete admin
    public function deleteAdmin($admin_id)
    {
        $stmt = $this->con->prepare("DELETE FROM admin WHERE id=?");
        $stmt->bind_param("i", $admin_id);
        if ($stmt->execute()) {
            return ['status' => 202, 'message' => 'Admin deleted'];
        }
        return ['status' => 303, 'message' => 'Failed to delete admin'];
    }
}


// ----------------------
// Handle AJAX requests
// ----------------------
if (isset($_POST['GET_ADMIN'])) {
    $a = new Admin();
    echo json_encode($a->getAdminList());
    exit();
}

if (isset($_POST['admin_login'])) {
    $a = new Admin();
    echo json_encode($a->login($_POST['email'], $_POST['password']));
    exit();
}

if (isset($_POST['DELETE_ADMIN'])) {
    $a = new Admin();
    echo json_encode($a->deleteAdmin($_POST['admin_id']));
    exit();
}
