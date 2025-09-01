$(document).ready(function () {

    // ----------------- INITIAL LOAD -----------------
    fetchCategories();
    fetchProducts();
    loadCartCount();
    getCartItems();

    // ----------------- FETCH CATEGORIES -----------------
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

    // ----------------- FETCH PRODUCTS -----------------
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

    // ----------------- SEARCH AUTOCOMPLETE -----------------
    $("#search").on("keyup", function () {
        let keyword = $(this).val().trim();
        if (keyword.length > 1) {
            $.ajax({
                url: "search_suggestions.php",
                method: "POST",
                data: { query: keyword },
                success: function (data) {
                    $("#search_suggestions").fadeIn().html(data);
                }
            });
        } else {
            $("#search_suggestions").fadeOut();
        }
    });

    // Click on suggestion
    $(document).on("click", ".suggestion-item", function () {
        let product = $(this).text();
        $("#search").val(product);
        $("#search_suggestions").fadeOut();
        $("#search_btn").trigger("click"); // trigger search
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
            success: function (data) {
                alert(data);
                loadCartCount();
                getCartItems();
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
    $("#login, #login_form").on("submit", function (e) {
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
                    let redirect = $(e.target).attr('id') === "login" ? "profile.php" : "index.php";
                    window.location.href = redirect;
                } else {
                    $("#e_msg").html(data);
                }
            }
        });
    });

    // ----------------- SIGNUP -----------------
    $("#signup_form").on("submit", function (e) {
        e.preventDefault();
        var f_name = $("#f_name").val(),
            l_name = $("#l_name").val(),
            email = $("#email").val(),
            password = $("#password").val(),
            repassword = $("#repassword").val(),
            mobile = $("#mobile").val(),
            address1 = $("#address1").val(),
            address2 = $("#address2").val();

        if (password !== repassword) {
            alert("Passwords do not match!");
            return;
        }

        $.ajax({
            url: "action.php",
            method: "POST",
            data: { f_name, l_name, email, password, mobile, address1, address2 },
            success: function (response) {
                alert(response);
                if (response === "register_success") {
                    window.location.href = "login_form.php";
                }
            }
        });
    });

    // ----------------- HERO SLIDER -----------------
    let heroImages = ["./images/achaar.jpg", "./images/achaar.jpg", "./images/achaar.jpg"];
    let index = 0;
    setInterval(() => {
        $("#hero").css("background-image", `url(${heroImages[index]})`);
        index = (index + 1) % heroImages.length;
    }, 5000);

});
