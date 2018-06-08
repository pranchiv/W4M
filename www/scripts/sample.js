$(document).ready(function() {
    $('#sample_notificationButton').on('click', function(e) {
        e.preventDefault();
        var name = $('#register_companyName').val();

        $.post('controllers/notification.php?action=send', $('#sample_form').serialize(), function(data) {
            if (data.error) {
                $('#register_registerError').html(data.errorMessage);
            } else if (data.recipients == null) {
                $('#register_registerError').html('Email sent');
            } else {
                ShowToastFromNotificationSend(data);
            }
        }, 'json');
        
        // if ($(this).data('env') == 'local') {
        //     $.toast({ 
        //         text: '<u>Text to Admins</u><br> New company registered: "' + name + '"',
        //         allowToastClose: true,
        //         hideAfter: false
        //     });
        // } else {
        //     $.post('controllers/company.php?action=testEmail', $('#sample_form').serialize(), function(data) {

        //         if (data.error) {
        //             $('#register_registerError').html(data.errorMessage);
        //         } else {
        //             $('#register_registerError').html('Email sent');
        //         }
    
        //     }, 'json');
        // }

        // $.post('controllers/company.php?action=register', $('#register_form').serialize(), function(data) {

        //     if (data.error) {
        //         $('#register_registerError').html(data.errorMessage);
        //     } else if (data.exists) {
        //         $('#register_registerError').html('Error: "' + name + '" already exists.');
        //     } else {
        //         $('#register_registerError').html('Company created (ID ' + data.company.CompanyID + ')');
        //     }

        // }, 'json');
    });

    $('#store_passwordButton').on('click', function(e) {
        e.preventDefault();

        $.post('controllers/company.php?action=testStorePassword', $('#sample_formPassword').serialize(), function(data) {
            $('#register_registerError').html(data.errorMessage);
        }, 'json');
    });
});
