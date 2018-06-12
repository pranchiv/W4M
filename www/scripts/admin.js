$(document).on('pagecreate', function() {
    LoadCompanyList();
    LoadMemberList();

    $('#admin_companyList_approveButton').on('click', function() {
        UpdateSelectedCompanyStatuses(2);
    });

    $('#admin_companyList_denyButton').on('click', function() {
        UpdateSelectedCompanyStatuses(5);
    });    
});

function LoadCompanyList(message) {
    var companyColumns = ['[checkbox=CompanyID]', 'CreateDate', 'CompanyID', 'Type', 'Name', 'Address', 'City', 'ZIP', 'Phone', 'MemberCount'];
    var $companyTablebody = $('#companyList_table tbody');
    var $companyTablefoot = $('#companyList_table tfoot');
    var companyLoadingRow = '<tr class="loading"><td colspan="' + companyColumns.length + '">Loading ...</td></tr>';
    var companyMessageRow = '<tr class="message"><td colspan="' + companyColumns.length + '"></td></tr>';
    $companyTablefoot.html(companyLoadingRow);
    $companyTablefoot.append(companyMessageRow);

    $.get('/controllers/company.php?action=getCompanies', { status: 1 }, function(data) {
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

function LoadMemberList() {
    var memberColumns = ['[checkbox=MemberID]', 'CreateDate', 'MemberID', 'MemberType', 'Name', 'Email', 'CellNumber', 'CompanyName', 'CompanyStatus'];
    var $memberTablebody = $('#memberList_table tbody');
    var $memberTablefoot = $('#memberList_table tfoot');
    var memberLoadingRow = '<tr class="loading"><td colspan="' + memberColumns.length + '">Loading ...</td></tr>';
    $memberTablefoot.html(memberLoadingRow);

    $.get('/controllers/member.php?action=getMembers', { status: 1 }, function(data) {
        if (data.error) {
            $memberTablefoot.find('td').html(data.errorMessage);
        } else if (data.data.length == 0) {
            $memberTablefoot.find('td').html('no matching members found');
        } else {
            var dataRows = BuildHtmlTableFromJson(data.data, memberColumns);

            $memberTablefoot.hide();
            $memberTablebody.html(dataRows);
            $('#memberList_table').table('refresh');
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
            LoadCompanyList(data.data[0]['count'] + ' companies updated');
            LoadMemberList();
        }
    }, 'json');    
}