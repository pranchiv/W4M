<?php require_once('../includes/common.php'); ?>

<!DOCTYPE html>
<html>
<head>
    <?php include($top."includes/head.php"); ?>
    <script src="<?= $top ?>scripts/common.js"></script>
</head>
<body>

<div data-role="page">
    <div data-role="header" data-position="fixed">
        <?php include($top.'includes/header.php'); ?>
    </div>

    <div role="main" class="ui-content">
        <?php include($top."includes/banner.php"); ?>

        <div class="form_headers">
            <h3>Beneficiary Stuff</h3>

            <div style="display: flex; justify-content: flex-end; font-weight: bold;">
                <?= $_SESSION['Company'] ?>
            </div>
        </div>

        <a href="<?= $top ?>pages/companySettings.php" data-transition="flip">Settings</a>

        <?php include($top."includes/debug.php"); ?>
    </div><!-- /content -->

    <div data-role="footer" data-position="fixed">
        <?php include($top."includes/footer.php"); ?>
    </div>
</div><!-- /page -->

</body>
</html>