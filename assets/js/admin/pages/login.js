$(document).ready(function(){  
        
        $('#loginForm').validate({
            rules: {
                email: {
                    required: true,
                    EmailRegex: true
                },
                password: {
                    required: true
                }
            },
            messages: {
                email: {
                    required: "The email field is required.",
                    EmailRegex: "Please enter a valid email address."
                },
                password: {
                    required: "The password field is required."
                }
            },
            submitHandler: function(form) {
                // do other things for a valid form
                $(form).find("button[type='submit']").attr('disabled', true);
                form.submit();
            }
        });
    });