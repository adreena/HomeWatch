require(['jquery', 'vendor/jquery.validate.min'],

function ($) {
    "use strict";

    var PASSWORD_LENGTH_MIN = 8; // TODO: this should match up with the one in register.js

    var ACCT_FORM = "#acct-form";
    
    var acctForm;
    
    $(function () {
        initAcctForm();
    });
    
    function initAcctForm() {
    	acctForm = $(ACCT_FORM)[0];
    	acctForm.reset();
        
        $(acctForm).validate({
            submitHandler: acctSettings,
            rules: {
            	curpassword: {
            		required: true
            	},
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
	        onkeyup: false
        });
    }
    
    function acctSettings() {
        var acctInfo = getAcctInfo();
        
        $.post('/HomeWatch/auth/account-settings.php', acctInfo)
        .done(onAcctSettingsDone)
        .fail(onAcctSettingsFail);
        
        return false;
    }
    
    function onAcctSettingsDone(response) {
        alert("Account settings updated successfully.");
    }

    function onAcctSettingsFail(response) {
        alert("Failed to update account settings: " +  (response.responseText ? response.responseText : response.statusText));
    }
    
    function getAcctInfo() {
        return {
            curpassword: $(acctForm).find('input[name=curpassword]').val(),
            password: $(acctForm).find('input[name=password]').val()
        };
    }
    
});
