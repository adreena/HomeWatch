require(['jquery', 'vendor/jquery.validate.min'],

function ($) {
    "use strict";

    // IDs
    var LOGIN_FORM_ID = "#login-form";
    var LOGIN_ERROR_ID = "#login-error";

    // Forms
    var loginForm;

    $(function () {
        initLoginForm();
    });
    
    function initLoginForm() {
        loginForm = $(LOGIN_FORM_ID)[0];
        loginForm.reset();
        
        $(loginForm).validate({
            submitHandler: loginUser,
            rules: {
	            username: "required",
	            password: "required"
	        },
            errorPlacement: function(error, element) { },
	        onkeyup: false
        });
    }

    function loginUser() {
        var credentials = getCredentials();

        $.post('/auth/login-user.php', credentials)
        .done(onLoginDone)
        .fail(onLoginFail);
        
        return false;
    }

    function getCredentials() {
        return {
            username: $(LOGIN_FORM_ID).find("input[name=username]").val(),
            password: $(LOGIN_FORM_ID).find("input[name=password]").val()
        };
    }
    
    function onLoginDone(response) {
        window.location.reload();
    }

    function onLoginFail(response) {
        var message = "Incorrect login information.";
        
        switch (response.status) {
            case 500:
                message = "Unknown login error. Please try again later.";
                break;
        }
        
        $(LOGIN_ERROR_ID).text(message);
    }

});

