$(document).ready(function () {
    var timezone = moment.tz.guess();
    
    function setCookie(cname, cvalue, exdays) {
        var d = new Date();
        d.setTime(d.getTime() + (exdays*24*60*60*1000));
        var expires = "expires=" + d.toUTCString();
        document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
    }
    
    function getCookie(a) {
        var b = document.cookie.match('(^|;)\\s*' + a + '\\s*=\\s*([^;]+)');
        return b ? b.pop() : '';
    }
    
    
    
    var tz = getCookie("timezone");
//    if(tz == "" || tz == null){
        setCookie("timezone", timezone, 1);
//    }
});