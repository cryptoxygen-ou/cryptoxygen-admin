//console.log("In Join Us page");
$(document).ready(function () {
    $("#login").validationEngine('attach', {maxErrorsPerField: 1, promptPosition: "bottomLeft", validateNonVisibleFields: true,
        updatePromptsPosition: true, scroll: false});
    $(".login-btn").click(function (e) {
        $('#login').ajaxForm({
            success: function (response) {
                window.location = "dashboard";
            },
            error : errorHandler
        });
    });
});

