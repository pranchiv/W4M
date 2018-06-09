<?php require_once('../includes/common.php'); ?>
<?php $showBanner = false; ?>

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

        <h1>Admin Stuff</h1>

        <?php include($top."includes/debug.php"); ?>
    </div><!-- /content -->

    <div data-role="footer" data-position="fixed">
        <?php include($top."includes/footer.php"); ?>
    </div>
</div><!-- /page -->

</body>
</html>