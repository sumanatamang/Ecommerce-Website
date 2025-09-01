<?php
session_start();
include "db.php";

// ================== REGISTER USER ==================
if (isset($_POST["f_name"])) {
    $f_name = $_POST["f_name"];
    $l_name = $_POST["l_name"];
    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $mobile = $_POST["mobile"];
    $address1 = $_POST["address1"];
    $address2 = $_POST["address2"];

    // Check if email already exists
    $stmt = $con->prepare("SELECT user_id FROM user_info WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        echo "Email already registered!";
        exit();
    }

    // Insert new user
    $stmt = $con->prepare("INSERT INTO user_info (first_name,last_name,email,password,mobile,address1,address2) VALUES (?,?,?,?,?,?,?)");
    $stmt->bind_param("sssssss", $f_name, $l_name, $email, $password, $mobile, $address1, $address2);
    if ($stmt->execute()) {
        echo "register_success";
    } else {
        echo "Something went wrong: " . $con->error;
    }
    exit();
}

// ================== LOGIN USER ==================
if (isset($_POST["userLogin"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $stmt = $con->prepare("SELECT user_id, first_name, password FROM user_info WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row["password"])) {
            $_SESSION["uid"] = $row["user_id"];
            $_SESSION["name"] = $row["first_name"];
            echo "login_success";
        } else {
            echo "Incorrect Password!";
        }
    } else {
        echo "Email not registered!";
    }
    exit();
}

// ================= Get Categories =================
if (isset($_POST["category"])) {
    $sql = "SELECT * FROM categories ORDER BY cat_title ASC";
    $run_query = mysqli_query($con, $sql);

    $html = '<div class="row">';
    while ($row = mysqli_fetch_array($run_query)) {
        $cat_id = $row['cat_id'];
        $cat_title = $row['cat_title'];

        $html .= '
        <div class="col-md-4 col-sm-6 mb-3">
            <div class="card category-card text-center p-3">
                <h5>' . $cat_title . '</h5>
                <button class="btn btn-success category" cid="' . $cat_id . '">View Pickles</button>
            </div>
        </div>';
    }
    $html .= '</div>';

    echo $html;
    exit();
}

// ================= Get All Products =================
if (isset($_POST["getProduct"])) {
    $product_query = "SELECT * FROM products ORDER BY product_id DESC LIMIT 9";
    $run_query = mysqli_query($con, $product_query);

    $html = "";
    if (mysqli_num_rows($run_query) > 0) {
        while ($row = mysqli_fetch_array($run_query)) {
            $pro_id = $row['product_id'];
            $pro_title = $row['product_title'];
            $pro_price = $row['product_price'];
            $pro_image = $row['product_image'];

            $html .= "
            <div class='col-md-4 col-sm-6 mb-4'>
                <div class='card product-card'>
                    <img class='card-img-top' src='product_images/$pro_image' style='height:200px; object-fit:cover;'>
                    <div class='card-body text-center'>
                        <h5 class='card-title'>$pro_title</h5>
                        <p class='card-text'>Price: Rs $pro_price</p>
                        <button pid='$pro_id' class='btn btn-primary btn-sm addToCart'>Add to Cart</button>
                    </div>
                </div>
            </div>";
        }
    } else {
        $html = "<h4>No products available!</h4>";
    }

    echo $html;
    exit();
}

// ================= Get Products by Category =================
if (isset($_POST["get_seleted_Category"])) {
    $cat_id = $_POST["cat_id"];
    $sql = "SELECT * FROM products WHERE product_cat='$cat_id'";
    $run_query = mysqli_query($con, $sql);

    $html = "";
    if (mysqli_num_rows($run_query) > 0) {
        while ($row = mysqli_fetch_array($run_query)) {
            $pro_id = $row['product_id'];
            $pro_title = $row['product_title'];
            $pro_price = $row['product_price'];
            $pro_image = $row['product_image'];

            $html .= "
            <div class='col-md-4 col-sm-6 mb-4'>
                <div class='card product-card'>
                    <img class='card-img-top' src='product_images/$pro_image' style='height:200px; object-fit:cover;'>
                    <div class='card-body text-center'>
                        <h5 class='card-title'>$pro_title</h5>
                        <p class='card-text'>Price: Rs $pro_price</p>
                        <button pid='$pro_id' class='btn btn-primary btn-sm addToCart'>Add to Cart</button>
                    </div>
                </div>
            </div>";
        }
    } else {
        $html = "<h4>No products found in this category!</h4>";
    }

    echo $html;
    exit();
}

// ================= Search Products =================
if (isset($_POST["search"])) {
    $keyword = $_POST["keyword"];
    $sql = "SELECT * FROM products WHERE product_title LIKE '%$keyword%' OR product_keywords LIKE '%$keyword%'";
    $run_query = mysqli_query($con, $sql);

    $html = "";
    if (mysqli_num_rows($run_query) > 0) {
        while ($row = mysqli_fetch_array($run_query)) {
            $pro_id = $row['product_id'];
            $pro_title = $row['product_title'];
            $pro_price = $row['product_price'];
            $pro_image = $row['product_image'];

            $html .= "
            <div class='col-md-4 col-sm-6 mb-4'>
                <div class='card product-card'>
                    <img class='card-img-top' src='product_images/$pro_image' style='height:200px; object-fit:cover;'>
                    <div class='card-body text-center'>
                        <h5 class='card-title'>$pro_title</h5>
                        <p class='card-text'>Price: Rs $pro_price</p>
                        <button pid='$pro_id' class='btn btn-primary btn-sm addToCart'>Add to Cart</button>
                    </div>
                </div>
            </div>";
        }
    } else {
        $html = "<h4>No products found for '$keyword'!</h4>";
    }

    echo $html;
    exit();
}

// ================== ADD TO CART ==================
if (isset($_POST["addToCart"]) && isset($_POST["proId"])) {
    $p_id = (int) $_POST["proId"];
    $user_id = $_SESSION["uid"] ?? null;

    if (!$user_id) {
        echo json_encode(["status" => "error", "msg" => "You must be logged in to add to cart!"]);
        exit();
    }

    $ip_add = $_SERVER['REMOTE_ADDR'];

    // Check if product already in cart
    $stmt = $con->prepare("SELECT id, qty FROM cart WHERE p_id=? AND user_id=?");
    $stmt->bind_param("ii", $p_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        // Product already exists → update quantity
        $new_qty = $row['qty'] + 1;
        $update = $con->prepare("UPDATE cart SET qty=? WHERE id=?");
        $update->bind_param("ii", $new_qty, $row['id']);
        $update->execute();
    } else {
        // Insert as new item
        $insert = $con->prepare("INSERT INTO cart (p_id, ip_add, user_id, qty) VALUES (?,?,?,1)");
        $insert->bind_param("isi", $p_id, $ip_add, $user_id);
        $insert->execute();
    }

    // Return updated cart count
    $countStmt = $con->prepare("SELECT SUM(qty) as total_qty FROM cart WHERE user_id=?");
    $countStmt->bind_param("i", $user_id);
    $countStmt->execute();
    $countRes = $countStmt->get_result()->fetch_assoc();

    echo json_encode([
        "msg" => "Product added to cart!",
    ]);
    exit();
}

// ================== GET CART COUNT ==================
if (isset($_POST['get_cart_count'])) {
    $user_id = $_SESSION["uid"] ?? null;
    if (!$user_id) {
        echo 0;
        exit();
    }

    $stmt = $con->prepare("SELECT SUM(qty) AS total_items FROM cart WHERE user_id=?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    echo $result['total_items'] ?? 0;
    exit();
}


// ================== REMOVE CART ITEM ==================
if (isset($_POST["removeItemFromCart"])) {
    if (!isset($_SESSION["uid"]))
        exit();
    $user_id = $_SESSION["uid"];
    $cart_id = $_POST["rid"];

    $stmt = $con->prepare("DELETE FROM cart WHERE id=? AND user_id=?");
    $stmt->bind_param("ii", $cart_id, $user_id);
    if ($stmt->execute()) {
        echo "Item removed from cart!";
    } else {
        echo "Error removing item!";
    }
    exit();
}
?>