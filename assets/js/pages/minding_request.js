$(function () {
   
    var db_minding_id = $("#minding_id").val();
    if (db_minding_id == '') {
        $(".child_cls").click();
        //$(".pet_names").attr("checked", "true");
        $(".pet_names").click();
        $(".elders").click();
        // $(".services_cls").click();
        // $(".services_cls_garden").click();
    }
    $('#datetimepicker_fromw,#datetimepicker_tow').datetimepicker({
        format: 'D MMM YY',
        useCurrent: false,
        minDate: moment()
    });

    $('#datetimepicker_fromp,#datetimepicker_top').datetimepicker({
        format: 'D MMM YY h:mm A',
        // useCurrent: false,
        minDate: moment()
    });
    $('#datetimepicker_from,#datetimepicker_to').datetimepicker({
        format: 'D MMM YY hh:mm A',
        useCurrent: true,
        //minDate: moment()
    });


    $('#datetimepicker_from').datetimepicker().on('dp.change', function (e) {
        var incrementDay = moment(new Date(e.date));
        incrementDay.add(0, 'days');
        $('#datetimepicker_to').data('DateTimePicker').minDate(incrementDay);
        // $(this).data("DateTimePicker").hide();
    });
    $('#datetimepicker_to').datetimepicker().on('dp.change', function (e) {
        var decrementDay = moment(new Date(e.date));
        decrementDay.subtract(1, 'days');
        //$('#datetimepicker_from').data('DateTimePicker').maxDate(decrementDay);
        // $(this).data("DateTimePicker").hide();
    });
    //*********** for edit
    $('#datetimepicker_fromedit,#datetimepicker_toedit').datetimepicker({
        format: 'D MMM YY hh:mm A',
// useCurrent: false,
// minDate: moment()
    });
    $('#datetimepicker_fromedit').datetimepicker().on('dp.change', function (e) {
        var incrementDay = moment(new Date(e.date));
        incrementDay.add(0, 'days');
        $('#datetimepicker_toedit').data('DateTimePicker').minDate(incrementDay);
        $(this).data("DateTimePicker").hide();
    });
    $('#datetimepicker_toedit').datetimepicker().on('dp.change', function (e) {
        var decrementDay = moment(new Date(e.date));
        decrementDay.subtract(1, 'days');
        $('#datetimepicker_fromedit').data('DateTimePicker').maxDate(decrementDay);
        $(this).data("DateTimePicker").hide();
    });
});
//console.log("Minding Request Page");
$(document).ready(function () {
     $(".pet_section_dog #Other").hide();
    $("#create_children_minding").validationEngine('attach', {maxErrorsPerField: 1, promptPosition: "bottomLeft", validateNonVisibleFields: true,
        updatePromptsPosition: true});
    $('#create_children_minding').ajaxForm({
        success: function (response) {
            toastr.success('Minding request created successfully.');
            setTimeout(function () {
            }, 4000);
            window.location.href = "mindings/children_detail" + "/" + response.minding_id;
        },
        error: errorHandler
    });
    // **************** Pets minding ********************************
    $(".pet_button").click(function () {
        $('.minding_Sec').each(function () {
            if ($('.minding_Sec .pet_list:visible').length == 0) {
                $('.minding_Sec ul li input').removeClass('validate[required]');
            }
            if ($('.minding_Sec .pet_list:visible').length > 0) {
                $('.minding_Sec ul li input').addClass('validate[required]');
            }
        });
    });
    $("#create_pet_minding").validationEngine('attach', {maxErrorsPerField: 1, promptPosition: "topRight", validateNonVisibleFields: true,
        updatePromptsPosition: true, ignore: ":hidden"});
    $('#create_pet_minding').ajaxForm({
        success: function (response) {
            toastr.success('Minding request created successfully.');
            setTimeout(function () {
            }, 4000);
            window.location.href = "mindings/pet_detail" + "/" + response.minding_id;
        },
        error: errorHandler
    });
    $('.pet_section input').each(function () {
        if ($(this).is(':checked')) {
            var chkid = $(this).attr('id');
//            var val = $(this).val();
//            show_hide(chkid, val);

            $("#minding_Sec_" + chkid).css({'display': 'block'});
        } else {
            $("#minding_Sec_" + chkid).css({'display': 'none'});
        }
    });
    $('.pet_section input').each(function () {
        if ($(this).is(':checked')) {
            var chkid = $(this).attr('id');
            var mytext = $(this).data('value');
            if (mytext == 'Fish' || mytext == 'fish' || mytext == 'Birds' || mytext == 'Bird' || mytext == 'Reptiles') {
                $('#minding_Sec_' + chkid + ' li').hide();
                $("#minding_Sec_" + chkid + " #Feeding").show();
            }
            if (mytext == 'Cats' || mytext == 'cats' || mytext == 'cat' || mytext == 'Cat') {
                $('#minding_Sec_' + chkid + ' li').hide();
                $("#minding_Sec_" + chkid + " #Minding").show();
                $("#minding_Sec_" + chkid + " #Feeding").show();
                $("#minding_Sec_" + chkid + " #Others").show();
            }
            if (mytext == 'Dogs' || mytext == 'Dog') {
                $("#minding_Sec_" + chkid + " #MindingorFeeding").hide();
            }
//            var val = $(this).val();
//            show_hide(chkid, val);
            $("#minding_Sec_" + chkid).css({'display': 'block'});
        } else {
            $("#minding_Sec_" + chkid).css({'display': 'none'});
        }
    });
    if ($(".chd:checked").val() == '3') {
        $(".other_address").show();
    }
    if ($(".sup_radio:checked").val() == '4') {
        $(".other_address").show();
    }
});
function pet_click(pet_id) {
    // $(".pet_list").css({'display': 'block'});
    if ($('#' + pet_id).is(':checked')) {
        $(".pc" + pet_id).removeAttr("disabled");
        $("#pet_list" + pet_id).css({'display': 'block'});
        $("#minding_Sec_" + pet_id).css({'display': 'block'});
        $(".p" + pet_id).css({'display': 'block'});
        $("#minding_Sec_" + pet_id + " input").addClass('validate[required]');
    } else {
        $(".pc" + pet_id).attr("disabled", true);
        $("#pet_list" + pet_id).css({'display': 'none'});
        $("#minding_Sec_" + pet_id).css({'display': 'none'});
        $(".p" + pet_id).css({'display': 'none'});
    }
    $('.pet_section input').each(function () {
        if ($(this).is(':checked')) {
            var chkid = $(this).attr('id');
            var mytext = $(this).data('value');
            if (mytext == 'Fish' || mytext == 'fish' || mytext == 'Birds' || mytext == 'Bird' || mytext == 'Reptiles') {
                $('#minding_Sec_' + chkid + ' li').hide();
                $("#minding_Sec_" + chkid + " #Feeding").show();
            }
            if (mytext == 'Cats' || mytext == 'cats' || mytext == 'cat' || mytext == 'cats' || mytext == 'Cat') {
                $('#minding_Sec_' + chkid + ' li').hide();
                $("#minding_Sec_" + chkid + " #Minding").show();
                $("#minding_Sec_" + chkid + " #Feeding").show();
                $("#minding_Sec_" + chkid + " #Others").show();
            }
            if (mytext == 'Dogs' || mytext == 'Dog') {
                $("#minding_Sec_" + chkid + " #MindingorFeeding").hide();
            }
//            show_hide(chkid, val);
            $("#minding_Sec_" + chkid).css({'display': 'block'});
        } else {
            $("#minding_Sec_" + chkid).css({'display': 'none'});
        }
    });
}

