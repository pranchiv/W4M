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
            ResetDonationForm();

            if (data.notifications) { ShowToastFromNotificationSend(data.notifications); }
            
            if (! LoadDonationData($('#donation_Pending'), data.data, 'Donor')) {
                $('#donation_Pending').html('No donations are pending.');
            }
        }
    }, 'json');
});

$(document).on('click', '#donation_ResetButton', function(e) {
    e.preventDefault();
    ResetDonationForm();
});
/////////////////////////////////

/////////////////////////////////
// DRIVER PAGE
$(document).on('pagecreate', '#driver_page', function() {
    RefreshPage('Driver');
    setInterval(function() { RefreshPage('Driver'); }, 60 * 1000); // refresh every minute
});
/////////////////////////////////

/////////////////////////////////
// BENEFICIARY PAGE
$(document).on('pagecreate', '#beneficiary_page', function() {
    RefreshPage('Beneficiary');
    setInterval(function() { RefreshPage('Beneficiary'); }, 60 * 1000); // refresh every minute
});

/////////////////////////////////
// DONATION HISTORY PAGE
$(document).on('pagecreate', '#donationHistory_page', function() {
    var $container = $('#donation_History');
    var role = $container.data('role');
    $container.html('<span class="loading">Loading ...</span>');

    $.get('/controllers/donation.php?action=getDonations', { Active: 0, Role: role }, function(data) {
        if (data.error) {
            $container.html(data.message);
        } else {
            if (! LoadDonationData($container, data.data, role)) {
                $container.html('You have no donation history.');
            }
        }
    }, 'json');
});

$(document).on('click', '.toggleFails', function() {
    var show = ($(this).attr('title') == 'Show Fails');
    var $fails = $('#donation_History .donationCard').not('.Received');
    var newClass = 'ui-btn-a';
    var oldClass = 'ui-btn-b';

    if (show) {
        newClass = 'ui-btn-b';
        oldClass = 'ui-btn-a';
        $fails.show();
    } else {
        $fails.hide();
    }

    $(this).addClass(newClass).removeClass(oldClass);
    $(this).attr('title', (show ? 'Hide Fails' : 'Show Fails'));
    $(this).attr('title', (show ? 'Hide Fails' : 'Show Fails'));
});
/////////////////////////////////

function RefreshPage(pagename) {
    switch (pagename) {
        case 'Driver':
            var $container = $('#driver_Scheduled');
            $('#driver_Scheduled_loading').show();
        
            $.get('/controllers/donation.php?action=getDonations', { Role: 'Driver', Active: 1 }, function(data) {
                $('#driver_Scheduled_loading').hide();

                if (data.error) {
                    $container.html(data.message);
                } else {
                    if (! LoadDonationData($container, data.data, 'Driver')) {
                        $container.html('You have no donations scheduled.');
                    }
                }
            }, 'json');
        
            var $container2 = $('#driver_Pending');
            $('#driver_Pending_loading').show();
        
            $.get('/controllers/donation.php?action=getDonations', { Status: 2 }, function(data) {
                $('#driver_Pending_loading').hide();

                if (data.error) {
                    $container2.html(data.message);
                } else {
                    if (! LoadDonationData($container2, data.data, 'Driver')) {
                        $container2.html('No donations are pending.');
                    }
                }
            }, 'json');
                
            break;

        case 'Beneficiary':
            $container = $('#beneficiary_Scheduled');
            $('#beneficiary_Scheduled_loading').show();
        
            $.get('/controllers/donation.php?action=getDonations', { Active: 1, Role: 'Beneficiary' }, function(data) {
                $('#beneficiary_Scheduled_loading').hide();

                if (data.error) {
                    $container.html(data.message);
                } else {
                    if (! LoadDonationData($container, data.data, 'Beneficiary')) {
                        $container.html('You have no donations scheduled.');
                    }
                }
            }, 'json');
        
            var $container2 = $('#beneficiary_Available');
            $('#beneficiary_Available_loading').show();
        
            $.get('/controllers/donation.php?action=getDonations', { Status: 1 }, function(data) {
                $('#beneficiary_Available_loading').hide();

                if (data.error) {
                    $container2.html(data.message);
                } else {
                    if (! LoadDonationData($container2, data.data, 'Beneficiary')) {
                        $container2.html('No donations are available.');
                    }
                }
            }, 'json');
    
            break;
    }
}

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
    var role = $container.data('role');
    var $card = $(this).parents('.donationCard');
    var action = $(this).data('action');
    var donationId = $card.data('id');
    var statusId = $card.find('.status').data('id');
    var beneficiaryId = $card.find('.beneficiary').data('id');
    var driverId = $card.find('.driver').data('id');

    $.get('/controllers/donation.php?action=updateStatus', { DonationId: donationId, Action: action, PreviousStatus: statusId, 
                                                             PreviousBeneficiaryId: beneficiaryId, PreviousDriverId: driverId }, function(data) {
        if (data.error) {
            $('#' + role.toLowerCase() + '_page .popup_refreshNeeded_message').html(data.message);
            $('#' + role.toLowerCase() + '_page .popup_refreshNeeded').popup('open');
        } else {
            if (data.notifications) { ShowToastFromNotificationSend(data.notifications); }

            if (! LoadDonationData($container, data.data, role)) { 
                $container.html('No donations.');
            }
        }
    }, 'json');
});

