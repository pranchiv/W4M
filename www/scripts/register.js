$(document).on('pagecreate', '#register_page', function() {

});

$(document).on('pagecreate', '#registerCompany_page', function() {
    $('#registerCompany_form').validate({
        rules: {
            CompanyName: { required: true, maxlength: 80 },
            Address1: { required: true, maxlength: 40 },
            Address2: { maxlength: 40 },
            City: { required: true, letterswithbasicpunc: true, maxlength: 40 },
            Phone: { required: true, phoneUS: true }
        },
        errorPlacement: function (error, element) {
            error.appendTo(element.parent().parent());
        },
        submitHandler: function (form) {
            // form is valid
            RegisterCompany();
            return false; 
        }
    });
});

$(document).on('pagecreate', '#registerMember_page', function() {
    $('#registerMember_form').validate({
        rules: {
            FirstName: { required: true, letterswithbasicpunc: true, maxlength: 40 },
            LastName: { required: true, letterswithbasicpunc: true, maxlength: 40 },
            Email: { required: true, email: true, maxlength: 100 },
            CellNumber: { required: true, phoneUS: true },
            CellCarrier: { required: true },
            Username: { required: true, minlength: 5, maxlength: 30 },
            Password: { required: true, minlength: 8, maxlength: 64 },
            ConfirmPassword: { required: true, equalTo: '#registerMember_Password' }
        },
        errorPlacement: function (error, element) {
            error.appendTo(element.parent().parent());
        },
        submitHandler: function (form) {
            // form is valid
            RegisterMember();
            return false; 
        }
    });

    $.validator.addMethod('selected', function (value) {
        return (value != '0');
    }, 'This field is required.');
});

$(document).on('pagecreate', '#accountSettings_page', function() {
    $.post('/controllers/member.php?action=getAccount', $('#password_form').serialize(), function(data) {
        if (data.error) {
            $('#accountSettings_updateError').html(data.errorMessage);
            $('#accountSettings_updateButton').prop('disabled', true);
        } else {
            // populate form with current account info
            $('#accountSettings_Username').val(data.member['Username']);
            $('#accountSettings_FirstName').val(data.member['FirstName']);
            $('#accountSettings_LastName').val(data.member['LastName']);
            $('#accountSettings_Email').val(data.member['Email']);
            $('#accountSettings_CellNumber').val(FormatPhone(data.member['CellNumber']));
            $('#accountSettings_CellCarrier').val(data.member['CellCarrierID']); $('#accountSettings_CellCarrier').selectmenu('refresh', true);
            $('#accountSettings_updateButton').prop('disabled', false);
        }
    }, 'json');

    $('#accountSettings_form').validate({
        rules: {
            FirstName: { required: true, letterswithbasicpunc: true, maxlength: 40 },
            LastName: { required: true, letterswithbasicpunc: true, maxlength: 40 },
            Email: { required: true, email: true, maxlength: 100 },
            CellNumber: { required: true, phoneUS: true },
            CellCarrier: { required: true },
            Username: { required: true, minlength: 5, maxlength: 30 }
        },
        errorPlacement: function (error, element) {
            error.appendTo(element.parent().parent());
        },
        submitHandler: function (form) {
            // form is valid
            UpdateAccount();
            return false; 
        }
    });

    $.validator.addMethod('selected', function (value) {
        return (value != '0');
    }, 'This field is required.');
});

$(document).on('pagecreate', '#password_page', function() {
    $('#password_form').validate({
        rules: {
            OldPassword: { required: true },
            Password: { required: true, minlength: 8, maxlength: 64 },
            ConfirmPassword: { required: true, equalTo: '#password_Password' }
        },
        errorPlacement: function (error, element) {
            error.appendTo(element.parent().parent());
        },
        submitHandler: function (form) {
            // form is valid
            UpdatePassword();
            return false; 
        }
    });

    $.validator.addMethod('selected', function (value) {
        return (value != '0');
    }, 'This field is required.');
});

