$("#addpp").change(function () {
    var ext = $(this).val().split('.').pop().toLowerCase();
    if ($.inArray(ext, ['gif', 'png', 'jpg', 'jpeg']) == -1) {
        alert('Only png, jpg, gif files are allowed.');
        $(this).val("");
    } else {
        $("#browse_div").hide();
        $("#cropcancel").show();
        console.log("Herer");
        readURL(this);
    }
});

function readURL(input) {
    $(".pro-sec").fadeOut(500);
    $(".propicBody").fadeIn(500);
    $("#maincropdiv").html('<div id="upload-demo" ></div>');
    console.log("Here");
    if (input.files && input.files[0]) {

        var reader = new FileReader();

        reader.onload = function (e) {
            uploadCrop.croppie('bind', {
                url: e.target.result
            });

        }

        reader.readAsDataURL(input.files[0]);
    }
    
    console.log(boundary);
    uploadCrop = $('#upload-demo').croppie({
        viewport: viewport,
        boundary: boundary,
        enableExif: true,
    });
}

$(".cancelCrop").click(function (e) {

//    e.preventDefault();
    $("#browse_div").show();
    $("#cropcancel").hide();
    $("#addpp").val('');
    $("#upload-demo").html("");
});

$('.saveCrop').on('click', function (ev) {
    uploadCrop.croppie('result', {
        type: 'canvas',
        size: 'viewport'
    }).then(function (resp) {
        var imageTag = '<img class="final-preview" alt="" src="' + resp + '"> <br><br>';
        var cancelBtn = "<a href='#' onclick='window.location.reload();' id='cancelPreview' class='btn btn-default'> Cancel </a> ";
        console.log(imageTag);
        $('#img-preview').html(imageTag);
        $('#img-preview').append(cancelBtn);
        $("#browse_div").hide();
        $("#cropcancel").hide();
        $("#upload-demo").html("");
        $("#imageBase64").val(resp);
    });
});

$(document).on("click", "#cancelPreview", function (e) {
    e.preventDefault();
    $('#img-preview').html("");
    $("#browse_div").show();
});

$(".next-btn").click(function () {
    if ($("#imageBase64").val() == '') {
        toastr.error("Please select and crop image first or skip.");
    } else {
        $('#profilepic_submit').submit();
    }
})
$('#profilepic_submit').ajaxForm({
    success: function (response) {
        window.location = "profile_info";
        console.log(response);
    },
    error: errorHandler
});