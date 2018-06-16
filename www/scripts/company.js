$(document).on('pagecreate', function() {
    $('#companySettingsSchedule_AddModeButton').on('click', function(e) {
        $('#companySettingsSchedule_form').show();
        $(this).hide();
    });

    $('#companySettingsSchedule_AddButton').on('click', function(e) {
        e.preventDefault();

        $.post('../controllers/company.php?action=addSchedule', $('#companySettingsSchedule_form').serialize(), function(data) {
            if (data.error) {
                $('#companySettingsSchedule_Error').html(data.message);
            } else {
                $('#companySettingsSchedule_Error').html(data.message);
                LoadCompanyScheduleFromData(data.data);
            }
        }, 'json');
    });

    $('#companySettingsSchedule_schedule').on('click', '.timeslot .remove', function(e) {
        var schedId = $(this).parent().data('id');

        $.post('../controllers/company.php?action=removeSchedule', { companyScheduleId: schedId }, function(data) {
            if (data.error) {
                $('#companySettingsSchedule_Error').html(data.message);
            } else {
                $('#companySettingsSchedule_Error').html(data.message);
                LoadCompanyScheduleFromData(data.data);
            }
        }, 'json');
    });

    $('#companySettingsDonationTypes_button').on('click', function(e) {
        e.preventDefault();

        $.post('../controllers/company.php?action=updateDonationTypes', $('#companySettingsDonationTypes_form').serialize(), function(data) {
            if (data.error) {
                $('#companySettingsDonationTypes_Error').html(data.message);
            } else {
                $('#companySettingsDonationTypes_Error').html(data.message);
            }
        }, 'json');
    });
});

function LoadCompanySettings() {
    LoadCompanySchedule();
}

function LoadCompanySchedule() {
    $.get('../controllers/company.php?action=loadSchedule', null, function(data) {
        if (data.error) {
            $('#companySettingsSchedule_Error').html(data.message);
        } else {
            $('#companySettingsSchedule_Error').html(data.message);
            LoadCompanyScheduleFromData(data.data);
        }
    }, 'json');
}

function LoadCompanyScheduleFromData(data) {
    // clear them all first
    $('[id^=companySettingsSchedule_Day]').empty();

    // populate grid with each timeslot found
    $.each(data, function(i, schedule) {
        var startTime = FormatDate(schedule['StartTime'], false, true);
        var endTime = FormatDate(schedule['EndTime'], false, true);
        var timeslot = '<div class="timeslot" data-id="' + schedule['CompanyScheduleID'] + '">'
                        + '<div class="times">' + startTime + ' - ' + endTime + '</div><div class="remove" title="remove">X</div></div>\r\n';

        $('#companySettingsSchedule_Day' + schedule['DayOfWeek']).append(timeslot);
    });

    // put placeholder in any empty ones
    for (var i = 1; i <= 7; i++) {
        if ($('#companySettingsSchedule_Day' + i).is(':empty')) {
            $('#companySettingsSchedule_Day' + i).html('-- none --');
        }
    }
}