$(document).on('click', '#register_registerButton', function(e) {
    e.preventDefault();
    var validZips = ['19067', '19030', '19047', '19053', '19054', '19055', '19056', '19057', '18940'];
    var zip = $('#register_Zip').val();
    var regType = $('#register_RegistrationType').val();
    
    if (regType == '' || zip == '') {
        $('#register_registerError').html('Both fields are required.');
    } else if (!validZips.includes(zip)) {
        $('#register_registerError').html('ZIP ' + zip + ' is out of our current range.');
    } else {
        $.post('../controllers/company.php?action=startRegistration', $('#register_form').serialize(), function(data) {
            if (data.error) {
                // don't proceed
                $('#register_registerError').html('Invalid');
            } else {
                var nextPage = '';

                if (regType == 'Driver') {
                    nextPage = 'registerMember';
                } else {
                    nextPage = 'registerCompany';
                }

                $(':mobile-pagecontainer').pagecontainer('change', '/pages/' + nextPage + '.php', { transition: 'flip' } );
            }
        }, 'json');
    }
});

function RegisterCompany() {
    $.post('/controllers/company.php?action=register', $('#registerCompany_form').serialize(), function(data) {

        if (data.error) {
            $('#registerCompany_registerError').html(data.errorMessage);
        } else if (data.exists) {
            $('#registerCompany_registerError').html('Error: "' + name + '" already exists.');
        } else {
            $('#registerCompany_registerError').html('');
            if (data.notifications) { ShowToastFromNotificationSend(data.notifications); }
            $(':mobile-pagecontainer').pagecontainer('change', '/pages/registerMember.php', { transition: 'flip' } );
        }

    }, 'json');
}

function RegisterMember() {
    $('#registerMember_registerButton').prop('disabled', true);
    $('#registerMember_registerError').html('');
    var name = $('#registerMember_FirstName').val() + ' ' + $('#registerMember_LastName').val();

    $.post('/controllers/member.php?action=register', $('#registerMember_form').serialize(), function(data) {
        if (data.error) {
            $('#registerMember_registerError').html(data.errorMessage);
            $('#registerMember_registerButton').prop('disabled', false);
        } else if (data.exists) {
            $error = 'Error: ';
            switch (data.existsType) {
                case '1': $error += 'username already exists.'; break;
                case '2': $error += 'email already exists.'; break;
                case '3': $error += name + ' is already registered with this company.'; break;
                default:  $error += 'member already exists.'; break;
            }
            $('#registerMember_registerError').html($error);
            $('#registerMember_registerButton').prop('disabled', false);
        } else {
            if (data.notifications) { ShowToastFromNotificationSend(data.notifications); }
            $(':mobile-pagecontainer').pagecontainer('change', '/' + data.nextPage, { transition: 'flip' } );
        }
    }, 'json');
}

function UpdateAccount() {
    var error = '';
    $('#accountSettings_updateButton').prop('disabled', true);
    $('#accountSettings_updateError').html('');

    $.post('/controllers/member.php?action=updateAccount', $('#accountSettings_form').serialize(), function(data) {
        if (data.error) {
            error = data.errorMessage;
        } else if (data.exists) {
            error = 'Error: ';
            switch (data.existsType) {
                case '1': error += 'username already exists.'; break;
                case '2': error += 'email already exists.'; break;
                case '3': error += name + ' is already registered with this company.'; break;
                default:  error += 'member already exists.'; break;
            }
        } else {
            error = 'your account has been updated';
        }

        $('#accountSettings_updateError').html(error);
        $('#accountSettings_updateButton').prop('disabled', false);
    }, 'json');
}

function UpdatePassword() {
    $('#password_updateButton').prop('disabled', true);
    $('#password_updateError').html('');

    $.post('/controllers/member.php?action=updatePassword', $('#password_form').serialize(), function(data) {
        if (data.error) {
            $('#password_updateError').html(data.errorMessage);
        } else {
            $(':mobile-pagecontainer').pagecontainer('change', '/pages/accountSettings.php', { transition: 'flip', reloadPage: 'true' } );
        }

        $('#password_updateButton').prop('disabled', false);
    }, 'json');
}