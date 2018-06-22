$(document).on('pagecreate', '#donation_page', function() {
    $('#donation_Pending').html('<span style="font-size: 16px; font-style: italic;">Loading ...</span>');

    $.get('/controllers/donation.php?action=getDonations', { Role: 'Donor', Active: 1 }, function(data) {
        if (data.error) {
            $('#donation_Pending').html(data.message);
        } else {
            $('#donation_Pending').html(data.message);
            LoadCompanyDonationData(data.data, 'Donor');
        }
    }, 'json');
});

$(document).on('click', '#donation_AddModeButton', function(e) {
    $('#donation_today').html(FormatDate(null, true, false));
    $('#donation_form').show();
    $(this).hide();
});

$(document).on('click', '#donation_AddButton', function(e) {
    e.preventDefault();

    $.post('/controllers/donation.php?action=add', $('#donation_form').serialize(), function(data) {
        if (data.error) {
            $('#donation_AddError').html(data.message);
        } else {
            $('#donation_AddError').html(data.message);
            LoadCompanyDonationData(data.data, 'Donor');
        }
    }, 'json');
});

function LoadCompanyDonationData(data, role) {
    var empty = ($('#donation_Pending .donationCard').length == 0);

    if (data) {
        var donations = data[0];
        var donationTypes = data[1];
        var donationLogs = data[2];

        $.each(donations, function(i, donation) {
            var $existingCardCheck = $('#donation_Pending .donationCard[data-id=' + donation['DonationID'] + ']');
            var $existingCard = null;

            if (empty && i == 0) { $('#donation_Pending').empty(); }
            empty = false;
            if ($existingCardCheck.length) { $existingCard = $existingCardCheck[0]; }

            var cardClass = 'class="donationCard ' + donation['Status'] + ' ' + role + '"';

            var cardTitle = '<div class="title">'
                            + '<div class="id">#' + donation['DonationID'] + '</div>'
                            + '<div class="status">' + donation['Status'] + '</div>'
                            + '<a href="#" class="ui-shadow ui-corner-all ui-icon-bars ui-btn-icon-notext">Menu</a>'
                          + '</div>';

            var cardStats = '<div class="horizgroup">\r\n'
                            + '<div>'
                                + '<div class="label">Type</div>'
                                + '<div class="detail">' + BuildCardTypes(donation['DonationID'], donationTypes) + '</div>'
                            + '</div>\r\n'
                            + '<div>'
                                + '<div class="label">Boxes</div>'
                                + '<div class="detail">' + donation['BoxQuantity'] + '</div>'
                            + '</div>\r\n'
                            + '<div>'
                                + '<div class="label">Weight</div>'
                                + '<div class="detail">' + donation['Weight'] + '</div>'
                            + '</div>\r\n'
                          + '</div>';

            var card = '<div ' + cardClass + ' data-id="' + donation['DonationID'] + '">\r\n'
                            + cardTitle + '\r\n'
                            + '<div class="body">\r\n'
                                + '<div class="label">Donor</div>\r\n'
                                + '<div class="company">' + donation['Donor'] + '</div>\r\n'
                                + '<div class="label">Beneficiary</div>\r\n'
                                + '<div class="company">' + (donation['Beneficiary'] || '--') + '</div>\r\n'
                                + '<div class="label">Driver</div>\r\n'
                                + '<div class="driver">--</div>\r\n'
                                + cardStats + '\r\n'
                                + BuildCardTimes(donation['DonationID'], donationLogs) + '\r\n'
                            + '</div>\r\n';
                     + '</div>\r\n';

            if ($existingCard) {
                $existingCard.html(card);
            } else {
                $('#donation_Pending').append(card);
            }
        });
    }

    if (empty) {
        $('#donation_Pending').html('No donations are pending.');
    }
}

function BuildCardTimes(donationId, data) {
    var result = '';
    var timePosted = null;
    var timeClaimed = null;
    var timeScheduled = null;
    var timePickedUp = null;
    var timeDroppedOff = null;
    var today = FormatDate((new Date()).toString(), true, false); today = new Date(today);
    var todayTicks = today.getTime();

    var filteredData = $.grep(data, function(x) {
        return (x['DonationID'] == donationId);
    });

    // this will set each time to the LAST occurrence of the status
    // except that we want "Posted" to always be the original time
    $.each(filteredData, function(i, row) {
        switch (row['Status']) {
            case 'Posted'       : if (!timePosted) { timePosted = row; } break;
            case 'Claimed'      : timeClaimed = row; break;
            case 'Scheduled'    : timeScheduled = row; break;
            case 'PickedUp'     : timePickedUp = row; break;
            case 'DroppedOff'   : timeDroppedOff = row; break;
        }
    });

    $.each([timePosted, timeClaimed, timeScheduled, timePickedUp, timeDroppedOff], function(i, row) {
        if (row) {
            var isToday = ((new Date(row['ModifyDate'])).getTime() > todayTicks);

            result += '<div>'
                        + '<div class="label">' + row['Status'] + '</div>'
                        + '<div class="detail">' + FormatDate(row['ModifyDate'], !isToday, true) + '</div>'
                    + '</div>\r\n'
        }
    });

    result = '<div class="horizgroup">\r\n' + result + '</div>\r\n';

    return result;
}

function BuildCardTypes(donationId, data) {
    var result = '';
    var delim = '';

    var filteredData = $.grep(data, function(x) {
        return (x['DonationID'] == donationId);
    });

    $.each(filteredData, function(i, row) {
        result += delim + row['Name'];
        delim = ', ';
    });

    return result;
}