/*
 *  Supported
 */
//$(document).ready(function () { 
$("input[name$='time']").click(function () {
    var test = $(this).val();
    $("div.desc").hide();
    $(".Cars" + test).show();
});
$('.btn-show-nav').click(function () {
// $('.right_main').toggleClass('show');
});
$('input[type="radio"]').change(function () {
    if (($(this).val() == '4')) {
        $(".other_address").show();
    } else {
        $(".other_address").hide();
    }
});
$('.chd').change(function () {
    if (($(this).val() == '3')) {
        $(".other_address").show();
    } else {
        $(".other_address").hide();
    }
});
$('input[type="checkbox"]').change(function () {
    if (this.checked) {
        $(this).parents('li').addClass('normal');
    } else {
        $(this).parents('li').removeClass('normal');
    }
});
//});
$(function () {
    if ($('#customize-spinner').length) {
        $('#customize-spinner').spinner('changed', function (e, newVal, oldVal) {
            $('#old-val').text(oldVal);
            $('#new-val').text(newVal);
        });
        $('[data-trigger="spinner"]').spinner('changing', function (e, newVal, oldVal) {
            var well = $(e.currentTarget).closest('.well');
            $('small', well).text("Old = " + oldVal + ", New = " + newVal);
        });
        $('#step-spinner').spinner({
            step: function (dir) {
                if ((this.oldValue === 1 && dir === 'down') || (this.oldValue === -1 && dir === 'up')) {
                    return 2;
                }
                return 1;
            }
        });
    }
});
// **************** Supported minding ********************************
$("#create_supported_minding").validationEngine('attach', {maxErrorsPerField: 1, promptPosition: "bottomLeft", validateNonVisibleFields: true,
    updatePromptsPosition: true, ignore: ":hidden"});
