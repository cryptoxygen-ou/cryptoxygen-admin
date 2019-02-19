var boundary1, boundary2, viewport1, viewport2;
$(document).ready(function () {
    dataContainer = $('.references_list');
    
    $(document).on("click", "input[name$='cars']", function () { 
        var test = $(this).data('id');
        $("div.desc").hide();
        $("#Cars"+test).show();
    });
    
    $('.upload_gallery_images').click(function() {
        $('.upload_gallery').toggle('hide');
    });
    
    $('#family_children_edit, #add_family_pet, #addfamily_model, #family_support_edit').on('hidden.bs.modal', function () { 
        $(this).find("input").val('').end();
        $(this).find("select.selectpicker").val('');
        $(this).find("select.selectpicker").selectpicker("refresh");
        $(this).find("select.chosen-select").val('').trigger('chosen:updated');
        $(this).find("img").attr('src', '');
        $(this).find("img").css('display', 'none');
        $('#family_children_edit .modal-title').text('Add Children');
        $('#add_family_pet .modal-title').text('Add Pet');
        $('#family_support_edit .modal-title').text('Add Supported');
    });
    
//    function readURL(input, id) {
//        if (input.files && input.files[0]) {
//            var reader = new FileReader();
//            reader.onload = function (e) {
//                $('.'+id).show();
//                $('.'+id).attr('src', e.target.result);
//            }
//
//            reader.readAsDataURL(input.files[0]);
//            //console.log(input.files[0]);
//            if(id == 'family_imag_upload') {
//                if ($("#p_family_image").val() == '') {
//                    toastr.error("Error, Please add image again.");
//                } else {
//                    $('#form_up_family_img').submit();
//                }
//            }
//        }
//    }
    
//    $('#form_up_family_img').ajaxForm({
//        success: function (response) {
//            toastr.success('Family photo updated successfully.');
//        },
//        error : errorHandler
//    });
    
//    $('#add_profile_image').change(function () {
//        readURL(this, 'img_upload_profile');
//    });
//    
//    $('#children_image').change(function () {
//        readURL(this, 'img_upload_children');
//    });
//    
//    $('#pet_image').change(function () {
//        readURL(this, 'img_upload_pet');
//    });
//    
//    $('#elder_image').change(function () {
//        readURL(this, 'img_upload_elder');
//    });
//    
//    $('#add_children_image').change(function () {
//        readURL(this, 'img_upload_add_children');
//    });
//    
//    $('#profile_pet_image').change(function () {
//        readURL(this, 'profile_img_upload_pet');
//    });
//    
//    $('#support_image').change(function () {
//        readURL(this, 'img_upload_support');
//    });
//    
    $('#parent_admin_image').change(function () {
        //readURL(this, 'img_upload_parent_admin');
    });
    
    $('#parent_image').change(function () {
        //readURL(this, 'img_upload_parent');
    });
    
    $('#p_family_image').change(function () {
        //readURL(this, 'family_imag_upload');
    });
    
    $('.profile_pet_type').change(function () {
        $.ajax({
            type: "POST",
            global: false,
            data: { 'pet_id': $(this).val(), 'breed_id': '' },
            url: "user/profile/get_breed_list",
            success: function(result) { 
                if(result != '') {
                    $('#form_add_pet .chosen-select-deselect').prop('disabled', false).trigger("chosen:updated");
                    $('#form_add_pet select.profile_breed_type').html(result).trigger('chosen:updated');
                } else {
                    $('#form_add_pet select.profile_breed_type').html('<option value=""></option>').trigger('chosen:updated');
                    $('#form_add_pet .chosen-select-deselect').prop('disabled', true).trigger("chosen:updated");
                }
                //$("#form_add_pet select.profile_breed_type").selectpicker("refresh");
            }
        });
    });
    
    $('.profile_edit_pet_type').change(function () {
        $.ajax({
            type: "POST",
            data: { 'pet_id': $(this).val(), 'breed_id': $('#breed_hidden_id').val() },
            url: "user/profile/get_breed_list",
            success: function(result) { 
                if(result != '') {
                    $('#edit_form_add_pet .chosen-select-deselect').prop('disabled', false).trigger("chosen:updated");
                    $("#edit_form_add_pet select.profile_edit_breed").html(result).trigger('chosen:updated');;
                } else {
                    $("#edit_form_add_pet select.profile_edit_breed").html('<option value=""></option>').trigger('chosen:updated');
                    $('#edit_form_add_pet .chosen-select-deselect').prop('disabled', true).trigger("chosen:updated");
                }
                //$("#edit_form_add_pet select.profile_edit_breed").selectpicker("refresh");
            }
        });
    });
    
    $('#elder_country').change(function () {
        $.ajax({
            type: "POST",
            data: { 'country_id': $(this).val() },
            url: "user/profile/get_states_list",
            success: function(result) { 
                if(result != '') {
                    $('select#elder_state').html(result);
                } else {
                    $('select#elder_state').html('<option value="">Select State</option>');
                }
                $("#form_add_elder select#elder_state").selectpicker("refresh");
            }
        });
    });
    
    $('#support_country').change(function () {
        $.ajax({
            type: "POST",
            data: { 'country_id': $(this).val() },
            url: "user/profile/get_states_list",
            success: function(result) { 
                if(result != '') {
                    $('select#support_state').html(result);
                } else {
                    $('select#support_state').html('<option value="">Select State</option>');
                }
                $("#form_support_elder select#support_state").selectpicker("refresh");
            }
        });
    });
    
    $('#home_country').change(function () {
        $.ajax({
            type: "POST",
            data: { 'country_id': $(this).val() },
            url: "user/profile/get_states_list",
            success: function(result) { 
                if(result != '') {
                    $('select#home_state').html(result);
                } else {
                    $('select#home_state').html('<option value="">Select State</option>');
                }
                $("#form_add_home select#home_state").selectpicker("refresh");
            }
        });
    });
    
    $('#emerg_country').change(function () {
        $.ajax({
            type: "POST",
            data: { 'country_id': $(this).val() },
            url: "user/profile/get_states_list",
            success: function(result) { 
                if(result != '') {
                    $('select#emerg_state').html(result);
                } else {
                    $('select#emerg_state').html('<option value="">Select State</option>');
                }
                $("#form_add_emerg select#emerg_state").selectpicker("refresh");
            }
        });
    });

    $('#doctor_country').change(function () {
        $.ajax({
            type: "POST",
            data: { 'country_id': $(this).val() },
            url: "user/profile/get_states_list",
            success: function(result) { 
                if(result != '') {
                    $('select#doctor_state').html(result);
                } else {
                    $('select#doctor_state').html('<option value="">Select State</option>');
                }
                $("#form_add_doctor select#doctor_state").selectpicker("refresh");
            }
        });
    });
    
    $('#profile_filter').change(function () {
        var sort = $("#profile_sort option:selected").val();
        var type = $(this).val();
        load_resources(type, sort);
    });
    
    $('#profile_sort').change(function () {
        var type = $("#profile_filter option:selected").val();
        var sort = $(this).val();
        load_resources(type, sort);
    });
    
    $(document).on("click", ".parent_add_edit", function () {
        var id = $(this).data('id');
        $.ajax({
            type: "POST",
            dataType: "json",
            data: { 'par_id': id },
            url: "user/profile/edit_parent_details",
            success: function(result) {
                if(result != '') {
                    $('#form_add_parent_admin .img_upload_parent_admin').attr('src', result['image']);
                    $('#form_add_parent_admin .img_upload_parent_admin').css('display', 'block');
                    $('#parent_admin_edit .modal-title').html(result['name']+' '+result['last_name']+ ' Profile');
                    $('#form_add_parent_admin #p_id').val(id);
                    $('#form_add_parent_admin #fname').val(result['name']);
                    $('#form_add_parent_admin #Surname').val(result['last_name']);
                    $("#form_add_parent_admin select.parent_role").html(result['role']);
                    $("#form_add_parent_admin select.parent_role").selectpicker("refresh");
                    $('#form_add_parent_admin #parent_dob').val(result['dob']);
                    $('#form_add_parent_admin #admin_email').val(result['email']);
                    $('#form_add_parent_admin #user_id').val(result['user_id']);
                    
                    $('#parent_admin_edit').modal("toggle");
                }
            }
        });
    });
    
    $(document).on("click", ".elder_edit_profile_page", function () {
        var id = $(this).data('id');
        $.ajax({
            type: "POST",
            dataType: "json",
            data: { 'sup_id': id },
            url: "user/profile/edit_support_details",
            success: function(result) {
                if(result != '') {
                    //console.log(result); 
                    initialize('support');
                    $('#form_support_elder .img_upload_support').attr('src', result['image']);
                    $('#family_support_edit .modal-title').html(result['name']+' '+result['last_name']+ ' Profile');
                    //$('#form_support_elder .support_radio').html(result['support']);
                    $('#form_support_elder #el_id').val(id);
                    $('#form_support_elder #fname').val(result['name']);
                    $('#form_support_elder #lname').val(result['last_name']);
                    $('#form_support_elder #dob5').val(result['dob']);
                    $("#form_support_elder select#role").html(result['role']);
                    $("#form_support_elder select#role").selectpicker("refresh");
                    $('#form_support_elder #support_lat').val(result['lat']);
                    $('#form_support_elder #support_lon').val(result['lon']);
                    $('#form_support_elder #support_search').val(result['location']);
                    $('#form_support_elder #support_street').val(result['street_name']);
                    $('#form_support_elder #support_suburb').val(result['suburb']);
                    $("#form_support_elder select#support_state").html(result['state']);
                    $("#form_support_elder select#support_state").selectpicker("refresh");
                    $('#form_support_elder #support_postcode').val(result['postcode']);
                   // $("#form_support_elder select#support_country").html(result['country']);
                    //$("#form_support_elder select#support_country").selectpicker("refresh");
                    $('select#support_country').selectpicker('val', result['country_id']);
                    
                    
                    $('#family_support_edit').modal("toggle");
                }
            }
        });
    });
    
    $(document).on("click", ".edit_family_child", function () {
        var id = $(this).data('id');
        $.ajax({
            type: "POST",
            dataType: "json",
            data: { 'child_id': id },
            url: "user/profile/edit_children_details",
            success: function(result) {
                if(result != '') {
                    $('#form_add_edit_children .img_upload_add_children').attr('src', result['image']);
                    $('#form_add_edit_children .img_upload_add_children').css('display', 'block');
                    $('#family_children_edit .modal-title').html(result['name'] + ' Profile');
                    $('#form_add_edit_children #ch_id').val(id);
                    $('#form_add_edit_children #add_fname').val(result['name']);
                    $("#form_add_edit_children select.child_relation").html(result['relation']);
                    $("#form_add_edit_children select.child_relation").selectpicker("refresh");
                    $('#form_add_edit_children .dob').val(result['dob']);
                    
                    $("#form_add_edit_children select#edit_interests").html(result['interests']).trigger('chosen:updated');
                    $("#form_add_edit_children select#edit_allergies").html(result['allergy']).trigger('chosen:updated');
                    $("#form_add_edit_children select#edit_food").html(result['food']).trigger('chosen:updated');
                    $("#form_add_edit_children select#edit_medical").html(result['medical']).trigger('chosen:updated');
                    
                    $('#family_children_edit').modal("toggle");
                }
            }
        });
    });
    
    $(document).on("click", ".pet_edit_profile_page", function () { 
        var id = $(this).data('id');
        $.ajax({ 
            type: "POST",
            dataType: "json",
            data: { 'pet_id': id },
            url: "user/profile/edit_pet_details",
            success: function(result) {
                if(result != '') {
                    $('#edit_form_add_pet .profile_img_upload_pet').attr('src', result['image']);
                    $('#edit_form_add_pet .profile_img_upload_pet').css('display', 'block');
                    $('#add_family_pet .modal-title').html(result['name'] + ' Profile');
                    $('#edit_form_add_pet #pet_id').val(id);
                    $('#edit_form_add_pet #edit_pet_name').val(result['name']);
                    $("#edit_form_add_pet select.profile_edit_pet_type").html(result['pet']);
                    $("#edit_form_add_pet select.profile_edit_pet_type").selectpicker("refresh");
                    $('#edit_form_add_pet .chosen-select-deselect').prop('disabled', false).trigger("chosen:updated");
                    $("#edit_form_add_pet select.profile_edit_breed").html(result['breed']).trigger('chosen:updated');
                    //$("#edit_form_add_pet select.profile_edit_breed").selectpicker("refresh");
                    $('#edit_form_add_pet .dob').val(result['dob']);
                    $('#edit_form_add_pet #breed_hidden_id').val(result['pet_breed']);
                    
                    $("#edit_form_add_pet select#edit_feeding").html(result['feeding']).trigger('chosen:updated');
                    $("#edit_form_add_pet select#edit_rules").html(result['rule']).trigger('chosen:updated');
                    $("#edit_form_add_pet select#edit_allergy").html(result['alergy']).trigger('chosen:updated');
                    $("#edit_form_add_pet select#edit_wo").html(result['wother']).trigger('chosen:updated');
                    
                    $('#add_family_pet').modal("toggle");
                }
            }
        });
    });
    
    $(document).on("click", ".edit_profile_type", function () { 
        var type = $(this).data('type');
        if(type == '1') {
            $('.type_two').hide();
            $('.type_one').show();
            $('#sub_type').val('1');
        } else if(type == '2') {
            $('.type_two').show();
            $('.type_one').hide();
            $('#sub_type').val('2');
        }
    });
    
    $("#form_profile_edit").validationEngine('attach', {maxErrorsPerField: 1, promptPosition: "bottomLeft", validateNonVisibleFields: false,
        updatePromptsPosition: true});
    $(".btn_profile_edit").click(function (e) {
        $('#form_profile_edit').ajaxForm({
            success: function (response) {
                response = JSON.parse(response);
                $('.profile_top').html(response.output);
                $('.parent_profile_main img').attr('src', response.imagefull);
                $('.parent_profile_main h3 span').text(response.name);
                $('.parent_profile_main p.profile_main_dob').text(response.dob);
                $('.Profile_sec img').attr('src', response.imageicon);
                $('.Profile_sec li a.Profile_sec_name').text(response.name);
                $('.ProfileTabs_head h3').text(response.familyname);
                $('#profile_edit').modal("toggle");
                toastr.success('Profile updated successfully.');
            },
            error : errorHandler
        });
    });
    $("#form_profile_edit1").validationEngine('attach', {maxErrorsPerField: 1, promptPosition: "bottomLeft", validateNonVisibleFields: false,
        updatePromptsPosition: true});
    $(".btn_profile_edit1").click(function (e) {
        $('#form_profile_edit1').ajaxForm({
            success: function (response) {
                response = JSON.parse(response);
                $('.profile_top').html(response.output);
                $('.parent_profile_main1 img').attr('src', response.imagefull);
                $('.parent_profile_main1 h3 span').text(response.name);
                $('.parent_profile_main1 p.profile_main_dob').text(response.dob);
                $('.Profile_sec img').attr('src', response.imageicon);
                $('.Profile_sec li a.Profile_sec_name').text(response.name);
                $('.ProfileTabs_head h3').text(response.familyname);
                $('#parent_profile_edit_admin').modal("toggle");
                toastr.success('Profile updated successfully.');
            },
            error : errorHandler
        });
    });
    
    $("#form_overview_edit").validationEngine('attach', {maxErrorsPerField: 1, promptPosition: "bottomLeft", validateNonVisibleFields: true,
        updatePromptsPosition: true});
    $(".btn_overview_edit").click(function (e) {
        $('#form_overview_edit').ajaxForm({
            success: function (response) {
                $('.profile_over p').html(response);
                $('#overview_model').modal("toggle");
                toastr.success('Overview added successfully.');
            },
            error : errorHandler
        });
    });
    
    $("#form_add_parent").validationEngine('attach', {maxErrorsPerField: 1, promptPosition: "bottomLeft", validateNonVisibleFields: true,
        updatePromptsPosition: true});
    $(".btn_add_parent").click(function (e) {
            $('#form_add_parent').ajaxForm({
                success: function (response) {
                    response = JSON.parse(response);
                    $('.parent_admin_section ul').append(response.res);
                    $('#addfamily_model').modal("toggle");
                    toastr.success('Parent added successfully.');
                },
                error : errorHandler
            });
    });
    
    $("#form_add_parent_admin").validationEngine('attach', {maxErrorsPerField: 1, promptPosition: "bottomLeft", validateNonVisibleFields: true,
        updatePromptsPosition: true});
    $(".btn_add_parent_admin").click(function (e) {
            $('#form_add_parent_admin').ajaxForm({
                success: function (response) {
                    response = JSON.parse(response);
                    $('.parent_profile_'+response.id).replaceWith(response.res);
                    $('#parent_admin_edit').modal("toggle");
                    toastr.success('Parent updated successfully.');
                },
                error : errorHandler
            });
    });
    
    $("#form_add_children").validationEngine('attach', {maxErrorsPerField: 1, promptPosition: "bottomLeft", validateNonVisibleFields: true,
        updatePromptsPosition: true});
    $(".btn_add_children").click(function (e) {
            $('#form_add_children').ajaxForm({
                success: function (response) {
                    response = JSON.parse(response);
                    $('.children_tab .profile_list').append(response.res1);
                    $('.profile_home_child ul').append(response.res2);
                    $('.profile_home_child').css('display', 'block');
                    toastr.success('Children added successfully.');
                    $('#addfamily_model').modal("toggle");
                },
                error : errorHandler
            });
    });
    
    $("#form_add_pet").validationEngine('attach', {maxErrorsPerField: 1, promptPosition: "bottomLeft", validateNonVisibleFields: true,
        updatePromptsPosition: true});
    $(".btn_add_pet").click(function (e) {
            $('#form_add_pet').ajaxForm({
                success: function (response) {
                    response = JSON.parse(response);
                    $('.pet_tab_children .profile_list').append(response.res1);
                    $('.profile_home_pet ul').append(response.res2);
                    $('#addfamily_model').modal("toggle");
                    $('.profile_home_pet').css('display', 'block');
                    if($(".profile_home_pet ul li").length > 1) {
                        $(".profile_home_pet h5").text('Pets');
                        $(".pet_tab_children .children_tab_top h3").text('Pets');
                    }
                    toastr.success('Pet added successfully.');
                },
                error : errorHandler
            });
    });
    
    $("#form_add_elder").validationEngine('attach', {maxErrorsPerField: 1, promptPosition: "bottomLeft", validateNonVisibleFields: true,
        updatePromptsPosition: true});
    $(".btn_add_elder").click(function (e) {
            $('#form_add_elder').ajaxForm({
                success: function (response) {
                    response = JSON.parse(response);
                    //console.log(response.id);
                    if(response.type == '1') {
                        $('.elder_prof_'+response.id).replaceWith(response.res1);
                        $('.elder_profile_'+response.id).replaceWith(response.res2);
                        toastr.success('Children updated successfully.');
                    } else {
                        $('.elder_tab_main .profile_list').append(response.res1);
                        $('.profile_home_elder ul').append(response.res2);
                        $('.profile_home_elder').css('display', 'block');
                        toastr.success('Children added successfully.');
                    }
                    $('#addfamily_model').modal("hide");
                },
                error : errorHandler
            });
    });
    
    $("#form_add_edit_children").validationEngine('attach', {maxErrorsPerField: 1, promptPosition: "bottomLeft", validateNonVisibleFields: true,
        updatePromptsPosition: true});
    $(".add_edit_family_children").click(function (e) {
            $('#form_add_edit_children').ajaxForm({
                success: function (response) {
                    response = JSON.parse(response);
                    //console.log(response.id);
                    if(response.type == '1') {
                        $('.children_prof_'+response.id).replaceWith(response.res1);
                        $('.children_profile_'+response.id).replaceWith(response.res2);
                        toastr.success('Children updated successfully.');
                    } else {
                        $('.child_tab_children .profile_list').append(response.res1);
                        $('.profile_home_child ul').append(response.res2);
                        $('.profile_home_child').css('display', 'block');
                        toastr.success('Children added successfully.');
                    }
                    $('#family_children_edit').modal("hide");
                },
                error : errorHandler
            });
    });
    
    $("#edit_form_add_pet").validationEngine('attach', {maxErrorsPerField: 1, promptPosition: "bottomLeft", validateNonVisibleFields: true,
        updatePromptsPosition: true});
    $(".btn_edit_pet").click(function (e) {
            $('#edit_form_add_pet').ajaxForm({
                success: function (response) {
                    response = JSON.parse(response);
                    //console.log(response.id);
                    if(response.type == '1') {
                        $('.pet_prof_'+response.id).replaceWith(response.res1);
                        $('.pet_profile_'+response.id).replaceWith(response.res2);
                        toastr.success('Pet updated successfully.');
                    } else {
                        $('.pet_tab_children .profile_list').append(response.res1);
                        $('.profile_home_pet ul').append(response.res2);
                        $('.profile_home_pet').css('display', 'block');
                        if($(".profile_home_pet ul li").length > 1) {
                            $(".profile_home_pet h5").text('Pets');
                            $(".pet_tab_children .children_tab_top h3").text('Pets');
                        }
                        toastr.success('Pet added successfully.');
                    }
                    $('#add_family_pet').modal("hide");
                },
                error : errorHandler
            });
    });
    
    $("#form_support_elder").validationEngine('attach', {maxErrorsPerField: 1, promptPosition: "bottomLeft", validateNonVisibleFields: true,
        updatePromptsPosition: true});
    $(".btn_add_support_elder").click(function (e) {
            $('#form_support_elder').ajaxForm({
                success: function (response) {
                    response = JSON.parse(response);
                    //console.log(response.id);
                    if(response.type == '1') {
                        $('.elder_prof_'+response.id).replaceWith(response.res1);
                        $('.elder_profile_'+response.id).replaceWith(response.res2);
                        toastr.success('Elder updated successfully.');
                    } else {
                        $('.elder_tab_main .profile_list').append(response.res1);
                        $('.profile_home_elder ul').append(response.res2);
                        $('.profile_home_elder').css('display', 'block');
                        toastr.success('Elder added successfully.');
                    }
                    $('#family_support_edit').modal("hide");
                },
                error : errorHandler
            });
    });
    
    $("#form_add_home").validationEngine('attach', {maxErrorsPerField: 1, promptPosition: "bottomLeft", validateNonVisibleFields: true,
        updatePromptsPosition: true});
    $(".home_address_btn").click(function (e) {
            $('#form_add_home').ajaxForm({
                success: function (response) {
                    response = JSON.parse(response);
                    $('#home .profile_home_address p').html(response.street + '<br>' + response.suburb + ' ' + response.postcode + '<br>' + response.cname);
                    toastr.success('Address updated successfully.');
                    $('#home_address').modal("hide");
                },
                error : errorHandler
            });
    });
    
    $("#form_add_emerg").validationEngine('attach', {maxErrorsPerField: 1, promptPosition: "bottomLeft", validateNonVisibleFields: true,
        updatePromptsPosition: true});
    $(".emerg_address_btn").click(function (e) {
            $('#form_add_emerg').ajaxForm({
                success: function (response) {
                    response = JSON.parse(response);
                    $('#home .profile_emerg_address p').html(response.person_name + ' (' + response.relation_to_you + ')<br>' + response.street + '<br>' + response.suburb + ' ' + response.postcode + '<br>' + response.cname + '<br> Tel: ' + response.contact_no);
                    toastr.success('Emergency contact updated successfully.');
                    $('#emergency_contact').modal("hide");
                },
                error : errorHandler
            });
    });
    
    $("#form_add_doctor").validationEngine('attach', {maxErrorsPerField: 1, promptPosition: "bottomLeft", validateNonVisibleFields: true,
        updatePromptsPosition: true});
    $(".doctor_address_btn").click(function (e) {
            $('#form_add_doctor').ajaxForm({
                success: function (response) {
                    response = JSON.parse(response);
                    //console.log(response.street);
                    $('#home .profile_doctor_address p').html('Dr. '+response.doctor_name + '<br>' + response.street + '<br>' + response.suburb + ' ' + response.postcode + '<br>' + response.cname + '<br> Tel: ' + response.contact_no);
                    toastr.success('Emergency contact updated successfully.');
                    $('#add_doctor').modal("hide");
                },
                error : errorHandler
            });
    });
    
    $('form').on('keyup keypress', function(e) {
        var keyCode = e.keyCode || e.which;
        if ((keyCode === 13) && (!$('textarea#profile_about').hasClass('clicked')) && ($(this).attr('id') != 'form_overview_edit')) { 
          e.preventDefault();
          return false;
        }
//        if ((keyCode === 13) && ($(this).attr('id') != 'form_overview_edit')) { 
//          e.preventDefault();
//          return false;
//        }
    });
    
    

    $('textarea#profile_about').focus(function() {
       $(this).addClass('clicked');
    });
    $('textarea#profile_about').blur(function() {
       $(this).removeClass('clicked');
    });


    
    //$("#profile_about").keyup(function(event) {
//    $('form #user_interest').on('keyup keypress', function(event) {
//        if (event.keyCode === 13) {
//            event.preventDefault();
//            return false;
//        }
//    });
//    
//    $('form textarea#profile_about').on('keyup keypress', function(event) {
//    //$('textarea#profile_about').keypress(function(event) {
//        if (event.keyCode == 13) {
//            event.preventDefault();
//            return true;
//        }
//    });
    
    // Selectable dropdown
    $('.chosen-select, .chosen-select-deselect').chosen({ no_results_text: "No result found. Press enter to add item" });
    $('.chosen-select-deselect').chosen({ allow_single_deselect: true });
    $('.chosen-select-deselect').prop('disabled', true).trigger("chosen:updated");
    
//    $('.chosen-select').on('keyup keypress', function(e) {
//        var keyCode = e.keyCode || e.which;
//        if (keyCode === 13) { 
//          e.preventDefault();
//          return false;
//        }
//    });
    
    
    // image crop section
    $('#parent_image, #children_image, #pet_image, #elder_image, #add_children_image, #profile_pet_image, #support_image, #parent_admin_image, #add_profile_image, #add_profile_image1').change(function () {
        var ext = $(this).val().split('.').pop().toLowerCase();
        var form_name = $(this).data('form');
        var modal_name = $(this).data('name');
        //console.log(form_name); console.log(modal_name);
        if ($.inArray(ext, ['gif', 'png', 'jpg', 'jpeg']) == -1) {
            alert('Only png, jpg, gif files are allowed.');
            $(this).val("");
        } else {
            //$("#"+modal_name+" #"+form_name+" #browse_div").hide();
            $("#"+modal_name+" #"+form_name+" #cropcancel").show();
            console.log("Herer");
            readURLprofile(this, form_name, modal_name);
        }
        //readURLprofile(this, form_name, modal_name);
    });
    
    boundary1 = {width: 800, height: 400};
    viewport1 = {width: 270, height: 270, type: 'circle', border: 0};
    if ($(window).width() < 800) {
        boundary1.width = 300;
        boundary1.height = 300;
        viewport1.width = 200;
        viewport1.height = 200;
    }
    
    function readURLprofile(input, form_name, modal_name) {
        //$(".pro-sec").fadeOut(500);
        //$(".propicBody").fadeIn(500);
        //console.log("#"+modal_name+" #"+form_name+" #maincropdiv");
        $("#"+modal_name+" #"+form_name+" #maincropdiv").html('<div id="upload-demo" ></div>');
        $("#"+modal_name+" #"+form_name+" .complete_cont_crop").show();
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
        uploadCrop = $("#"+modal_name+" #"+form_name+" #upload-demo").croppie({
            viewport: viewport1,
            boundary: boundary1,
            enableExif: true,
        });
    }
    
    $(".cancelCrop").click(function (e) {
        //e.preventDefault();
        var form_name = $(this).data('form');
        var modal_name = $(this).data('name');
        //$("#browse_div").show();
        $("#"+modal_name+" #"+form_name+" #cropcancel").hide();
        //$("#addpp").val('');
        $("#"+modal_name+" #"+form_name+" #upload-demo").html("");
        if($(this).attr('data-type')) {
            $('#parent_image').val('');
        }
    });
    
    $('.saveCrop').on('click', function (ev) {  //alert();
        var form_name = $(this).data('form');
        var modal_name = $(this).data('name');
        var src_name = $(this).data('src');
        uploadCrop.croppie('result', {
            type: 'canvas',
            size: 'viewport'
        }).then(function (resp) {
            //var imageTag = '<img class="final-preview" alt="" src="' + resp + '"> <br><br>';
            //var cancelBtn = "<a href='#' onclick='window.location.reload();' id='cancelPreview' class='btn btn-default'> Cancel </a> ";
            //console.log(resp);
            $("#"+modal_name+" #"+form_name+" ."+src_name).attr('src', resp);
            $("#"+modal_name+" #"+form_name+" ."+src_name).css('display', "block");
            $("#"+modal_name+" #"+form_name+" .complete_cont_crop").hide();
            $("#"+modal_name+" #"+form_name+" #imageBase64").val(resp);
            //$('#img-preview').html(imageTag);
//            $('#img-preview').append(cancelBtn);
            //$("#browse_div").hide();
            $("#"+modal_name+" #"+form_name+" #cropcancel").hide();
            $("#"+modal_name+" #"+form_name+" #upload-demo").html("");
//            $("#imageBase64").val(resp);
        });
    });
    
    $('#p_family_image').change(function () {
        var ext = $(this).val().split('.').pop().toLowerCase();
        var form_name = $(this).data('form');
        var modal_name = $(this).data('name');
        if ($.inArray(ext, ['gif', 'png', 'jpg', 'jpeg']) == -1) {
            alert('Only png, jpg, gif files are allowed.');
            $(this).val("");
        } else {
            $("#"+modal_name+" #"+form_name+" #cropcancel").show();
            console.log("Herer");
            readURLprofile1(this, form_name, modal_name);
        }
        //readURLprofile1(this, form_name, modal_name);
    });
    
    boundary2 = {width: 800, height: 526};
    viewport2 = {width: 775, height: 370, type: 'rectangle', border: 0};
    if ($(window).width() < 800) {
        var wwidth = $( window ).width(); //alert(wwidth);
        var half = Math.ceil(wwidth/2); //alert(half);
        boundary2.width = wwidth - 55;
        boundary2.height = (wwidth - half) + 50;
        viewport2.width = wwidth - 80;
        viewport2.height = (wwidth - half) - 40;
    }
    
    function readURLprofile1(input, form_name, modal_name) {
        $("#"+modal_name+" #"+form_name+" #maincropdiv").html('<div id="upload-demo" ></div>');
        $("#"+modal_name+" #"+form_name+" .complete_cont_crop").show();
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
        uploadCrop = $("#"+modal_name+" #"+form_name+" #upload-demo").croppie({
            viewport: viewport2,
            boundary: boundary2,
            enableExif: true,
        });
    }
    
    $(".cancelCrop1").click(function (e) {
        var form_name = $(this).data('form');
        var modal_name = $(this).data('name');
        $("#"+modal_name+" #"+form_name+" #cropcancel").hide();
        $("#"+modal_name+" #"+form_name+" #upload-demo").html("");
    });
    
    $('.saveCrop1').on('click', function (ev) {
        var form_name = $(this).data('form');
        var modal_name = $(this).data('name');
        var src_name = $(this).data('src');
        uploadCrop.croppie('result', {
            type: 'canvas',
            size: 'viewport'
        }).then(function (resp) {
            $("#"+modal_name+" #"+form_name+" ."+src_name).attr('src', resp);
            $("#"+modal_name+" #"+form_name+" ."+src_name).css('display', "block");
            $("#"+modal_name+" #"+form_name+" .complete_cont_crop").hide();
            $("#"+modal_name+" #"+form_name+" #imageBase64").val(resp);
           
            $("#"+modal_name+" #"+form_name+" #cropcancel").hide();
            $("#"+modal_name+" #"+form_name+" #upload-demo").html("");

            $('#form_up_family_img').trigger('submit');
            
        });
    });
    
    $('#form_up_family_img').ajaxForm({
        success: function (response) {
            toastr.success('Family photo updated successfully.');
        },
        error : errorHandler
    });

    load_resources(type = '', sort = '');

    function load_resources(type, sort) {
        var loc = window.location.pathname;
        $.ajax({
            type: "POST",
            data: { 'type': type, 'loc': loc },
            url: "user/profile/get_datasource_list",
            success: function(result) {
                var item = [];
                for(var i=1; i<=result; i++) {
                    item.push(i);
                }
                
                $('.references_list_main').pagination({
                    dataSource: item,
                    pageSize: 10,
                    pageRange: 2,
                    showPageNumbers: true,
                    showNavigator: true,
                    totalNumber: 5,
                    showLastOnEllipsisShow: true,
                    
                    callback: function(data, pagination) {
                        var html = template(data, pagination, type, sort, loc, result);
                    }
                });
            }
        });
    }
    
    function template(data, pagination, type, sort, loc, res) {
        $.ajax({
            type: "POST",
            dataType: 'json',
            data: { 'type': type, 'sort': sort, 'range': pagination.pageNumber, 'size': pagination.pageSize, 'loc': loc },
            url: "user/profile/get_reference_filter_sort",
            success: function(result) {
                dataContainer.html(result);
                if(res <= pagination.pageSize) {
                    $('.paginationjs').css('display', 'none');
                }
            }
        });
    }
    
});
$(function () {
    $('#dob1, #dob2, #dob4, #add_child_dob, #dob5, #parent_dob').datetimepicker({
        format: 'D MMM YY',
        maxDate: new Date()
    });

    $('#dob').datetimepicker({
        maxDate: moment(),
        format: 'D MMM YY',
        useCurrent: false,
    });

    $('#dob3, #pet_edit_dob').datetimepicker({
        format: 'D MMM YY',
        maxDate: new Date()
    }).val('');
     
});

