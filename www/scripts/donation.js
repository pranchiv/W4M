/////////////////////////////////
// ADMIN PAGE
$(document).on('pagecreate', '#admin_page', function() {
    var $container = $('#admin_activeDonations');
    $container.html('<span class="loading">Loading ...</span>');

    $.get('/controllers/donation.php?action=getDonations', { Role: 'Admin', Active: 1 }, function(data) {
        if (data.error) {
            $container.html(data.message);
        } else {
            // sort by status, then date posted
            data.data[0].sort(function(a, b) {
                if (a['DonationStatusID'] == b['DonationStatusID']) {
                    return (a['CreateDate'] < a['CreateDate'] ? -1 : 1);
                } else {
                    return (a['DonationStatusID'] - b['DonationStatusID']);
                }
            });

            if (! LoadDonationData($container, data.data, 'Admin')) {
                $container.html('No active donations.');
            }
        }
    }, 'json');
});
/////////////////////////////////

/////////////////////////////////
// DONOR PAGE
$(document).on('pagecreate', '#donation_page', function() {
    var $container = $('#donation_Pending');
    $container.html('<span class="loading">Loading ...</span>');

    $.get('/controllers/donation.php?action=getDonations', { Role: 'Donor', Active: 1 }, function(data) {
        if (data.error) {
            $container.html(data.message);
        } else {
            if (! LoadDonationData($container, data.data, 'Donor')) {
                $container.html('No donations are pending.');
            }
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

            if (! LoadDonationData($('#donation_Pending'), data.data, 'Donor')) {
                $('#donation_Pending').html('No donations are pending.');
            }
        }
    }, 'json');
});
/////////////////////////////////

/////////////////////////////////
// DRIVER PAGE
$(document).on('pagecreate', '#driver_page', function() {
    var $container = $('#driver_Scheduled');
    $container.html('<span class="loading">Loading ...</span>');

    $.get('/controllers/donation.php?action=getDonations', { Role: 'Driver', Active: 1 }, function(data) {
        if (data.error) {
            $container.html(data.message);
        } else {
            if (! LoadDonationData($container, data.data, 'Driver')) {
                $container.html('You have no donations scheduled.');
            }
        }
    }, 'json');

    var $container2 = $('#driver_Pending');
    $container2.html('<span class="loading">Loading ...</span>');

    $.get('/controllers/donation.php?action=getDonations', { Status: 2 }, function(data) {
        if (data.error) {
            $container2.html(data.message);
        } else {
            if (! LoadDonationData($container2, data.data, 'Driver')) {
                $container2.html('No donations are pending.');
            }
        }
    }, 'json');
});
/////////////////////////////////

/////////////////////////////////
// BENEFICIARY PAGE
$(document).on('pagecreate', '#beneficiary_page', function() {
    $container = $('#beneficiary_Scheduled');
    $container.html('<span class="loading">Loading ...</span>');

    $.get('/controllers/donation.php?action=getDonations', { Active: 1, Role: 'Beneficiary' }, function(data) {
        if (data.error) {
            $container.html(data.message);
        } else {
            if (! LoadDonationData($container, data.data, 'Beneficiary')) {
                $container.html('You have no donations scheduled.');
            }
        }
    }, 'json');

    var $container2 = $('#beneficiary_Available');
    $container2.html('<span class="loading">Loading ...</span>');

    $.get('/controllers/donation.php?action=getDonations', { Status: 1 }, function(data) {
        if (data.error) {
            $container2.html(data.message);
        } else {
            if (! LoadDonationData($container2, data.data, 'Beneficiary')) {
                $container2.html('No donations are available.');
            }
        }
    }, 'json');
});
/////////////////////////////////

$(document).on('click', '.donationCard', function(e) {
    if (!$(e.target).hasClass('action')) {
        var $menu = $(this).find('.menu');

        if ($menu.is(':visible')) {
            $menu.slideUp();
        } else {
            $menu.slideDown();
        }
    }
});

