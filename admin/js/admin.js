function getAdmins(){
    $.ajax({
        url: '../admin/classes/Admin.php',
        method: 'POST',
        data: {GET_ADMIN:1},
        success: function(response){
            console.log(response);
            var resp = $.parseJSON(response);
            
            if(resp.status == 202){
                var adminHTML = '';
                $.each(resp.message, function(index, value){
                    adminHTML += '<tr>'+
                                    '<td>' + (index+1) + '</td>'+
                                    '<td>' + value.name + '</td>'+
                                    '<td>' + value.email + '</td>'+
                                    '<td>' + (value.is_active == 1 ? 'Active' : 'Inactive') + '</td>'+
                                    '<td>'+
                                        '<button class="btn btn-sm btn-danger delete-admin" data-id="'+value.admin_id+'">'+
                                            '<i class="fas fa-trash-alt"></i>'+
                                        '</button>'+
                                    '</td>'+
                                 '</tr>';
                });
                $("#admin_list").html(adminHTML);
            } else {
                $("#admin_list").html('<tr><td colspan="5">'+resp.message+'</td></tr>');
            }
        },
        error: function(xhr, status, error){
            console.error(error);
        }
    });
}

// Delete admin
$(document).on('click', '.delete-admin', function(){
    var admin_id = $(this).data('id');
    if(confirm("Are you sure you want to delete this admin?")){
        $.ajax({
            url: '../admin/classes/Admin.php',
            method: 'POST',
            data: {DELETE_ADMIN:1, admin_id: admin_id},
            success: function(resp){
                getAdmins(); // Refresh list
            }
        });
    }
});
