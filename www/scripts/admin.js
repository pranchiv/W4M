$(document).on('pagecreate', function() {
    var companyColumns = ['[checkbox]', 'CreateDate', 'CompanyID', 'Type', 'Name', 'Address', 'City', 'ZIP', 'Phone', 'MemberCount'];
    var $companyTablebody = $('#companyList_table tbody');
    var $companyTablefoot = $('#companyList_table tfoot');
    var companyLoadingRow = '<tr class="loading"><td colspan="' + companyColumns.length + '">Loading ...</td></tr>';
    $companyTablefoot.html(companyLoadingRow);

    $.get('/controllers/company.php?action=getCompanies', { status: 1 }, function(data) {
        if (data.error) {
            $companyTablefoot.find('td').html(data.errorMessage);
        } else if (data.data.length == 0) {
            $companyTablefoot.find('td').html('no companies found');
        } else {
            var dataRows = BuildHtmlTableFromJson(data.data, companyColumns);

            $companyTablefoot.hide();
            $companyTablebody.html(dataRows);
        }
    }, 'json');

    var memberColumns = ['[checkbox]', 'CreateDate', 'MemberID', 'MemberType', 'Name', 'Email', 'CellNumber', 'CompanyName', 'CompanyStatus'];
    var $memberTablebody = $('#memberList_table tbody');
    var $memberTablefoot = $('#memberList_table tfoot');
    var memberLoadingRow = '<tr class="loading"><td colspan="' + memberColumns.length + '">Loading ...</td></tr>';
    $memberTablefoot.html(memberLoadingRow);

    $.get('/controllers/member.php?action=getMembers', { status: 1 }, function(data) {
        if (data.error) {
            $memberTablefoot.find('td').html(data.errorMessage);
        } else if (data.data.length == 0) {
            $memberTablefoot.find('td').html('no members found');
        } else {
            var dataRows = BuildHtmlTableFromJson(data.data, memberColumns);

            $memberTablefoot.hide();
            $memberTablebody.html(dataRows);
        }
    }, 'json');
});