$(document).on('click', '.action', function(e) {
    var $container = $(this).parents('.donationCardContainer');
    var $card = $(this).parents('.donationCard');
    var action = $(this).data('action');
    var donationId = $card.data('id');
    var statusId = $card.find('.status').data('id');
    var beneficiaryId = $card.find('.beneficiary').data('id');
    var driverId = $card.find('.driver').data('id');

    $.get('/controllers/donation.php?action=updateStatus', { DonationId: donationId, Action: action, PreviousStatus: statusId, 
                                                             PreviousBeneficiaryId: beneficiaryId, PreviousDriverId: driverId }, function(data) {
        if (data.error) {
            $container.html(data.message);
        } else {
            var role = $container.data('role');

            if (! LoadDonationData($container, data.data, role)) {
                $container.html('No donations.');
            }
        }
    }, 'json');
});

function LoadDonationData($container, data, role) {
    var empty = ($container.find('.donationCard').length == 0);

    if (data) {
        var donations = data[0];
        var donationTypes = data[1];
        var donationLogs = data[2];

        $.each(donations, function(i, donation) {
            var $existingCardCheck = $container.find('.donationCard[data-id=' + donation['DonationID'] + ']');
            var $existingCard = null;

            if (i == 0) {
                if (empty) { $container.empty(); } // remove any "loading" or other messages
                empty = false; // for next items, don't consider it empty anymore, since we're starting to load it up
            }

            if ($existingCardCheck.length) { $existingCard = $existingCardCheck; }

            var cardClass = 'class="donationCard ' + donation['Status'].replace(' ', '') + ' ' + role + '"';

            var cardTitle = '<div class="title">'
                            + '<div class="id">#' + donation['DonationID'] + '</div>'
                            + '<div class="status" data-id="' + donation['DonationStatusID'] + '">' + donation['Status'] + '</div>'
                            + '<a href="#" class="ui-shadow ui-corner-all ui-icon-bars ui-btn-icon-notext menuicon">Menu</a>'
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
                            + BuildCardMenu(role, donation['Status']) + '\r\n'
                            + '<div class="body">\r\n'
                                + '<div class="label">Donor</div>\r\n'
                                + '<div class="company">' + donation['Donor'] + '</div>\r\n'
                                + '<div class="label">Beneficiary</div>\r\n'
                                + '<div class="beneficiary company" data-id="' + (donation['BeneficiaryCompanyID'] || '0') + '">' + (donation['Beneficiary'] || '--') + '</div>\r\n'
                                + '<div class="label">Driver</div>\r\n'
                                + '<div class="driver" data-id="' + (donation['DriverMemberID'] || '0') + '">' + (donation['Driver'] || '--') + '</div>\r\n'
                                + cardStats + '\r\n'
                                + BuildCardTimes(donation['DonationID'], donationLogs) + '\r\n'
                            + '</div>\r\n';
                     + '</div>\r\n';

            if ($existingCard) {
                $existingCard.replaceWith(card);
            } else {
                $container.append(card);
            }
        });
    }

    if (!empty) { $container.trigger('create'); }

    return (!empty);
}

function BuildCardMenu(role, status) {
    var result = '';
    var actions = [];

    switch (role) {
        case 'Donor':
            if (status == 'Posted' || status == 'Scheduled') { actions.push(['Cancel', 'Cancel']); }
            break;
        case 'Beneficiary':
            if (status == 'Posted') { actions.push(['Claim', 'Claim']); }
            if (status == 'Claimed' || status == 'Scheduled') { actions.push(['Unclaim', 'Unclaim']); }
            if (status == 'Dropped Off') { actions.push(['Receive', 'Confirm Receipt']); }
            break;
        case 'Driver':
            if (status == 'Claimed') { actions.push(['Schedule', 'Pick Up']); }
            if (status == 'Scheduled') {
                actions.push(['Pick Up', 'Confirm Pickup']);
                actions.push(['Unschedule', 'Cancel Pickup']);
                actions.push(['Lost', 'Mark as Lost']);
                actions.push(['Damaged', 'Mark as Damaged']);
            }
            if (status == 'Picked Up') {
                actions.push(['Drop Off', 'Mark as Delivered']);
                actions.push(['Lost', 'Mark as Lost']);
                actions.push(['Damaged', 'Mark as Damaged']);
            }
            break;
    }

    $.each(actions, function(i, settings) {
        result += '<input type="button" class="action" data-action="' + settings[0] + '" data-inline="true" data-theme="b" data-icon="check" value="' + settings[1] + '">';
    });

    if (result != '') { result = '<div class="menu">' + result + '</div>'; }
    return result;
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

    // TODO: don't show status times that have been "undone"
    // for example, if it was claimed, then unclaimed, don't show the claimed time

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