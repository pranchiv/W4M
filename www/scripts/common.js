$(document).on('pagecreate', function() {
    // from login page
    $('#login_button').on('click', function(e) {
        e.preventDefault();

        $.post('../controllers/member.php?action=logIn', $('#login_form').serialize(), function(data) {
            if (data.error) {
                $('#login_error').html(data.errorMessage);
            } else {
                $('#login_error').html(data.errorMessage);
                $.mobile.changePage('/' + data.nextPage, { transition: 'flip' } );
            }
        }, 'json');
    });

    $('.logout').on('click', function(e) {
        e.preventDefault();

        $.post('../controllers/member.php?action=logOut', null, function(data) {
            window.location = '/';
        }, 'json');
    });
});

// the "data" parameter is the JSON data returned from Notification.send()
// it's expected to have a "recipients" array with specific properties
function ShowToastFromNotificationSend(data) {
    for (var i = 0; i < data.recipients.length; i++) {
        var name = data.recipients[i]["Username"];
        var address = data.recipients[i]["TextAddress"];

        $.toast({ 
            text: '<u>Text to ' + name + ' (' + address + ')</u><br>' + data.body,
            allowToastClose: true,
            hideAfter: false
        });    
    }
}
