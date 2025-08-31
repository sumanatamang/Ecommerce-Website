<?php
include "db.php";
session_start();

if (isset($_POST["email"]) && isset($_POST["password"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Prepare statement to prevent SQL injection
    $stmt = $con->prepare("SELECT user_id, first_name, password FROM user_info WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $row["password"])) {
            // Set session variables
            $_SESSION["uid"] = $row["user_id"];
            $_SESSION["name"] = $row["first_name"];
            $ip_add = $_SERVER['REMOTE_ADDR'];

            // Merge cart if user has a cookie
            if (isset($_COOKIE["product_list"])) {
                $product_list = json_decode(stripslashes($_COOKIE["product_list"]), true);

                foreach ($product_list as $pid) {
                    // Check if this product is already in user's cart
                    $stmt_cart = $con->prepare("SELECT id FROM cart WHERE user_id = ? AND p_id = ?");
                    $stmt_cart->bind_param("ii", $_SESSION["uid"], $pid);
                    $stmt_cart->execute();
                    $res = $stmt_cart->get_result();

                    if ($res->num_rows < 1) {
                        // Assign cart items added before login
                        $update_cart = $con->prepare("UPDATE cart SET user_id = ? WHERE ip_add = ? AND user_id = -1 AND p_id = ?");
                        $update_cart->bind_param("isi", $_SESSION["uid"], $ip_add, $pid);
                        $update_cart->execute();
                    } else {
                        // Remove duplicate guest cart items
                        $delete_cart = $con->prepare("DELETE FROM cart WHERE user_id = -1 AND ip_add = ? AND p_id = ?");
                        $delete_cart->bind_param("si", $ip_add, $pid);
                        $delete_cart->execute();
                    }
                }

                // Delete cookie after merging
                setcookie("product_list", "", strtotime("-1 day"), "/");

                // Redirect to cart if items were in cart before login
                echo "cart_login";
                exit();
            }

            // Normal login without cart
            echo "login_success";
            exit();
        } else {
            echo "<span style='color:red;'>Invalid email or password.</span>";
            exit();
        }
    } else {
        echo "<span style='color:red;'>No account found. Please register first.</span>";
        exit();
    }
}
?>