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

function fblogin()
{

    FB.login(function (response) {
        if (response.authResponse)
        {
            getUserInfo();
        } else
        {
            console.log('User cancelled login or did not fully authorize.');
        }
    }, {scope: 'email,user_photos,user_videos'});

}

function getUserInfo() {
    FB.api('/me', {fields: 'name,email'}, function (response) {
//        console.log(response.email);
        var sendData = {};
        sendData.type = 'fb';
        sendData.fbid = response.id;
        sendData.lname = '';
        if (typeof response.email !== "undefined") {
            sendData.email = response.email;
        }
        var str = response.name;
        var res = str.split(" ");
        
        if(typeof res[1] !== 'undefined'){
            sendData.lname = res[1];
        }
        if(typeof res[0] !== 'undefined'){
            sendData.fname = res[0];
        }else{
            sendData.fname = response.name;
        }
        
//        console.log(sendData);
//        console.log(response);
        socailLogin(sendData);

    });
}


/* ------------ Logout ------------ */
function Logout()
{
    FB.logout(function () {
        document.location.reload();
    });
}

// Load the SDK asynchronously
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


window.onbeforeunload = function (e) {
    gapi.auth2.getAuthInstance().signOut();
};
function onSignIn(googleUser) {
    // Useful data for your client-side scripts:
    var profile = googleUser.getBasicProfile();
    console.log(googleUser);
    $("#uemail").val(profile.getEmail());
    $("#ufirst_name").val(profile.getGivenName());
    $("#ulast_name").val(profile.getFamilyName());
    $("#usocial_id").val(profile.getId());
    $("#uprovider").val('google');

    var sendData = {};
    sendData.type = 'google';
    sendData.googleid = profile.getId();
    sendData.fname = profile.getGivenName();
    sendData.lname = profile.getFamilyName();
    if (typeof profile.getEmail() !== "undefined") {
        sendData.email = profile.getEmail();
    }

    console.log(sendData)
    socailLogin(sendData);

}
