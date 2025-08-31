$(document).ready(function () {

    // ----------------- FETCH CATEGORIES -----------------

    fetchCategories();
    fetchProducts();

    function fetchCategories() {
        $.ajax({
            url: "action.php",
            method: "POST",
            data: { category: 1 },
            success: function (data) {
                $("#get_category").html(data); // matches updated HTML
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

    // Add to cart, login, search, category filter, etc. remain unchanged

    // ----------------- CATEGORY FILTER -----------------
    $("body").delegate(".category", "click", function () {
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

    $(document).ready(function () {

        // ================== Add to Cart ==================
        $("body").on("click", ".addToCart", function () {
            var pid = $(this).attr("pid");

            $.ajax({
                url: "action.php",
                method: "POST",
                data: { addToCart: 1, proId: pid },
                success: function (response) {
                    // Show alert
                    alert(response);

                    // Update cart count in navbar
                    loadCartCount();
                },
                error: function (xhr, status, error) {
                    console.error("AJAX Error:", error);
                }
            });
        });

        // ================== Load Cart Count ==================
        function loadCartCount() {
            $.ajax({
                url: "action.php",
                method: "POST",
                data: { Common: 1, getCartItem: 1 },
                success: function (response) {
                    // Count the number of items returned
                    var count = $(response).length; // count number of cart rows returned
                    $("#cart_count").text(count); // Update element with id 'cart_count'
                },
                error: function (xhr, status, error) {
                    console.error("Error loading cart count:", error);
                }
            });
        }

        // ================== Initial Cart Count ==================
        loadCartCount();

    });

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
    getCartItems();

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

    // ----------------- GUEST ADD TO CART -----------------
    // If user is not logged in, store cart in localStorage
    if (!sessionStorage.getItem("uid")) {
        $("body").delegate(".add-to-cart", "click", function () {
            var pid = $(this).attr("pid");
            var qty = $(this).siblings(".qty").val() || 1;
            var guestCart = JSON.parse(localStorage.getItem("guestCart") || "{}");
            guestCart[pid] = qty;
            localStorage.setItem("guestCart", JSON.stringify(guestCart));
            alert("Product added to cart (guest)!");
        });
    }
    $("#signup_form").on("submit", function(e){
    e.preventDefault(); // prevent default form submission

    // Collect values
    var f_name = $("#f_name").val();
    var l_name = $("#l_name").val();
    var email = $("#email").val();
    var password = $("#password").val();
    var repassword = $("#repassword").val();
    var mobile = $("#mobile").val();
    var address1 = $("#address1").val();
    var address2 = $("#address2").val();

    // Optional: validate password match
    if(password !== repassword){
        alert("Passwords do not match!");
        return;
    }

    $.ajax({
        url: "action.php", // correct relative path
        method: "POST",
        data: {
            f_name: f_name,
            l_name: l_name,
            email: email,
            password: password,
            mobile: mobile,
            address1: address1,
            address2: address2
        },
        success: function(response){
            alert(response);
            if(response === "register_success"){
                window.location.href = "login_form.php"; // redirect to login
            }
        }
    });
});

$("#login_form").on("submit", function(e){
    e.preventDefault(); // prevent default form submission

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
        success: function(response){
            alert(response);
            if(response === "login_success"){
                window.location.href = "index.php"; // redirect after login
            }
        }
    });
});


    // ----------------- HERO SLIDER -----------------
    // Simple slider behind the hero text
    let heroImages = ["product_images/achaar.jpg", "product_images/achaar.jpg", "product_images/achaar.jpg"];
    let index = 0;
    setInterval(() => {
        $("#hero").css("background-image", `url(${heroImages[index]})`);
        index = (index + 1) % heroImages.length;
    }, 5000);
});