$("#btn_submit").click(function () {
    $('#create_supported_minding').ajaxForm({
        success: function (response) {
            toastr.success('Minding request created successfully.');
            setTimeout(function () {
            }, 4000);
            console.log(response.minding_id);
            window.location.href = "mindings/supported_detail" + "/" + response.minding_id;
        },
        error: errorHandler
    });
});
// For grey effect on images
$('input[type="checkbox"]').change(function () {
    if (this.checked) {
        $(this).parents('li').addClass('normal');
    } else {
        $(this).parents('li').removeClass('normal');
    }
});
$(".cancel_button").click(function () {
    var str_id = this.id;
    console.log(str_id);
    var res = str_id.split("_");
    var id = res[1];
    $.ajax({
        // type: "POST",
        url: 'mindings/minding_status' + "/" + id,
    }).done(function (response) {
        toastr.success('Minding request cancelled.');
        window.location.href = "create_minding_children";
    });
});
/*
 *  Home Minding Request 
 */
$(document).ready(function () {
    $('#datetimepicker_home,#datetimepicker6,#datetimepicker7,#datetimepicker8').datetimepicker({format: 'D MMM YY', minDate: moment()});
    $('#timepicker_home,#timepicker_home2, #timepicker_home3, #timepicker_home4, #timepicker_home5, #timepicker_home6, #timepicker_home8 ').datetimepicker({format: 'hh:mm A',
        // disabledTimeIntervals: [[moment({h: 0}), moment({h: 6})], [moment({h: 17, m: 30}), moment({h: 24})]],
        //enabledHours: [6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17],
        stepping: 15});
    $("#cleaning_section,#cooking_section,#mail_section,#other_section").css("display", "none");
    $("#mowing_section,#rubbish_section,#gardenother_section,#lawn_section").css("display", "none");
    $('input.services_cls').each(function () {
        if ($(this).is(':checked')) {
            var chkid = $(this).attr('id');
            var val = $(this).val();
            show_hide(chkid, val);
        }
    });
    $('input.services_cls_garden').each(function () {
        if ($(this).is(':checked')) {
            var chkid = $(this).attr('id');
            var val = $(this).val();
            gardenshow_hide(chkid, val);
        }
    });
});
$("#create_home_minding").validationEngine('attach', {maxErrorsPerField: 1, promptPosition: "bottomLeft", validateNonVisibleFields: true,
    updatePromptsPosition: true, ignore: ":hidden"});
$('#create_home_minding').ajaxForm({
    success: function (response) {
        toastr.success('Minding request created successfully.');
        setTimeout(function () {
        }, 4000);
        window.location.href = "mindings/home_detail" + "/" + response.minding_id;
    },
    error: errorHandler
});

$(".family_provide").click(function () {
    $("#provide_type").val('FAMILY');
});
$(".elder_provide").click(function () {
    $("#provide_type").val('ELDER');
});

function home_services(ths, val) {
    $("#" + ths.id).on('change', function () {
        var val = this.checked ? this.value : '';
        var chkid = ths.id;
        show_hide(chkid, val);
    });
}

