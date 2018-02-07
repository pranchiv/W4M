$(document).ready(function() {
    $('#register_registerButton').on('click', function(e) {
        e.preventDefault();
        var name = $('#register_companyName').val();

        $.post('controllers/company.php?action=register', $('#register_form').serialize(), function(data) {

            if (data.error) {
                $('#register_registerError').html(data.errorMessage);
            } else if (data.exists) {
                $('#register_registerError').html('Error: "' + name + '" already exists.');
            } else {
                $('#register_registerError').html('Company created (ID ' + data.company.CompanyID + ')');
            }

        }, 'json');
    });
});
