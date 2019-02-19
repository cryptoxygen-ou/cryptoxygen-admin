$(function () {
    $('#datetimepicker1').datetimepicker({maxDate: moment(),format: 'D MMM YY'});
    var wordLen = 150,
            len; // Maximum word length
    $('#comment_body').keydown(function (event) {
        len = $('#comment_body').val().split(/[\s]+/);
        if (len.length > wordLen) {
            if (event.keyCode == 46 || event.keyCode == 8) {// Allow backspace and delete buttons
            } else if (event.keyCode < 48 || event.keyCode > 57) {//all other buttons
                event.preventDefault();
            }
        }
        console.log(len.length + " words are typed out of an available " + wordLen);
        wordsLeft = (wordLen) - len.length;
        var textVal = $('#comment_body').val();
        if (wordsLeft == 0 || wordsLeft == -1) {
            $('.words-left').html('0 W');
            $('.words-left').css({
                'color': 'red'
            }).prepend('<i class="fa fa-exclamation-triangle"></i>');

//            $('#comment_body').val(textVal.substring(0, textVal.lastIndexOf('.')));
        } else {
            $('.words-left').css({
                'color': '#94979a'
            });
            $('.words-left').html(wordsLeft + 'W');
        }

    });
});

$("#profile_info").validationEngine('attach', {maxErrorsPerField: 1, promptPosition: "bottomLeft", validateNonVisibleFields: true,
    updatePromptsPosition: true});

$('#profile_info').ajaxForm({
    success: function (response) {
        window.location = "invite_friends";
    },
    error: errorHandler
});