$(document).ready(function () {
    
    get_lat_lon();
    
    function getLocation() {
        if( navigator.geolocation ) {
           navigator.geolocation.getCurrentPosition( success, fail );
        } else {
           alert("Sorry, your browser does not support geolocation services.");
        }
    }
    function success(position) {
        lat = position['coords']['latitude'];
        lon = position['coords']['longitude'];
        get_near_mindings(lat, lon);
    }

    function fail() {
        var lat = '';
        var lon = '';
        get_near_mindings(lat, lon);
    }
    
    function get_near_mindings(lat, lon) {
        $.ajax({
            type: "POST",
            global: false,
            data: { 'lat': lat, 'lon': lon },
            url: "frontend/frontend/get_mindings_near_me",
            beforeSend: function(){
                $(".minding_sec").waitMe();
            },
            success: function(result) {
                if(result != '') {
                    $(".minding_sec").waitMe('hide');
                    $(".minding_sec").html(result);
                    document.cookie = "mindlat="+lat;
                    document.cookie = "mindlon="+lon;
                }
            }
        });
    }
    
    function get_lat_lon() {
        var lat = '';
        var lon = '';
        if ((document.cookie.indexOf('mindlat') >= 0) && (document.cookie.indexOf('mindlon') >= 0)) { 
            var parts = document.cookie;
            var lat1 = 'mindlat=';
            var lon1 = 'mindlon=';
            var ca = document.cookie.split(';');
            for (var i = 0; i < ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0) == ' ') {
                    c = c.substring(1);
                }
                if (c.indexOf(lat1) == 0) {
                    lat = c.substring(lat1.length, c.length);
                }
                if (c.indexOf(lon1) == 0) {
                    lon = c.substring(lon1.length, c.length);
                }
            }
        }
        if((typeof lat !== 'undefined' && lat != '') && (typeof lon !== 'undefined' && lon != '')) {
            get_near_mindings(lat, lon);
        } else {
            getLocation();
        }
    }
    
});