function show_hide(chkid, val) {

    if (chkid == 3 && val != '') {
        $("#cleaning_section").show();
        
        $("#cooking_section").hide();
        $("#mail_section").hide();
        $("#other_section").hide();
        
        $("#cooking_serves").removeClass('validate[min[1]]');
        $(".mail_type_cls").removeClass('validate[minCheckbox[1]]');
        
        $("#datetimepicker6").removeClass('validate[required]');
        $("#datetimepicker7").removeClass('validate[required]');
        $("#timepicker_home8").removeClass('validate[required]');
        $(".collectiondays").removeClass('validate[minCheckbox[1]]');
        
        
        $("#timepicker_home").addClass('validate[required]');
        $("#timepicker_home2").addClass('validate[required]');
        $("#cleaning_equipment").addClass('validate[required]');
        // $("#cleaning_minutes").addClass('validate[min[10]]');

    } else if (chkid == 3 && val == '') {
        $("#cleaning_section").hide();
        $("#timepicker_home").removeClass('validate[required]');
        $("#timepicker_home2").removeClass('validate[required]');
        $("#cleaning_equipment").removeClass('validate[required]');
        // $("#cleaning_minutes").removeClass('validate[min[10]]');

    }

    if (chkid == 2 && val != '') {
        $("#cleaning_section").hide();
        $("#mail_section").hide();
        $("#other_section").hide();
        
        $("#datetimepicker6").removeClass('validate[required]');
        $("#datetimepicker7").removeClass('validate[required]');
        $("#timepicker_home8").removeClass('validate[required]');
        $(".collectiondays").removeClass('validate[minCheckbox[1]]');
        
        $("#timepicker_home").removeClass('validate[required]');
        $("#timepicker_home2").removeClass('validate[required]');
        $("#cleaning_equipment").removeClass('validate[required]');
        
        $("#cooking_section").show();
        $("#cooking_serves").addClass('validate[min[1]]');
        $(".mail_type_cls").addClass('validate[minCheckbox[1]]');
    } else if (chkid == 2 && val == '') {
        $("#cooking_section").hide();
        $("#cooking_serves").removeClass('validate[min[1]]');
        $(".mail_type_cls").removeClass('validate[minCheckbox[1]]');
    }

    if (chkid == 1 && val != '') {
        $("#cleaning_section").hide();
        $("#other_section").hide();
        $("#cooking_section").hide();
        
        
        $("#timepicker_home").removeClass('validate[required]');
        $("#timepicker_home2").removeClass('validate[required]');
        $("#cleaning_equipment").removeClass('validate[required]');
        
        
         $("#cooking_serves").removeClass('validate[min[1]]');
        $(".mail_type_cls").removeClass('validate[minCheckbox[1]]');
        
        
        $("#mail_section").show();
        $("#datetimepicker6").addClass('validate[required]');
        $("#datetimepicker7").addClass('validate[required]');
        $("#timepicker_home8").addClass('validate[required]');
        $(".collectiondays").addClass('validate[required]');
    } else if (chkid == 1 && val == '') {
        $("#mail_section").hide();
        $("#datetimepicker6").removeClass('validate[required]');
        $("#datetimepicker7").removeClass('validate[required]');
        $("#timepicker_home8").removeClass('validate[required]');
        $(".collectiondays").removeClass('validate[minCheckbox[1]]');
    }

    if (chkid == 0 && val != '') {
        $("#cleaning_section").hide();
        $("#cooking_section").hide();
        $("#mail_section").hide();
        
        $("#other_section").show();
        
         $("#datetimepicker6").removeClass('validate[required]');
        $("#datetimepicker7").removeClass('validate[required]');
        $("#timepicker_home8").removeClass('validate[required]');
        $(".collectiondays").removeClass('validate[required]');
        $(".collectiondays").removeClass('validate[minCheckbox[1]]');
        
       // $("#exampleTextarea4").addClass('validate[required]');
        //$("#other_minutes").addClass('validate[required]');
       // $(".other_equipment").addClass('validate[required]');
    } else if (chkid == 0 && val == '') {
        $("#other_section").hide();
        //$("#exampleTextarea4").removeClass('validate[min[10]]');
        // $("#other_minutes").removeClass('validate[min[10]]');
        // $(".other_equipment").removeClass('validate[min[10]]');
    }
}
/*
 * Garden Mindng
 */
$("#create_garden_minding").validationEngine('attach', {maxErrorsPerField: 1, promptPosition: "bottomLeft", validateNonVisibleFields: true,
    updatePromptsPosition: true, ignore: ":hidden"});
