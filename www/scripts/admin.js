$(document).on('pagecreate', '#admin_page', function() {
    LoadProspectiveCompanyList();
    LoadMemberList(1);
    LoadMemberList(2);
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

$(document).on('click', '.companyCard .settingsicon', function(e) {
    var $settings = $(this).parent().find('.settings');

    if ($settings.is(':visible')) {
        $settings.slideUp();
    } else {
        $settings.slideDown();
    }
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
        } else {
            var companies = data.data[0];

            if (companies.length == 0) {
                if (!message) { message = 'no matching companies found'; }
            } else {
                message = (message ? message + '<br/>': '') + companies.length + ' matching companies found';
                var dataRows = BuildHtmlTableFromJson(companies, companyColumns);
                $companyTablebody.html(dataRows);
                $('#companyList_table').table('refresh');
            }            
        }

        $companyTablefoot.find('.message td').html(message);
    }, 'json');
}

function LoadMemberList(status, message) {
    var memberColumns = null;
    var $memberTable = null;

    if (status == 1) {
        memberColumns = ['[checkbox=MemberID]', 'CreateDate', 'MemberID', 'MemberType', 'Name', 'Email', 'CellNumber', 'CompanyName', 'CompanyStatus'];
        $memberTable = $('#memberListProspective_table');
    } else {
        memberColumns = ['CreateDate', 'MemberID', 'MemberType', 'Name', 'Username', 'Email', 'CellNumber', 'CompanyName', 'CompanyStatus'];
        $memberTable = $('#memberListActive_table');
    }

    var $memberTablebody = $memberTable.find('tbody');
    var $memberTablefoot = $memberTable.find('tfoot');
    var memberLoadingRow = '<tr class="loading"><td colspan="' + memberColumns.length + '">Loading ...</td></tr>';
    var memberMessageRow = '<tr class="message"><td colspan="' + memberColumns.length + '"></td></tr>';
    $memberTablefoot.html(memberLoadingRow);
    $memberTablefoot.append(memberMessageRow);

    $.get('/controllers/member.php?action=getMembers', { status: status }, function(data) {
        $memberTablefoot.find('.loading').hide();

        if (data.error) {
            message = (message ? message + '<br/>': '') + data.message;
        } else if (data.data.length == 0) {
            if (!message) { message = 'no matching members found'; }
        } else {
            message = (message ? message + '<br/>': '') + data.data.length + ' matching members found';
            var dataRows = BuildHtmlTableFromJson(data.data, memberColumns);
            $memberTablebody.html(dataRows);
            $memberTable.table('refresh');
        }

        $memberTablefoot.find('.message td').html(message);
    }, 'json');
}

function LoadActiveCompanyList() {
    $.get('/controllers/company.php?action=getCompanies', { status: null, active: 1 }, function(data) {
        if (!data.error) {
            $('#admin_activeCompanies_Donors').empty();
            $('#admin_activeCompanies_Beneficiaries').empty();

            var companies       = data.data[0];
            var donationTypes   = data.data[1];
            var schedules       = data.data[2];

            $.each(companies, function(i, company) {
                var address = (company['Address1'] || '') + ' ' 
                            + (company['Address2'] || '') + ' ' 
                            + (company['City'] || '')
                            + (company['ZIP'] ? ' (' + company['ZIP'] + ')' : '');

                var settings = '';
                var types = '';
                var schedule = '';
                var typesArray = $.grep(donationTypes,   function(item) { return item['CompanyID'] == company['CompanyID']; });
                var schedArray = $.grep(schedules,       function(item) { return item['CompanyID'] == company['CompanyID']; });

                if (company['PrimaryContactID']) {
                    settings += '<div><b>Primary:</b> ' + company['PrimaryFirstName'] + ' ' + company['PrimaryLastName']
                              + ' (' + FormatPhone(company['PrimaryPhone']) + ', ' + company['PrimaryEmail'] + ')'
                              + '</div>';
                }

                if (company['SecondaryContactID']) {
                    settings += '<div><b>Secondary:</b> ' + company['SecondaryFirstName'] + ' ' + company['SecondaryLastName']
                              + ' (' + FormatPhone(company['SecondaryPhone']) + ', ' + company['SecondaryEmail'] + ')'
                              + '</div>';
                }

                $.each(schedArray, function(i, schedTime) {
                    var startTime = FormatDate(schedTime['StartTime'], false, true);
                    var endTime = FormatDate(schedTime['EndTime'], false, true);
                    schedule += '<br/>' + GetDayOfWeek(schedTime['DayOfWeek']) + ' ' + startTime + ' - ' + endTime;
                });
                if (schedule != '') { settings += '<div><b>Schedule:</b> ' + schedule + '</div>'; }

                $.each(typesArray, function(i, dtype) {
                    if (i > 0) { types += ', '; }
                    types += dtype['Name'];
                });
                if (types != '') { settings += '<div><b>Donation Types:</b> ' + types + '</div>'; }

                var card = '<div class="companyCard ' + company['Status'] + '">\r\n'
                                + '<a href="#" class="ui-shadow ui-corner-all ui-icon-gear ui-btn-icon-notext settingsicon">Settings</a>\r\n'
                                + '<div class="name">' + company['Name'] + '</div>\r\n'
                                + '<div class="address">' + address + '</div>\r\n'
                                + '<div class="phone">' + (company['Phone'] || '') + '</div>\r\n'
                                + '<div class="memberCountContainer">MEMBERS <div class="memberCount">' + company['MemberCount'] + '</div></div>\r\n'
                                + '<div class="donationCountContainer">DONATIONS <div class="donationCount">' + company['DonationCount'] + '</div></div>\r\n'
                                + '<div class="settings">' + settings + '</div>\r\n'
                         + '</div>\r\n';

                if (company['CompanyTypeID'] == 1) {
                    $('#admin_activeCompanies_Donors').append(card);
                } else {
                    $('#admin_activeCompanies_Beneficiaries').append(card);
                }
            });

            $('#admin_activeCompanies_Donors, #admin_activeCompanies_Beneficiaries').trigger('create');
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
            $('#companyList_table .message td').html('<span class="errormessage">' + data.message + '</span>');
        } else {
            LoadProspectiveCompanyList(data.data[0]['count'] + ' companies updated');
            LoadMemberList(1);
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
            $('#memberListProspective_table .message td').html('<span class="errormessage">' + data.message + '</span>');
        } else {
            LoadMemberList(1, data.data[0]['count'] + ' members updated');
            LoadMemberList(2);
        }
    }, 'json');
}
