
$(document).ready(function () {
    $("#account_update").validationEngine('attach', {maxErrorsPerField: 1, promptPosition: "bottomLeft", validateNonVisibleFields: true,
        updatePromptsPosition: true});
    $(".updat_btn").click(function (e) {
        $('#account_update').submit();
    });
    $('#account_update').ajaxForm({
        success: function (response) {
            toastr.success('Account updated successfully.');
            setTimeout(function () {
                window.location.reload();
            }, 1000);

        },
        error: errorHandler
    });

    /*
     * Update Password Section
     */

    $("#update_password_form").validationEngine('attach', {maxErrorsPerField: 1, promptPosition: "bottomLeft", validateNonVisibleFields: true,
        updatePromptsPosition: true, 'custom_error_messages': {
            '#pwc': {
                'equals': {
                    'message': "Current password does not match."
                }
            }
        }});

    $('#update_password_form').ajaxForm({
        success: function (response) {
            toastr.success("Password updated successfully.");
        },
        error: errorHandler
    });


    /*
     *  Subscribe Payment Section
     */

    function format(input, format, sep) {
        var output = "";
        var idx = 0;
        for (var i = 0; i < format.length && idx < input.length; i++) {
            output += input.substr(idx, format[i]);
            if (idx + format[i] < input.length)
                output += sep;
            idx += format[i];
        }

        output += input.substr(idx);

        return output;
    }

    $('.creditCardText').keyup(function () {
        var foo = $(this).val().replace(/-/g, ""); // remove hyphens
        // You may want to remove all non-digits here
        // var foo = $(this).val().replace(/\D/g, "");

        if (foo.length > 0) {
            foo = format(foo, [4, 4, 4], "-");
        }


        $(this).val(foo);
    });
    $('.dateText').keyup(function () {
        var foo = $(this).val().replace(/-/g, ""); // remove hyphens
        // You may want to remove all non-digits here
        // var foo = $(this).val().replace(/\D/g, "");

        if (foo.length > 0) {
            foo = format(foo, [2], "/");
        }


        $(this).val(foo);
    });


    $("#payment_form").validationEngine('attach', {maxErrorsPerField: 1, promptPosition: "topLeft", validateNonVisibleFields: true,
        updatePromptsPosition: true});

    $('#payment_form').ajaxForm({
        success: function (response) {
            var msg = response.msg;
            toastr.success(msg);

            $(".modal-body #plan_id").val('');
            $(".modal-body #amount").val('');
            $(".modal-body #p_amount").html('');
            $(".modal-body #plan_time").val('');
            $(".modal-body #p_time").html('');
            $('.sub_pay_model').modal('hide');
            window.location.reload();
        },
        error: errorHandler
    });

    /* ===== Assign values to modal Form ================== */
    $(document).on("click", ".sub_pay_model_btn", function () {

        var plan_id = $(this).data('id');
        var plan_amount = $(this).data('amount');
        var plan_time = $(this).data('time');

        var exp_date = $("#plan_expire_date").val();
        var today_date = $("#today_date").val();
        if (exp_date != '' && exp_date >= today_date) {

            swal({
                title: "Your current plan will expire on " + exp_date,
                text: "Do you want to upgrade?",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Yes",
                cancelButtonText: "No",
                closeOnConfirm: false,
                closeOnCancel: false
            },
            function (isConfirm) {
                if (isConfirm) {
                    $(".sweet-overlay").hide();
                    $(".sweet-alert").hide();
                    $('.sub_pay_model').modal('show');
                    //$('.sub_pay_model').modal({backdrop: 'static', keyboard: false})  

                    $(".modal-body #plan_id").val(plan_id);
                    $(".modal-body #amount").val(plan_amount);
                    $(".modal-body #p_amount").html(plan_amount);
                    $(".modal-body #plan_time").val(plan_time);
                    $(".modal-body #p_time").html(plan_time);
                } else {
                    swal("Cancelled");
                }
            });

        }
        else {

            $('.sub_pay_model').modal('show');
            //$('.sub_pay_model').modal({backdrop: 'static', keyboard: false})  

            var plan_id = $(this).data('id');
            var plan_amount = $(this).data('amount');
            var plan_time = $(this).data('time');
            $(".modal-body #plan_id").val(plan_id);
            $(".modal-body #amount").val(plan_amount);
            $(".modal-body #p_amount").html(plan_amount);
            $(".modal-body #plan_time").val(plan_time);
            $(".modal-body #p_time").html(plan_time);
            // $('#addBookDialog').modal('show');
        }
    });

    $("#card_form").validationEngine('attach', {maxErrorsPerField: 1, promptPosition: "topLeft", validateNonVisibleFields: true,
        updatePromptsPosition: true});

    $('#card_form').ajaxForm({
        success: function (response) {
            var msg = response.msg;
            toastr.success(msg);
            window.location.reload();
        },
        error: errorHandler
    });
    $("#coupon_form").validationEngine('attach', {maxErrorsPerField: 1, promptPosition: "topLeft", validateNonVisibleFields: true,
        updatePromptsPosition: true});

    $('#coupon_form').ajaxForm({
        success: function (response) {
            var msg = response.msg;
            toastr.success(msg);
            window.location.reload();
        },
        error: errorHandler
    });

    $("#exp_date").datepicker({
        format: "mm/yy",
        viewMode: "months",
        minViewMode: "months"
    });
});
/*
 * Assign values to Invoice POPup
 */

