$(document).ready(function () {
    $("#contact_us").validationEngine('attach', {maxErrorsPerField: 1, promptPosition: "bottomLeft", validateNonVisibleFields: true,
        updatePromptsPosition: true});
    $(".submitquery").click(function (e) {
        e.preventDefault();
        $('#contact_us').submit();
    });
    $('#contact_us').ajaxForm({
        success: function (response) {
            console.log(response);
            toastr.success(response.msg);
            $('.form-control').val('');
        },
        error: errorHandler
    });

});



  

