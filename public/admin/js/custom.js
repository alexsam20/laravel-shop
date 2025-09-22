$(document).ready(function () {
    // Check admin password is correct or not
    $("#current_pwd").keyup(function () {
        const current_pwd = $("#current_pwd").val();
        // alert(current_pwd);
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "post",
            url: "/admin/check-current-password",
            data:{current_pwd: current_pwd},
            success: function (resp) {
                if (resp == "false"){
                    $("#verifyCurrentPwd").html("<span style='color: #f133f1'>Current Password is Incorrect</span>");
                } else if (resp == "true"){
                    $("#verifyCurrentPwd").html("<span style='color: #00a379'>Current Password is Correct</span>");
                }
            },error: function () {
                alert("Error");
            }
        })
    });

    // Update CMS Page Status
    $(document).on("click", ".updateCmsPageStatus", function () {
        const status = $(this).children("i").attr("status");
        const page_id = $(this).attr("page_id");
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "post",
            url: "/admin/update-cms-page-status",
            data:{status: status, page_id: page_id},
            success: function (resp) {
                if (resp['status'] == 0){
                    $("#page-"+page_id).html('<i class="fas fa-toggle-off" style="color: grey" status="Inactive"></i>');
                }else if (resp['status'] == 1) {
                    $("#page-"+page_id).html('<i class="fas fa-toggle-on" style="color: #3f6ed3" status="Active"></i>');
                }
            }, error: function () {
                alert("Error");
            }
        });
    });

    // Update Category Status
    $(document).on("click", ".updateCategoryStatus", function () {
        const status = $(this).children("i").attr("status");
        const category_id = $(this).attr("category_id");
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "post",
            url: "/admin/update-category-status",
            data:{status: status, category_id: category_id},
            success: function (resp) {
                if (resp['status'] == 0){
                    $("#category-"+category_id).html('<i class="fas fa-toggle-off" style="color: grey" status="Inactive"></i>');
                }else if (resp['status'] == 1) {
                    $("#category-"+category_id).html('<i class="fas fa-toggle-on" style="color: #3f6ed3" status="Active"></i>');
                }
            }, error: function () {
                alert("Error");
            }
        });
    });

    // Confirm the deletion of the CMS Page
    /*$(document).on("click", ".confirmDelete", function () {
        const name = $(this).attr("name");
        return confirm("Are you sure you want to delete this " + name + "?");

    });*/

    // Confirm Deletion with SweetAlert
    $(document).on("click", ".confirmDelete", function () {
        const record = $(this).attr("record");
        const recordid = $(this).attr("recordid");

        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: "Deleted!",
                    text: "Your file has been deleted.",
                    icon: "success"
                });
                window.location.href= "/admin/delete-"+record+"/"+recordid;
            }
        });
    });

    // Update Subadmin Status
    $(document).on("click", ".updateSubadminStatus", function () {
        const status = $(this).children("i").attr("status");
        const subadmin_id = $(this).attr("subadmin_id");
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "post",
            url: "/admin/update-subadmin-status",
            data:{status: status, subadmin_id: subadmin_id},
            success: function (resp) {
                if (resp['status'] == 0){
                    $("#subadmin-"+subadmin_id).html('<i class="fas fa-toggle-off" style="color: grey" status="Inactive"></i>');
                }else if (resp['status'] == 1) {
                    $("#subadmin-"+subadmin_id).html('<i class="fas fa-toggle-on" style="color: #3f6ed3" status="Active"></i>');
                }
            }, error: function () {
                alert("Error");
            }
        });
    });

    // Update Product Status
    $(document).on("click", ".updateProductStatus", function () {
        const status = $(this).children("i").attr("status");
        const product_id = $(this).attr("product_id");
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "post",
            url: "/admin/update-product-status",
            data:{status: status, product_id: product_id},
            success: function (resp) {
                if (resp['status'] == 0){
                    $("#product-"+product_id).html('<i class="fas fa-toggle-off" style="color: grey" status="Inactive"></i>');
                }else if (resp['status'] == 1) {
                    $("#product-"+product_id).html('<i class="fas fa-toggle-on" style="color: #3f6ed3" status="Active"></i>');
                }
            }, error: function () {
                alert("Error");
            }
        });
    });

});
