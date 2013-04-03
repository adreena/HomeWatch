require(['jquery', 'vendor/jquery.validate.min'],

function ($) {
    "use strict";

    var PASSWORD_LENGTH_MIN = 8; // TODO: this should match up with the one in register.js

    var RESET_FORM = "#reset-form";
    
    var resetForm;
    
    $(function () {
        initResetForm();
    });
    
    function initResetForm() {
        resetForm = $(RESET_FORM)[0];
        resetForm.reset();
        
        $(resetForm).validate({
            submitHandler: resetPassword,
            rules: {
                password: {
                    required: true,
                    minlength: PASSWORD_LENGTH_MIN
                },
                confpassword: {
                    required: true,
                    equalTo: "#password"
                }
	        },
	        errorElement: "div",
            errorPlacement: function(error, element) {
                $(element).prev().before(error);
            },
	        onkeyup: false
        });
    }
    
    function resetPassword() {
        var resetInfo = getResetInfo();
        console.debug(resetInfo);
        $.post('/auth/reset-password.php', resetInfo)
        .done(onResetDone)
        .fail(onResetFail);
        
        return false;
    }
    
    function onResetDone(response) {
        alert("Reset successful. You may now login with your new password.");
        window.location.replace("/");
    }

    function onResetFail(response) {
        alert("Failed to reset password. Try initiating a new request.");
    }
    
    function getResetInfo() {
        return {
            username: $(resetForm).find('input[name=username]').val(),
            password: $(resetForm).find('input[name=password]').val(),
            token: $(resetForm).find('input[name=token]').val()
        };
    }
    
});
