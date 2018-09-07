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

        <div class="ui-corner-all custom-corners">
            <div class="ui-bar ui-bar-b">
                <h3>Active Donations</h3>
            </div>
            <div class="ui-body ui-body-a">
                <div id="admin_activeDonations" class="donationCardContainer"></div>
            </div>
        </div>

        <?php include($top."includes/debug.php"); ?>
    </div><!-- /content -->

    <div data-role="footer" data-position="fixed">
        <?php include($top."includes/footer.php"); ?>
    </div>
</div><!-- /page -->

</body>
</html>