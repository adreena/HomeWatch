require(['jquery', 'vendor/jquery.validate.min'],

function ($) {
    "use strict";

    // Roles
    var ROLE_DEV = '1';
    var ROLE_ADMIN = '2';
    var ROLE_MANAGER = '3';
    var ROLE_ENGINEER = '4';
    var ROLE_RESIDENT = '5';

    // User Restrictions
    var PASSWORD_LENGTH_MIN = 8;
    var USERNAME_LENGTH_MIN = 1;
    var USERNAME_LENGTH_MAX = 30;

    // IDs
    var REGISTER_FORM_ID = "#register-form";
    var RESIDENT_FORM_ID = "#resident-form";
    var REGISTER_BUTTON_ID = "#register-button";
    var REGISTER_ROLE_SELECT_ID = "#roles";

    // Forms
    var registerForm;
    var residentForm;
    var roleForm;

    var roleData;
    
    // Forms
    var loginForm;

    $(function () {
        initForms();
    });

    function initForms() {
        roleData = null;
        
        initValidator();
        
        initRegisterForm();
        initResidentForm();
        
        // Install the register button
        $(REGISTER_BUTTON_ID).click(function() {
            if (!$(registerForm).valid()) return false;
            $(registerForm).validate().settings.submitHandler();
            return false;
        });
    }

    function initValidator() {
        $.validator.addMethod("valueNotEquals", function(value, element, arg){
            return arg != value;
        }, "Value must not equal arg.");
    }

    // =================================================================================================
    // MAIN REGISTER FORM
    // =================================================================================================

    function initRegisterForm() {
        registerForm = $(REGISTER_FORM_ID)[0];
        registerForm.reset();
        
        $(registerForm).validate({
            submitHandler: registerUser,
            rules: {
	            username: {
		            required: true,
		            minlength: USERNAME_LENGTH_MIN,
		            maxlength: USERNAME_LENGTH_MAX
	            },
	            password: {
		            required: true,
		            minlength: PASSWORD_LENGTH_MIN
	            },
	            confpassword: {
		            required: true,
		            equalTo: "#password"
	            },
	            email: {
		            required: true,
		            email: true
	            },
	            role: {
	                valueNotEquals: "default"
	            }
	        },
	        messages: {
		        role: "Please select a role"
	        },
	        errorElement: "div",
            errorPlacement: function(error, element) {
                $(element).prev().before(error);
            },
	        onkeyup: false
        });
        
        initRoles();
    }

    function registerUser() {
        if (roleForm) {
            if (!$(roleForm).valid()) return false;
            roleData = $(roleForm).validate().settings.submitHandler();
        }
        
        var registrationData = {
            accountdata: getAccountData(),
            roledata: roleData
        };
        
        $.post('/HomeWatch/auth/register-user.php', registrationData)
        .done(onRegisterDone)
        .fail(onRegisterFail);
        
        return false;
    }

    function onRegisterDone(response) {
        alert("Successfully registered user '" + response.username + "'");
        window.location.reload();
    }

    function onRegisterFail(response) {
        var message = "Failed to register user";
        
        if (response.responseText) {
            var result = $.parseJSON(response.responseText);
            message += " '" + result.username + "':\n" + result.message;
        }
        
        alert(message);
    }

    function getAccountData() {    
        return {
            username: $(registerForm).find('input[name=username]').val(),
            password: $(registerForm).find('input[name=password]').val(),
            email: $(registerForm).find('input[name=email]').val(),
            role: $(registerForm).find('select[name=role]').val()        
        };
    }

    function initRoles() {
        var roleSelector = $(registerForm).find(REGISTER_ROLE_SELECT_ID);
        roleSelector.change(onRoleChanged);
        roleSelector.append("<option value='" + ROLE_RESIDENT + "'>Resident</option>");
        roleSelector.append("<option value='" + ROLE_ENGINEER + "'>Engineer</option>");
        roleSelector.append("<option value='" + ROLE_MANAGER + "'>Manager</option>");
        roleSelector.append("<option value='" + ROLE_ADMIN + "'>Admin</option>");
        roleSelector.append("<option value='" + ROLE_DEV + "'>Developer</option>");
    }

    function onRoleChanged() {
        // Hide the role form, if any
        if (roleForm)
            roleForm.style.display = 'none';
        
        // Determine which role form to show
        switch (this.value) {
            case ROLE_RESIDENT:
                roleForm = residentForm;
                break;
            default:
                roleForm = null;
                break;
        }
        
        // Show the role form, if any
        if (roleForm)
            roleForm.style.display = 'inline-block';
    }

    // =================================================================================================
    // RESIDENT FORM
    // =================================================================================================

    function initResidentForm() {
        residentForm = $(RESIDENT_FORM_ID)[0];
        residentForm.reset();

        $(residentForm).validate({
            submitHandler: registerResident,
            rules: {
	            name: {
		            required: true
	            },
	            roomnumber: {
		            required: true,
		            number: true
	            },
	        },
	        errorElement: "div",
            errorPlacement: function(error, element) {
                $(element).prev().before(error);
            },
	        onkeyup: false
        });
    }

    function registerResident() {
        roleData = getResidentData();
        return roleData;
    }

    function getResidentData() {
        return {
            name: $(residentForm).find('input[name=name]').val(),
            roomnumber: $(residentForm).find('input[name=roomnumber]').val(),
            location: $(residentForm).find('input[name=location]').val()        
        };
    }
});