$('#create_garden_minding').ajaxForm({
    success: function (response) {
        console.log(response);
        toastr.success('Minding request created successfully.');
        setTimeout(function () {
        }, 4000);
        window.location.href = "mindings/garden_detail" + "/" + response.minding_id;
    },
    error: errorHandler
});
function garden_services(ths, val) {
    $("#" + ths.id).on('change', function () {
        var val = this.checked ? this.value : '';
        var chkid = ths.id;
        gardenshow_hide(chkid, val);
    });
}

function gardenshow_hide(chkid, val) {
    if (chkid == 3 && val != '') {
        $("#mowing_section").show();
        $(".mowing_equipment").addClass('validate[required]');
        $(".trimming").addClass('validate[required]');
    } else if (chkid == 3 && val == '') {
        $("#mowing_section").hide();
        $(".mowing_equipment").removeClass('validate[required]');
        $(".trimming").removeClass('validate[required]');
    }
    if (chkid == 2 && val != '') {
        $("#lawn_section").show();
        $(".lawn_equipment").addClass('validate[required]');
    } else if (chkid == 2 && val == '') {
        $("#lawn_section").hide();
        $(".lawn_equipment").removeClass('validate[required]');
    }

    if (chkid == 1 && val != '') {
        $("#rubbish_section").show();
        $("#rubbish_loads").addClass('validate[min[1]]');
        $(".rubbish_loadtype").addClass('validate[required]');
    } else if (chkid == 1 && val == '') {
        $("#rubbish_section").hide();
        $("#rubbish_loads").removeClass('validate[min[1]]');
        $(".rubbish_loadtype").removeClass('validate[required]');
    }

    if (chkid == 0 && val != '') {
        $("#gardenother_section").show();
        $("#other_description").addClass('validate[required]');
        $(".other_equipment").addClass('validate[required]');
    } else if (chkid == 0 && val == '') {
        $("#gardenother_section").hide();
        $("#other_description").removeClass('validate[required]');
        $(".other_equipment").removeClass('validate[required]');
    }
}

function children_karma_points() {
    var state_id = $("#state_id").val();
    var country_id = $("#country_id").val();
    var date_from = $(".datetimepicker_from").val();
    var date_to = $(".datetimepicker_to").val();
    var total = $('input[name="children_id[]"]:checked').length;
    if (date_from != '' && date_to != '' && total != 0) {
        var settings = {"async": true, "crossDomain": true,
            "url": "frontend/get_karma_points",
            "method": "POST",
            "global": false,
            "data": {"type": "1",
                "from": date_from,
                "state_id": state_id,
                "country_id": country_id,
                "to": date_to,
                "count": total}};
        $.ajax(settings).done(function (response) {
            if (response.length <= 9) {
                $("#loading").show();
                $(".kc_class").hide();
                setTimeout(function () {
                    $("#loading").hide();
                    $(".kc_class").show();
                }, 2000);

                $(".karmas").html(response);
                $("#minding_points").val(response);
            }
        });
    } else {
        $(".karmas").html(0);
        $("#minding_points").val(0);
    }
}

function pet_karma_points() {
    var state_id = $("#state_id").val();
    var country_id = $("#country_id").val();
    var date_from = $(".datetimepicker_from").val();
    var date_to = $(".datetimepicker_to").val();
    var total = $('input[name="pet_id[]"]:checked').length;

    var animals = [];
    var pettype = '';
    // console.log(('input[name="pet_id[]"]:checked').length);
    $('input[name="pet_id[]"]:checked').each(function () {
        var id = $(this).attr("id");
        console.log(id + "ID PET");
        //pettype = $(this).data('pettype');
        console.log(".p" + id);
        //var rd_val = $(".pc" + id).val();
        //$('input[name=radioName]:checked', '#myForm').val();
        var rd_val = $(".pc" + id + ":checked").val();
        console.log(rd_val + " here");

        animals.push({'type': $(this).data('pettype'), 'mindid': rd_val});
    });
    console.log(animals);

    if (date_from != '' && date_to != '') {
        var settings = {
            "async": true,
            "crossDomain": true,
            "url": "frontend/get_karma_points",
            "method": "POST",
            "global": false,
            "data": {
                "from": date_from,
                "to": date_to,
                "state_id": state_id,
                "country_id": country_id,
                "type": 2,
                "animals": animals
            }
        }

        $.ajax(settings).done(function (response) {
            if (response.length <= 9) {
                $("#loading").show();
                $(".kc_class").hide();
                setTimeout(function () {
                    $("#loading").hide();
                    $(".kc_class").show();
                }, 2000);

                $(".karmas").html(response);
                $("#minding_points").val(response);
            }
        });
    } else {
        $(".karmas").html(0);
        $("#minding_points").val(0);
    }
}

