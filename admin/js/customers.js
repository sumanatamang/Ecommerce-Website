$(document).ready(function () {

    // -------------------- Fetch Data --------------------
    getCustomers();
    getCustomerOrders();

    // -------------------- Customers --------------------
    function getCustomers() {
        $.ajax({
            url: '../admin/classes/Customers.php',
            method: 'POST',
            data: { GET_CUSTOMERS: 1 },
            success: function (response) {
                var resp = $.parseJSON(response);

                if (resp.status === 202) {
                    var customersHTML = "";
                    $.each(resp.message, function (index, value) {
                        customersHTML += '<tr>' +
                            '<td>' + (index + 1) + '</td>' +
                            '<td>' + value.first_name + ' ' + value.last_name + '</td>' +
                            '<td>' + value.email + '</td>' +
                            '<td>' + value.mobile + '</td>' +
                            '<td>' + value.address1 + '<br>' + (value.address2 || '') + '</td>' +
                            '</tr>';
                    });
                    $("#customer_list").html(customersHTML);
                } else {
                    $("#customer_list").html('<tr><td colspan="5">' + resp.message + '</td></tr>');
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error:", status, error);
                $("#customer_list").html('<tr><td colspan="5">Error loading customers.</td></tr>');
            }
        });
    }

    // -------------------- Customer Orders --------------------
    function getCustomerOrders() {
        $.ajax({
            url: '../admin/classes/Customers.php',
            method: 'POST',
            data: { GET_CUSTOMER_ORDERS: 1 },
            success: function (response) {
                var resp = $.parseJSON(response);

                if (resp.status === 202) {
                    var customerOrderHTML = "";
                    $.each(resp.message, function (index, value) {
                        customerOrderHTML += '<tr>' +
                            '<td>' + (index + 1) + '</td>' +
                            '<td>' + value.order_id + '</td>' +
                            '<td>' + value.product_id + '</td>' +
                            '<td>' + value.product_title + '</td>' +
                            '<td>' + value.qty + '</td>' +
                            '<td>' + value.trx_id + '</td>' +
                            '<td>' + value.p_status + '</td>' +
                            // Shipment status dropdown
                            '<td>' +
                            '<select class="shipment_status" data-order="' + value.order_id + '">' +
                            '<option value="Pending"' + (value.shipment_status === 'Pending' ? ' selected' : '') + '>Pending</option>' +
                            '<option value="Dispatched"' + (value.shipment_status === 'Dispatched' ? ' selected' : '') + '>Dispatched</option>' +
                            '<option value="In Transit"' + (value.shipment_status === 'In Transit' ? ' selected' : '') + '>In Transit</option>' +
                            '<option value="Delivered"' + (value.shipment_status === 'Delivered' ? ' selected' : '') + '>Delivered</option>' +
                            '</select>' +
                            '</td>' +
                            // Tracking number input
                            '<td><input type="text" class="tracking_number" data-order="' + value.order_id + '" value="' + (value.tracking_number || '') + '" /></td>' +
                            // Update shipment button
                            '<td><button class="btn btn-sm btn-primary update_shipment" data-order="' + value.order_id + '">Update</button></td>' +
                            '</tr>';
                    });
                    $("#customer_order_list").html(customerOrderHTML);
                } else {
                    $("#customer_order_list").html('<tr><td colspan="10">' + resp.message + '</td></tr>');
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error:", status, error);
                $("#customer_order_list").html('<tr><td colspan="10">Error loading orders.</td></tr>');
            }
        });
    }

    // -------------------- Update Shipment --------------------
    $("body").on("click", ".update_shipment", function () {
        var order_id = $(this).data("order");
        var shipment_status = $('.shipment_status[data-order="' + order_id + '"]').val();
        var tracking_number = $('.tracking_number[data-order="' + order_id + '"]').val();

        $.ajax({
            url: 'update_shipment.php',
            method: 'POST',
            data: {
                order_id: order_id,
                shipment_status: shipment_status,
                tracking_number: tracking_number
            },
            dataType: 'json',
            success: function (response) {
                if (response.status === 202) {
                    alert(response.message);
                    // Update only the row dynamically without reloading the whole table
                    var row = $('button.update_shipment[data-order="' + order_id + '"]').closest('tr');
                    row.find('select.shipment_status').val(shipment_status);
                    row.find('input.tracking_number').val(tracking_number);
                } else {
                    alert("Failed: " + response.message);
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error:", error, xhr.responseText);
                alert("Failed to update shipment");
            }
        });
    });


});
