$(document).ready(function () {
   // $("#invite").validationEngine('attach', {maxErrorsPerField: 1, promptPosition: "bottomLeft", validateNonVisibleFields: true,
   //     updatePromptsPosition: true, scroll: false});
    $('#invite').ajaxForm({
        success: function (response) {
            toastr.success(response.msg);
            $("#invite").find("input").val('');
        },
        error: errorHandler
    });

    $("#fbinvitebtn").add("#invitebtn").click(function () {
        FB.login(function (response) {
            console.log(response);
            if (response.authResponse) {
                FB.ui({
                    method: 'send',
               //     message: 'People Argue Just to Win',
                    link: $("#invite-link").val(),
                    //   app_id: '511261055904322',
                }, function (response) {
                    console.log(response);
                    if (response && !response.error_message) {
                        toastr.success('Posting completed.');
                    } else {
                        toastr.success('Error while posting.');
                    }
                });
            } else {

            }
        }, {scope: 'email,user_friends,publish_actions'}, {return_scopes: true});
    });
    // fbAsyncInit();
});

/* ------------ Logout ------------ */
function Logout()
{
    FB.logout(function () {
        document.location.reload();
    });
}

function sortMethod(a, b) {
    var x = a.name.toLowerCase();
    var y = b.name.toLowerCase();
    return ((x < y) ? -1 : ((x > y) ? 1 : 0));
}
window.fbAsyncInit = function () {
    FB.init({
        appId: '511261055904322', // Set YOUR APP ID
        //   channelUrl : 'http://hayageek.com/examples/oauth/facebook/oauth-javascript/channel.html', // Channel File
        status: true, // check login status
        cookie: true, // enable cookies to allow the server to access the session
        xfbml: true  // parse XFBML
    });

    FB.Event.subscribe('auth.authResponseChange', function (response)
    {
        if (response.status === 'connected') {
            console.log("connected");
        } else if (response.status === 'not_authorized') {
            console.log("failed");
        } else {
            console.log("logout");
        }
    });

};



(function (d) {
    var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
    if (d.getElementById(id)) {
        return;
    }
    js = d.createElement('script');
    js.id = id;
    js.async = true;
    js.src = "//connect.facebook.net/en_US/all.js";
    ref.parentNode.insertBefore(js, ref);
}(document));
