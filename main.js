$(document).ready(function () {

    // ----------------- FETCH CATEGORIES -----------------
    fetchCategories();
    fetchProducts();
    loadCartCount();
    getCartItems();

    function fetchCategories() {
        $.ajax({
            url: "action.php",
            method: "POST",
            data: { category: 1 },
            success: function (data) {
                $("#get_category").html(data);
            }
        });
    }

    function fetchProducts() {
        $.ajax({
            url: "action.php",
            method: "POST",
            data: { getProduct: 1 },
            success: function (data) {
                $("#get_product").html(data);
            }
        });
    }

    // ----------------- CATEGORY FILTER -----------------
    $("body").on("click", ".category", function () {
        var cid = $(this).attr('cid');
        $("#get_product").html("<h3>Loading...</h3>");
        $.ajax({
            url: "action.php",
            method: "POST",
            data: { get_seleted_Category: 1, cat_id: cid },
            success: function (data) {
                $("#get_product").html(data);
            }
        });
    });

    // ----------------- SEARCH PRODUCTS -----------------
    $("#search_btn").click(function (e) {
        e.preventDefault();
        var keyword = $("#search").val().trim();
        if (keyword != "") {
            $("#get_product").html("<h3>Loading...</h3>");
            $.ajax({
                url: "action.php",
                method: "POST",
                data: { search: 1, keyword: keyword },
                success: function (data) {
                    $("#get_product").html(data);
                }
            });
        }
    });

    // ----------------- ADD TO CART -----------------
    $("body").on("click", ".addToCart", function () {
        var pid = $(this).attr("pid");

        $.ajax({
            url: "action.php",
            method: "POST",
            data: { addToCart: 1, proId: pid },
            success: function (response) {
                alert(response);
                loadCartCount(); // refresh badge count
                getCartItems();  // refresh cart dropdown
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error:", error);
            }
        });
    });

    // ----------------- LOAD CART COUNT -----------------
    function loadCartCount() {
        $.ajax({
            url: "action.php",
            method: "POST",
            data: { get_cart_count: 1 },
            success: function (data) {
                $("#cart_count").text(data);
            },
            error: function (xhr, status, error) {
                console.error("Error loading cart count:", error);
            }
        });
    }

    // ----------------- GET CART ITEMS -----------------
    function getCartItems() {
        $.ajax({
            url: "action.php",
            method: "POST",
            data: { Common: 1, getCartItem: 1 },
            success: function (data) {
                $("#cart_product").html(data);
            }
        });
    }

    // ----------------- LOGIN -----------------
    $("#login").on("submit", function (e) {
        e.preventDefault();
        $(".overlay").show();
        $.ajax({
            url: "action.php",
            method: "POST",
            data: {
                userLogin: 1,
                email: $("#email").val(),
                password: $("#password").val()
            },
            success: function (data) {
                $(".overlay").hide();
                if (data === "login_success") {
                    window.location.href = "profile.php";
                } else {
                    $("#e_msg").html(data);
                }
            }
        });
    });

    // ----------------- SIGNUP -----------------
    $("#signup_form").on("submit", function (e) {
        e.preventDefault();
        var f_name = $("#f_name").val();
        var l_name = $("#l_name").val();
        var email = $("#email").val();
        var password = $("#password").val();
        var repassword = $("#repassword").val();
        var mobile = $("#mobile").val();
        var address1 = $("#address1").val();
        var address2 = $("#address2").val();

        if (password !== repassword) {
            alert("Passwords do not match!");
            return;
        }

        $.ajax({
            url: "action.php",
            method: "POST",
            data: {
                f_name, l_name, email, password, mobile, address1, address2
            },
            success: function (response) {
                alert(response);
                if (response === "register_success") {
                    window.location.href = "login_form.php";
                }
            }
        });
    });

    // ----------------- LOGIN FORM -----------------
    $("#login_form").on("submit", function (e) {
        e.preventDefault();
        var email = $("#email").val();
        var password = $("#password").val();

        $.ajax({
            url: "action.php",
            method: "POST",
            data: {
                userLogin: 1,
                email: email,
                password: password
            },
            success: function (response) {
                alert(response);
                if (response === "login_success") {
                    window.location.href = "index.php";
                }
            }
        });
    });

    // ----------------- HERO SLIDER -----------------
    let heroImages = ["product_images/achaar.jpg", "product_images/achaar.jpg", "product_images/achaar.jpg"];
    let index = 0;
    setInterval(() => {
        $("#hero").css("background-image", `url(${heroImages[index]})`);
        index = (index + 1) % heroImages.length;
    }, 5000);

});
