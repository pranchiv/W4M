$(document).on('pagecreate', '#admin_page', function() {
    LoadProspectiveCompanyList();
    LoadProspectiveMemberList();
    LoadActiveCompanyList();
});

$(document).on('click', '#admin_companyList_approveButton', function() {
    UpdateSelectedCompanyStatuses(2);
});

$(document).on('click', '#admin_companyList_denyButton', function() {
    UpdateSelectedCompanyStatuses(5);
});

$(document).on('click', '#admin_memberList_approveButton', function() {
    UpdateSelectedMemberStatuses(2);
});

$(document).on('click', '#admin_memberList_denyButton', function() {
    UpdateSelectedMemberStatuses(4);
});

function LoadProspectiveCompanyList(message) {
    var companyColumns = ['[checkbox=CompanyID]', 'CreateDate', 'CompanyID', 'Type', 'Name', 'Address', 'City', 'ZIP', 'Phone', 'MemberCount'];
    var $companyTablebody = $('#companyList_table tbody');
    var $companyTablefoot = $('#companyList_table tfoot');
    var companyLoadingRow = '<tr class="loading"><td colspan="' + companyColumns.length + '">Loading ...</td></tr>';
    var companyMessageRow = '<tr class="message"><td colspan="' + companyColumns.length + '"></td></tr>';
    $companyTablefoot.html(companyLoadingRow);
    $companyTablefoot.append(companyMessageRow);

    $.get('/controllers/company.php?action=getCompanies', { status: 1, active: null }, function(data) {
        $companyTablefoot.find('.loading').hide();

        if (data.error) {
            message = (message ? message + '<br/>': '') + data.message;
        } else if (data.data.length == 0) {
            if (!message) { message = 'no matching companies found'; }
        } else {
            message = (message ? message + '<br/>': '') + data.data.length + ' matching companies found';
            var dataRows = BuildHtmlTableFromJson(data.data, companyColumns);
            $companyTablebody.html(dataRows);
            $('#companyList_table').table('refresh');
        }

        $companyTablefoot.find('.message td').html(message);
    }, 'json');
}

function LoadProspectiveMemberList(message) {
    var memberColumns = ['[checkbox=MemberID]', 'CreateDate', 'MemberID', 'MemberType', 'Name', 'Email', 'CellNumber', 'CompanyName', 'CompanyStatus'];
    var $memberTablebody = $('#memberList_table tbody');
    var $memberTablefoot = $('#memberList_table tfoot');
    var memberLoadingRow = '<tr class="loading"><td colspan="' + memberColumns.length + '">Loading ...</td></tr>';
    var memberMessageRow = '<tr class="message"><td colspan="' + memberColumns.length + '"></td></tr>';
    $memberTablefoot.html(memberLoadingRow);
    $memberTablefoot.append(memberMessageRow);

    $.get('/controllers/member.php?action=getMembers', { status: 1 }, function(data) {
        $memberTablefoot.find('.loading').hide();

        if (data.error) {
            message = (message ? message + '<br/>': '') + data.message;
        } else if (data.data.length == 0) {
            if (!message) { message = 'no matching members found'; }
        } else {
            message = (message ? message + '<br/>': '') + data.data.length + ' matching members found';
            var dataRows = BuildHtmlTableFromJson(data.data, memberColumns);
            $memberTablebody.html(dataRows);
            $('#memberList_table').table('refresh');
        }

        $memberTablefoot.find('.message td').html(message);
    }, 'json');
}

function LoadActiveCompanyList() {
    $.get('/controllers/company.php?action=getCompanies', { status: null, active: 1 }, function(data) {
        if (!data.error) {
            $('#admin_activeCompanies_Donors').empty();
            $('#admin_activeCompanies_Beneficiaries').empty();

            $.each(data.data, function(i, company) {
                var address = (company['Address1'] || '') + ' ' 
                            + (company['Address2'] || '') + ' ' 
                            + (company['City'] || '')
                            + (company['ZIP'] ? ' (' + company['ZIP'] + ')' : '');

                var card = '<div class="companyCard ' + company['Status'] + '">\r\n'
                                + '<div class="name">' + company['Name'] + '</div>\r\n'
                                + '<div class="address">' + address + '</div>\r\n'
                                + '<div class="phone">' + (company['Phone'] || '') + '</div>\r\n'
                                + '<div class="memberCountContainer">MEMBERS <div class="memberCount">' + company['MemberCount'] + '</div></div>\r\n'
                                + '<div class="donationCountContainer">DONATIONS <div class="donationCount">' + company['DonationCount'] + '</div></div>\r\n'
                         + '</div>\r\n';

                if (company['CompanyTypeID'] == 1) {
                    $('#admin_activeCompanies_Donors').append(card);
                } else {
                    $('#admin_activeCompanies_Beneficiaries').append(card);
                }
            });
        }
    }, 'json');
}

function UpdateSelectedCompanyStatuses(status) {
    var selectedArray = [];
    $('#admin_companyList_form input[type=checkbox]').each( function() {
        if( $(this).is(':checked') ) {
            selectedArray.push( $(this).val() );
        }
    });
    var selected = selectedArray.join(',');

    $.post('/controllers/company.php?action=updateStatus', { status: status, selected: selected }, function(data) {
        if (data.error) {
            $('#companyList_table .message td').html('<span class="error">' + data.message + '</span>');
        } else {
            LoadProspectiveCompanyList(data.data[0]['count'] + ' companies updated');
            LoadProspectiveMemberList();
            LoadActiveCompanyList();
        }
    }, 'json');
}

function UpdateSelectedMemberStatuses(status) {
    var selectedArray = [];
    $('#admin_memberList_form input[type=checkbox]').each( function() {
        if( $(this).is(':checked') ) {
            selectedArray.push( $(this).val() );
        }
    });
    var selected = selectedArray.join(',');

    $.post('/controllers/member.php?action=updateStatus', { status: status, selected: selected }, function(data) {
        if (data.error) {
            $('#memberList_table .message td').html('<span class="error">' + data.message + '</span>');
        } else {
            LoadProspectiveMemberList(data.data[0]['count'] + ' members updated');
        }
    }, 'json');
}