function countChar(val) {
    var len = val.value.length;
    if (len > 500) {
      val.value = val.value.substring(0, 500);
      $('#charNum').text(0);
    } else {
      $('#charNum').text(500 - len);
    }
}

function countChar1(val) {
    var len = val.value.length;
    if (len > 500) {
      val.value = val.value.substring(0, 500);
      $('#charNum1').text(0);
    } else {
      $('#charNum1').text(500 - len);
    }
}
//var cars = [];
$(document).ready(function(){
    
    load_famlies();
    
    function load_famlies() {
        $.ajax({
            type: "POST",
            data: { },
            dataType: 'json',
            url: "user/profile/get_all_famlies",
            success: function(result) {
                var cars = [];
                $.map( result, function( val, i ) {
                    cars.push(val.name);	
                });
                
                // Constructing the suggestion engine
                var cars = new Bloodhound({
                    datumTokenizer: Bloodhound.tokenizers.whitespace,
                    queryTokenizer: Bloodhound.tokenizers.whitespace,
                    local: cars
                });

                // Initializing the typeahead
                $('.typeahead').typeahead({
                    hint: true,
                    highlight: true, /* Enable substring highlighting */
                    minLength: 1, /* Specify minimum characters required for showing suggestions */
                },
                {
                    name: 'cars',
                    source: cars,
                }).bind("typeahead:selected", function(obj, datum, name) {
                    console.log(datum);
                    var href = 'javascript:void(0)';
                    for (var i=0; i < result.length; i++) {
                        if (result[i].name === datum) {
                            href = 'family/'+result[i].id+'/view';
                        }
                    }
                    $('.search_family').attr('target', '_blank');
                    $('.search_family').attr('href', href);
                });
            }
        });
    }
    
});  