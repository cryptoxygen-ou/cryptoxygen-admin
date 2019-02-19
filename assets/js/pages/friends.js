$(document).ready(function () {
    
    $('.btn-show-nav').click(function(){    
        $('.right_main').toggleClass('show');
    });
    $('.responsive-tabs').responsiveTabs({
      accordionOn: ['xs', 'sm']
    });
    
    $(document).on("click", ".friend_remove", function () { 
        var id = $(this).data('id');
        $.ajax({ 
            type: "POST",
            dataType: "json",
            data: { 'f_id': id },
            url: "user/friends/delete_friend",
            success: function(result) {
                if(result != '0') {
                    $('.friend_remove_'+id).remove();
                    toastr.success('Friend removed successfully.');
                    $('.view_friend_remove').css('display', 'none');
                    $('.view_friend_add').css('display', 'block');
                    $('.view_friend_accept').css('display', 'none');
                    $('.view_friend_reject').css('display', 'none');
                    $('.friend_add, .friend_req_cancel, .friend_remove, .friend_accept, .friend_reject').attr('data-id', '');
                }
                error : errorHandler
            }
        });
    });
    
    $(document).on("click", ".friend_reject", function () { 
        var id = $(this).data('id');
        $.ajax({ 
            type: "POST",
            dataType: "json",
            data: { 'f_id': id, 'status': 'REJECTED' },
            url: "user/friends/friend_status_update",
            success: function(result) {
                if(result != '0') {
                    $('.request_rec_'+id).remove();
                    toastr.success('Friend request rejected successfully.');
                }
                error : errorHandler
            }
        });
    });
    
    $(document).on("click", ".friend_req_cancel", function () { 
        var id = $(this).data('id');
        $.ajax({ 
            type: "POST",
            dataType: "json",
            data: { 'f_id': id, 'status': 'CANCELLED' },
            url: "user/friends/friend_status_update",
            success: function(result) {
                if(result != '0') {
                    $('.request_sent_'+id).remove();
                    toastr.success('Friend request cancelled successfully.');
                    $('.view_friend_cancel').css('display', 'none');
                    $('.view_friend_add').css('display', 'block');
                }
                error : errorHandler
            }
        });
    });
    
    $(document).on("click", ".friend_accept", function () { 
        var id = $(this).data('id');
        $.ajax({ 
            type: "POST",
            dataType: "json",
            data: { 'f_id': id, 'status': 'ACCEPTED' },
            url: "user/friends/friend_status_update",
            success: function(result) {
                if(result != '0') {
                    $('.request_rec_'+id).remove();
                    toastr.success('Friend request accepted successfully.');
                    $('.view_friend_accept').css('display', 'none');
                    $('.view_friend_reject').css('display', 'none');
                    $('.view_friend_remove').css('display', 'block');
                }
                error : errorHandler
            }
        });
    });
    
    $(document).on("click", ".friend_add", function () { 
        var id = $(this).data('id');
        var famid = $(this).data('famid');
        $.ajax({ 
            type: "POST",
            dataType: "json",
            data: { 'f_id': id, 'famid': famid, 'status': 'SENT' },
            url: "user/friends/friend_status_update",
            success: function(result) {
                if(result != '0') {
                    toastr.success('Friend request sent successfully.');
                    document.location.reload();
//                    $('.friend_add').css('display', 'none');
//                    $('.friend_req_cancel').css('display', 'block');
//                    $('.friend_add, .friend_req_cancel, .friend_remove, .friend_accept, .friend_reject').attr('data-id', result);

                }
                error : errorHandler
            }
        });
    });
    
});

