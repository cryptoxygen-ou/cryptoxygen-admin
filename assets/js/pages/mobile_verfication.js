//console.log("In Join Us page");
$(document).ready(function () {

    $("#sendcode").validationEngine('attach', {maxErrorsPerField: 1, promptPosition: "bottomLeft", validateNonVisibleFields: true,
        updatePromptsPosition: true});
    $(".sendcode-btn").click(function (e) {
        $('#sendcode').ajaxForm({
            success: function (response) {
                toastr.success(response.msg);
                $("#mobiledivid").hide();
                $("#checkdivcode").show();
            },
            error: errorHandler
        });
    });


    $("#checkcode").validationEngine('attach', {maxErrorsPerField: 1, promptPosition: "bottomLeft", validateNonVisibleFields: true,
        updatePromptsPosition: true});
    $(".checkcode-btn").click(function (e) {
        $('#checkcode').ajaxForm({
            success: function (response) {
                window.location = "profile_pic";
            },
            error: errorHandler
        });
    });
    
    $(".resend").click(function(){
        $(".sendcode-btn").click();
    })
});

