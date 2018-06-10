$(document).on('pagecreate', function() {
    $('#register_registerButton').on('click', function(e) {
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
                var nextPage = '';

                if (regType == 'Driver') {
                    nextPage = 'registerMember';
                } else {
                    nextPage = 'registerCompany';
                }

                $.mobile.changePage('/pages/' + nextPage + '.php', { transition: 'flip' } );
            }, 'json');
        }
    });

    $('#registerCompany_registerButton').on('click', function(e) {
        e.preventDefault();
        var name = $('#registerCompany_Name').val();

        $.post('../controllers/company.php?action=register', $('#registerCompany_form').serialize(), function(data) {

            if (data.error) {
                $('#registerCompany_registerError').html(data.errorMessage);
            } else if (data.exists) {
                $('#registerCompany_registerError').html('Error: "' + name + '" already exists.');
            } else {
                $('#registerCompany_registerError').html('Company created (ID ' + data.company.CompanyID + ')');
                $.mobile.changePage('/pages/registerMember.php', { transition: 'flip' } );
                if (data.notifications) { ShowToastFromNotificationSend(data.notifications); }
            }

        }, 'json');
    });

    $('#registerMember_registerButton').on('click', function(e) {
        e.preventDefault();
        var name = $('#registerMember_FirstName').val() + ' ' + $('#registerMember_LastName').val();

        $.post('../controllers/member.php?action=register', $('#registerMember_form').serialize(), function(data) {

            if (data.error) {
                $('#registerMember_registerError').html(data.errorMessage);
            } else if (data.exists) {
                $error = 'Error: ';
                switch (data.existsType) {
                    case '1': $error += 'username already exists.'; break;
                    case '2': $error += 'email already exists.'; break;
                    case '3': $error += name + ' is already registered with this company.'; break;
                    default:  $error += 'member already exists.'; break;
                }
                $('#registerMember_registerError').html($error);
            } else {
                $('#registerMember_registerError').html('Member created (ID ' + data.member.MemberID + ')');
                if (data.notifications) { ShowToastFromNotificationSend(data.notifications); }
                $.mobile.changePage('/' + data.nextPage, { transition: 'flip' } );
            }

        }, 'json');
    });
});
