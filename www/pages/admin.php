<?php require_once('../includes/common.php'); ?>
<?php $showBanner = false; ?>

<!DOCTYPE html>
<html>
<head>
    <?php include($top."includes/head.php"); ?>
    <script src="<?= $top ?>scripts/common.js"></script>
    <script src="<?= $top ?>scripts/admin.js"></script>
</head>
<body>

<div data-role="page">
    <div data-role="header" data-position="fixed">
        <?php include($top.'includes/header.php'); ?>
    </div>

    <div role="main" class="ui-content">
        <?php include($top."includes/banner.php"); ?>

        <h2>Admin Stuff</h2>

        <h3>Prospective Companies</h3>

        <form id="admin_companyList_form">
            <?php include($top."includes/companyList.php"); ?>
            <input type="button" id="admin_companyList_approveButton" data-inline="true" data-theme="b" data-icon="check" value="Approve">
            <input type="button" id="admin_companyList_denyButton" data-inline="true" data-icon="delete" value="Deny">
        </form>

        <h3>Prospective Members</h3>
        <form id="admin_memberList_form">
            <?php include($top."includes/memberList.php"); ?>
        </form>

        <?php include($top."includes/debug.php"); ?>
    </div><!-- /content -->

    <div data-role="footer" data-position="fixed">
        <?php include($top."includes/footer.php"); ?>
    </div>
</div><!-- /page -->

</body>
</html>