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
});
