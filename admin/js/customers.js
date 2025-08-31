$(document).ready(function() {

    // Fetch and display customers
    getCustomers();
    // Fetch and display customer orders
    getCustomerOrders();

    function getCustomers() {
        $.ajax({
            url: '../admin/classes/Customers.php',
            method: 'POST',
            data: { GET_CUSTOMERS: 1 },
            success: function(response) {
                console.log(response);
                var resp = $.parseJSON(response);

                if (resp.status === 202) {
                    var customersHTML = "";
                    $.each(resp.message, function(index, value) {
                        customersHTML += '<tr>' +
                            '<td>' + (index + 1) + '</td>' +
                            '<td>' + value.first_name + ' ' + value.last_name + '</td>' +
                            '<td>' + value.email + '</td>' +
                            '<td>' + value.mobile + '</td>' +
                            '<td>' + value.address1 + '<br>' + value.address2 + '</td>' +
                            '</tr>';
                    });
                    $("#customer_list").html(customersHTML);
                } else if (resp.status === 303) {
                    $("#customer_list").html('<tr><td colspan="5">' + resp.message + '</td></tr>');
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error:", status, error);
                $("#customer_list").html('<tr><td colspan="5">Error loading customers.</td></tr>');
            }
        });
    }

    function getCustomerOrders() {
        $.ajax({
            url: '../admin/classes/Customers.php',
            method: 'POST',
            data: { GET_CUSTOMER_ORDERS: 1 },
            success: function(response) {
                console.log(response);
                var resp = $.parseJSON(response);

                if (resp.status === 202) {
                    var customerOrderHTML = "";
                    $.each(resp.message, function(index, value) {
                        customerOrderHTML += '<tr>' +
                            '<td>' + (index + 1) + '</td>' +
                            '<td>' + value.order_id + '</td>' +
                            '<td>' + value.product_id + '</td>' +
                            '<td>' + value.product_title + '</td>' +
                            '<td>' + value.qty + '</td>' +
                            '<td>' + value.trx_id + '</td>' +
                            '<td>' + value.p_status + '</td>' +
                            '</tr>';
                    });
                    $("#customer_order_list").html(customerOrderHTML);
                } else if (resp.status === 303) {
                    $("#customer_order_list").html('<tr><td colspan="7">' + resp.message + '</td></tr>');
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error:", status, error);
                $("#customer_order_list").html('<tr><td colspan="7">Error loading orders.</td></tr>');
            }
        });
    }

});
