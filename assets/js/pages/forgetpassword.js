$(document).ready(function () {
    $("#forget").validationEngine('attach', {maxErrorsPerField: 1, promptPosition: "bottomLeft", validateNonVisibleFields: true,
        updatePromptsPosition: true, scroll: false});
    $(".forget-btn").click(function (e) {
        $('#forget').ajaxForm({
            success: function (response) {
                toastr.success(response.msg);
//                window.location = "login";
            },
            error : errorHandler
        });
    });
});

