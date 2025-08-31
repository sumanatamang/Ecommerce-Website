$(document).ready(function() {

    // Admin Registration
    $(".register-btn").on("click", function() {
        $.ajax({
            url: '../admin/classes/Credentials.php', // backend script for registration
            method: "POST",
            data: $("#admin-register-form").serialize(),
            success: function(response) {
                console.log(response); // for debugging
                try {
                    var resp = $.parseJSON(response);
                    if (resp.status == 202) {
                        $("#admin-register-form").trigger("reset");
                        $(".message").html('<span class="text-success">' + resp.message + '</span>');
                    } else if (resp.status == 303) {
                        $(".message").html('<span class="text-danger">' + resp.message + '</span>');
                    }
                } catch (e) {
                    console.error("Invalid JSON response:", e);
                    $(".message").html('<span class="text-danger">Unexpected error occurred.</span>');
                }
            },
            error: function() {
                $(".message").html('<span class="text-danger">AJAX request failed.</span>');
            }
        });
    });

    // Admin Login
    $(".login-btn").on("click", function() {
        $.ajax({
            url: '../admin/classes/Credentials.php', // backend script for login
            method: "POST",
            data: $("#admin-login-form").serialize(),
            success: function(response) {
                console.log(response); // for debugging
                try {
                    var resp = $.parseJSON(response);
                    if (resp.status == 202) {
                        $("#admin-login-form").trigger("reset");
                        window.location.href = window.origin + "/ecommerce-website/admin/index.php";
                    } else if (resp.status == 303) {
                        $(".message").html('<span class="text-danger">' + resp.message + '</span>');
                    }
                } catch (e) {
                    console.error("Invalid JSON response:", e);
                    $(".message").html('<span class="text-danger">Unexpected error occurred.</span>');
                }
            },
            error: function() {
                $(".message").html('<span class="text-danger">AJAX request failed.</span>');
            }
        });
    });

});