$('.spin_supported').click(function () {
//alert("gg");
    $(this).siblings('input').change();
    supported_karma_points();
});

function supported_karma_points() {
    var date_from = $(".datetimepicker_from").val();
    var date_to = $(".datetimepicker_to").val();
    var total = $('input[name="elder_id[]"]:checked').length;

    var hours = $("#time_hours").val();
    var pre_minutes = $("#time_minutes").val();
    var minutes = hours * 60;
    var total_min = parseInt(pre_minutes) + parseInt(minutes);

    if (date_from != '' && date_to != '') {
        var settings = {
            "async": true,
            "crossDomain": true,
            "url": "frontend/get_karma_points",
            "method": "POST",
            "global": false,
            "data": {
                "type": "3",
                "from": date_from,
                "to": date_to,
                "min": total_min,
                "count": total
            }
        }

        $.ajax(settings).done(function (response) {
            if (response.length <= 9) {
                $("#loading").show();
                $(".kc_class").hide();
                setTimeout(function () {
                    $("#loading").hide();
                    $(".kc_class").show();
                }, 2000);

                $(".karmas").html(response);
                $("#minding_points").val(response);
            }
        });
    }
}

$('.spin_home').click(function () {
    $(this).siblings('input').change();
    home_karma_points();
});

function home_karma_points() {
    // var date_from = $("#datetimepicker_home").val();
    //  var date_to = $("#datetimepicker_home").val();

    var date_from = $(".datetimepicker_from").val();
    var date_to = $(".datetimepicker_to").val();

    var total_min = 0;
    var cleaning_item = false;
    var pickup_type = false;


    if ($('#3:checked').length != '0') {
        var hours = $("#cleaning_hours").val();
        var pre_minutes = $("#cleaning_minutes").val();
        var minutes = hours * 60;
        total_min = parseInt(pre_minutes) + parseInt(minutes);
        var cl_eqp = $(".cleaning_equipment:checked").val();
        var checkedElement = $('input[name=cleaning_equipment]:checked');

        if (cl_eqp == 'YOURS' || cl_eqp == 'EITHER') {
            cleaning_item = true;
        }
    }
    var mail_from = '';
    var mail_to = '';
    var days = '';

    if ($('#1:checked').length != '0') {
        mail_from = $("#datetimepicker6").val();
        mail_to = $("#datetimepicker7").val();
        days = $('.collectiondays:checked').map(function () {
            return this.id;
        }).get().join(',');
    }

    var cooking_serves = 0;
    var main_meal = 0;
    var main_dessert = 0;

    if ($('#2:checked').length != '0') {
        var cooking_serves = $("#cooking_serves").val();
        main_meal = 0;
        main_dessert = 0;
        $('.mail_type_cls:checked').each(function () {
            if (this.value == 'm') {
                main_meal = cooking_serves;
            }
            if (this.value == 'cd') {
                main_dessert = cooking_serves;
            }
            var pickup_typeval = $(".pickup_type:checked").val();
            if (pickup_typeval == '2') {
                pickup_type = true;
            }
        });
    }

    var other_total_min = 0;
    if ($('#0:checked').length != '0') {
        var ohours = $("#other_hours").val();
        var opre_minutes = $("#other_minutes").val();
        var ominutes = ohours * 60;
        other_total_min = parseInt(opre_minutes) + parseInt(ominutes);
    }


    console.log(total_min);
    if (date_from != '' && date_to != '') {
        var settings = {
            "async": true,
            "crossDomain": true,
            "url": "frontend/get_karma_points",
            "method": "POST",
            "global": false,
            "data": {
                "type": "4",
                "cleaning": total_min,
                "cleaning_item": cleaning_item,
                "from": date_from,
                "to": date_to,
                "mail_colllection[from]": mail_from,
                "mail_colllection[to]": mail_to,
                "mail_colllection[days]": days,
                "main_meal": main_meal,
                "main_dessert": main_dessert,
                "pickup_type": pickup_type,
                "other": other_total_min
            }
        }

        $.ajax(settings).done(function (response) {
            if (response.length <= 9) {
                $("#loading").show();
                $(".kc_class").hide();
                setTimeout(function () {
                    $("#loading").hide();
                    $(".kc_class").show();
                }, 2000);

                $(".karmas").html(response);
                $("#minding_points").val(response);
            }
        });
    }
}