$(document).on('click', '#driver_page .popup_refreshNeeded_button', function(e) {
    $('#driver_page .popup_refreshNeeded').popup('close');
    RefreshPage('Driver');
});

$(document).on('click', '#beneficiary_page .popup_refreshNeeded_button', function(e) {
    $('#beneficiary_page .popup_refreshNeeded').popup('close');
    RefreshPage('Beneficiary');
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

            var cardClass = 'class="donationCard new ' + donation['Status'].replace(' ', '') + ' ' + role + '"';

            var cardTitle = '<div class="title">'
                            + '<div class="id">#' + donation['DonationID'] + '</div>'
                            + '<div class="status" data-id="' + donation['DonationStatusID'] + '">' + donation['Status'] + '</div>'
                            + '<a href="#" class="ui-shadow ui-corner-all ui-icon-bars ui-btn-icon-notext menuicon">Menu</a>'
                          + '</div>';

            var donorAddress = BuildCardAddress(role, donation['Donor'],       donation['Donor Address1'], donation['Donor Address2'], donation['Donor City'], donation['Donor State'], donation['Donor ZIP']);
            var beneAddress  = BuildCardAddress(role, donation['Beneficiary'], donation['Bene Address1'],  donation['Bene Address2'],  donation['Bene City'],  donation['Bene State'],  donation['Bene ZIP']);

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
                                + '<div class="company">' + donation['Donor'] + ' ' + donorAddress + '</div>\r\n'
                                + '<div class="label">Beneficiary</div>\r\n'
                                + '<div class="beneficiary company" data-id="' + (donation['BeneficiaryCompanyID'] || '0') + '">' + (donation['Beneficiary'] || '--') + ' ' + beneAddress + '</div>\r\n'
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

        // remove any cards that shouldn't be there anymore
        var $oldcards = $container.find('.donationCard').not('.new');

        if ($oldcards.length) {
            $oldcards.hide(500, function() { 
                $(this).remove();
                $container.find('.donationCard').removeClass('new');
            });
        } else {
            $container.find('.donationCard').removeClass('new');
        }
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
            if (status == 'Posted') { actions.push(['Claim', 'Accept']); }
            if (status == 'Claimed' || status == 'Scheduled') { actions.push(['Unclaim', 'Unaccept']); }
            if (status == 'Dropped Off') { actions.push(['Receive', 'Confirm Receipt']); }
            break;
        case 'Driver':
            if (status == 'Claimed') { actions.push(['Schedule', 'Schedule']); }
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
    var numTimes = 0;
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
            case 'Picked Up'    : timePickedUp = row; break;
            case 'Dropped Off'  : timeDroppedOff = row; break;
        }
    });

    // TODO: don't show status times that have been "undone"
    // for example, if it was claimed, then unclaimed, don't show the claimed time

    $.each([timePosted, timeClaimed, timeScheduled, timePickedUp, timeDroppedOff], function(i, row) {
        if (row) {
            numTimes++;
            var isToday = ((new Date(row['ModifyDate'])).getTime() > todayTicks);

            result += '<div>'
                        + '<div class="label">' + row['Status'] + '</div>'
                        + '<div class="detail">' + FormatDate(row['ModifyDate'], !isToday, true) + '</div>'
                    + '</div>\r\n'
        }
    });

    var altStyle = (numTimes > 3 ? ' style="flex-wrap: wrap;"' : '');
    result = '<div class="horizgroup"' + altStyle + '>\r\n' + result + '</div>\r\n';

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

function BuildCardAddress(role, companyName, address1, address2, city, state, zip) {
    var result = '';

    if ((role == 'Admin' || role == 'Driver') && companyName != null) {
        var querystring = companyName + ', ' + address1 + ' ' + address2 + ' ' + city + ', ' + state + ' ' + zip;

        result = '<div class="maplink">'
               + '<a href="https://www.google.com/maps/search/?api=1&query=' + encodeURI(querystring) + '" target="_blank">'
               + '<img src="../images/google-maps.png" style="height:24px;" /></a>'
               + '</div>\r\n';

    }

    return result;
}

function ResetDonationForm() {
    $('#donation_pickuptime').val('0').selectmenu('refresh', true);
    $('#donation_form input[type="checkbox"]').attr('checked', false).checkboxradio('refresh');
    $('#donation_boxes').val('');
    $('#donation_weight').val('');
}