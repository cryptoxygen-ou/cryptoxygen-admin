toastr.options.progressBar = true;
toastr.options.closeDuration = 300;
toastr.options.closeButton = true;
toastr.options.positionClass = 'toast-top-center';
toastr.options.showMethod = 'slideDown';
toastr.options.hideMethod = 'slideUp';
toastr.options.closeMethod = 'slideUp';

var boundary = {width: 750, height: 526};
var viewport = {width: 270, height: 270, type: 'circle', border: 0};
if ($(window).width() < 800) {
    boundary.width = 300;
    boundary.height = 300;
    viewport.width = 200;
    viewport.height = 200;
}



function errorHandler(data) {
    var msg = data.responseJSON.msg;
    toastr.error(msg);
    //$('body').waitMe('hide');
}

$(document).ajaxStart(function () {
    $('body').waitMe();
});

$(document).ajaxStop(function () {
    $('body').waitMe('hide');
});

$(document).ajaxError(function () {
    $('body').waitMe('hide');
});

$(document).ajaxSuccess(function () {
    $('body').waitMe('hide');
});


$('.btn-show-nav').click(function () {
    $('.right_main').toggleClass('show');
});

$('.accordion-link').on('click', function (e) {
    $('html,body').animate({scrollTop: $(this).offset().top - 100}, 800);
});

function socailLogin(data) {
    console.log(data);
    $.ajax({
        url: "frontend/socail_submit",
        type: "POST",
        data: data,
        success: function (result) {
            toastr.success(result.msg);
            window.location = result.redirecturi;
        },
        error: errorHandler
    });
}

function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    var expires = "expires=" + d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function getCookie(a) {
    var b = document.cookie.match('(^|;)\\s*' + a + '\\s*=\\s*([^;]+)');
    return b ? b.pop() : '';
}

var timezone_offset_minutes = new Date().getTimezoneOffset();
timezone_offset_minutes = timezone_offset_minutes == 0 ? 0 : -timezone_offset_minutes;


setCookie('timezone_offset',timezone_offset_minutes, 365 );