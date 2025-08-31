$(document).ready(function() {

    // Fetch and display categories
    getCategories();

    function getCategories() {
        $.ajax({
            url: '../admin/classes/Products.php',
            method: 'POST',
            data: { GET_CATEGORIES: 1 },
            success: function(response) {
                console.log(response);
                var resp = $.parseJSON(response);

                if (resp.status === 202) {
                    var categoryHTML = '';
                    $.each(resp.message, function(index, value) {
                        categoryHTML += '<tr>' +
                            '<td>' + (index + 1) + '</td>' +
                            '<td>' + value.cat_title + '</td>' +
                            '<td>' +
                            '<a class="btn btn-sm btn-info edit-category" data-cat=\'' + JSON.stringify(value) + '\'>' +
                            '<i class="fas fa-pencil-alt"></i></a> &nbsp;' +
                            '<a class="btn btn-sm btn-danger delete-category" data-cid="' + value.cat_id + '">' +
                            '<i class="fas fa-trash-alt"></i></a>' +
                            '</td>' +
                            '</tr>';
                    });
                    $("#category_list").html(categoryHTML);
                } else if (resp.status === 303) {
                    $("#category_list").html('<tr><td colspan="3">' + resp.message + '</td></tr>');
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error: ", status, error);
                $("#category_list").html('<tr><td colspan="3">Error loading categories.</td></tr>');
            }
        });
    }

    // Add new category
    $(".add-category").on("click", function() {
        $.ajax({
            url: '../admin/classes/Products.php',
            method: 'POST',
            data: $("#add-category-form").serialize(),
            success: function(response) {
                var resp = $.parseJSON(response);
                alert(resp.message);
                if (resp.status === 202) {
                    getCategories();
                    $("#add_category_modal").modal('hide');
                }
            },
            error: function() {
                alert("Error adding category.");
            }
        });
    });

    // Edit category (populate modal)
    $(document.body).on("click", ".edit-category", function() {
        var cat = $(this).data('cat');
        $("input[name='e_cat_title']").val(cat.cat_title);
        $("input[name='cat_id']").val(cat.cat_id);
        $("#edit_category_modal").modal('show');
    });

    // Submit edited category
    $(".edit-category-btn").on('click', function() {
        $.ajax({
            url: '../admin/classes/Products.php',
            method: 'POST',
            data: $("#edit-category-form").serialize(),
            success: function(response) {
                var resp = $.parseJSON(response);
                alert(resp.message);
                if (resp.status === 202) {
                    getCategories();
                    $("#edit_category_modal").modal('hide');
                }
            },
            error: function() {
                alert("Error updating category.");
            }
        });
    });

    // Delete category
    $(document.body).on('click', '.delete-category', function() {
        var cid = $(this).data('cid');
        if (confirm("Are you sure you want to delete this category?")) {
            $.ajax({
                url: '../admin/classes/Products.php',
                method: 'POST',
                data: { DELETE_CATEGORY: 1, cid: cid },
                success: function(response) {
                    var resp = $.parseJSON(response);
                    alert(resp.message);
                    if (resp.status === 202) {
                        getCategories();
                    }
                },
                error: function() {
                    alert("Error deleting category.");
                }
            });
        }
    });

    // Reset forms on modal hide
    $('#add_category_modal, #edit_category_modal').on('hidden.bs.modal', function() {
        $(this).find('form')[0].reset();
    });

});