$('.spin_garden').click(function () {
    $(this).siblings('input').change();
    garden_karma_points();
});
function garden_karma_points() {
    var date_from = $(".datetimepicker_from").val();
    var date_to = $(".datetimepicker_to").val();

    var total_min = 0;
    var mowing_equipment = false;

    if ($('#3:checked').length != '0') {
        var hours = $("#mowing_hours").val();
        var pre_minutes = $("#mowing_minutes").val();
        var minutes = hours * 60;
        total_min = parseInt(pre_minutes) + parseInt(minutes);

        var cl_eqp = $("input[name=mowing_equipment]:checked").val();
        // alert(cl_eqp);
        if (cl_eqp == 'YOURS' || cl_eqp == 'EITHER') {
            mowing_equipment = true;
        }
    }

    var lawn = 0;
    var lawn_item = false;
    //if ($('#2:checked').length != '0') {
    //if ($('.services_cls_garden:checked').val() == 2) {
    if ($('#2:checked').length != '0') {
        lawn = 60;
        //lawn_item = true;
    }

    var lawn_eqp = $("input[name=lawn_equipment]:checked").val();
    // alert(cl_eqp);
    if (lawn_eqp == 'YOURS' || lawn_eqp == 'EITHER') {
        lawn_item = true;
    }

    //}
    var rubbish_ute = 0;
    var rubbish_van = 0;
    //if ($('.services_cls_garden:checked').val() == 1) {
    if ($('#1:checked').length != '0') {
        //alert($(".rubbish_loadtype").val());
        if ($("input[name='rubbish_loadtype']:checked").val() == 'UTE') {
            rubbish_ute = $("#rubbish_loads").val();
        }
        if ($("input[name='rubbish_loadtype']:checked").val() == 'VAN') {
            rubbish_van = $("#rubbish_loads").val();
        }
    }

    var ototal_min = 0;
    var other_item = false;
    //if ($('.services_cls_garden:checked').val() == 0) {
    if ($('#0:checked').length != '0') {
        var ohours = $("#other_hours").val();
        var opre_minutes = $("#other_minutes").val();
        var ominutes = ohours * 60;
        ototal_min = parseInt(opre_minutes) + parseInt(ominutes);
        var ot_eqp = $(".other_equipment").val();
        // if (ot_eqp != 'OURS') {
        if (ot_eqp == 'YOURS' || ot_eqp == 'EITHER') {
            other_item = true;
        }
    }

    if (date_from != '' && date_to != '') {
        var settings = {
            "async": true,
            "crossDomain": true,
            "url": "frontend/get_karma_points",
            "method": "POST",
            "global": false,
            "data": {
                "type": "5",
                "mowing": total_min,
                "mowing_item": mowing_equipment,
                "from": date_from,
                "to": date_to,
                "lawn": lawn,
                "lawn_item": lawn_item,
                "other": ototal_min,
                "other_item": other_item,
                "rubbish_ute": rubbish_ute,
                "rubbish_van": rubbish_van
            }
        }

        $.ajax(settings).done(function (response) {
            if (response.length <= 9) {
                $("#loading").show();
                $(".kc_class").hide();
                setTimeout(function () {
                    $("#loading").hide();
                    $(".kc_class").show();
                }, 2000);

                $(".karmas").html(response);
                $("#minding_points").val(response);
            }
        });
    }
}


$(document).ready(function () {
    chked_help();
});

$("#elder_help_0").click(function () {
    chked_help();
});

function chked_help() {
    if ($("#elder_help_0").is(':checked')) {
        $("#other_task").show();
    } else {
        $("#other_task").hide();
    }
}