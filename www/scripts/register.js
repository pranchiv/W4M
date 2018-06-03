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
                if (data.notifications) { ShowToastFromNotificationSend(data.notifications); }
            }

        }, 'json');
    });
});
