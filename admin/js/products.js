$(document).ready(function() {

    // ----------------- GLOBAL -----------------
    var productList;

    // ----------------- FETCH PRODUCTS & CATEGORIES -----------------
    function getProducts() {
        $.ajax({
            url: '../admin/classes/Products.php',
            method: 'POST',
            data: { GET_PRODUCT: 1 },
            success: function(response) {
                try {
                    var resp = $.parseJSON(response);
                } catch(e) {
                    console.error("Invalid JSON response", response);
                    return;
                }

                if (resp.status === 202) {

                    // Populate product table
                    productList = resp.message.products;
                    var productHTML = '';
                    if (productList && productList.length > 0) {
                        $.each(productList, function(index, product) {
                            productHTML += `<tr>
                                <td>${index + 1}</td>
                                <td>${product.product_title}</td>
                                <td><img width="60" height="60" src="../product_images/${product.product_image}" alt="${product.product_title}"></td>
                                <td>${product.product_price}</td>
                                <td>${product.product_qty}</td>
                                <td>${product.cat_title}</td>
                                <td>
                                    <a class="btn btn-sm btn-info edit-product" style="color:#fff;">
                                        <span style="display:none;">${JSON.stringify(product)}</span>
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>
                                    &nbsp;
                                    <a pid="${product.product_id}" class="btn btn-sm btn-danger delete-product" style="color:#fff;">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </td>
                            </tr>`;
                        });
                    } else {
                        productHTML = '<tr><td colspan="7" class="text-center">No products found</td></tr>';
                    }
                    $("#product_list").html(productHTML);

                    // Populate category dropdowns
                    var catSelectHTML = '<option value="">Select Category</option>';
                    $.each(resp.message.categories, function(index, cat) {
                        catSelectHTML += `<option value="${cat.cat_id}">${cat.cat_title}</option>`;
                    });
                    $(".category_list").html(catSelectHTML);

                } else {
                    console.error("Failed to fetch products");
                }
            }
        });
    }

    getProducts();

    // ----------------- ADD NEW PRODUCT -----------------
    $(".add-product").on("click", function() {
        var formData = new FormData($("#add-product-form")[0]);
        $.ajax({
            url: '../admin/classes/Products.php',
            method: 'POST',
            data: formData,
            contentType: false,
            cache: false,
            processData: false,
            success: function(response) {
                try { var resp = $.parseJSON(response); } catch(e) { alert("Error adding product"); return; }
                if (resp.status === 202) {
                    $("#add-product-form").trigger("reset");
                    $("#add_product_modal").modal('hide');
                    getProducts();
                    alert("Product added successfully!");
                } else {
                    alert(resp.message || "Failed to add product");
                }
            }
        });
    });

    // ----------------- EDIT PRODUCT -----------------
    $(document).on('click', '.edit-product', function() {
        var product = $.parseJSON($.trim($(this).find('span').text()));

        $("#edit-product-form input[name='e_product_name']").val(product.product_title);
        $("#edit-product-form select[name='e_category_id']").val(product.cat_id);
        $("#edit-product-form textarea[name='e_product_desc']").val(product.product_desc);
        $("#edit-product-form input[name='e_product_qty']").val(product.product_qty);
        $("#edit-product-form input[name='e_product_price']").val(product.product_price);
        $("#edit-product-form input[name='e_product_keywords']").val(product.product_keywords);
        $("#edit-product-form input[name='pid']").val(product.product_id);

        // Image preview
        $("#edit-product-form img").attr("src", "../product_images/" + product.product_image);

        $("#edit_product_modal").modal('show');
    });

    $(".submit-edit-product").on('click', function() {
        var formData = new FormData($("#edit-product-form")[0]);
        $.ajax({
            url: '../admin/classes/Products.php',
            method: 'POST',
            data: formData,
            contentType: false,
            cache: false,
            processData: false,
            success: function(response) {
                try { var resp = $.parseJSON(response); } catch(e) { alert("Error updating product"); return; }
                if (resp.status === 202) {
                    $("#edit-product-form").trigger("reset");
                    $("#edit_product_modal").modal('hide');
                    getProducts();
                    alert("Product updated successfully!");
                } else {
                    alert(resp.message || "Failed to update product");
                }
            }
        });
    });

    // ----------------- DELETE PRODUCT -----------------
    $(document).on('click', '.delete-product', function() {
        var pid = $(this).attr('pid');
        if (confirm("Are you sure you want to delete this product?")) {
            $.ajax({
                url: '../admin/classes/Products.php',
                method: 'POST',
                data: { DELETE_PRODUCT: 1, pid: pid },
                success: function(response) {
                    try { var resp = $.parseJSON(response); } catch(e) { alert("Error deleting product"); return; }
                    if (resp.status === 202) {
                        getProducts();
                        alert("Product deleted successfully!");
                    } else {
                        alert(resp.message || "Failed to delete product");
                    }
                }
            });
        }
    });

});
