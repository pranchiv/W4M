$(document).on('pagecreate', '#donation_page', function() {
    $('#donation_Pending').html('<span style="font-size: 16px; font-style: italic;">Loading ...</span>');

    $.get('/controllers/donation.php?action=getDonations', { Role: 'Donor', Active: 1 }, function(data) {
        if (data.error) {
            $('#donation_Pending').html(data.message);
        } else {
            $('#donation_Pending').html(data.message);
            LoadCompanyDonationData(data.data);
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
            LoadCompanyDonationData(data.data);
        }
    }, 'json');
});

function LoadCompanyDonationData(data) {
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

            var card = '<div class="donationCard ' + donation['Status'] + '" data-id="' + donation['DonationID'] + '">\r\n'
                            + '<div class="status">' + donation['Status'] + '</div>\r\n'
                            + '<div class="name">Donor: ' + donation['Donor'] + '</div>\r\n'
                            + '<div class="name">Beneficiary: ' + (donation['Beneficiary'] || '--') + '</div>\r\n'
                            + '<div class="time">' + FormatDate(donation['Expiration'], false, true) + '</div>\r\n'
                            + '<div class="detail">Boxes: ' + donation['BoxQuantity'] + '</div>\r\n'
                            + '<div class="detail">Weight: ' + donation['Weight'] + '</div>\r\n'
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