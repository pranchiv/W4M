<?php require_once('../includes/common.php'); ?>

<!DOCTYPE html>
<html>
<head>
    <?php include($top."includes/head.php"); ?>
</head>
<body>

<?php require_once($top."connection.php"); ?>

<div id="donationHistory_page" data-role="page">
 
    <div data-role="header" data-position="fixed">
        <?php require_once($top."includes/header.php"); ?>
    </div>

    <div role="main" class="ui-content">
        <?php include($top."includes/banner.php"); ?>

        <h3>Donation History</h3>

        <a href="<?= $top ?>pages/beneficiary.php" data-rel="back">Back to Main Page</a>
        
        <div id="beneficiary_History" class="donationCardContainer" data-role="Beneficiary" style="margin-bottom: 32px;"></div>
    </div><!-- /content -->

    <div data-role="footer" data-position="fixed">
        <?php require_once($top."includes/footer.php"); ?>
    </div>
</div><!-- /page -->

<!-- <script src="<?= $top ?>scripts/company.js"></script> -->

</body>
</html>