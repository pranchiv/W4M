<?php require_once('../includes/common.php'); ?>
<?php $showBanner = false; ?>

<!DOCTYPE html>
<html>
<head>
    <?php include($top."includes/head.php"); ?>
</head>
<body>

<?php require_once($top."connection.php"); ?>

<div id="admin_page" data-role="page">
    <div data-role="header" data-position="fixed">
        <?php 
        include($top.'includes/header.php'); 
        ?>
    </div>

    <div role="main" class="ui-content">
        <?php include($top."includes/banner.php"); ?>

        <div class="ui-corner-all custom-corners" data-role="collapsible" data-collapsed="false" data-theme="b">
            <h3>Prospective Companies</h3>
            <form id="admin_companyList_form">
                <?php include($top."includes/companyList.php"); ?>
                <input type="button" id="admin_companyList_approveButton" data-inline="true" data-theme="b" data-icon="check" value="Approve">
                <input type="button" id="admin_companyList_denyButton" data-inline="true" data-icon="delete" value="Deny">
            </form>
        </div>
        <div class="ui-corner-all custom-corners" data-role="collapsible" data-collapsed="false" data-theme="b">
            <h3>Prospective Members</h3>
            <form id="admin_memberList_form">
                <?php include($top."includes/memberListProspective.php"); ?>
                <input type="button" id="admin_memberList_approveButton" data-inline="true" data-theme="b" data-icon="check" value="Approve">
                <input type="button" id="admin_memberList_denyButton" data-inline="true" data-icon="delete" value="Deny">
            </form>
        </div>
        <div class="ui-corner-all custom-corners" data-role="collapsible" data-theme="b">
            <h3>Active Members</h3>
            <?php include($top."includes/memberListActive.php"); ?>
        </div>
        <div class="ui-corner-all custom-corners" data-role="collapsible" data-theme="b">
            <h3>Active Companies</h3>
            <div id="admin_activeCompanies">
                <div>
                    <div class="ui-bar ui-bar-a ui-corner-all" style="margin-bottom: 12px;">DONORS</div>
                    <div id="admin_activeCompanies_Donors" class="companyCardContainer"></div>
                </div>
                <div>
                    <div class="ui-bar ui-bar-a ui-corner-all" style="margin-bottom: 12px;">BENEFICIARIES</div>
                    <div id="admin_activeCompanies_Beneficiaries" class="companyCardContainer"></div>
                </div>
            </div>
        </div>

        <?php include($top."includes/debug.php"); ?>
    </div><!-- /content -->

    <div data-role="footer" data-position="fixed">
        <?php include($top."includes/footer.php"); ?>
    </div>
    <a id="backtotop" href="#admin_page" class="ui-shadow ui-corner-all ui-icon-carat-u ui-btn-icon-notext"></a>
</div><!-- /page -->

</body>
</html>