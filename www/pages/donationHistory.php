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
        <div class="form_headers">
            <?php if ($_SESSION['MemberTypeID'] != 1) { ?>
                <?php include($top."includes/banner.php"); ?>
            <?php } ?>

            <h3>Donation History</h3>

            <?php if ($_SESSION['MemberTypeID'] == 1) { ?>
                <button class="ui-btn ui-btn-b ui-corner-all ui-icon-forbidden ui-btn-icon-notext toggleFails" title="Hide Fails"></button>
            <?php } else { ?>
                <a href="<?= $top ?>pages/<?= strtolower($_SESSION['MemberType']) ?>.php" data-rel="back">Back to Main Page</a>

                <div style="display: flex; justify-content: flex-end; font-weight: bold;">
                    <?= $_SESSION['Company'] ?>
                </div>
            <?php } ?>
        </div>

        <div id="donation_History" class="donationCardContainer" data-role="<?php echo $_SESSION['MemberType'] ?>" style="margin-bottom: 32px;"></div>

        <?php include($top."includes/debug.php"); ?>
    </div><!-- /content -->

    <div data-role="footer" data-position="fixed">
        <?php require_once($top."includes/footer.php"); ?>
    </div>
</div><!-- /page -->

<!-- <script src="<?= $top ?>scripts/company.js"></script> -->

</body>
</html>