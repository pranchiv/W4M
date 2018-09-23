$(document).on('pagecreate', '[data-role=page]', function() {
    var page = $(this).attr('id');
});

$(document).on('pageshow', '[data-role=page]', function() {
    var page = $(this).attr('id');

    var mySwiper = new Swiper('#' + page + ' .swiper-container', {
        loop: true,
        spaceBetween: 0,
        speed: 1000,
        centeredSlides: true,
        autoplay: {
            delay: 3000,
            disableOnInteraction: true,
        },
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
    })
});

$(document).on('click', '#backtotop', function () {
    $('body,html').animate({ scrollTop: 0 }, 600);
});

$(window).on('scrollstop', function () {
    if ($(window).scrollTop() > 150) {
        $('#backtotop').addClass('visible');
    } else {
        $('#backtotop').removeClass('visible');
    }
});

$(document).on('pagecreate', '#login_page', function() {
  
});

$(document).on('click', '#login_button', function(e) {
    e.preventDefault();

    $.post('../controllers/member.php?action=logIn', $('#login_form').serialize(), function(data) {
        if (data.error) {
            $('#login_error').html(data.errorMessage);
        } else {
            $('#login_error').html('');

            $(':mobile-pagecontainer').pagecontainer('change', '/' + data.nextPage, { transition: 'flip' } );
        }
    }, 'json');
});

$(document).on('click', '#login_forgotlink', function(e) {
    $('#login_forgotform').show();

    $('#login_forgotform').validate({
        rules: {
            Email: { required: true, email: true, maxlength: 100 }
        },
        errorPlacement: function (error, element) {
            error.appendTo(element.parent().parent());
        },
        submitHandler: function (form) {
            // form is valid
            SendForgotPasswordEmail();
            return false; 
        }
    });  
    
    // make sure they notice the new form by scrolling down for them
    $('html, body').animate({ scrollTop: $(document).height()-$(window).height() }, 'slow');
});

$(document).on('click', '.logout', function(e) {
    e.preventDefault();

    $.post('../controllers/member.php?action=logOut', null, function(data) {
        window.location = '/';
    }, 'json');
});

function SendForgotPasswordEmail() {
    $('#login_forgotbutton').prop('disabled', true);
    $('#login_forgoterror').html('');

    $.post('/controllers/member.php?action=forgotPassword', $('#login_forgotform').serialize(), function(data) {
        if (data.error) {
            $('#login_forgoterror').html(data.errorMessage);
            $('#login_forgotbutton').prop('disabled', false);
        } else {
            $email = $('#login_forgotemail').val();

            $('#login_forgoterror').html('Follow the link in the email that has just been sent, which will work for only one hour.');
        }
    }, 'json');
}

function BuildHtmlTableFromJson(data, columns) {
    var result = '';

    $.each(data, function(i, row) {
        result += '<tr>';

        $.each(columns, function(j, col) {
            var value = row[col];

            if (value == null) {
                value = '--';
            } else if (/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/.test(value)) {
                value = FormatDate(value, true, true);
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
    if (data.recipients) {
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
}

function FormatPhone(phoneNumber) {
    return phoneNumber ? phoneNumber.replace(/(\d{3})(\d{3})/, '$1-$2-') : '--';
}

function FormatDate(dateString, includeDate, includeTime) {
    var result = '';
    var d, month, day, year, hr, min, ampm;
    
    if (dateString) {
        if (!dateString.includes(' ')) { dateString = '01-01-01 ' + dateString; }

        // Apply each element to the Date function
        var t = dateString.split(/[- :]/);
        var temp = new Date(t[0], t[1]-1, t[2], t[3], t[4], t[5]);
        d = new Date(temp);
    } else {
        d = new Date();
    }

    if (includeDate) {
        month = d.getMonth(); month++;
        day = d.getDate();
        year = d.getFullYear() - 2000;    
    }

    if (includeTime) {
        hr = d.getHours();
        min = d.getMinutes() + '';
        ampm = '';

        if (hr < 12) {
            if (hr == 0) { hr = 12; }
            ampm = 'am';
        } else {
            if (hr > 12) { hr -= 12; }
            ampm = 'pm';
        }

        if (min.length == 1) { min = '0' + min; }
    }

    if (includeDate) { result = month + '/' + day + '/' + year; }
    if (includeTime) {
        if (includeDate) { result += ' '; }
        result += hr + ':' + min + ' ' + ampm;
    }

    return result;
}

function GetDayOfWeek(dayNumber) {
    return ['PLACEHOLDER','Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'][dayNumber];
}