function invoice_popup(plan_id, payment_id) {
    $.ajax({
        // type: "POST",
        url: 'accounts/get_invoice' + '/' + plan_id + '/' + payment_id,
    }).done(function (response) {
        $("#inc_amount").html(response.amount);
        $("#inc_date").html(response.created_at);
        $("#inc_month").html(response.plan_months + " months");
        $("#inc_expdate").html(response.plan_expire);
        $("#inc_t_id").html(response.transaction_id);
        $("#inc_lastfour").html(response.last_four);
        $("#inc_card_expire").html(response.expire_date);
        $("#inc_ctype").html(response.card_type);
        $("#inc_origin").html(response.origin);
        $("#inc_cvv").html(response.cvv_check);
        $("#download").attr("href", "accounts/pdf" + "/" + plan_id + "/" + payment_id)
    });
}

$("#family_friends_of_friends").change(function () {
    if ($(this).val() == 'PRIVATE') {
        $("#family_others").val('PRIVATE');
        $("#family_others").attr('disabled', 'disabled');
        $("#family_others").selectpicker('refresh');
    } else {
        // $("#family_others").val('');
        $("#family_others").removeAttr('disabled');
        $("#family_others").selectpicker('refresh');
    }
});
$("#personal_friends_of_friends").change(function () {
    if ($(this).val() == 'PRIVATE') {
        $("#personal_other").val('PRIVATE');
        $("#personal_other").attr('disabled', 'disabled');
        $("#personal_other").selectpicker('refresh');
    } else {
        //$("#personal_other").val('');
        $("#personal_other").removeAttr('disabled');
        $("#personal_other").selectpicker('refresh');
    }
});
$("#settings_form").submit(function () {
    $(".selectpicker").prop("disabled", false);
});
if ($("#ex13").length) {
    var val_slider = $("#ex13").val();
    $("#ex13").slider({
        ticks: [5, 10, 15, 20, 25, 30],
        ticks_labels: ['5', '10', '15', '20', '25', '30'],
        ticks_snap_bounds: 30,
        value: val_slider

    });
}


$("#settings_form").validationEngine('attach', {maxErrorsPerField: 1, promptPosition: "topLeft", validateNonVisibleFields: true,
    updatePromptsPosition: true});


$("#verification_modal").click(function () {
    var cc = $("#country_code").val();
    var phn = $("#phn").val();

    $("#code").val(cc);
    var m_no = $("#mobile_no").val(phn);
    if (m_no != '') {
        $('#sendcode').submit();
    }

});

//function sub_code(){
$('#sendcode').ajaxForm({
    success: function (response) {
        var msg = response.msg;
        if (msg == "Mobile number already exists.") {
            $('#verification_code_modal').modal('hide');
        } else {
            $('#verification_code_modal').modal('show');
        }
        // toastr.success(msg);
        //window.location.reload();
    },
    error: errorHandler
});

$('#checkcode').ajaxForm({
    success: function (response) {
        var msg = response.msg;
        toastr.success(msg);
        setTimeout(function () {
            $('#verification_code_modal').modal('hide');
        }, 1000);

        window.location.reload();
    },
    error: errorHandler
});

$("#resend_btn").click(function () {
    var cc = $("#country_code").val();
    var phn = $("#phn").val();

    $("#code").val(cc);
    var m_no = $("#mobile_no").val(phn);
    if (m_no != '') {
        $('#sendcode').submit();
    }
    $('#verification_code_modal').modal('show');
});
//}