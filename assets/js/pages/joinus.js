//console.log("In Join Us page");
$(document).ready(function () {
    $("#signup").validationEngine('attach', {maxErrorsPerField: 1, promptPosition: "bottomLeft", validateNonVisibleFields: true,
        updatePromptsPosition: true, scroll: false});
    $(".join-btn").click(function (e) {
        $('#signup').ajaxForm({
            success: function (response) {
//                alert('Success');
                window.location = "mobile_verfication";
            },
            error: errorHandler
        });
    });
});

