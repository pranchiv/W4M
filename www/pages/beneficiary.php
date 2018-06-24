<?php require_once('../includes/common.php'); ?>

<!DOCTYPE html>
<html>
<head>
    <?php include($top."includes/head.php"); ?>
</head>
<body>

<?php require_once($top."connection.php"); ?>

<div id="beneficiary_page" data-role="page">
    <div data-role="header" data-position="fixed">
        <?php include($top.'includes/header.php'); ?>
    </div>

    <div role="main" class="ui-content">
        <?php include($top."includes/banner.php"); ?>

        <div class="form_headers">
            <a href="<?= $top ?>pages/companySettings.php" data-transition="flip" style="flex-grow: 1;">Settings</a>

            <a href="" data-transition="flip" style="flex-grow: 1;">Donation History</a>

            <div style="flex-grow: 1; display: flex; justify-content: flex-end; font-weight: bold;">
                <?= $_SESSION['Company'] ?>
            </div>
        </div>

        <h3>Scheduled Donations</h3>
        <div id="beneficiary_Scheduled" class="donationCardContainer" data-role="Beneficiary" style="margin-bottom: 32px;"></div>

        <h3>Available Donations</h3>
        <div id="beneficiary_Available" class="donationCardContainer" data-role="Beneficiary" style="margin-bottom: 32px;"></div>

        <?php include($top."includes/debug.php"); ?>
    </div><!-- /content -->

    <div data-role="footer" data-position="fixed">
        <?php include($top."includes/footer.php"); ?>
    </div>
</div><!-- /page -->

</body>
</html>