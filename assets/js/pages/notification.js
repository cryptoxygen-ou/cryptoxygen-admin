var browser_active = true;
$(document).ready(function() {
    
    $(document).on('click', '.notification_status', function() {
        var id = $(this).data('id');
        var path = $(this).data('href');
        $.ajax({
            type: "POST",
            data: { 'id': id },
            url: "notification/notification/notification_status_update",
            success: function(result) { 
                if(result.id) {
                    window.location = result.id + path;
                }
            }
        });
    });
    
//    $(document).on('click', '.notify-sec .dropdown-toggle', function() { alert($('.dropdown-menu .menu_notification li').length);
//        if($('.dropdown-menu .menu_notification li').length) {
//            $(this).parent().removeClass('open');
//        }
//    });
    
     $(window).blur(function(){
          browser_active = false;
     });
     $(window).focus(function(){
          browser_active = true;
     });
     
    get_notification_load();
    
    window.setInterval(function(){
        if(browser_active == true) {
            get_notification_load();
        }
    }, 30000);
    
    function get_notification_load() {
        $.ajax({
            type: "POST",
            global: false,
            async: true,
            data: { },
            url: "notification/notification/get_all_notifications",
            success: function(result) { 
                if(result != '') {
                    $(".menu_notification").html(result);
                }
            }
        });
    }
    
    user_pages_viewed();
    
    function user_pages_viewed() {
        var loc = window.location.pathname;
        $.ajax({
            type: "POST",
            global: false,
            async: true,
            data: { 'loc': loc },
            url: "user/profile/user_pages_viewed",
            success: function(result) {
            }
        });
    }
    
});
