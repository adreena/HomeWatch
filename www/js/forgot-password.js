require(['jquery', 'vendor/jquery.validate.min'],

function ($) {
    "use strict";

    var RESET_FORM = "#reset-form";
    
    var resetForm;
    
    $(function () {
        initResetForm();
    });
    
    function initResetForm() {
        resetForm = $(RESET_FORM)[0];
        resetForm.reset();
        
        $(resetForm).validate({
            submitHandler: sendResetToken,
            rules: {
                email: {
                    required: true,
                    email: true
                }
	        },
	        errorElement: "div",
	        onkeyup: false
        });
    }
    
    function sendResetToken() {
        var resetInfo = getResetInfo();
        
        $.post('/HomeWatch/auth/send-reset-token.php', resetInfo)
        .done(onResetDone)
        .fail(onResetFail);
        
        return false;
    }
    
    function onResetDone(response) {
        alert("Reset successful. Check your email to reset your password.");
        window.location.replace("/HomeWatch/");
    }

    function onResetFail(response) {
        alert("Incorrect login information.");
    }
    
    function getResetInfo() {
        return {
            email: $(resetForm).find('input[name=email]').val()
        };
    }
    
});
