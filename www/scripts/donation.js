$(document).on('pagecreate', '#driver_page', function() {
    
});

$(document).on('click', '#donation_AddModeButton', function(e) {
    $('#donation_today').html(FormatDate(null, true, false));
    $('#donation_form').show();
    $(this).hide();
});

$(document).on('click', '#donation_AddButton', function(e) {
    e.preventDefault();

    $.post('../controllers/donation.php?action=add', $('#donation_form').serialize(), function(data) {
        if (data.error) {
            $('#donation_Error').html(data.message);
        } else {
            $('#donation_Error').html(data.message);
            LoadCompanyDonationData(data.data);
        }
    }, 'json');
});

function LoadCompanyDonationData(data) {
    
}