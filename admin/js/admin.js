// Handle admin login
$(document).on("click", ".login-btn", function (e) {
    e.preventDefault();

    var formData = $("#admin-login-form").serialize();

    $.ajax({
        url: "../admin/classes/Admin.php", // your backend PHP class
        method: "POST",
        data: formData,
        success: function (response) {
            try {
                var resp = $.parseJSON(response);

                if (resp.status == 202) {
                    // Login successful
                    window.location.href = "index.php"; 
                } else {
                    $(".message").html('<div class="alert alert-danger">'+resp.message+'</div>');
                }
            } catch (err) {
                console.error("Parse error:", err, response);
                $(".message").html('<div class="alert alert-danger">Unexpected server response.</div>');
            }
        },
        error: function (xhr, status, error) {
            console.error(error);
            $(".message").html('<div class="alert alert-danger">Something went wrong.</div>');
        }
    });
});
