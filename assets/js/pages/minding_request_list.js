$(document).ready(function () {

    $(document).on("click", ".confirm_minding_popup", function () {
        var mid = $(this).data('mid'); //alert(mid);
        $.ajax({
            type: "POST",
            dataType: "json",
            data: {'mid': mid},
            url: "mindings/mindingOperations/child_accept_minding",
            success: function (response) {
                $('#confirm_minding_model .accept_details').html(response);
                $('#confirm_minding_model').modal("show");
            },
            error: errorHandler
        });
    });

    $(document).on("click", ".confirm_minding_btn", function () {
        var id = $(this).data('id');
        var mid = $(this).data('mid');
        $.ajax({
            type: "POST",
            dataType: "json",
            data: {'id': id, 'status': 'AP', 'mid': mid},
            url: "mindings/mindingOperations/accept_minding_status",
            success: function (response) {
                toastr.success('Minding updated successfully.');
                $('#confirm_minding_model').modal("hide");
                $(this).attr('disabled', true);
            },
            error: errorHandler
        });
    });

    $(document).on("click", ".decline_minding_btn", function () {
        var id = $(this).data('id');
        var mid = $(this).data('mid');
        $.ajax({
            type: "POST",
            dataType: "json",
            data: {'id': id, 'status': 'D', 'mid': mid},
            url: "mindings/mindingOperations/accept_minding_status",
            success: function (response) {
                toastr.success('Minding updated successfully.');
                $(this).attr('disabled', true);
            },
            error: errorHandler
        });
    });

});
$(".share").click(function () {
    $('#myModal').modal('toggle');
    var id = $(this).attr('id');
    var rs = id.split("_");
    var bases = document.getElementsByTagName('base');
    var base_url = null;

    if (bases.length > 0) {
        base_url = bases[0].href;
    }
    var url = base_url+$("#link_" + rs[1]).attr('href');
    // var url = "http://google.com";
    var text = "MindForMe";

    call_this(url, text);
});

$(function () {

    var url = "http://google.com";
    var text = "MindForMe";
    call_this(url, text);
});

function call_this(url, text) {
    $("#shareRoundIcons").jsSocials({
        url: url,
        text: text,
        showLabel: false,
        showCount: false,
        shares: ["email", "twitter", "facebook", "googleplus", "linkedin", "pinterest"]
    });
}