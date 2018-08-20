<?php require_once('../includes/common.php'); ?>

<!DOCTYPE html>
<html>
<head>
    <?php include($top."includes/head.php"); ?>
</head>
<body>

<?php require_once($top."connection.php"); ?>

<div id="driver_page" data-role="page">
    <div data-role="header" data-position="fixed">
        <?php include($top.'includes/header.php'); ?>
    </div>

    <div role="main" class="ui-content">
        <?php include($top."includes/banner.php"); ?>

        <div class="form_headers">
            <a href="<?= $top ?>pages/donationHistory.php" data-transition="flip" style="flex-grow: 1;">Donation History</a>
        </div>

        <h3>Your Active Donations</h3>
        <div id="driver_Scheduled" class="donationCardContainer" style="margin-bottom: 32px;"></div>

        <h3>Donations Needing a Driver</h3>
        <div id="driver_Pending" class="donationCardContainer" style="margin-bottom: 32px;"></div>

        <?php include($top."includes/debug.php"); ?>        
    </div><!-- /content -->

    <div data-role="footer" data-position="fixed">
        <?php include($top."includes/footer.php"); ?>
    </div>
</div><!-- /page -->

</body>
</html>