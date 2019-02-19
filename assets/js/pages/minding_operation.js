$(document).ready(function() {
    
    $("#rating_slider").slider({
        ticks: [1, 2, 3, 4, 5],
        ticks_labels: ['1', '2', '3', '4', '5'],
        ticks_snap_bounds: 3
    });
    
    $("#rating_slider1").slider({
        ticks: [1, 2, 3, 4, 5],
        ticks_labels: ['1', '2', '3', '4', '5'],
        ticks_snap_bounds: 3
    });
    
//    //not used
//    $(".comment").shorten({showChars: 272});
//    
//    $('.btn-show-nav').click(function(){    
//        $('.right_main').toggleClass('show');
//    });
    //not used end
    
    $('.owl-carousel').owlCarousel({
        loop: true,
        margin: 10,
        responsiveClass: true,
        responsive: {
          0: {
           items: 1,
           nav: true
          },
          450: {
           items: 2,
           nav: true
          },
          550: {
           items: 3,
           nav: true
          },
          1000: {
           items: 4,
           nav: true,
           loop: false,
           margin:0,
          }
        }
    });
    
    $("#accept_minding").validationEngine('attach', {maxErrorsPerField: 1, promptPosition: "bottomLeft", validateNonVisibleFields: true,
        updatePromptsPosition: true});
    $(".accept_minding_btn").click(function (e) {
        $('#accept_minding').ajaxForm({
            success: function (response) {
                    if(response.id) {
                        toastr.success('Minding accepted successfully.');
                        $('#accept_minding_model').modal("hide");
                        $('.profile_list a.accepted').css('display', 'inline');
                        //$('.profile_list a.complete').css('display', 'inline');
                        $('.profile_list a.accept').css('display', 'none');
                    }
            },
            error : errorHandler
        });
    });
    
    $("#rating_minding").validationEngine('attach', {maxErrorsPerField: 1, promptPosition: "bottomLeft", validateNonVisibleFields: true,
        updatePromptsPosition: true});
    $(".review_save_btn").click(function (e) {
        $('#rating_minding').ajaxForm({ 
            success: function (response) {
                    if(response.id) {
                        toastr.success('Rating added successfully.');
                        $('.review_save_btn').attr('disabled', true);
                    }
            },
            error : errorHandler
        });
    });
    
    $("#family_rating_minding").validationEngine('attach', {maxErrorsPerField: 1, promptPosition: "bottomLeft", validateNonVisibleFields: true,
        updatePromptsPosition: true});
    $(".f_review_save_btn").click(function (e) {
        $('#family_rating_minding').ajaxForm({
            success: function (response) {
                    if(response.id) {
                        toastr.success('Rating added successfully.');
                        $('.f_review_save_btn').attr('disabled', true);
                    }
            },
            error : errorHandler
        });
    });
    
    $("#adjust_minding").validationEngine('attach', {maxErrorsPerField: 1, promptPosition: "bottomLeft", validateNonVisibleFields: true,
        updatePromptsPosition: true});
    $(".adjust_save_btn").click(function (e) {
        $('#adjust_minding').ajaxForm({
            success: function (response) {
                toastr.success('Adjustment updated successfully.');
            },
            error : errorHandler
        });
    });
    
    $("#adjust_minding_pet").validationEngine('attach', {maxErrorsPerField: 1, promptPosition: "bottomLeft", validateNonVisibleFields: true,
        updatePromptsPosition: true});
    $(".adjust_pet_save_btn").click(function (e) {
        $('#adjust_minding_pet').ajaxForm({
            success: function (response) {
                toastr.success('Adjustment updated successfully.');
            },
            error : errorHandler
        });
    });
    
    $("#adjust_minding_supp").validationEngine('attach', {maxErrorsPerField: 1, promptPosition: "bottomLeft", validateNonVisibleFields: true,
        updatePromptsPosition: true});
    $(".adjust_supp_save_btn").click(function (e) {
        $('#adjust_minding_supp').ajaxForm({
            success: function (response) {
                toastr.success('Adjustment updated successfully.');
            },
            error : errorHandler
        });
    });
    
    $("#adjust_minding_home").validationEngine('attach', {maxErrorsPerField: 1, promptPosition: "bottomLeft", validateNonVisibleFields: true,
        updatePromptsPosition: true});
    $(".adjust_home_save_btn").click(function (e) {
        $('#adjust_minding_home').ajaxForm({
            success: function (response) {
                toastr.success('Adjustment updated successfully.');
            },
            error : errorHandler
        });
    });
    
    $("#adjust_minding_garden").validationEngine('attach', {maxErrorsPerField: 1, promptPosition: "bottomLeft", validateNonVisibleFields: true,
        updatePromptsPosition: true});
    $(".adjust_garden_save_btn").click(function (e) {
        $('#adjust_minding_garden').ajaxForm({
            success: function (response) {
                toastr.success('Adjustment updated successfully.');
            },
            error : errorHandler
        });
    });
    
    $(document).on('click', '.minding_complete', function() {
        //$('.adjment_block').css('display', 'block');
        $('.rating_review').css('display', 'block');
    });
    
    $(document).on('click', '.minding_review_complete', function() {
        //$('.adjment_block').css('display', 'block');
        //$('.rating_review_minding').css('display', 'block');
        $.ajax({
            type: "POST",
            data: { 'mid': $(this).data('mid') },
            url: "mindings/mindingOperations/family_minding_complete",
            success: function(result) { 
                document.location.reload();
            }
        });
    });
    
    $(document).on('click', '.review_cancel', function() {
        $('.rating_review').css('display', 'none');
    });
    
    $(document).on('click', '.minding_f_adjust', function() {
        $('.adjment_block').toggle('show');
    });
    
    $(document).on('click', '.minding_f_confirm', function() {
        $('.rating_review_minding').css('display', 'block');
    });
    
    $(document).on('click', '.link_accept_minding', function() {
        var plan = $(this).data('plan');
        if(plan) {
            toastr.error('Trial has expired subscribe for Minding Requests');
        } else {
            $('#accept_minding_model').modal("toggle");
        }
        return false;
    });

});