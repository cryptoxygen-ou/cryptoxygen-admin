$(document).ready(function () {
    $("#reset").validationEngine('attach', {maxErrorsPerField: 1, promptPosition: "bottomLeft", validateNonVisibleFields: true,
        updatePromptsPosition: true, scroll: false});
    $(".reset-btn").click(function (e) {
        $('#reset').ajaxForm({
            success: function (response) {
                toastr.success(response.msg);
                //window.location = "login";
                setTimeout(function() {
                    window.location = "login";
                }, 3000);
            },
            error : errorHandler
        });
    });
});
