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
});
