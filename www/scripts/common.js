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

function BuildHtmlTableFromJson(data, columns) {
    var result = '';

    $.each(data, function(i, row) {
        result += '<tr>';

        $.each(columns, function(j, col) {
            var value = row[col];

            if (value == null) {
                value = '--';
            } else if (/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/.test(value)) {
                value = FormatDate(value);
            }

            var isCheckbox = col.match(/^\[checkbox=(.*)\]$/);

            if (isCheckbox) {
                var checkboxName = isCheckbox[1];
                result += '<td><input type="checkbox" name="selected" value="' + row[checkboxName] + '"></td>';
            } else {
                result += '<td>' + value + '</td>';
            }
        });

        result += '</tr>\r\n';
    });

    return result;
}

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

function FormatDate(dateString) {
    var d = new Date(dateString);
    var month = d.getMonth(); month++;
    var day = d.getDate();
    var year = d.getFullYear() - 2000;
    var hr = d.getHours();
    var min = d.getMinutes();
    var ampm = '';

    if (hr < 12) {
        if (hr == 0) { hr = 12; }
        ampm = 'am';
    } else {
        if (hr > 12) { hr -= 12; }
        ampm = 'pm';
    }

    min += '';
    if (min.length == 1) { min = '0' + min; }

    return month + '/' + day + '/' + year + ' ' + hr + ':' + min + ' ' + ampm;
}